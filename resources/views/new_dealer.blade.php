<div id="new_dealer" class="modal fade" tabindex="-1" aria-labelledby="bs-example-modal-md" aria-hidden="true" style="display: none;">
  {{-- <div class="modal-dialog modal-dialog-scrollable modal-lg"> --}}
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header d-flex align-items-center">
        <h4 class="modal-title" id="myModalLabel">
          New Dealer
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"
          aria-label="Close"></button>
      </div>
      <form method='POST' action='{{url('new-dealer')}}' onsubmit='show()' enctype="multipart/form-data">
      @csrf
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 mb-3">
            <label class="form-label" for="wfirstName2"> Full Name  <span class="text-danger">*</span></label>
            <input type="text" class="form-control required" id="wfirstName2" name="name" placeholder="Enter Full Name" required/>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label" for="wemailAddress2"> Email Address <span class="text-danger">*</span></label>
            <input type="email" class="form-control required" id="wemailAddress2" name="email_address" placeholder="Enter Email Address" required/>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label" for="spo"> SPO <span class="text-danger">*</span></label>
            <input type="text" class="form-control required" id="spo" name="spo" placeholder="Enter SPO" required/>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label" for="wphoneNumber2">Phone Number <span class="text-danger">*</span></label>
            <input type="number" class="form-control required" id="wphoneNumber2" name="phone_number" placeholder="Enter Phone Number" step="0.01">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label" for="facebook2">Facebook <span class="text-danger">*</span></label>
            <input type="text" class="form-control required" id="facebook2" name='facebook' placeholder="Enter Facebook" required/>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label" for="store_name">Store Name  <span class="text-danger">*</span></label>
            <input type="text" class="form-control required" name='store_name' id="store_name" placeholder="Enter Store Name" />
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label" for="store_type">Store Type  <span class="text-danger">*</span></label>
            <input type="text" class="form-control required" name='store_type' id="store_type" placeholder="Enter Store Type" />
          </div>
          <div class="col-md-12 mb-3">
            <label class="form-label" for="wlocation2"> Address <span class="text-danger">*</span></label>
            <textarea class="form-control required" name='address' placeholder="Enter Address" required></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-danger-subtle text-danger  waves-effect"data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn bg-info-subtle text-info  waves-effect">Submit</button>
      </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
<!-- /.modal-dialog -->
</div>