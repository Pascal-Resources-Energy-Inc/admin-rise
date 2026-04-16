<div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">QR Code Scanner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="stopScanner()"></button>
            </div>
            <div class="modal-body">
                <div id="reader"></div>
                <div class="mt-3">
                    <strong>Scanned Result:</strong>
                    <div id="result" class="text-success mt-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addTransactionModaldd" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="addTransactionForm" method="POST" action="{{ url('store-transaction') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Transaction</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <!-- User Info Fields -->
          <div class="mb-3">
              {{-- <label for="userId" class="form-label">User ID</label> --}}
              <input type="hidden" id="userId" name="customer_id" class="form-control" hidden readonly>
          </div>

          <div class="mb-3">
              <label for="userName" class="form-label">User Name</label>
              <input type="text" id="userName" class="form-control" readonly>
          </div>

          <!-- Item Select -->
          <div class="mb-3">
            <label for="itemSelect" class="form-label">Select Item</label>
            <select id="itemSelect" name="item_id" class="form-select" required>
              <option value="">Select Item</option>
              @foreach($items as $item)
                  <option value="{{ $item->id }}">{{ $item->item }}</option>
              @endforeach
            </select>
          </div>

          <!-- Quantity -->
          <div class="mb-3">
            <label class="form-label">Quantity</label>
            <div style="max-width: 140px;">
              <div class="input-group">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="qtyMinusa">-</button>
                <input type="number" name="qty" id="qtyInputa" class="form-control form-control-sm text-center" value="1" min="1" required>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="qtyPlusa">+</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn bg-info-subtle text-info">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>