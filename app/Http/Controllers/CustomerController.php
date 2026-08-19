<?php

namespace App\Http\Controllers;
use App\Stove;
use App\User;
use App\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Client;
use RealRashid\SweetAlert\Facades\Alert;
use GuzzleHttp\Client as GuzzleClient;
class CustomerController extends Controller
{
    //
    public function index(Request $request)
    {   
        $activeCustomers = Client::where('status', 'Active')->count();
        $inactiveCustomers = Client::where('status', 'Inactive')->count();

        $stoves = Stove::where('client_id',null)->get();
        $customers = Client::with(['transactions', 'serial'])->get();
        return view('customers',
            array(
                'stoves' => $stoves,
                'customers' => $customers,
                'activeCustomers' => $activeCustomers,
                'inactiveCustomers' => $inactiveCustomers
            )
        );
    }
    public function view(Request $request,$id)
    {
        $transactions = TransactionDetail::where('client_id',$id)->orderBy('id','desc')->get();
        $customer = Client::findOrfail($id);
        $stoves = Stove::whereNull('client_id')
            ->orWhere('id', $customer->serial_number)
            ->orderBy('serial_number')
            ->get();

        return view('customer',
            array(
                'customer' => $customer,
                'transactions' => $transactions,
                'stoves' => $stoves,
            )
        );
    }
    public function show(Request $request)
    {
        return view('customer-dashboard');
    }
    public function newCustomer(Request $request)
    {
        $stoves = Stove::where('client_id',null)->get();
        return view('new-customer',
            array(
                'stoves' => $stoves
            )
        );
    }

    public function saveCustomer(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email_address;
        $user->role = 'Client';
        $user->password = bcrypt('12345678');
        $user->save();

        // Generate Client Reference
        $latestClient = Client::orderBy('id', 'desc')->first();

        if ($latestClient && $latestClient->client_reference) {
            $number = intval(substr($latestClient->client_reference, 3)) + 1;
        } else {
            $number = 1;
        }

        $client_reference = 'PRC' . str_pad($number, 5, '0', STR_PAD_LEFT);

        $customer = new Client;
        $customer->client_reference = $client_reference;
        $customer->user_id = $user->id;
        $customer->name = $request->name;
        $customer->email_address = $request->email_address;
        $customer->number = $request->phone_number;
        $customer->facebook = $request->facebook;
        $customer->address = $request->address;
        $customer->serial_number = $request->serial_number;
        $customer->location_region = $request->location_region;
        $customer->location_province = $request->location_province;
        $customer->location_city = $request->location_city;
        $customer->location_barangay = $request->location_barangay;
        $customer->postal_code = $request->postal_code;
        $customer->street_address = $request->street_address;
        $customer->spo = $request->spo;
        $customer->center = $request->center;
        $customer->status = $request->status;
        $customer->save();

        $serial_number = Stove::findOrfail($request->serial_number);
        $serial_number->client_id = $customer->id;
        $serial_number->save();


        Alert::success('Successfully encoded')->persistent('Dismiss');
        return redirect('view-client/' . $customer->id);
    }

    public function update(Request $request, $id)
    {
        $customer = Client::findOrFail($id);
        $emailRule = 'required|email|max:255';

        if ($customer->user_id) {
            $emailRule .= '|unique:users,email,' . $customer->user_id;
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email_address' => $emailRule,
            'number' => 'nullable|string|max:30',
            'serial_number' => 'nullable|exists:stoves,id',
            'facebook' => 'nullable|string|max:255',
            'location_region' => 'nullable|string|max:255',
            'location_province' => 'nullable|string|max:255',
            'location_city' => 'nullable|string|max:255',
            'location_barangay' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'street_address' => 'nullable|string|max:255',
            'spo' => 'nullable|string|max:255',
            'center' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        DB::transaction(function () use ($customer, $validated) {
            $newSerialId = $validated['serial_number'] ?? null;
            $newStove = $newSerialId ? Stove::lockForUpdate()->findOrFail($newSerialId) : null;

            if ($newStove && $newStove->client_id && (int) $newStove->client_id !== (int) $customer->id) {
                throw ValidationException::withMessages([
                    'serial_number' => 'The selected serial number is already assigned to another customer.',
                ]);
            }

            if ((int) $customer->serial_number !== (int) $newSerialId) {
                $oldStove = Stove::where('id', $customer->serial_number)
                    ->where('client_id', $customer->id)
                    ->lockForUpdate()
                    ->first();

                if ($oldStove) {
                    $oldStove->client_id = null;
                    $oldStove->save();
                }
            }

            $customer->fill($validated);
            $customer->save();

            if ($newStove) {
                $newStove->client_id = $customer->id;
                $newStove->save();
            }

            // Keep the login account aligned with the customer's editable name and email.
            if ($customer->user) {
                $customer->user->name = $validated['name'];
                $customer->user->email = $validated['email_address'];
                $customer->user->save();
            }
        });

        Alert::success('Success', 'Customer information updated successfully!');
        return redirect()->back();
    }
    
    public function changeAvatar(Request $request, $id)
    {
        $customer = Client::findOrfail($id);
        
        $imageData = $request->image_data;
        
        if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
            $imageType = $matches[1];
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
        } else {
            Alert::error('Invalid image format')->persistent('Dismiss');
            return back();
        }
        
        $imageData = base64_decode($imageData);
        
        if ($imageData === false) {
            Alert::error('Failed to decode image')->persistent('Dismiss');
            return back();
        }
        
        $directory = public_path('avatar-client');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $fileName = 'avatar_' . $customer->id . '_' . time() . '.png';
        $filePath = $directory . '/' . $fileName;
        
        if (file_put_contents($filePath, $imageData)) {
            if ($customer->avatar && 
                $customer->avatar !== url('design/assets/images/profile/user-1.png') && 
                file_exists(public_path(str_replace(url('/'), '', $customer->avatar)))) {
                unlink(public_path(str_replace(url('/'), '', $customer->avatar)));
            }
            
            $customer->avatar = 'avatar-client/' . $fileName;
            $customer->save();
            
            Alert::success('Successfully Uploaded')->persistent('Dismiss');
        } else {
            Alert::error('Failed to save image')->persistent('Dismiss');
        }
        
        return back();
    }
    public function uploadValidId(Request $request,$id)
    {
        // dd($request->all());
        $customer = Client::findOrfail($id);
        $customer->valid_id = $request->valid_id_type;
        $customer->valid_id_number = $request->id_number;

        $attachment = $request->file('id_file');
        $original_name = $attachment->getClientOriginalName();
        $name = time().'_'.$attachment->getClientOriginalName();
        $attachment->move(public_path().'/valid_ids/', $name);
        $file_name = '/valid_ids/'.$name;

        $customer->valid_file = $file_name;
        $customer->save();

        Alert::success('Successfully Uploaded')->persistent('Dismiss');
        return back();
    }
    public function contractSign(Request $request,$id)
    {
        // dd($request->all());

        $request->validate([
            'contract_signature' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $customer = Client::findOrfail($id);

        $attachment = $request->file('contract_signature');
        $original_name = $attachment->getClientOriginalName();
        $name = time().'_'.$attachment->getClientOriginalName();
        $attachment->move(public_path().'/signatures/', $name);
        $file_name = '/signatures/'.$name;
        $customer->signature = $file_name;

        $customer->save();

        Alert::success('Successfully Uploaded')->persistent('Dismiss');
       return redirect()->to('view-client/' . $customer->id);
    }

  public function getUser($id)
{
   $serials = Stove::where('serial_number', 'like', '%' . $id . '%')->first();
   if($serials)
   {
   $client = Client::findOrfail($serials->client_id);
    $user = User::find($client->user_id);

    if ($user) {
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $client->id,
                'name' => $user->name
            ]
        ]);
    } else {
        return response()->json(['success' => false], 404);
    }
       }
       else

       {
         return response()->json(['success' => false], 404);
       }
       
}
    public function sign($id)
    {
        $customer = Client::findOrfail($id);

        return view('signature',
        array(
        'customer' => $customer
        ));
    }

    public function regions()
    {
        try {
            $client = new GuzzleClient();
            $response = $client->get('https://psgc.cloud/api/regions');
            
            return response()->json(
                json_decode($response->getBody()->getContents(), true)
            );
        } catch (\Exception $e) {
            return response()->json([], 500);
        }
    }

    public function provinces($region)
    {
        try {
            $client = new GuzzleClient();
            $response = $client->get("https://psgc.cloud/api/regions/{$region}/provinces");

            return response()->json(json_decode($response->getBody()->getContents(), true));
        } catch (\Exception $e) {
            return response()->json([], 500);
        }
    }

    public function cities($province)
    {
        try {
            $client = new GuzzleClient();
            $response = $client->get("https://psgc.cloud/api/provinces/{$province}/cities-municipalities");

            return response()->json(json_decode($response->getBody()->getContents(), true));
        } catch (\Exception $e) {
            return response()->json([], 500);
        }
    }

    public function barangays($city)
    {
        try {
            $client = new GuzzleClient();
            $response = $client->get("https://psgc.cloud/api/cities-municipalities/{$city}/barangays");

            return response()->json(json_decode($response->getBody()->getContents(), true));
        } catch (\Exception $e) {
            return response()->json([], 500);
        }
    }
}
