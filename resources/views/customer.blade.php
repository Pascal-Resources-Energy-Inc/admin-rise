@extends('layouts.header')
@section('css')
<link rel="stylesheet" href="{{asset('design/vendors/select2/select2.min.css')}}">
<style>
    /* Custom styling */
    .transaction-table th {
        text-align: center;
    }
    .btn-view {
        width: 100px;
        font-size: 14px;
    }
    .dashboard-stats {
        display: flex;
        justify-content: space-around;
    }
    .dashboard-stats div {
        text-align: center;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 30%;
    }
    /* Welcome section styling */
    .welcome {
        margin-top: 20px;
    }
    .card-header {
        font-size: 1.25rem;
        font-weight: bold;
    }
    .card-body {
        padding: 20px;
    }
    .filter-container {
        margin-bottom: 20px;
    }
    .customer-info-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .customer-info-header h5 {
        margin: 0;
    }
    .customer-info-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    @media (max-width: 575.98px) {
        .customer-info-back .back-label {
            display: none;
        }

        #editCustomerModal .modal-dialog {
            margin: 0.5rem;
        }
    }

    .sales-territory-select + .select2-container {
        width: 100% !important;
    }

    .sales-territory-select + .select2-container .select2-selection--single {
        min-height: 38px;
        padding: 0.375rem 0.75rem;
        border-color: #ced4da;
    }

    .sales-territory-select + .select2-container .select2-selection__rendered {
        line-height: 24px;
        padding: 0;
    }

    .sales-territory-select + .select2-container .select2-selection__arrow {
        height: 36px;
    }

    #editCustomerModal .select2-container--open {
        z-index: 1060;
    }

    @media (max-width: 575.98px) {
        .sales-territory-select + .select2-container .select2-selection--single {
            min-height: 44px;
        }

        .select2-search__field,
        .select2-results__option {
            font-size: 16px;
        }

        .select2-results__options {
            max-height: 45vh;
        }
    }
    
</style>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
@endsection
@section('content')
<section class="welcome">
     <!-- Customer Info Section -->
     <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header customer-info-header">
                    <h5>Customer Information</h5>
                    <a href="{{ route('customers') }}" class="btn btn-outline-secondary btn-sm customer-info-back" aria-label="Back to customers">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                        <span class="back-label">Back to Customers</span>
                    </a>
                </div>
                <div class="card-body">
                    <div class='text-center'>
                    <img src="{{$customer->avatar ? asset($customer->avatar) : asset('design/assets/images/profile/user-1.png')}}" alt="Avatar Image" class="img-fluid rounded-circle" style="width: 100px; height: 100px;">
                    </div>  
                    <br>
                   <div class='text-center mb-3'>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"  data-bs-target="#uploadAvatarModal" title="Upload Avatar">
                        <i class="fas fa-camera"></i>
                        <span class="sr-only">Upload Avatar</span>
                        </button>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editCustomerModal" title="Edit customer information">
                            <i class="fas fa-edit"></i>
                            <span class="sr-only">Edit customer information</span>
                        </button>
                    </div>
                    <!-- Customer Personal Details -->
                    <p><strong>Name:</strong> {{$customer->name}}</p>
                    <p><strong>Contact:</strong> {{$customer->number}}</p>
                    <p><strong>Address:</strong> 
                        {{ implode(', ', array_filter([
                            $customer->street_address,
                            $customer->location_barangay,
                            $customer->location_city,
                            $customer->location_province
                        ])) }} {{ $customer->postal_code }}</p>
                    <p><strong>Serial Number:</strong> {{$customer->serial->serial_number ?? ''}}</p>
                    <p><strong>Facebook:</strong> {{$customer->facebook}}</p>

                    <!-- QR Code Generation -->
                    <div id="qrcode" class="mt-4 text-center">
                      
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class='row'>
                <div class="col-md-6">
                     <div class="card shadow-sm stretch">
                        @if($customer->valid_id)
                            <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-person-vcard-fill me-2"></i> Valid ID Information  &nbsp;
                                <button type="button" data-bs-toggle="modal"  data-bs-target="#viewValidId" class="btn btn-primary btn-sm btn-radius">
                                    <i class="bi bi-file-earmark"></i>
                                </button>
                            </h5>
                            <hr>
                            <p class="mb-2">
                                <strong><i class="bi bi-card-text me-2"></i>ID Type:</strong> {{$customer->valid_id}}
                            </p>
                            <p class="mb-0">
                                <strong><i class="bi bi-hash me-2"></i>ID Number:</strong> {{$customer->valid_id_number}}
                            </p>
                            </div>
                        @else
                        <div class="card-body text-center">
                        <h5 class="card-title"><i class="bi bi-person-vcard"></i> Upload Valid ID</h5>
                        <p class="card-text">Submit a valid government-issued ID.</p>
                        <button class="btn btn-danger" type='button' data-bs-toggle="modal"  data-bs-target="#uploadIdModal">
                            <i class="bi bi-upload"></i> Upload ID
                        </button>
                        </div>
                        @endif
                    </div>
                        </div>
                        <div class="col-md-6">
                            @if($customer->signature)
                        <div class="card shadow-sm stretch" >
                            <div class="card-body text-center">
                            <h6 class="card-title"><i class="mdi mdi-file-document-check-outline"></i> Signed Contract</h6>

                            @if($customer->signature)
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#contractView">
                            <i class="bi bi-file-text"></i> View Signed Contract
                            </button>
                            @else
                            <p class="text-muted"><i class="mdi mdi-close-circle-outline"></i> No contract uploaded.</p>
                            @endif
                        </div>
                    @else

                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                            <h5 class="card-title"><i class="bi bi-file-earmark-text"></i> Contract Signing</h5>
                            <p class="card-text">Review and sign the contract.</p>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#contractModal">
                                <i class="bi bi-pencil-square"></i> Sign Contract
                            </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Transactions</h5>
                    </div>
                    <div class="card-body">
                        <!-- Purchase History Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered" style='font-size:12px;'>
                                <thead>
                                    <tr>
                                        <th>Transaction No.</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Points Earned</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{$transaction->id}}</td>
                                        <td>{{$transaction->item}}</td>
                                        <td>{{$transaction->qty}}</td>
                                        <td><span class='text-success'>{{$transaction->points_client}}</span></td>
                                        <td>{{number_format($transaction->qty*$transaction->price,2)}}</td>
                                        <td>{{date('M d, Y',strtotime($transaction->created_at))}}</td>
                                    </tr>
                                    @endforeach
                                    <!-- Sample Purchase 1 -->
                                    {{-- <tr>
                                        <td>123</td>
                                        <td>330g LPG Cylinder</td>
                                        <td>5</td>
                                        <td>PHP XXX.00</td>
                                        <td>March 1, 2025</td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

</section>
@include('change_avatar')
@include('edit_customer')
@include('upload_valid_id')
@include('sign_contract')
@include('viewValidId')
@include('view_contract_signed')
@endsection

@section('javascript')
<script src="{{asset('design/vendors/select2/select2.min.js')}}"></script>
<script>
  $('#editCustomerModal').on('shown.bs.modal', function () {
    const $territory = $(this).find('.sales-territory-select');

    if (!$territory.hasClass('select2-hidden-accessible')) {
      $territory.select2({
        width: '100%',
        placeholder: 'Search Sales Territory',
        minimumResultsForSearch: 0,
        dropdownParent: $(this)
      });
    }

    initializeEditLocation();
    updateEditSalesTerritory();
  }).on('hidden.bs.modal', function () {
    const $territory = $(this).find('.sales-territory-select');
    if ($territory.hasClass('select2-hidden-accessible')) {
      $territory.select2('close');
    }
  });

  let territoryLookupTimer;

  function updateEditSalesTerritory() {
    const $territory = $('#customer_area');
    const $territoryHelp = $('#editCustomerModal .sales-territory-help');
    const location = {
      region: $('#customer_region').val().trim(),
      province: $('#customer_province').val().trim(),
      city: $('#customer_city').val().trim(),
      barangay: $('#customer_barangay').val().trim()
    };

    if (!location.region || !location.province || !location.city || !location.barangay) {
      $territory.prop('disabled', true).html('<option value="">Complete the location first</option>').trigger('change');
      $territoryHelp.text('Enter Region, Province, City/Municipality, and Barangay to match a territory.');
      return;
    }

    $territory.prop('disabled', true).html('<option value="">Finding matching territory…</option>').trigger('change');

    $.get($territory.data('match-url'), location)
      .done(function(response) {
        const areas = response.areas || [];
        const currentArea = $territory.data('current-area');
        let options = '';

        if (areas.length === 0) {
          options = '<option value="">No territory covers this location</option>';
          $territoryHelp.text('No Sales Territory matches the selected geographic coverage.');
        } else if (areas.length === 1) {
          const safeArea = $('<div>').text(areas[0]).html();
          options = `<option value="${safeArea}" selected>${safeArea}</option>`;
          $territoryHelp.text('Sales Territory was selected automatically from geographic coverage.');
        } else {
          options = '<option value="">Select Sales Territory</option>';
          areas.forEach(function(area) {
            const safeArea = $('<div>').text(area).html();
            options += `<option value="${safeArea}" ${area === currentArea ? 'selected' : ''}>${safeArea}</option>`;
          });
          $territoryHelp.text(`${areas.length} Sales Territories cover this location. Select one.`);
        }

        $territory.html(options).prop('disabled', areas.length === 0).trigger('change');
      })
      .fail(function() {
        $territory.prop('disabled', true).html('<option value="">Unable to match territory</option>').trigger('change');
        $territoryHelp.text('Sales Territory lookup is currently unavailable.');
      });
  }

  $('#customer_region, #customer_province, #customer_city, #customer_barangay').on('input change', function () {
    clearTimeout(territoryLookupTimer);
    territoryLookupTimer = setTimeout(updateEditSalesTerritory, 300);
  });

  let editLocationInitialized = false;

  function optionMarkup(items, selected) {
    let options = '<option value="">Select</option>';
    items.forEach(function (item) {
      const safeName = $('<div>').text(item.name).html();
      options += `<option value="${safeName}" ${item.name === selected ? 'selected' : ''}>${safeName}</option>`;
    });
    return options;
  }

  function initializeEditLocation() {
    if (editLocationInitialized) {
      return;
    }

    editLocationInitialized = true;
    const $region = $('#customer_region');
    const $province = $('#customer_province');
    const $city = $('#customer_city');
    const $barangay = $('#customer_barangay');
    const selectedRegion = $region.data('selected') || '';
    const selectedProvince = $province.data('selected') || '';
    const selectedCity = $city.data('selected') || '';
    const selectedBarangay = $barangay.data('selected') || '';
    let restoringLocation = true;

    function finishLocationRestore() {
      if (restoringLocation) {
        restoringLocation = false;
        updateEditSalesTerritory();
      }
    }

    function loadBarangays(city, selected) {
      $barangay.prop('disabled', true).html('<option value="">Loading…</option>');
      $.get('/api/cities/' + encodeURIComponent(city) + '/barangays')
        .done(function (items) {
          $barangay.html(optionMarkup(items, selected)).prop('disabled', false).trigger('change');
          finishLocationRestore();
        });
    }

    function loadCities(province, selected, barangay) {
      $city.prop('disabled', true).html('<option value="">Loading…</option>');
      $.get('/api/provinces/' + encodeURIComponent(province) + '/cities')
        .done(function (items) {
          $city.html(optionMarkup(items, selected)).prop('disabled', false).trigger('change');
          if (selected) {
            loadBarangays(selected, barangay);
          } else {
            finishLocationRestore();
          }
        });
    }

    function loadProvinces(region, selected, city, barangay) {
      $province.prop('disabled', true).html('<option value="">Loading…</option>');
      $.get('/api/regions/' + encodeURIComponent(region) + '/provinces')
        .done(function (items) {
          $province.html(optionMarkup(items, selected)).prop('disabled', false).trigger('change');
          if (selected) {
            loadCities(selected, city, barangay);
          } else {
            finishLocationRestore();
          }
        });
    }

    $.get('/api/regions').done(function (items) {
      $region.html(optionMarkup(items, selectedRegion)).trigger('change');
      if (selectedRegion) {
        loadProvinces(selectedRegion, selectedProvince, selectedCity, selectedBarangay);
      } else {
        finishLocationRestore();
      }
    });

    $region.on('change', function () {
      if (restoringLocation) return;
      $province.prop('disabled', true).html('<option value="">Select Region First</option>');
      $city.prop('disabled', true).html('<option value="">Select Province First</option>');
      $barangay.prop('disabled', true).html('<option value="">Select City First</option>');
      if (this.value) loadProvinces(this.value, '', '', '');
    });

    $province.on('change', function () {
      if (restoringLocation) return;
      $city.prop('disabled', true).html('<option value="">Select Province First</option>');
      $barangay.prop('disabled', true).html('<option value="">Select City First</option>');
      if (this.value) loadCities(this.value, '', '');
    });

    $city.on('change', function () {
      if (restoringLocation) return;
      $barangay.prop('disabled', true).html('<option value="">Select City First</option>');
      if (this.value) loadBarangays(this.value, '');
    });
  }
</script>
<script>
  const canvas = document.getElementById('signatureCanvas');
  const ctx = canvas.getContext('2d');
  let drawing = false;

  canvas.addEventListener('mousedown', () => drawing = true);
  canvas.addEventListener('mouseup', () => {
    drawing = false;
    ctx.beginPath();
    saveSignatureAsFile(); // Save after drawing
  });
  canvas.addEventListener('mouseout', () => drawing = false);
  canvas.addEventListener('mousemove', draw);

  function draw(e) {
    if (!drawing) return;
    const rect = canvas.getBoundingClientRect();
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#000';
    ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
  }

  function clearSignature() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    document.getElementById('contract_signature').value = '';
  }

  function saveSignatureAsFile() {
    canvas.toBlob(function (blob) {
      const file = new File([blob], "signature.png", { type: "image/png" });

      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);

      const input = document.getElementById('contract_signature');
      input.files = dataTransfer.files;
    }, 'image/png');
  }
</script>
<script>
const video = document.getElementById('video');
const preview = document.getElementById('preview');
const imageInput = document.getElementById('image_data');
const cameraSection = document.getElementById('cameraSection');

function handleFileUpload(event) {
  stopCamera();
  const file = event.target.files[0];
  if (file) {
    if (!file.type.startsWith('image/')) {
      alert('Please select a valid image file.');
      return;
    }
    
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
      alert('File size is too large. Please select an image smaller than 5MB.');
      return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
      const img = new Image();
      img.onload = function() {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        
        const maxWidth = 800;
        const maxHeight = 800;
        let { width, height } = img;
        
        if (width > height) {
          if (width > maxWidth) {
            height = (height * maxWidth) / width;
            width = maxWidth;
          }
        } else {
          if (height > maxHeight) {
            width = (width * maxHeight) / height;
            height = maxHeight;
          }
        }
        
        canvas.width = width;
        canvas.height = height;
        
        ctx.drawImage(img, 0, 0, width, height);
        const compressedDataUrl = canvas.toDataURL('image/png', 0.8);
        
        preview.src = compressedDataUrl;
        imageInput.value = compressedDataUrl;
      };
      img.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}

function enableCamera() {
  cameraSection.style.display = 'block';
  navigator.mediaDevices.getUserMedia({ 
    video: { 
      width: { ideal: 1280 }, 
      height: { ideal: 720 } 
    } 
  })
    .then(stream => {
      video.srcObject = stream;
    })
    .catch(err => {
      console.error("Camera access error:", err);
      alert("Camera access denied: " + err.message);
    });
}

function captureImage() {
  const canvas = document.createElement('canvas');
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;
  const ctx = canvas.getContext('2d');
  ctx.drawImage(video, 0, 0);
  
  const imageData = canvas.toDataURL('image/png', 0.8);
  preview.src = imageData;
  imageInput.value = imageData;
  stopCamera();
}

function stopCamera() {
  const stream = video.srcObject;
  if (stream) {
    stream.getTracks().forEach(track => track.stop());
  }
  video.srcObject = null;
  if (cameraSection) {
    cameraSection.style.display = 'none';
  }
}

document.addEventListener('DOMContentLoaded', function() {
  const uploadModal = document.getElementById('uploadAvatarModal');
  if (uploadModal) {
    uploadModal.addEventListener('hidden.bs.modal', function() {
      stopCamera();
    });
  }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script>
    // Valid data for QR code generation
    const customerData = {
        customerId: 'ST12345',
    };

    // Create a JSON string of the customer data
    const customerDataString = JSON.stringify(customerData);

    // Generate QR code for the customer data
    QRCode.toCanvas(document.getElementById('qrcode'), customerDataString, function(error) {
        if (error) {
            console.error(error);
        } else {
            console.log('QR code generated!');
        }
    });
</script>

@endsection
