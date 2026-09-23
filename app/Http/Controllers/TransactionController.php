<?php

namespace App\Http\Controllers;
use App\TransactionDetail;
use App\Item;
use App\Client;
use App\Dealer;
use App\Exports\TransactionsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use RealRashid\SweetAlert\Facades\Alert;
class TransactionController extends Controller
{
    //

    public function index(Request $request)
    {
        $customers = Client::where('status', 'Active')->whereHas('serial')->get();
        $items = Item::get();
        $dealers = Dealer::get();

        $transactions = $this->transactionQuery();
        $stats = (clone $transactions)->selectRaw(
            'COUNT(*) as transaction_count, COALESCE(SUM(qty), 0) as quantity_sold, COALESCE(SUM(price * qty), 0) as total_sales, COALESCE(SUM(points_dealer + points_client), 0) as total_points'
        )->first();

        return view('transactions',
            array(
                'transactionStats' => $stats,
                'items' => $items,
                'customers' => $customers,
                'dealers' => $dealers,
            )
        );
    }

    public function data(Request $request)
    {
        $query = $this->transactionQuery()
            ->leftJoin('users as transaction_dealers', 'transaction_details.dealer_id', '=', 'transaction_dealers.id')
            ->leftJoin('clients as transaction_customers', 'transaction_details.client_id', '=', 'transaction_customers.id')
            ->select(
                'transaction_details.*',
                'transaction_dealers.name as dealer_name',
                'transaction_customers.name as customer_name'
            );

        $recordsTotal = (clone $query)->count();
        $this->applyTransactionFilters($query, $request->input('filters', []));
        $this->applyTransactionSearch($query, trim($request->input('search.value', '')));

        $recordsFiltered = (clone $query)->count();
        $canManage = auth()->user()->role === 'Admin' && auth()->user()->can_delete === 'on';
        $columns = $canManage
            ? [null, 'transaction_details.id', 'transaction_details.date', 'transaction_details.qty', 'amount', 'transaction_dealers.name', 'transaction_customers.name', 'transaction_details.points_dealer', 'transaction_details.points_client', 'transaction_details.item', null]
            : ['transaction_details.id', 'transaction_details.date', 'transaction_details.qty', 'amount', 'transaction_dealers.name', 'transaction_customers.name', 'transaction_details.points_dealer', 'transaction_details.points_client', 'transaction_details.item'];
        $orderColumn = $columns[(int) $request->input('order.0.column', 0)] ?? 'transaction_details.id';
        $direction = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';

        if ($orderColumn === 'amount') {
            $query->orderByRaw('transaction_details.qty * transaction_details.price ' . $direction);
        } elseif ($orderColumn) {
            $query->orderBy($orderColumn, $direction);
        }

        $transactions = $query
            ->skip(max(0, (int) $request->input('start', 0)))
            ->take(min(100, max(10, (int) $request->input('length', 25))))
            ->get();

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $transactions->map(function ($transaction) use ($canManage) {
                $row = [];

                if ($canManage) {
                    $row[] = '<input type="checkbox" class="checkbox-item" data-id="' . $transaction->id . '">';
                }

                $row[] = $transaction->id;
                $row[] = date('M d, Y', strtotime($transaction->date));
                $row[] = number_format($transaction->qty, 2);
                $row[] = number_format($transaction->qty * $transaction->price, 2);
                $row[] = e($transaction->dealer_name ?? '');
                $row[] = e($transaction->customer_name ?? '');
                $row[] = $transaction->points_dealer;
                $row[] = $transaction->points_client;
                $row[] = e($transaction->item ?? '');

                if ($canManage) {
                    $row[] = '<button type="button" class="btn btn-danger btn-sm delete-single" data-id="' . $transaction->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                }

                return $row;
            }),
        ]);
    }

    public function export(Request $request)
    {
        $query = $this->transactionQuery()
            ->leftJoin('users as transaction_dealers', 'transaction_details.dealer_id', '=', 'transaction_dealers.id')
            ->leftJoin('clients as transaction_customers', 'transaction_details.client_id', '=', 'transaction_customers.id')
            ->select('transaction_details.*')
            ->with(['dealer', 'customer']);
        $this->applyTransactionFilters($query, $request->input('filters', []));
        $this->applyTransactionSearch($query, trim($request->input('search', '')));

        return Excel::download(
            new TransactionsExport($query->orderByDesc('transaction_details.id')),
            'transactions-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    private function transactionQuery()
    {
        $query = TransactionDetail::query();

        if (auth()->user()->role === 'Dealer') {
            $query->where('transaction_details.dealer_id', auth()->id());
        }

        return $query;
    }

    /** Apply the same filters to the DataTable and its Excel export. */
    private function applyTransactionFilters($query, $filters)
    {
        $filters = is_array($filters) ? $filters : [];

        if (!empty($filters['date_from'])) {
            $query->where('transaction_details.date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('transaction_details.date', '<=', $filters['date_to']);
        }

        if (!empty($filters['dealer_id']) && ctype_digit((string) $filters['dealer_id'])) {
            $query->where('transaction_details.dealer_id', (int) $filters['dealer_id']);
        }

        if (!empty($filters['customer_id']) && ctype_digit((string) $filters['customer_id'])) {
            $query->where('transaction_details.client_id', (int) $filters['customer_id']);
        }

        if (!empty($filters['item'])) {
            $query->where('transaction_details.item', $filters['item']);
        }
    }

    private function applyTransactionSearch($query, $search)
    {
        if ($search === '') {
            return;
        }

        $query->where(function ($query) use ($search) {
            $query->where('transaction_details.id', 'like', "%{$search}%")
                ->orWhere('transaction_details.date', 'like', "%{$search}%")
                ->orWhere('transaction_details.item', 'like', "%{$search}%")
                ->orWhere('transaction_details.qty', 'like', "%{$search}%")
                ->orWhereRaw('(transaction_details.qty * transaction_details.price) like ?', ["%{$search}%"])
                ->orWhere('transaction_details.points_dealer', 'like', "%{$search}%")
                ->orWhere('transaction_details.points_client', 'like', "%{$search}%")
                ->orWhere('transaction_dealers.name', 'like', "%{$search}%")
                ->orWhere('transaction_customers.name', 'like', "%{$search}%");
        });
    }

    public function adTransactions(Request $request)
    {
        $customers = Client::where('status', 'Active')->whereHas('serial')->get();
        $items = Item::get();
        $dealers = Dealer::get();

        $user = auth()->user();

        $centers = $user->ad->areas->pluck('area_name')->toArray();

        $transactions = [];
        //  dd(auth()->user());
        $transactions = TransactionDetail::whereHas('adDealer', function($q) use ($centers) {
            $q->whereIn('center', $centers);
        })->get();

        // if(auth()->user()->role == "Admin")
        // {
        //     $transactions = TransactionDetail::get();
        // }
        // elseif(auth()->user()->role == "Area Distributor")
        // {
        //     $transactions = TransactionDetail::where('dealer_id',auth()->user()->id)->get();
        // }
        return view('area_distributor.transactions',
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
