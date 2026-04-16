@extends('layouts.header')
@section('css')
<style>
    .back-btn i {
        color: #5DADE2;
        font-size: 20px;
    }

    /* Tab Navigation Styles */
    .rewards-tabs {
        background: white;
        border-radius: 20px;
        padding: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: flex;
        gap: 8px;
        width: 100%;
    }

    .tab-button {
        background: transparent;
        border: none;
        padding: 12px 16px;
        border-radius: 14px;
        font-weight: 600;
        font-size: 14px;
        color: #666;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        white-space: nowrap;
        flex: 1;
    }

    @media (min-width: 768px) {
        .tab-button {
            padding: 12px 20px;
            font-size: 15px;
            flex: 0 1 auto;
        }
        
        .rewards-tabs {
            width: auto;
        }
    }

    .tab-button:hover {
        color: #e3342f;
    }

    .tab-button.active {
        /* background: linear-gradient(180deg, #5DADE2 0%, #6BB8E8 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(93, 173, 226, 0.4); */
        background: linear-gradient(180deg, #ff6b59 0%, #e3342f 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(255, 107, 89, 0.38);
    }

    .tab-badge {
        position: absolute;
        top: 0px;
        right: 0px;
        background: #E74C3C;
        color: white;
        border-radius: 10px;
        padding: 2px 6px;
        font-size: 11px;
        font-weight: 700;
        min-width: 18px;
        text-align: center;
    }

    .tab-button.active .tab-badge {
        background: white;
        color: #5DADE2;
    }

    /* Tab Content */
    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .add-reward-btn {
        /* background: linear-gradient(180deg, #5DADE2 0%, #6BB8E8 100%); */
        background: linear-gradient(180deg, #ff6b59 0%, #e3342f 100%);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 25px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
        width: 100%;
    }

    @media (min-width: 768px) {
        .add-reward-btn {
            width: auto;
        }
    }

    .add-reward-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
    }

    .add-reward-btn i {
        margin-right: 8px;
    }

    #addRewardModal input::placeholder, .editRewardModal input::placeholder {
        color: #888888c4 !important;
        opacity: 1 !important;
    }

    /* Rewards Grid Container */
    .rewards-grid-container {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Reward Card Styles */
    .reward-card-small {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        position: relative;
        height: 100%;
        transition: all 0.3s ease;
    }

    .reward-card-small:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }

    /* Action Buttons Overlay */
    .reward-actions {
        position: absolute;
        top: 8px;
        left: 8px;
        display: flex;
        gap: 6px;
        z-index: 10;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .reward-card-small:hover .reward-actions {
        opacity: 1;
    }

    .reward-action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .reward-action-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    }

    .reward-action-btn.edit {
        background: #3498DB;
        color: white;
    }

    .reward-action-btn.edit:hover {
        background: #2980B9;
    }

    .reward-action-btn.delete {
        background: #E74C3C;
        color: white;
    }

    .reward-action-btn.delete:hover {
        background: #C0392B;
    }

    .reward-image-small {
        width: 100%;
        height: 140px;
        background: #f5f5f5;
        position: relative;
        overflow: visible;
    }

    .reward-image-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .reward-image-small .placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 40px;
    }

    .reward-info-small {
        padding: 15px 12px 12px;
    }

    .badge {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #007CFF;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        bottom: -10px;
        right: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        z-index: 10;
    }

    .badge img {
        width: 100%;
        height: auto;
    }

    .reward-title-small {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 6px;
    }

    .reward-subtitle-small {
        font-size: 13px;
        color: #666;
        margin-bottom: 6px;
    }

    .reward-points-small {
        font-size: 12px;
        color: #999;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .reward-points-small::before {
        content: "";
        display: inline-block;
        width: 16px;
        height: 16px;
        background-image: url('design/assets/images/profile/user-1.png');
        background-size: contain;
        background-repeat: no-repeat;
        margin-right: 4px;
        vertical-align: middle;
        border: 1px solid #5BC2E7;
        border-radius: 50%;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
    }

    .reward-status-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(231, 76, 60, 0.9);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        z-index: 1;
    }

    .reward-status-badge.active {
        background: rgba(39, 174, 96, 0.9);
    }

    /* Redemptions Table Styles */
    .redemptions-table-container {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        overflow-x: auto;
    }

    .redemptions-table thead {
        background: linear-gradient(180deg, #5DADE2 0%, #6BB8E8 100%);
        color: white;
    }

    .redemptions-table th:first-child {
        border-radius: 12px 0 0 0;
    }

    .redemptions-table th:last-child {
        border-radius: 0 12px 0 0;
    }


    .user-avatar1 {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
        flex-shrink: 0;
    }

    .user-details {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-weight: 600;
        color: #333;
    }

    .user-email {
        font-size: 12px;
        color: #666;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .status-badge.pending {
        background: #FFF3CD;    
        color: #856404;
    }

    .status-badge.submitted {
        background: #ffe2cdff;
        color: #853c04ff;
    }

    .status-badge.approved {
        background: #D4EDDA;
        color: #155724;
    }

    .status-badge.rejected {
        background: #F8D7DA;
        color: #721C24;
    }

    .status-badge.completed {
        background: #D1ECF1;
        color: #0C5460;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 16px;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .btn-action:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-approve {
        background: #27AE60;
        color: white;
    }

    .btn-approve:hover:not(:disabled) {
        background: #229954;
    }

    .btn-reject {
        background: #E74C3C;
        color: white;
    }

    .btn-reject:hover:not(:disabled) {
        background: #C0392B;
    }

    .btn-view {
        background: #5DADE2;
        color: white;
    }

    .btn-view:hover {
        background: #4A9DD1;
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        color: #ddd;
    }

    .modal-recipient-details {
        padding: 20px;
    }
    
    .recipient-info-item {
        margin-bottom: 15px;
    }
    
    .recipient-info-label {
        font-size: 13px;
        font-weight: 600;
        color: #666;
        margin-bottom: 5px;
    }
    
    .recipient-info-value {
        font-size: 16px;
        font-weight: 700;
        color: #333;
    }
    
    .recipient-qr-container {
        text-align: center;
        padding: 20px;
    }
    
    .recipient-qr-container img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Tab Navigation and Add Button Row -->
            <div class="row align-items-center mb-3 g-2">
                <!-- Tab Navigation - Left Side -->
                <div class="col-12 col-md-auto">
                    <div class="rewards-tabs">
                        <button class="tab-button active" onclick="switchTab('rewards')">
                            <i class="fas fa-gift"></i> <span class="d-none d-sm-inline">Manage Rewards</span><span class="d-inline d-sm-none">Rewards</span>
                        </button>
                        <button class="tab-button" onclick="switchTab('redemptions')">
                            <i class="fas fa-clipboard-list"></i> <span class="d-sm-inline">Reward Approval</span>
                            @if($pendingCount > 0)
                                <span class="tab-badge">{{ $pendingCount }}</span>
                            @endif
                        </button>
                    </div>
                </div>
                @php
                     // Check current user permissions
                    $currentUser = auth()->user();
                    $canEdit = $currentUser && $currentUser->role === 'Admin' && $currentUser->can_edit_rewards === 'on';
                    $canAdd = $currentUser && $currentUser->role === 'Admin' && $currentUser->can_add_rewards === 'on';
                    $canDelete = $currentUser && $currentUser->role === 'Admin' && $currentUser->can_delete_rewards === 'on';
                @endphp

                @if($canAdd)
                <div class="col-12 col-md-auto ms-md-auto" id="addRewardBtnContainer">
                    <button class="add-reward-btn btn-danger" data-bs-toggle="modal" data-bs-target="#addRewardModal">
                        <i class="fas fa-plus"></i> Add New Reward
                    </button>
                </div>
                @endif
            </div>

            <!-- Tab 1: Manage Rewards -->
            <div id="rewards-tab" class="tab-content active">
                <!-- Rewards Grid -->
                <div class="rewards-grid-container">
                    @if($rewards->count() > 0)
                        <div class="row g-3">
                            @foreach($rewards as $reward)
                                <div class="col-12 col-sm-6 col-lg-4"
                                    data-reward-id="{{ $reward->id }}"
                                    data-points-required="{{ $reward->points_required }}"
                                    data-reward-value="{{ $reward->value ?? 100 }}"
                                    data-reward-partner="{{ $reward->partner_name ?? $reward->description }}"
                                    data-is-active="{{ $reward->is_active ? '1' : '0' }}"
                                    data-is-expired="{{ $reward->isExpired() ? '1' : '0' }}">
                                    
                                    
                                    <div class="reward-card-small {{ $reward->is_limit_reached ? 'limit-reached' : '' }}">
                                        @if($canEdit || $canDelete)
                                        <div class="reward-actions">
                                            @if($canEdit)
                                            <button class="reward-action-btn edit btn-edit" 
                                                    onclick="handleEditReward({{ $reward->id }}, {{ $reward->has_claims ? 'true' : 'false' }})"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endif
                                            
                                            @if($canDelete)
                                            <button class="reward-action-btn delete" 
                                                    onclick="handleDeleteReward({{ $reward->id }}, {{ $reward->has_claims ? 'true' : 'false' }})"
                                                    title="Delete Reward">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                        @endif

                                        <div class="reward-image-small">
                                            @if($reward->image)
                                                <img src="{{ $reward->image }}" alt="{{ $reward->name }}" style="{{ $reward->is_limit_reached ? 'opacity: 0.5; filter: grayscale(100%);' : '' }}">
                                            @else
                                                <div class="placeholder" style="{{ $reward->is_limit_reached ? 'opacity: 0.5; filter: grayscale(100%);' : '' }}">
                                                    <i class="fas fa-gift"></i>
                                                </div>
                                            @endif

                                            <div class="badge">
                                                <img src="{{ asset('images/gcash-logo.png') }}" alt="badge">
                                            </div>
                                            
                                            <!-- Status Badge -->
                                            @if($reward->is_limit_reached)
                                                <span class="reward-status-badge" style="background: rgba(149, 165, 166, 0.9);">Fully Redeemed</span>
                                            @elseif(!$reward->is_active)
                                                <span class="reward-status-badge">Inactive</span>
                                            @elseif($reward->isExpired())
                                                <span class="reward-status-badge">Expired</span>
                                            @elseif($reward->is_active)
                                                <span class="reward-status-badge active">Available</span>
                                            @endif
                                        </div>
                                        
                                        <div class="reward-info-small">
                                            <div class="reward-title-small">₱{{ $reward->price_reward }} Rewards</div>
                                            <div class="reward-subtitle-small">{{ $reward->description }}</div>
                                            <div class="reward-points-small">{{ number_format($reward->points_required) }} points</div>
                                            
                                            <!-- Claim Count Display with Limit -->
                                            <div class="reward-claim-count" style="margin-top: 8px; font-size: 12px; color: {{ $reward->is_limit_reached ? '#95A5A6' : '#5DADE2' }}; font-weight: 600;">
                                                <i class="fas fa-users"></i> {{ $reward->claims_count ?? 0 }} 
                                                @if($reward->redemption_limit)
                                                    / {{ $reward->redemption_limit }}
                                                @endif
                                                {{ ($reward->claims_count ?? 0) === 1 ? 'claim' : 'claims' }}
                                                @if($reward->is_limit_reached)
                                                    <span style="color: #E74C3C; font-weight: 700; margin-left: 5px;">
                                                        <i class="fas fa-exclamation-circle"></i> Full
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-gift"></i>
                            <p>No rewards available at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tab 2: Redemption History -->
            <div id="redemptions-tab" class="tab-content">
                <div class="redemptions-table-container">
                    <div class="row g-2 mb-3">
                        <div class="col-12 col-md-2">
                            <select class="form-select" id="entriesPerPage">
                                <option value="10">Show 10</option>
                                <option value="25">Show 25</option>
                                <option value="50">Show 50</option>
                                <option value="100">Show 100</option>
                                <option value="all">Show All</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <input type="text" class="form-control" placeholder="Search by user name or email..." id="searchRedemptions">
                        </div>
                        <div class="col-12 col-md-4">
                            <select class="form-select" id="filterStatus">
                                <option value="all">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="submitted">Submitted</option>
                                <option value="approved">Approved</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive" style="font-size: 12px;">
                        <table class="table table-hover redemptions-table mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Reward</th>
                                    <th>Points Used</th>
                                    <!-- <th>Balance Before</th> -->
                                    <!-- <th>Balance After</th> -->
                                    <th>Date Redeemed</th>
                                    <th>Recipient</th>
                                    <th>Proof of Payment</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="redemptionTableBody">
                                @if(isset($redeemhistory) && $redeemhistory->count() > 0)
                                    @foreach($redeemhistory as $history)
                                        <tr class="redemption-row" 
                                            data-username="{{ strtolower($history->user->name ?? '') }}"
                                            data-email="{{ strtolower($history->user->email ?? '') }}"
                                            data-status="{{ strtolower($history->status) }}">
                                            <td>
                                                <div class="user-info">
                                                    <div class="user-avatar1">
                                                        {{ strtoupper(substr($history->user->name ?? 'U', 0, 2)) }}
                                                    </div>
                                                    <div class="user-details">
                                                        <span class="user-name">{{ $history->user->name ?? 'Unknown User' }}</span>
                                                        <span class="user-email">{{ $history->user->email ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $history->reward_name }}</strong>
                                                <div style="font-size: 12px; color: #666;">{{ $history->description }}</div>
                                            </td>
                                            <td>
                                                <span style="color: #E74C3C; font-weight: 600;">
                                                    {{ number_format(abs($history->points_amount)) }}
                                                </span>
                                            </td>
                                            <!-- <td>{{ number_format($history->balance_before) }}</td> -->
                                            <!-- <td>{{ number_format($history->balance_after) }}</td> -->
                                            <td>{{ $history->created_at->format('M d, Y h:i A') }}</td>
                                            <td>
                                                <button class="btn-action btn-view" 
                                                        onclick="viewRecipientDetails({{ $history->id }})"
                                                        title="View Recipient Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                            <td>
                                                @if($history->proof_of_payment)
                                                    <button class="btn-action btn-view" 
                                                            onclick="viewProofOfPayment('{{ $history->proof_of_payment }}')"
                                                            title="View Proof of Payment">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                @else
                                                    <span style="color: #999; font-size: 11px;">Not uploaded</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="status-badge {{ strtolower($history->status) }}">
                                                    {{ ucfirst($history->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    @if(strtolower($history->status) === 'submitted')
                                                        <button class="btn-action btn-approve" 
                                                                onclick="updateRedemptionStatus({{ $history->id }}, 'approved')"
                                                                title="Approve">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @elseif(strtolower($history->status) === 'pending')
                                                        <span style="color: #999; font-size: 12px;">Pending</span>
                                                    @else
                                                        <span style="color: #999; font-size: 12px;">Successfully Sent</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="empty-row">
                                        <td colspan="10">
                                            <div class="empty-state">
                                                <i class="fas fa-clipboard-list"></i>
                                                <p>No redemption history yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="mb-2 mt-2">
                            <small class="text-muted" id="showingInfo"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Reward Modal -->
<div class="modal fade" id="addRewardModal" tabindex="-1" aria-labelledby="addRewardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRewardModalLabel">Add New Reward</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addRewardForm" action="{{ route('rewards.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rewardName" class="form-label">Reward Amount <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="rewardName" name="price_reward" placeholder="e.g., 100" required>
                    </div>

                    <div class="mb-3">
                        <label for="rewardDescription" class="form-label">Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="rewardDescription" name="description" placeholder="e.g., Gcash" required>
                    </div>

                    <div class="mb-3">
                        <label for="pointsRequired" class="form-label">Points Required <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="pointsRequired" name="points_required" min="1" placeholder="e.g., 200" required>
                    </div>

                    <div class="mb-3">
                        <label for="redemption_limit" class="form-label">Redemption Limit</label>
                        <input type="text" class="form-control" id="redemption_limit" name="redemption_limit" placeholder="e.g., 10">
                        <small class="form-text text-muted">Leave empty for no redemption limit</small>
                    </div>

                    <div class="mb-3">
                        <label for="expiryDate" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="expiryDate" name="expiry_date">
                        <small class="form-text text-muted">Leave empty for no expiry</small>
                    </div>

                    <div class="mb-3">
                        <label for="rewardImage" class="form-label">Reward Image</label>
                        <input type="file" class="form-control" id="rewardImage" name="image" accept="image/*">
                        <small class="form-text text-muted">Recommended size: 400x300px</small>
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <img src="" alt="Preview" class="img-fluid rounded">
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="isActive" name="is_active" value="1" checked>
                        <label class="form-check-label" for="isActive">
                            Active (Available for redemption)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Reward</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($rewards as $reward)
<!-- Edit Reward Modal -->
<div class="modal fade editRewardModal" id="editRewardModal{{ $reward->id }}" tabindex="-1" aria-labelledby="editRewardModalLabel{{ $reward->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRewardModalLabel{{ $reward->id }}">Edit Reward</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('rewards.update', $reward->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editRewardName{{ $reward->id }}" class="form-label">Reward Amount <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="editRewardName{{ $reward->id }}" name="price_reward" 
                               value="{{ $reward->price_reward }}" 
                               placeholder="e.g., 100" required>
                    </div>

                    <div class="mb-3">
                        <label for="editRewardDescription{{ $reward->id }}" class="form-label">Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editRewardDescription{{ $reward->id }}" name="description" 
                               value="{{ $reward->description }}" 
                               placeholder="e.g., Gcash" required>
                    </div>

                    <div class="mb-3">
                        <label for="editPointsRequired{{ $reward->id }}" class="form-label">Points Required <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="editPointsRequired{{ $reward->id }}" name="points_required" 
                               value="{{ $reward->points_required }}" 
                               min="1" placeholder="e.g., 200" required>
                    </div>

                    <div class="mb-3">
                        <label for="editRedemptionLimit{{ $reward->id }}" class="form-label">Redemption Limit</label>
                        <input type="text" class="form-control" id="editRedemptionLimit{{ $reward->id }}" name="redemption_limit" 
                               value="{{ $reward->redemption_limit ?? '' }}" 
                               placeholder="e.g., 10">
                        <small class="form-text text-muted">Leave empty for no redemption limit</small>
                    </div>

                    <div class="mb-3">
                        <label for="editExpiryDate{{ $reward->id }}" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="editExpiryDate{{ $reward->id }}" name="expiry_date" 
                               value="{{ $reward->expiry_date ? \Carbon\Carbon::parse($reward->expiry_date)->format('Y-m-d') : '' }}">
                        <small class="form-text text-muted">Leave empty for no expiry</small>
                    </div>

                    <div class="mb-3">
                        <label for="editRewardImage{{ $reward->id }}" class="form-label">Reward Image</label>
                        <input type="file" class="form-control" id="editRewardImage{{ $reward->id }}" name="image" accept="image/*">
                        <small class="form-text text-muted">Leave empty to keep current image</small>
                        
                        @if($reward->image)
                            <div class="mt-2">
                                <p class="text-muted mb-1">Current Image:</p>
                                <img src="{{ $reward->image }}" alt="Current Reward" class="img-fluid rounded" style="max-width: 200px;">
                            </div>
                        @endif
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="editIsActive{{ $reward->id }}" name="is_active" 
                               value="1" {{ $reward->is_active == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="editIsActive{{ $reward->id }}">
                            Active (Available for redemption)
                        </label>    
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Reward</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Recipient Modal -->
<div class="modal fade" id="recipientModal" tabindex="-1" aria-labelledby="recipientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recipientModalLabel">Recipient Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-recipient-details" id="recipientModalBody">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Display Session Messages as SweetAlert -->
@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#E74C3C',
        timer: 5000,
        timerProgressBar: true
    });
</script>
@endif

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#27AE60',
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif

@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: '<ul style="text-align: left; padding-left: 20px;">' +
            @foreach($errors->all() as $error)
                '<li>{{ $error }}</li>' +
            @endforeach
            '</ul>',
        confirmButtonColor: '#E74C3C',
        width: '500px'
    });
</script>
@endif

<script>
    // Handle Edit Reward with Claims Check
    function handleEditReward(rewardId, hasClaims) {
        if (hasClaims) {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Edit Reward',
                text: 'You are not able to edit this reward because it has already been claimed by users.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        // Open the edit modal
        const modal = new bootstrap.Modal(document.getElementById('editRewardModal' + rewardId));
        modal.show();
    }

    // Handle Delete Reward with Claims Check
    function handleDeleteReward(rewardId, hasClaims) {
        if (hasClaims) {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Delete Reward',
                text: 'You are not able to delete this reward because it has already been claimed by users.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        // Proceed with delete confirmation
        deleteReward(rewardId);
    }

    // Delete Reward Function
    function deleteReward(rewardId) {
        Swal.fire({
            title: 'Delete Reward?',
            text: "This action cannot be undone. Are you sure you want to delete this reward?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E74C3C',
            cancelButtonColor: '#95a5a6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit delete form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ url('rewards') }}/" + rewardId;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                
                form.submit();
            }
        });
    }

    // Filter and Show Entries
    document.addEventListener('DOMContentLoaded', function() {
        const entriesSelect = document.getElementById('entriesPerPage');
        const searchInput = document.getElementById('searchRedemptions');
        const statusFilter = document.getElementById('filterStatus');
        const showingInfo = document.getElementById('showingInfo');

        function filterAndShowEntries() {
            const rows = document.querySelectorAll('.redemption-row');
            const entries = entriesSelect.value;
            const search = searchInput.value.toLowerCase();
            const status = statusFilter.value;

            let visibleCount = 0;
            let totalFiltered = 0;

            rows.forEach(row => {
                const username = row.getAttribute('data-username');
                const email = row.getAttribute('data-email');
                const rowStatus = row.getAttribute('data-status');

                // Check search filter
                const matchesSearch = search === '' || 
                    username.includes(search) || 
                    email.includes(search);

                // Check status filter
                const matchesStatus = status === 'all' || rowStatus === status;

                if (matchesSearch && matchesStatus) {
                    totalFiltered++;
                    if (entries === 'all' || visibleCount < parseInt(entries)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                } else {
                    row.style.display = 'none';
                }
            });

            // Update showing info
            if (totalFiltered === 0) {
                showingInfo.textContent = 'No entries found';
            } else {
                showingInfo.textContent = `Showing ${visibleCount} of ${totalFiltered} entries`;
            }
        }

        // Event listeners
        if (entriesSelect) entriesSelect.addEventListener('change', filterAndShowEntries);
        if (searchInput) searchInput.addEventListener('input', filterAndShowEntries);
        if (statusFilter) statusFilter.addEventListener('change', filterAndShowEntries);

        // Initial display
        filterAndShowEntries();
    });

    // Store rewards data for editing
    const rewardsData = {
        @foreach($rewards as $reward)
            {{ $reward->id }}: {
                price_reward: {{ $reward->price_reward }},
                description: "{{ $reward->description }}",
                points_required: {{ $reward->points_required }},
                expiry_date: "{{ $reward->expiry_date ?? '' }}",
                is_active: {{ $reward->is_active ? 'true' : 'false' }},
                image: {!! json_encode($reward->image ?? '') !!}
            },
        @endforeach
    };

    function switchTab(tabName) {
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        
        event.target.closest('.tab-button').classList.add('active');
        document.getElementById(tabName + '-tab').classList.add('active');
        
        const addRewardBtn = document.getElementById('addRewardBtnContainer');
        if (addRewardBtn) {
            if (tabName === 'rewards') {
                addRewardBtn.style.display = 'block';
            } else {
                addRewardBtn.style.display = 'none';
            }
        }
    }

    function handleImagePreview(e) {
        const file = e.target.files[0];
        const imagePreview = document.getElementById('imagePreview');
        
        if (file) {
            // Check file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: `Image size: ${(file.size / 1024 / 1024).toFixed(2)}MB. Maximum allowed: 5MB`,
                    confirmButtonColor: '#E74C3C'
                });
                e.target.value = '';
                if (imagePreview) imagePreview.style.display = 'none';
                return;
            }

            // Check file type
            if (!file.type.match('image.*')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please upload an image file (JPG, PNG, GIF)',
                    confirmButtonColor: '#E74C3C'
                });
                e.target.value = '';
                if (imagePreview) imagePreview.style.display = 'none';
                return;
            }

            // Show preview
            if (imagePreview) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.querySelector('img').src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }
    }

    function filterTable(searchTerm, status) {
        const rows = document.querySelectorAll('.redemptions-table tbody tr');
        
        rows.forEach(row => {
            if (row.querySelector('.empty-state')) {
                return;
            }
            
            const userInfo = row.querySelector('.user-info')?.textContent.toLowerCase() || '';
            const statusBadge = row.querySelector('.status-badge')?.textContent.toLowerCase() || '';
            
            const matchesSearch = userInfo.includes(searchTerm);
            const matchesStatus = status === 'all' || statusBadge.includes(status);
            
            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function updateRedemptionStatus(rewardsId, status) {
        const actionText = status === 'approved' ? 'approve' : 'reject';
        const actionColor = status === 'approved' ? '#27AE60' : '#E74C3C';
        
        Swal.fire({
            title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} Redemption?`,
            text: `Are you sure you want to ${actionText} this redemption request?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: actionColor,
            cancelButtonColor: '#95a5a6',
            confirmButtonText: `Yes, ${actionText} it!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                if (status === 'approved') {
                    showProofOfPaymentUpload(rewardsId);
                } else {
                    submitStatusUpdate(rewardsId, status, null);
                }
            }
        });
    }

    function showProofOfPaymentUpload(rewardsId) {
        Swal.fire({
            title: 'Upload Proof of Payment',
            html: `
                <div style="text-align: left; padding: 10px;">
                    <p style="margin-bottom: 15px; color: #666; font-size: 14px;">
                        Please upload proof of payment for this approved reward.
                    </p>
                    
                    <label for="proofOfPayment" style="
                        display: block;
                        width: 100%;
                        padding: 40px 20px;
                        border: 2px dashed #5DADE2;
                        border-radius: 12px;
                        text-align: center;
                        cursor: pointer;
                        background: #f8f9fa;
                        transition: all 0.3s ease;
                        margin-bottom: 15px;
                    " onmouseover="this.style.borderColor='#4A9DD1'; this.style.background='#e8f4f8';" 
                    onmouseout="this.style.borderColor='#5DADE2'; this.style.background='#f8f9fa';">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #5DADE2; margin-bottom: 10px;"></i>
                        <div style="font-size: 16px; font-weight: 600; color: #333; margin-bottom: 5px;">
                            Click to upload proof of payment
                        </div>
                        <div style="font-size: 12px; color: #999;">
                            PNG, JPG, GIF up to 5MB
                        </div>
                    </label>
                    
                    <input type="file" 
                        id="proofOfPayment" 
                        accept="image/*" 
                        style="display: none;"
                        required>
                    
                    <div id="imagePreviewContainer" style="
                        display: none;
                        margin-top: 15px;
                        padding: 15px;
                        background: #f8f9fa;
                        border-radius: 12px;
                        border: 1px solid #dee2e6;
                    ">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <span style="font-size: 13px; font-weight: 600; color: #333;">
                                <i class="fas fa-image" style="color: #5DADE2; margin-right: 5px;"></i>
                                Image Preview
                            </span>
                            <button type="button" id="removeImage" style="
                                background: #E74C3C;
                                color: white;
                                border: none;
                                padding: 4px 8px;
                                border-radius: 6px;
                                font-size: 11px;
                                cursor: pointer;
                            ">
                                <i class="fas fa-times"></i> Remove
                            </button>
                        </div>
                        <div id="imageInfo" style="
                            margin-top: 10px;
                            font-size: 12px;
                            color: #666;
                            text-align: center;
                        "></div>
                    </div>
                </div>
            `,
            width: '500px',
            showCancelButton: true,
            confirmButtonColor: '#27ae60',
            cancelButtonColor: '#95a5a6',
            confirmButtonText: '<i class="fas fa-check"></i> Submit & Approve',
            cancelButtonText: '<i class="fas fa-times"></i> Cancel',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            didOpen: () => {
                const fileInput = document.getElementById('proofOfPayment');
                const fileLabel = fileInput.previousElementSibling;
                const previewContainer = document.getElementById('imagePreviewContainer');
                const imageInfo = document.getElementById('imageInfo');
                const removeBtn = document.getElementById('removeImage');
                
                fileLabel.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    fileInput.click();
                });
                
                fileInput.addEventListener('change', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const file = e.target.files[0];
                    
                    if (!file) {
                        previewContainer.style.display = 'none';
                        fileLabel.style.display = 'block';
                        return;
                    }
                    
                    // Check file size
                    if (file.size > 5 * 1024 * 1024) {
                        Swal.showValidationMessage(`File too large: ${(file.size / 1024 / 1024).toFixed(2)}MB (Max: 5MB)`);
                        fileInput.value = '';
                        previewContainer.style.display = 'none';
                        fileLabel.style.display = 'block';
                        return;
                    }
                    
                    // Check file type
                    if (!file.type.match('image.*')) {
                        Swal.showValidationMessage('Please upload a valid image file');
                        fileInput.value = '';
                        previewContainer.style.display = 'none';
                        fileLabel.style.display = 'block';
                        return;
                    }
                    
                    const fileSizeKB = (file.size / 1024).toFixed(2);
                    previewContainer.style.display = 'block';
                    fileLabel.style.display = 'none';
                    
                    imageInfo.innerHTML = `
                        <strong>${file.name}</strong><br>
                        Size: ${fileSizeKB} KB
                    `;
                    
                    const validationMessage = document.querySelector('.swal2-validation-message');
                    if (validationMessage) {
                        validationMessage.style.display = 'none';
                    }
                });
                
                removeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    fileInput.value = '';
                    previewContainer.style.display = 'none';
                    fileLabel.style.display = 'block';
                    imageInfo.innerHTML = '';
                });
            },
            preConfirm: () => {
                const fileInput = document.getElementById('proofOfPayment');
                const file = fileInput.files[0];
                
                if (!file) {
                    Swal.showValidationMessage('Please upload proof of payment image');
                    return false;
                }
                
                if (file.size > 5 * 1024 * 1024) {
                    Swal.showValidationMessage(`File too large: ${(file.size / 1024 / 1024).toFixed(2)}MB (Max: 5MB)`);
                    return false;
                }
                
                if (!file.type.match('image.*')) {
                    Swal.showValidationMessage('Please upload a valid image file');
                    return false;
                }
                
                return file;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                submitStatusUpdate(rewardsId, 'approved', result.value);
            }
        });
    }

    function submitStatusUpdate(rewardsId, status, proofFile) {
        Swal.fire({
            title: 'Processing...',
            html: `
                <div style="text-align: center;">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p style="margin-top: 15px; color: #666;">
                        Please wait while we ${status === 'approved' ? 'approve' : 'reject'} the redemption...
                    </p>
                </div>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false
        });

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('status', status);
        
        if (proofFile) {
            formData.append('proof_of_payment', proofFile);
        }

        fetch("{{ url('rewards') }}/" + rewardsId + "/update-status", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Failed to update status');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    html: `
                        <div style="text-align: center;">
                            <p>Redemption has been <strong>${status === 'approved' ? 'approved' : 'rejected'}</strong> successfully.</p>
                            ${data.data && data.data.has_proof ? '<p style="color: #27AE60; margin-top: 10px;"><i class="fas fa-check-circle"></i> Proof of payment uploaded</p>' : ''}
                        </div>
                    `,
                    confirmButtonColor: '#5DADE2',
                    timer: 2500,
                    showConfirmButton: true
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(data.message || 'Failed to update status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Failed to update redemption status. Please try again.',
                confirmButtonColor: '#E74C3C'
            });
        });
    }

    // Event listeners for search and filters
    const searchInput = document.getElementById('searchRedemptions');
    const filterStatus = document.getElementById('filterStatus');
    
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            filterTable(searchTerm, filterStatus ? filterStatus.value : 'all');
        });
    }
    
    if (filterStatus) {
        filterStatus.addEventListener('change', function(e) {
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
            filterTable(searchTerm, e.target.value);
        });
    }

    // Image preview handlers
    const rewardImage = document.getElementById('rewardImage');
    if (rewardImage) {
        rewardImage.addEventListener('change', handleImagePreview);
    }

    // Recipient Data
    const recipientData = {
        @foreach($redeemhistory as $history)
            {{ $history->id }}: {
                gcash_number: "{{ $history->number ?? '' }}",
                gcash_name: "{{ $history->gcash_name ?? '' }}",
                qr_code: {!! json_encode($history->qr_code ?? '') !!}
            },
        @endforeach
    };

    function viewRecipientDetails(redemptionId) {
        const modal = new bootstrap.Modal(document.getElementById('recipientModal'));
        const modalBody = document.getElementById('recipientModalBody');
        
        const data = recipientData[redemptionId];
        
        if (!data) {
            modalBody.innerHTML = '<p class="text-center text-muted">No recipient information available.</p>';
            modal.show();
            return;
        }
        
        let content = '';
        
        const hasGcashNumber = data.gcash_number && data.gcash_number.trim() !== '';
        const hasQrCode = data.qr_code && data.qr_code.trim() !== '';
        
        if (hasGcashNumber) {
            content = `
                <div class="recipient-info-item">
                    <div class="recipient-info-label">GCash Number</div>
                    <div class="recipient-info-value">${data.gcash_number}</div>
                </div>
                <div class="recipient-info-item">
                    <div class="recipient-info-label">GCash Name</div>
                    <div class="recipient-info-value">${data.gcash_name || 'N/A'}</div>
                </div>
            `;
        } else if (hasQrCode) {
            content = `
                <div class="recipient-qr-container">
                    <div class="recipient-info-label mb-3">GCash QR Code</div>
                    <img src="${data.qr_code}" alt="QR Code" style="max-width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                </div>
            `;
        } else {
            content = '<p class="text-center text-muted">No recipient information available.</p>';
        }
        
        modalBody.innerHTML = content;
        modal.show();
    }

    function viewProofOfPayment(imageUrl) {
        if (!imageUrl) {
            Swal.fire({
                icon: 'info',
                title: 'No Proof of Payment',
                text: 'Proof of payment has not been uploaded yet.',
                confirmButtonColor: '#5DADE2'
            });
            return;
        }
        
        Swal.fire({
            title: 'Proof of Payment',
            html: `
                <div style="text-align: center; padding: 10px;">
                    <img src="${imageUrl}" 
                         alt="Proof of Payment" 
                         style="max-width: 100%; max-height: 70vh; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                </div>
            `,
            width: '600px',
            confirmButtonText: 'Close',
            confirmButtonColor: '#5DADE2',
            showCloseButton: true
        });
    }
</script>
@endsection