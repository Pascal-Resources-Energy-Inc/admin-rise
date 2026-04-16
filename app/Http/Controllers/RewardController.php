<?php

namespace App\Http\Controllers;

use App\Reward;
use App\Dealer;
use App\Client;
use App\User;
use App\RedeemedHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RewardController extends Controller
{
    public function index()
    {
        $users = User::with(['dealer', 'client'])->get();
        
        $rewards = Reward::orderBy('created_at', 'desc')->get();
        
        foreach ($rewards as $reward) {
            $reward->claims_count = RedeemedHistory::where('reward_id', $reward->id)->count();
            
            $reward->is_limit_reached = false;
            if ($reward->redemption_limit !== null && $reward->redemption_limit > 0) {
                $reward->is_limit_reached = $reward->claims_count >= $reward->redemption_limit;
            }
            
            $reward->has_claims = $reward->claims_count > 0;
        }
        
        $redeemhistory = RedeemedHistory::with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingCount = RedeemedHistory::where('status', 'submitted')->count();

        return view('rewards', compact('users', 'rewards', 'redeemhistory', 'pendingCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'price_reward' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'points_required' => 'required|integer|min:1',
            'redemption_limit' => 'nullable|integer|min:1',
            'expiry_date' => 'nullable|date',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif,JPG,JPEG|max:5120',
            'is_active' => 'nullable|boolean'
        ]);

        $reward = new Reward();
        $reward->price_reward = $validated['price_reward'];
        $reward->description = $validated['description'];
        $reward->points_required = $validated['points_required'];
        $reward->redemption_limit = $validated['redemption_limit'] ?? null;
        $reward->expiry_date = $validated['expiry_date'] ?? null;
        $reward->is_active = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            $imageContent = file_get_contents($image->getRealPath());
            $base64Image = base64_encode($imageContent);
            
            $mimeType = $image->getMimeType();
            $reward->image = 'data:' . $mimeType . ';base64,' . $base64Image;
        }

        $reward->save();

        return redirect()->route('rewards')
            ->with('success', 'Reward added successfully!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'price_reward' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'points_required' => 'required|integer|min:1',
            'redemption_limit' => 'nullable|integer|min:5',
            'expiry_date' => 'nullable|date',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif,JPG,JPEG|max:5120',
            'is_active' => 'nullable|boolean'
        ]);

        $reward = Reward::findOrFail($id);
        $reward->price_reward = $validated['price_reward'];
        $reward->description = $validated['description'];
        $reward->points_required = $validated['points_required'];
        $reward->redemption_limit = $validated['redemption_limit'] ?? null;
        $reward->expiry_date = $validated['expiry_date'] ?? null;
        $reward->is_active = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            $imageContent = file_get_contents($image->getRealPath());
            $base64Image = base64_encode($imageContent);
            
            $mimeType = $image->getMimeType();
            $reward->image = 'data:' . $mimeType . ';base64,' . $base64Image;
        }

        $reward->save();

        return redirect()->route('rewards')
            ->with('success', 'Reward updated successfully!');
    }

    public function destroy($id)
    {
        $reward = Reward::findOrFail($id);
        
        $reward->delete();

        return redirect()->route('rewards')
            ->with('success', 'Reward deleted successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:approved,rejected,completed,pending',
                'proof_of_payment' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120'
            ]);

            $redemption = RedeemedHistory::findOrFail($id);
            
            $redemption->status = ucfirst($validated['status']);
            
            if ($validated['status'] === 'approved' && $request->hasFile('proof_of_payment')) {
                $proofImage = $request->file('proof_of_payment');
                
                $imageContent = file_get_contents($proofImage->getRealPath());
                $base64Image = base64_encode($imageContent);
                $mimeType = $proofImage->getMimeType();
                $redemption->proof_of_payment = 'data:' . $mimeType . ';base64,' . $base64Image;
                
                Log::info('Proof of payment uploaded for redemption ID: ' . $id);
            }
            
            $redemption->viewed = '0';
            $redemption->save();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Redemption status updated successfully!',
                    'data' => [
                        'id' => $redemption->id,
                        'status' => $redemption->status,
                        'has_proof' => !empty($redemption->proof_of_payment)
                    ]
                ], 200);
            }

            return redirect()->route('rewards')->with('success', 'Redemption status updated successfully!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error updating redemption status: ' . $e->getMessage());
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update redemption status: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to update redemption status: ' . $e->getMessage());
        }
    }
    
    public function viewProof($id)
    {
        $redemption = RedeemedHistory::findOrFail($id);
        
        if (empty($redemption->proof_of_payment)) {
            return response()->json([
                'success' => false,
                'message' => 'No proof of payment available'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'proof_url' => $redemption->proof_of_payment
        ]);
    }
}