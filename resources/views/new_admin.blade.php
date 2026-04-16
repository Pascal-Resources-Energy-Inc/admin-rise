<div class="modal fade" id="add-admin-modal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAdminModalLabel">Add Admin User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('new-admin')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="admin_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="admin_name" name="full_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="admin_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="admin_email" name="email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="admin_address" class="form-label">Address</label>
                        <textarea class="form-control" id="admin_address" name="address" rows="3"></textarea>
                    </div>
                    
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i> Default password will be set to: <strong>12345678</strong>
                    </div>
                    
                    <input type="hidden" name="role" value="Admin">
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
    </div>
</div>