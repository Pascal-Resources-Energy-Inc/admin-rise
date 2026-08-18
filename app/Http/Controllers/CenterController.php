<?php

namespace App\Http\Controllers;

use App\Center;
use App\Client;
use App\Dealer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CenterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorizeAdmin();

        $centers = Center::orderBy('name')->get();

        return view('centers.index', [
            'centers' => $centers,
            'mfiOptions' => $this->mfiOptions(),
        ]);
    }

    public function create()
    {
        $this->authorizeAdmin();

        return view('centers.create', ['mfiOptions' => $this->mfiOptions()]);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $data = $this->validatedData($request);
        Center::create($data);

        return redirect()->route('centers.index')->with('success', 'Center added successfully.');
    }

    public function edit(Center $center)
    {
        $this->authorizeAdmin();

        return view('centers.edit', [
            'center' => $center,
            'mfiOptions' => $this->mfiOptions(),
        ]);
    }

    public function update(Request $request, Center $center)
    {
        $this->authorizeAdmin();

        $data = $this->validatedData($request, $center->id);
        $oldName = $center->name;

        DB::transaction(function () use ($center, $data, $oldName) {
            $center->update($data);

            // Dealers and customers store the center name rather than a center ID.
            // Keep those records linked when a center is renamed.
            if ($oldName !== $data['name']) {
                Dealer::where('center', $oldName)->update(['center' => $data['name']]);
                Client::where('center', $oldName)->update(['center' => $data['name']]);
            }
        });

        return redirect()->route('centers.index')->with('success', 'Center updated successfully.');
    }

    public function destroy(Center $center)
    {
        $this->authorizeAdmin();

        $isInUse = Dealer::where('center', $center->name)->exists()
            || Client::where('center', $center->name)->exists();

        if ($isInUse) {
            return redirect()->route('centers.index')->with('error', 'This center cannot be deleted because it is assigned to a dealer or customer.');
        }

        $center->delete();

        return redirect()->route('centers.index')->with('success', 'Center deleted successfully.');
    }

    private function validatedData(Request $request, $ignoreId = null)
    {
        $uniqueRule = $ignoreId
            ? 'unique:centers,name,' . $ignoreId . ',id,deleted_at,NULL'
            : 'unique:centers,name,NULL,id,deleted_at,NULL';

        return $request->validate([
            'name' => ['required', 'string', 'max:255', $uniqueRule],
            'mfi' => ['required', 'in:SEDP,ASHI'],
        ]);
    }

    private function mfiOptions()
    {
        return ['SEDP', 'ASHI'];
    }

    private function authorizeAdmin()
    {
        abort_unless(auth()->user() && auth()->user()->role === 'Admin', 403);
    }
}
