<?php

namespace App\Http\Controllers;

use App\Dealer;
use App\Client;
use App\TransactionDetail;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->back()->with('error', 'Please enter a search term.');
        }

        $client = Client::where('name', 'LIKE', '%' . $query . '%')->first();
        $dealer = Dealer::where('name', 'LIKE', '%' . $query . '%')->first();

        if ($client) {
            return $this->viewProfile($client->id, 'client');
        } elseif ($dealer) {
            return $this->viewProfile($dealer->id, 'dealer');
        } else {
            return redirect()->back()->with('error', 'No user found with the name "' . $query . '".');
        }
    }

    public function viewProfile($id, $type)
    {
        if ($type === 'client') {
            $profile = Client::findOrFail($id);
            $transactions = TransactionDetail::where('client_id', $profile->id)->get();
        } elseif ($type === 'dealer') {
            $profile = Dealer::findOrFail($id);
            $transactions = TransactionDetail::where('dealer_id', $profile->user_id)->get();
        } else {
            abort(404);
        }

        return view('view_profile', [
            'profile' => $profile,
            'transactions' => $transactions,
        ]);
    }

    public function searchSuggestions(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $clients = Client::where('name', 'LIKE', '%' . $query . '%')
                        ->select('id', 'name')
                        ->limit(5)
                        ->get()
                        ->map(function($client) {
                            return [
                                'id' => $client->id,
                                'name' => $client->name,
                                'type' => 'client'
                            ];
                        });

        $dealers = Dealer::where('name', 'LIKE', '%' . $query . '%')
                        ->select('id', 'name')
                        ->limit(5)
                        ->get()
                        ->map(function($dealer) {
                            return [
                                'id' => $dealer->id,
                                'name' => $dealer->name,
                                'type' => 'dealer'
                            ];
                        });

        $suggestions = $clients->concat($dealers)->take(10);

        return response()->json($suggestions);
    }

    public function markNotificationRead(Request $request)
    {
        $notificationId = $request->input('notification_id');
        
        if ($notificationId) {
            $readNotifications = session('read_notifications', []);
            
            if (!in_array($notificationId, $readNotifications)) {
                $readNotifications[] = $notificationId;
                session(['read_notifications' => $readNotifications]);
                
                return response()->json([
                    'success' => true, 
                    'message' => 'Notification marked as read'
                ]);
            }
            
            return response()->json([
                'success' => true, 
                'message' => 'Notification already read'
            ]);
        }
        
        return response()->json([
            'success' => false, 
            'message' => 'Invalid notification ID'
        ]);
    }

    // Mark all notifications as read
    public function markAllNotificationsRead(Request $request)
    {
        try {
            $recentClients = Client::whereDate('created_at', '>=', now()->subDays(3))
                ->orderBy('created_at', 'desc')
                ->get();
            $recentTransactions = TransactionDetail::with(['customer', 'dealer', 'product'])
                ->whereDate('created_at', '>=', now()->subDays(3))
                ->orderBy('created_at', 'desc')
                ->get();
            
            $allNotificationIds = [];
            
            foreach ($recentClients as $client) {
                $allNotificationIds[] = 'client_' . $client->id;
            }
            
            foreach ($recentTransactions as $transaction) {
                $allNotificationIds[] = 'transaction_' . $transaction->id;
            }
            
            session(['read_notifications' => $allNotificationIds]);
            
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error marking notifications as read: ' . $e->getMessage()
            ]);
        }
    }

    public function getNotificationCount(Request $request)
    {
        $recentClients = Client::whereDate('created_at', '>=', now()->subDays(3))
            ->orderBy('created_at', 'desc')
            ->get();
        $recentTransactions = TransactionDetail::with(['customer', 'dealer', 'product'])
            ->whereDate('created_at', '>=', now()->subDays(3))
            ->orderBy('created_at', 'desc')
            ->get();
        
        $readNotifications = session('read_notifications', []);
        
        $unreadClients = $recentClients->reject(function($client) use ($readNotifications) {
            return in_array('client_' . $client->id, $readNotifications);
        });
        
        $unreadTransactions = $recentTransactions->reject(function($transaction) use ($readNotifications) {
            return in_array('transaction_' . $transaction->id, $readNotifications);
        });
        
        $totalUnread = $unreadClients->count() + $unreadTransactions->count();
        
        return response()->json([
            'success' => true,
            'count' => $totalUnread
        ]);
    }
}