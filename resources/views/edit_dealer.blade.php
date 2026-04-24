<div class="modal fade" id="editDealerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Dealer Information</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="{{ route('dealer.update', $dealer->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $dealer->name }}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Email Address</label>
                            <input type="email" name="email_address" class="form-control" value="{{ $dealer->email_address }}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Contact Number</label>
                            <input type="text" name="number" class="form-control" value="{{ $dealer->number }}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Facebook</label>
                            <input type="text" name="facebook" class="form-control" value="{{ $dealer->facebook }}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Store Name</label>
                            <input type="text" name="store_name" class="form-control" value="{{ $dealer->store_name }}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Store Type</label>
                            <input type="text" name="store_type" class="form-control" value="{{ $dealer->store_type }}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="center">Center</label>
                            <select class="form-control" id="center" name="center" required>
                                <option value="">Select Center</option>
                                @foreach($centers as $center)
                                    <option value="{{ $center->name }}"
                                        {{ (isset($dealer) && $dealer->center == $center->name) ? 'selected' : '' }}>
                                        {{ $center->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>SPO</label>
                            <input type="text" name="spo" class="form-control" value="{{ $dealer->spo }}">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" value="{{ $dealer->address }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>