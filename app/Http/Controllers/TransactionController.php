<?php

namespace App\Http\Controllers;
use App\TransactionDetail;
use App\Item;
use App\Client;
use App\Dealer;
use Illuminate\Http\Request;

use RealRashid\SweetAlert\Facades\Alert;
class TransactionController extends Controller
{
    //

    public function index(Request $request)
    {
        $customers = Client::where('status', 'Active')->whereHas('serial')->get();
        $items = Item::get();
        $dealers = Dealer::get();
         $transactions = [];
        //  dd(auth()->user());
        if(auth()->user()->role == "Admin")
        {
            $transactions = TransactionDetail::get();
        }
        elseif(auth()->user()->role == "Dealer")
        {
            $transactions = TransactionDetail::where('dealer_id',auth()->user()->id)->get();
        }
        return view('transactions',
            array(
                'transactions' => $transactions,
                'items' => $items,
                'customers' => $customers,
                'dealers' => $dealers,
            )
        );
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $item = Item::findOrfail($request->item_id);


        $transaction = new TransactionDetail;
        $transaction->item = $item->item;
        $transaction->points_dealer = $item->dealer_points * $request->qty;
        $transaction->points_client = $item->customer_points * $request->qty;
        $transaction->item_description = $item->item_description;
        $transaction->qty = $request->qty;
        $transaction->price = $item->price;
        $transaction->client_id = $request->customer_id;
        $transaction->date = date('Y-m-d');
        $transaction->dealer_id = auth()->user()->id;
        $transaction->created_by = auth()->user()->id;
        $transaction->save();


         Alert::success('Successfully Save')->persistent('Dismiss');
        return back();
    }
    
    public function storeAdmin(Request $request)
    {
        // dd($request->all());
        $item = Item::findOrfail($request->item_id);


        $transaction = new TransactionDetail;
        $transaction->item = $item->item;
        $transaction->points_dealer = $item->dealer_points * $request->qty;
        $transaction->points_client = $item->customer_points * $request->qty;
        $transaction->item_description = $item->item_description;
        $transaction->qty = $request->qty;
        $transaction->price = $item->price;
        $transaction->client_id = $request->customer_id;
        $transaction->dealer_id = $request->dealer;
        $transaction->date = $request->date;
        $transaction->created_by = auth()->user()->id;
        $transaction->save();


         Alert::success('Successfully Save')->persistent('Dismiss');
        return back();
    }

  public function destroy($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return response()->json(['error' => 'Invalid transaction ID'], 400);
            }

            $transaction = TransactionDetail::findOrFail($id);

            if (auth()->user()->role === "Dealer" && $transaction->dealer_id != auth()->user()->id) {
                return response()->json(['error' => 'Unauthorized to delete this transaction'], 403);
            }

            $transaction->delete();

            return response()->json([
                'success' => 'Transaction deleted successfully',
                'transaction_id' => $id
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Transaction not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete transaction'], 500);
        }
    }


   public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids');

            if (!$ids || !is_array($ids) || empty($ids)) {
                return response()->json(['error' => 'No transactions selected'], 400);
            }

            $validIds = array_filter($ids, function ($id) {
                return is_numeric($id) && intval($id) > 0;
            });

            if (empty($validIds)) {
                return response()->json(['error' => 'Invalid transaction IDs provided'], 400);
            }

            $validIds = array_map('intval', $validIds);

            $query = TransactionDetail::whereIn('id', $validIds);

            if (auth()->user()->role === "Dealer") {
                $query->where('dealer_id', auth()->user()->id);
            }

            $transactions = $query->get();

            if ($transactions->isEmpty()) {
                return response()->json(['error' => 'No valid transactions found or unauthorized'], 403);
            }

            $deletedIds = $transactions->pluck('id')->toArray();
            $deletedCount = TransactionDetail::whereIn('id', $deletedIds)->delete();

            return response()->json([
                'success' => "Successfully deleted {$deletedCount} transaction(s)",
                'deleted_count' => $deletedCount,
                'deleted_ids' => $deletedIds
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete transactions'], 500);
        }
    }


       
}