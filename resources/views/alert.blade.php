<div class="modal fade" id="homeModal" tabindex="-1" aria-labelledby="homeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger" id="homeModalLabel">Last purchase was more than 7 days ago. <button class='btn btn-sm btn-danger' onclick="printTable()">Print</button></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <div class="row">
              <div class="col-lg-12 col-xl-12 d-flex align-items-stretch">
                <div class="card w-100">
                  <div class="card-body">

                    <div class="table-responsive" id='table-container' data-simplebar>
                      <table class="table table-bordered align-middle text-nowrap">
                        <thead>
                          <tr>
                            <th scope="col">Client</th>
                            <th scope="col">Barangay</th>
                            <th scope="col">SPO</th>
                            <th scope="col">Center</th>
                            <th scope="col">Last Purchase</th>
                          </tr>
                        </thead>
                        <tbody>
                           @foreach($customers_less as $cus)
                            <tr>
                              <td>{{strtoupper($cus->name)}}</td>
                              <td>{{$cus->location_barangay}}</td>
                              <td>{{$cus->spo}}</td>
                              <td>{{$cus->center}}</td>
                              <td>{{date('M d, Y',strtotime($cus->latestTransaction->date))}}</td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  <script>
    function printTable() {
      const printContents = document.getElementById('table-container').innerHTML;
      const originalContents = document.body.innerHTML;

      document.body.innerHTML = printContents;
      window.print();
      document.body.innerHTML = originalContents;
    }
  </script>