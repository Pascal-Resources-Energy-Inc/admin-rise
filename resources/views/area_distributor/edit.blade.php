<div id="edit_area_distributor-{{ $ad->id }}" class="modal fade modal-select2" tabindex="-1" aria-labelledby="bs-example-modal-md" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myModalLabel">
                    Edit Area Distributor
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ url('edit-ads/'.$ad->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="latitude" id="hidden_latitude" value="{{ $ad->latitude }}">
                <input type="hidden" name="longitude" id="hidden_longitude" value="{{ $ad->longitude }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Store Code</label>
                            <input type="text" class="form-control" id="store_code" name="store_code" placeholder="Enter Store Code" value="{{ $ad->store_code }}">
                        </div>

                        <!-- Avatar Upload -->
                        <div class="col-md-6 text-center">
                            <div class="avatar-wrapper mx-auto mb-2">
                                <img id="avatar-{{ $ad->id }}" 
                                    src="{{ $ad->avatar ? asset($ad->avatar) : asset('design/assets/images/profile/user-1.png') }}">
                            </div>

                            <!-- ✅ FIX: match the ID -->
                            <label for="inputImage-{{ $ad->id }}" class="btn btn-outline-primary btn-sm">
                                <i class="ti ti-upload"></i> Upload Image
                            </label>

                            <input type="file" 
                                name="avatar"
                                id="inputImage-{{ $ad->id }}"
                                hidden
                                accept="image/*"
                                onchange="uploadImage(this, {{ $ad->id }})">

                            <small class="d-block text-muted mt-1">
                                JPG, PNG (Max: 2MB)
                            </small>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="name">Full Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control required" id="name" name="name" placeholder="Enter Full Name" value="{{ $ad->name }}" required/>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="email_address"> Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control required" id="email_address" name="email_address" placeholder="Enter Email Address" value="{{ $ad->email_address }}" required/>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="contact_number">Contact Number&nbsp;<span class="text-danger">*</span></label>
                            <input type="number" class="form-control required" id="contact_number" name="contact_number" placeholder="Enter Contact Number" step="0.01" value="{{ $ad->contact_number }}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="facebook">Facebook<span class="text-danger">*</span></label>
                            <input type="text" class="form-control required" id="facebook" name='facebook' placeholder="Enter Facebook" value="{{ $ad->facebook }}" required/>
                        </div>
                        {{-- <div class="col-md-12 mb-2">
                            <label class="form-label">Street Name, Building, House No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="street_address" id="street_address" value="{{ old('street_address') }}" placeholder="e.g., 1868 Kapalaran St" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Region <span class="text-danger">*</span></label>
                            <select class="form-control" id="location_region" name="location_region" required onclick="event.stopPropagation();">
                                <option value="">-- Select Region --</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Province <span class="text-danger">*</span></label>
                            <select class="form-control" id="location_province" name="location_province" required onclick="event.stopPropagation();" disabled>
                                <option value="">-- Select Region First --</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">City/Municipality <span class="text-danger">*</span></label>
                            <select class="form-control" id="location_city" name="location_city" required onclick="event.stopPropagation();" disabled>
                                <option value="">-- Select Province First --</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Barangay <span class="text-danger">*</span></label>
                            <select class="form-control" 
                                    name="location_barangay" 
                                    id="location_barangay" 
                                    required 
                                    onclick="event.stopPropagation();" 
                                    disabled>
                                <option value="">-- Select City First --</option>
                            </select>
                            <small class="form-text text-muted">Select barangay from the list</small>
                        </div> --}}
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="business_name">Business Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control required" id="business_name" name="business_name" placeholder="Enter Business Name" value="{{ $ad->business_name }}" required/>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="business_type">Business Type<span class="text-danger">*</span></label>
                            <input type="text" class="form-control required" id="business_type" name="business_type" placeholder="Enter Business Type" value="{{ $ad->business_type }}" required/>
                        </div>
                        {{-- <div class="col-md-6 mb-2">
                            <label class="form-label">Area<span class="text-danger">*</span></label>
                            <select class="form-control" id="area" name="area" required>
                                <option value="">-- Select Area --</option>
                                @foreach($centers as $center)
                                    <option value="{{ $center->name }}">{{ $center->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Area <span class="text-danger">*</span></label>
                            @php
                                $selectedAreas = $ad->areas->pluck('area_name')->toArray();
                            @endphp

                            <select class="form-control area_name" name="area_name[]" multiple required>
                                @foreach($centers as $center)
                                    <option value="{{ $center->name }}"
                                        {{ in_array($center->name, $selectedAreas) ? 'selected' : '' }}>
                                        {{ $center->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-12">
                            <div class="form-group">
                            <label>Pin Exact Location</span></label>
                            <div class="alert alert-warning d-flex align-items-start" role="alert">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16" style="min-width: 24px; margin-right: 10px;">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                </svg>
                                <div>
                                    <strong>Place an accurate pin</strong><br>
                                    <small>We will deliver to your map location. Please check if it is correct, else click the map to adjust the pin location.</small>
                                </div>
                            </div>
                            <div id="location_map" style="height: 400px; border-radius: 8px; border: 2px solid #dee2e6;"></div>
                                <div class="mt-2 p-2 bg-light rounded">
                                    <strong>Current Pin Location:</strong><br>
                                    Latitude: <span id="display_lat">--</span>, Longitude: <span id="display_lng">--</span>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Complete Address Preview</label>
                            <textarea class="form-control bg-light" id="full_address_preview" rows="2" readonly>{{ $ad->address }}</textarea>
                            <input type="hidden" name="address" value="{{ $ad->address }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-danger-subtle text-danger  waves-effect"data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-info-subtle text-info  waves-effect">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    #location_map {
        height: 400px;
        width: 100%;
    }
    .avatar-wrapper {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
    }

    .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function uploadImage(input, id) {
        const file = input.files[0];

        if (!file) return;

        if (!file.type.startsWith('image/')) {
            Swal.fire('Error', 'Please upload a valid image file.', 'error');
            input.value = '';
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            Swal.fire('Error', 'Image must be less than 2MB.', 'error');
            input.value = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            document.getElementById('avatar-' + id).src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
</script>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK'
        }).then(() => {
            location.reload();
        });
    });
</script>
@endif