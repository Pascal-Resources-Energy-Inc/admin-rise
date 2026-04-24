<?php

namespace App\Http\Controllers;

use App\AreaDistributor;
use App\AreaAd;
use App\Center;
use App\Dealer;
use App\User;
use App\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AreaDistributorController extends Controller
{
    public function index()
    {
        $activeAds = AreaDistributor::where('status', 'Active')->count();
        $inactiveAds = AreaDistributor::where('status', 'Inactive')->count();
        $centers = Center::get();
        
        $ads = AreaDistributor::with('areas')->get();
        return view('area_distributor.index',
            array(
                'ads' => $ads,
                'activeAds' => $activeAds,
                'inactiveAds' => $inactiveAds,
                'centers' => $centers
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email_address;
        $user->role = 'Area Distributor';
        $user->password = bcrypt('12345678');
        $user->save();
        
        $latestAd = AreaDistributor::orderBy('id', 'desc')->first();

        $number = ($latestAd && $latestAd->ad_reference)
            ? intval(substr($latestAd->ad_reference, 4)) + 1
            : 1;

        $ad_reference = 'PRAD' . str_pad($number, 5, '0', STR_PAD_LEFT);

        $imagePath = null;

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/area_distributor'), $filename);
            $imagePath = 'uploads/area_distributor/' . $filename;
        }

        $fullAddress = $request->address;

        $areaDistributor = new AreaDistributor;
        $areaDistributor->user_id = $user->id;
        $areaDistributor->ad_reference = $ad_reference;
        $areaDistributor->name = $request->name;
        $areaDistributor->store_code = $request->store_code;
        $areaDistributor->email_address = $request->email_address;
        $areaDistributor->contact_number = $request->contact_number;
        $areaDistributor->facebook = $request->facebook;
        $areaDistributor->address = $fullAddress;
        $areaDistributor->business_name = $request->business_name;
        $areaDistributor->business_type = $request->business_type;
        $areaDistributor->latitude = $request->latitude;
        $areaDistributor->longitude = $request->longitude;
        $areaDistributor->status = "Active";

        if ($imagePath) {
            $areaDistributor->avatar = $imagePath;
        }

        $areaDistributor->save();

        foreach ($request->area_name as $area) {
            AreaAd::create([
                'ad_id' => $areaDistributor->id,
                'area_name' => $area
            ]);
        }

        return redirect()->route('ads')->with('success', 'Successfully encoded');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AreaDistributor  $areaDistributor
     * @return \Illuminate\Http\Response
     */
    public function show(AreaDistributor $areaDistributor)
    {
        //
    }

    public function edit($id)
    {
        $ad = AreaDistributor::with('areas')->findOrFail($id);
        $centers = Center::all(); // same source as your create

        return view('area_distributor.edit', compact('ad', 'centers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email_address' => 'required|email',
            'contact_number' => 'required',
            'business_name' => 'required',
            'business_type' => 'required',
            'area_name' => 'required|array',
        ]);

        $areaDistributor = AreaDistributor::findOrFail($id);

        // ✅ Update user
        if ($areaDistributor->user_id) {
            User::where('id', $areaDistributor->user_id)->update([
                'name' => $request->name,
                'email' => $request->email_address
            ]);
        }

        // ✅ Image Upload
        if ($request->hasFile('avatar')) {

            // delete old
            if ($areaDistributor->avatar && file_exists(public_path($areaDistributor->avatar))) {
                unlink(public_path($areaDistributor->avatar));
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/area_distributor'), $filename);

            // ✅ IMPORTANT: assign BEFORE update
            $areaDistributor->avatar = 'uploads/area_distributor/' . $filename;
        }

        // ✅ Update main data
        $areaDistributor->update([
            'name' => $request->name,
            'store_code' => $request->store_code,
            'email_address' => $request->email_address,
            'contact_number' => $request->contact_number,
            'facebook' => $request->facebook,
            'address' => $request->address,
            'business_name' => $request->business_name,
            'business_type' => $request->business_type,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // ✅ Sync Areas (cleaner)
        AreaAd::where('ad_id', $areaDistributor->id)->delete();

        foreach ($request->area_name as $area) {
            AreaAd::create([
                'ad_id' => $areaDistributor->id,
                'area_name' => $area
            ]);
        }

        return back()->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AreaDistributor  $areaDistributor
     * @return \Illuminate\Http\Response
     */
    public function destroy(AreaDistributor $areaDistributor)
    {
        //
    }

    public function geocodeLocation(Request $request)
    {
        try {
            $request->validate([
                'barangay' => 'required|string',
                'city' => 'required|string',
                'province' => 'required|string',
            ]);

            $barangay = $request->input('barangay');
            $city = $request->input('city');
            $province = $request->input('province');
            
            $query = urlencode("{$barangay}, {$city}, {$province}, Philippines");
            $url = "https://nominatim.openstreetmap.org/search?q={$query}&format=json&limit=1&countrycodes=ph";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'DealerRegistrationApp/1.0');
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error) {
                \Log::error('Geocoding cURL error: ' . $error);
            }
            
            if ($httpCode == 200 && $response) {
                $data = json_decode($response, true);
                
                if (!empty($data)) {
                    return response()->json([
                        'success' => true,
                        'lat' => $data[0]['lat'],
                        'lng' => $data[0]['lon']
                    ]);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Location not found'
            ], 200);
            
        } catch (\Exception $e) {
            \Log::error('Geocoding error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Geocoding failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 200);
        }
    }

    public function myDealer(Request $request)
    {
        $user = auth()->user();

        // $centers = $user->ad->areas->pluck('area_name')->toArray();
        $centers = optional($user->ad)
            ->areas
            ? $user->ad->areas->pluck('area_name')->toArray()
            : [];

        $dealers = Dealer::with([
            'orders' => function ($q) {
                $q->select('dealer_id', 'item', \DB::raw('SUM(qty) as total_qty'))
                ->groupBy('dealer_id', 'item');
            },
            'sales' => function ($q) {
                $q->select('dealer_id', 'item_description', \DB::raw('SUM(qty) as total_qty'))
                ->groupBy('dealer_id', 'item_description');
            }
        ])->whereIn('center', $centers)->get();
       
        $items = Item::select('item')->get(); // master list of items
        $activeDealers = Dealer::whereIn('center', $centers)
            ->where('status', 'Active')
            ->count();

        $inactiveDealers = Dealer::whereIn('center', $centers)
            ->where('status', 'Inactive')
            ->count();

        return view('dealers', [
            'dealers' => $dealers,
            'items' => $items,
            'activeDealers' => $activeDealers,
            'inactiveDealers' => $inactiveDealers
        ]);
    }

}
