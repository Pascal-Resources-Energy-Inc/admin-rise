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

                        <div class="col-md-6 mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $dealer->name }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Contact</label>
                            <input type="text" name="number" class="form-control" value="{{ $dealer->number }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" value="{{ $dealer->address }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Store Name</label>
                            <input type="text" name="store_name" class="form-control" value="{{ $dealer->store_name }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Store Type</label>
                            <input type="text" name="store_type" class="form-control" value="{{ $dealer->store_type }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Facebook</label>
                            <input type="text" name="facebook" class="form-control" value="{{ $dealer->facebook }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email_address" class="form-control" value="{{ $dealer->email_address }}">
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