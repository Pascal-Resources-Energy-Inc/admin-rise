<?php

namespace App\Http\Controllers;

use App\AreaDistributor;
use Illuminate\Http\Request;

class AreaDistributorController extends Controller
{
    public function index()
    {
        $activeAds = AreaDistributor::where('status', 'Active')->count();
        $inactiveAds = AreaDistributor::where('status', 'Inactive')->count();

        $ads = AreaDistributor::get();
        return view('area_distributor.index',
            array(
                'ads' => $ads,
                'activeAds' => $activeAds,
                'inactiveAds' => $inactiveAds
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
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AreaDistributor  $areaDistributor
     * @return \Illuminate\Http\Response
     */
    public function edit(AreaDistributor $areaDistributor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AreaDistributor  $areaDistributor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AreaDistributor $areaDistributor)
    {
        //
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
}
