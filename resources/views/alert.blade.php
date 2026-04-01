<div class="modal fade" id="homeModal" tabindex="-1" aria-labelledby="homeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger" id="homeModalLabel">Last purchase was more than 7 days ago.</h5>
        <button type="button" class="btn-close text-danger" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12 col-xl-12 d-flex align-items-stretch">
            <div class="card card-alert w-100">
              <div class="table-responsive">
                <table class="table table-striped align-middle text-nowrap mb-0" id="table-alert">
                  <thead>
                    <tr>
                      <th scope="col">Client</th>
                      <th scope="col">Number</th>
                      <th scope="col">Address</th>
                      <th scope="col">Last Purchase</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($customers_less as $cus)
                      <tr>
                        <td>{{strtoupper($cus->name)}}</td>
                        <td>{{$cus->number}}</td>
                        <td>{{$cus->address}}</td>
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
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-info" onclick="printTable()">Print Table</button>
      </div>
    </div>
  </div>
</div>

<style>
  .modal-header {
    padding: 15px 25px;
    border-bottom: 1px solid #e5e5e5;
  }
  .card-alert {
    margin-bottom: 0px !important;
  }
  .modal-footer {
    border-top: 1px solid #e5e5e5;
  }
</style>

<script>

  // function printTable() {
  //   const printContents = document.getElementById('table-container').innerHTML;
  //   const originalContents = document.body.innerHTML;

  //   document.body.innerHTML = printContents;
  //   window.print();
  //   document.body.innerHTML = originalContents;
  // }

  function printTable() {
    var content = document.getElementById('table-alert').outerHTML;
    var win = window.open('', '', 'height=700,width=900');
    win.document.write(`
      <html>
        <head>
          <title>Print Table</title>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
          <style>
              body { padding:20px; font-family: Arial; }
              table { width:100%; border-collapse: collapse; }
              th, td { border:1px solid #000; padding:8px; text-align:left; }
              th { background:#f2f2f2; }
          </style>
      </head>
      <body>
          <h5>Last purchase was more than 7 days ago.</h5>
          ${content}
      </body>
      </html>
    `);

    win.document.close();
    win.focus();
    win.print();
    win.close();
  }
</script>