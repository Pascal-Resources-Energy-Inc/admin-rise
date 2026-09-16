<style>
    #editCustomerModal .modal-dialog {
        height: calc(100% - 1rem);
        margin: 0.5rem auto;
    }

    #editCustomerModal .modal-content {
        max-height: 100%;
        overflow: hidden;
    }

    #editCustomerModal .modal-body {
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }

    @media (min-width: 576px) {
        #editCustomerModal .modal-dialog {
            height: calc(100% - 3.5rem);
            margin: 1.75rem auto;
        }
    }
</style>

<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('customer.update', $customer->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="customer_name">Full Name</label>
                            <input id="customer_name" type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="customer_email">Email Address</label>
                            <input id="customer_email" type="email" name="email_address" class="form-control" value="{{ old('email_address', $customer->email_address) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="customer_number">Contact Number</label>
                            <input id="customer_number" type="text" name="number" class="form-control" value="{{ old('number', $customer->number) }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="customer_serial_number">Serial Number</label>
                            <select id="customer_serial_number" name="serial_number" class="form-select">
                                <option value="">No serial number assigned</option>
                                @foreach($stoves as $stove)
                                    <option value="{{ $stove->id }}" {{ (string) old('serial_number', $customer->serial_number) === (string) $stove->id ? 'selected' : '' }}>
                                        {{ $stove->serial_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="customer_facebook">Facebook</label>
                            <input id="customer_facebook" type="text" name="facebook" class="form-control" value="{{ old('facebook', $customer->facebook) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="customer_region">Region</label>
                            <select id="customer_region" name="location_region" class="form-select" data-selected="{{ old('location_region', $customer->location_region) }}" required>
                                <option value="">Select Region</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="customer_province">Province</label>
                            <select id="customer_province" name="location_province" class="form-select" data-selected="{{ old('location_province', $customer->location_province) }}" required disabled>
                                <option value="">Select Region First</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="customer_city">City/Municipality</label>
                            <select id="customer_city" name="location_city" class="form-select" data-selected="{{ old('location_city', $customer->location_city) }}" required disabled>
                                <option value="">Select Province First</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="customer_barangay">Barangay</label>
                            <select id="customer_barangay" name="location_barangay" class="form-select" data-selected="{{ old('location_barangay', $customer->location_barangay) }}" required disabled>
                                <option value="">Select City First</option>
                            </select>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label" for="customer_street">Street Name, Building, House No.</label>
                            <input id="customer_street" type="text" name="street_address" class="form-control" value="{{ old('street_address', $customer->street_address) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="customer_postal_code">Postal Code</label>
                            <input id="customer_postal_code" type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $customer->postal_code) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="customer_spo">SPO</label>
                            <input id="customer_spo" type="text" name="spo" class="form-control" value="{{ old('spo', $customer->spo) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="customer_center">Center</label>
                            <input id="customer_center" type="text" name="center" class="form-control" value="{{ old('center', $customer->center) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="customer_area">Sales Territory <span class="text-danger">*</span></label>
                            <select id="customer_area" name="area" class="form-select sales-territory-select" data-match-url="{{ route('customer.sales-territories') }}" data-current-area="{{ old('area', $customer->area) }}" required disabled>
                                <option value="">Checking geographic coverage…</option>
                            </select>
                            <small class="form-text text-muted sales-territory-help">The territory is matched from the selected location.</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="customer_status">Status</label>
                            <select id="customer_status" name="status" class="form-select" required>
                                <option value="Active" {{ old('status', $customer->status) === 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status', $customer->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
