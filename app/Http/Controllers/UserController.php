<?php

namespace App\Http\Controllers;
use App\Dealer;
use App\Client;
use App\TransactionDetail;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function view(){

        if(auth()->user()->role == "Dealer")
        {
            $profile = Dealer::where('user_id',auth()->user()->id)->first();
             $transactions = TransactionDetail::where('dealer_id',$profile->user_id)->get();
        }
        else
        {
            $profile = Client::where('user_id',auth()->user()->id)->first();
            $transactions = TransactionDetail::where('client_id',$profile->id)->get();
        }
       
       return view('view_profile',
            array(
                'profile' => $profile,  
                'transactions' => $transactions,
            )
        );
    }
}
