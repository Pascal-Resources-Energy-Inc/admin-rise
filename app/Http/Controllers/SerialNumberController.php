<?php

namespace App\Http\Controllers;

use App\Client;
use App\Stove;
use Illuminate\Http\Request;

class SerialNumberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorizeAdmin();

        $stoves = Stove::with('client')->orderBy('serial_number')->get();

        return view('serial_numbers.index', compact('stoves'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        Stove::create($this->validatedData($request));

        return redirect()->route('serial-numbers')->with('success', 'Serial number added successfully.');
    }

    public function update(Request $request, Stove $stove)
    {
        $this->authorizeAdmin();

        $stove->update($this->validatedData($request, $stove->id));

        return redirect()->route('serial-numbers')->with('success', 'Serial number updated successfully.');
    }

    public function destroy(Stove $stove)
    {
        $this->authorizeAdmin();

        $isAssigned = $stove->client_id || Client::where('serial_number', $stove->id)->exists();

        if ($isAssigned) {
            return redirect()->route('serial-numbers')->with('error', 'This serial number cannot be deleted because it is assigned to a customer.');
        }

        $stove->delete();

        return redirect()->route('serial-numbers')->with('success', 'Serial number deleted successfully.');
    }

    private function validatedData(Request $request, $ignoreId = null)
    {
        $uniqueRule = $ignoreId
            ? 'unique:stoves,serial_number,' . $ignoreId . ',id,deleted_at,NULL'
            : 'unique:stoves,serial_number,NULL,id,deleted_at,NULL';

        return $request->validate([
            'serial_number' => ['required', 'string', 'max:255', $uniqueRule],
        ]);
    }

    private function authorizeAdmin()
    {
        abort_unless(auth()->user() && auth()->user()->role === 'Admin', 403);
    }
}
