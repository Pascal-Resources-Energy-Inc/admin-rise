<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Client;
use App\TransactionDetail;

class NotificationController extends Controller
{
    public function markAsRead(Request $request): JsonResponse
    {
        $notificationId = $request->input('notification_id');
        
        if (!$notificationId) {
            return response()->json(['success' => false, 'message' => 'Invalid notification ID']);
        }
        
        $userId = Auth::id();
        
        $exists = DB::table('notifications')
            ->where('user_id', $userId)
            ->where('notif_id', $notificationId)
            ->exists();
            
        if (!$exists) {
            $type = str_starts_with($notificationId, 'client_') ? 'client' : 'transaction';
            
            DB::table('notifications')->insert([
                'user_id' => $userId,
                'notif_id' => $notificationId,
                'type' => $type,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        return response()->json(['success' => true]);
    }
    
    public function saveNotification(Request $request)
    {
        try {
            $type = $request->input('type');
            $recordId = $request->input('record_id');
            $userId = Auth::id();
            
            if (!$type || !$recordId || !$userId) {
                return back()->with('error', 'Invalid parameters');
            }
            
            $notifId = '';
            if ($type === 'client') {
                $notifId = 'customer_' . $recordId;
            } elseif ($type === 'transaction') {
                $notifId = 'transaction_' . $recordId;
            }
            
            $exists = DB::table('notifications')
                ->where('user_id', $userId)
                ->where('notif_id', $notifId)
                ->exists();
                
            if ($exists) {
                return back()->with('info', 'Notification already saved');
            }
            
            DB::table('notifications')->insert([
                'user_id' => $userId,
                'notif_id' => $notifId,
                'type' => $type,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            if ($type === 'transaction') {
                return redirect('transactions')->with('success', 'Notification saved successfully');
            } elseif ($type === 'client') {
                return redirect('customers')->with('success', 'Notification saved successfully');
            } else {
                return back()->with('success', 'Notification saved successfully');
            }

            
        } catch (\Exception $e) {
            \Log::error('Error saving notification: ' . $e->getMessage());
            return back()->with('error', 'Error saving notification');
        }
    }
    
    public function getNotificationData()
    {
        $recentClients = Client::whereDate('created_at', '>=', now()->subDays(3))
            ->orderBy('created_at', 'desc')
            ->get();
        $recentTransactions = TransactionDetail::with(['customer', 'dealer', 'product'])
            ->whereDate('created_at', '>=', now()->subDays(3))
            ->orderBy('created_at', 'desc')
            ->get();
        
        $userId = Auth::id();
        
        $savedNotifications = DB::table('notifications')
            ->where('user_id', $userId)
            ->pluck('notif_id')
            ->toArray();
        
        $readNotifications = auth()->user()->read_notifications ?? [];
        
        $unreadClients = $recentClients->filter(function($client) use ($savedNotifications) {
            return !in_array('customer_' . $client->id, $savedNotifications);
        });
        
        $unreadTransactions = $recentTransactions->filter(function($transaction) use ($savedNotifications) {
            return !in_array('transaction_' . $transaction->id, $savedNotifications);
        });
        
        $totalUnreadCount = $unreadClients->count() + $unreadTransactions->count();
        
        $displayClients = $recentClients->take(5);
        $displayTransactions = $recentTransactions->take(5);

        $notifications = collect();

        foreach ($displayClients as $client) {
            $notifications->push([
                'type' => 'client',
                'data' => $client,
                'created_at' => $client->created_at,
            ]);
        }

        foreach ($displayTransactions as $transaction) {
            $notifications->push([
                'type' => 'transaction',
                'data' => $transaction,
                'created_at' => $transaction->created_at,
            ]);
        }

        $notifications = $notifications->sortByDesc('created_at')->values();

        return compact('recentClients', 'recentTransactions', 'readNotifications', 'unreadClients', 'unreadTransactions', 'totalUnreadCount', 'displayClients', 'displayTransactions', 'notifications', 'savedNotifications');
    }

    public function markAllAsRead()
    {
        try {
            $userId = Auth::id();
            
            $recentClients = Client::whereDate('created_at', '>=', now()->subDays(3))
                ->orderBy('created_at', 'desc')
                ->get();
            $recentTransactions = TransactionDetail::whereDate('created_at', '>=', now()->subDays(3))
                ->orderBy('created_at', 'desc')
                ->get();
            
            $savedNotifications = DB::table('notifications')
                ->where('user_id', $userId)
                ->pluck('notif_id')
                ->toArray();
            
            $notificationsToInsert = [];
            
            foreach ($recentClients as $client) {
                $notifId = 'customer_' . $client->id;
                if (!in_array($notifId, $savedNotifications)) {
                    $notificationsToInsert[] = [
                        'user_id' => $userId,
                        'notif_id' => $notifId,
                        'type' => 'client',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            
            foreach ($recentTransactions as $transaction) {
                $notifId = 'transaction_' . $transaction->id;
                if (!in_array($notifId, $savedNotifications)) {
                    $notificationsToInsert[] = [
                        'user_id' => $userId,
                        'notif_id' => $notifId,
                        'type' => 'transaction',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            
            if (!empty($notificationsToInsert)) {
                DB::table('notifications')->insert($notificationsToInsert);
            }
            
            return redirect()->back()->with('success', 'All notifications marked as read');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error marking notifications as read');
        }
    }

    public function getUnreadCount(): JsonResponse
    {
        $notificationData = $this->getNotificationData();
        
        return response()->json([
            'count' => $notificationData['totalUnreadCount']
        ]);
    }
}