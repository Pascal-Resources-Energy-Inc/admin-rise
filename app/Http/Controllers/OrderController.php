<?php

namespace App\Http\Controllers;
use App\OrderDetail;
use App\Item;
use App\Client;
use App\Dealer;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $customers = Client::where('status', 'Active')->whereHas('serial')->get();
        $items = Item::get();
        $dealers = Dealer::get();

        $user = auth()->user();

        $centers = $user->ad->areas->pluck('area_name')->toArray();

        $orders = [];
        
        $orders = OrderDetail::whereHas('adDealer', function($q) use ($centers) {
            $q->whereIn('center', $centers);
        })->get();
        // dd($orders);
        return view('area_distributor.orders',
            array(
                'orders' => $orders,
                'items' => $items,
                'customers' => $customers,
                'dealers' => $dealers,
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
