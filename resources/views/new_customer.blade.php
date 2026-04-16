<div id="new_customer" class="modal fade" tabindex="-1" aria-labelledby="bs-example-modal-md" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header d-flex align-items-center">
        <h4 class="modal-title" id="myModalLabel">New Customer</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <hr>
      <form method='POST' action='{{url('new-customer')}}' onsubmit='show()' enctype="multipart/form-data" class="validation-wizard wizard-circle">
        @csrf
        <div class="modal-body">
          <input style="display:none" value="Active" name="status" id="status">
          <div class="row">
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label" for="stoveId">Serial Number</label>
                <select class="js-example-basic-single w-100 form-control renz required chosen-select" name='serial_number'>
                  <option value="">Search Serial Number</option>
                  @foreach($stoves as $stove)
                    <option value="{{$stove->id}}">{{$stove->serial_number}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label" for="wfirstName2">Full Name&nbsp;<span class="text-danger">*</span></label>
                <input type="text" class="form-control required" id="wfirstName2" name="name" placeholder="Enter Full Name" required/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="wemailAddress2">Email Address&nbsp;<span class="text-danger">*</span></label>
                <input type="email" class="form-control required" id="wemailAddress2" name="email_address" placeholder="Enter Email Address" required/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="wphoneNumber2">Phone Number&nbsp;<span class="text-danger">*</span></label>
                <input type="text" class="form-control required" id="wphoneNumber2" maxlength="11" pattern="\d{11}" name="phone_number" placeholder="Enter Phone Number" required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);" />
              </div>
            </div>
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label" for="facebook2">Facebook&nbsp;<span class="text-danger">*</span></label>
                <input type="tel" class="form-control required" id="facebook2" name='facebook' placeholder="Enter Facebook" required/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label>Region <span class="text-danger">*</span></label>
                <select class="form-control" id="location_region" name="location_region" required onclick="event.stopPropagation();">
                  <option value="">-- Select Region --</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label>Province <span class="text-danger">*</span></label>
                <select class="form-control" id="location_province" name="location_province" required onclick="event.stopPropagation();" disabled>
                  <option value="">-- Select Region First --</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label>City/Municipality <span class="text-danger">*</span></label>
                <select class="form-control" id="location_city" name="location_city" required onclick="event.stopPropagation();" disabled>
                  <option value="">-- Select Province First --</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label>Barangay <span class="text-danger">*</span></label>
                <select class="form-control" name="location_barangay" id="location_barangay" required onclick="event.stopPropagation();" disabled>
                  <option value="">-- Select City First --</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label>Postal Code</span></label>
                <input type="text" class="form-control" name="postal_code" id="postal_code" placeholder="e.g., 1868">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label>Street Name, Building, House No. <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="street_address" id="street_address" value="{{ old('street_address') }}" placeholder="e.g., 1868 Kapalaran St" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">SPO</label>
                <input type="text" class="form-control required" name="spo" placeholder="Enter SPO">
              </div>
            </div>
             <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Center</label>
                <input type="text" class="form-control required" name="center" placeholder="Enter Center">
              </div>
            </div>
          </div>
          {{-- <div class="row">
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label" for="wlocation2"> Address <span class="text-danger">*</span>
                </label>
                <textarea class="form-control required" name='address' required></textarea>
              </div>
            </div>
          </div> --}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn bg-danger-subtle text-danger  waves-effect"
            data-bs-dismiss="modal">
            Close
          </button>
          <button type="submit" class="btn bg-info-subtle text-info  waves-effect">
            Submit
          </button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
<!-- /.modal-dialog -->
</div>
<!-- jQuery FIRST -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Then your custom script -->
<script>
$(document).ready(function() {

    // LOAD REGIONS
    $.get('/api/regions')
        .done(function(data) {
            let options = '<option value="">-- Select Region --</option>';
            data.forEach(function(item) {
                options += `<option value="${item.name}">${item.name}</option>`;
            });
            $('#location_region').html(options);
        })
        .fail(function() {
            alert('Failed to load regions');
        });


    // REGION CHANGE
    $('#location_region').on('change', function() {
        let regionID = $(this).val();

        $('#location_province').prop('disabled', true).html('<option>Loading...</option>');
        $('#location_city').prop('disabled', true).html('<option>-- Select Province First --</option>');
        $('#location_barangay').prop('disabled', true).html('<option>-- Select City First --</option>');

        if (!regionID) return;

        $.get('/api/regions/' + regionID + '/provinces')
            .done(function(data) {
                let options = '<option value="">-- Select Province --</option>';
                data.forEach(function(item) {
                    options += `<option value="${item.name}">${item.name}</option>`;
                });
                $('#location_province').html(options).prop('disabled', false);
            })
            .fail(function() {
                alert('Failed to load provinces');
            });
    });


    // PROVINCE CHANGE
    $('#location_province').on('change', function() {
        let provinceID = $(this).val();

        $('#location_city').prop('disabled', true).html('<option>Loading...</option>');
        $('#location_barangay').prop('disabled', true).html('<option>-- Select City First --</option>');

        if (!provinceID) return;

        $.get('/api/provinces/' + provinceID + '/cities')
            .done(function(data) {
                let options = '<option value="">-- Select City/Municipality --</option>';
                data.forEach(function(item) {
                    options += `<option value="${item.name}">${item.name}</option>`;
                });
                $('#location_city').html(options).prop('disabled', false);
            })
            .fail(function() {
                alert('Failed to load cities');
            });
    });


    // City change
    $('#location_city').change(function() {
        let cityID = $(this).val();
        $('#location_barangay').prop('disabled', true).html('<option>Loading...</option>');
        $('#postal_code').val('');

        if(cityID) {
            $.get(`/api/cities/${cityID}/barangays`, function(data){
                let options = '<option value="">-- Select Barangay --</option>';
                data.forEach(function(item){
                    options += `<option value="${item.name}" data-postal="${item.zip_code || ''}">${item.name}</option>`;
                });
                $('#location_barangay').html(options).prop('disabled', false);
            });
        }
    });

    // Barangay change → populate postal code
    $('#location_barangay').change(function() {
        let postal = $(this).find(':selected').data('postal') || '';
        $('#postal_code').val(postal);
    });

});
</script>