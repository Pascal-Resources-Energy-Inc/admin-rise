@extends('layouts.header')

@section('content')
<!--  Header End -->
          <!-- Welcome Section Start -->
          <section class="welcome">
            <div class="row">
              <div class="col-lg-12 col-xl-6 d-flex align-items-strech">
                <div class="card w-100">
                  <div class="card-body position-relative">
                    <div>
                      <h5 class="mb-1 fw-bold">Welcome Dealer 1</h5>
                      <p class="fs-3 mb-3 pb-1">Check all the statistics</p>
                      <button class="btn btn-primary rounded-pill" type="button">
                       View Profile
                      </button>
                    </div>
                    <div class="school-img d-none d-sm-block">
                      <img src="{{asset('design//assets/images/backgrounds/school.png')}}" class="img-fluid" alt="" />
                    </div>

                    <div class="d-sm-none d-block text-center">
                      <img src="{{asset('design//assets/images/backgrounds/school.png')}}" class="img-fluid" alt="" />
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-12 col-xl-6 text-left">
                <div class="card w-100 stretch">
                  <div class="card-body position-relative ">
                    <div class='row'>
                      
                      <div class="col-lg-12 col-xl-6">
                        Dealer ID: XXX-XXXXX<br>
                        Name: Dealer 1 <br>
                        Contact No.: 09XX-XXX-XXXX <br>
                        Registered: Feb 16, 2025 <br>
                      </div>
                    </div>
                   
                    
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12 col-xl-4 d-flex align-items-strech">
                <div class="card w-100">
                  <div class="card-body">
                    <div class="d-flex flex-row">
                      <div class="round-40 rounded-circle text-white d-flex align-items-center justify-content-center text-bg-success">
                        <i class="ti ti-credit-card fs-6"></i>
                      </div>
                      <div class="ms-3 align-self-center">
                        <h4 class="mb-0 fs-5">Total Points</h4>
                        <span class="text-muted">Earned</span>
                      </div>
                      <div class="ms-auto align-self-center">
                        <h2 class="fs-7 mb-0">20.0</h2>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12 col-xl-4 d-flex align-items-strech">
                <div class="card w-100">
                  <div class="card-body">
                    <div class="d-flex flex-row">
                      <div class="round-40 rounded-circle text-white d-flex align-items-center justify-content-center text-bg-warning">
                        <i class="ti ti-box-multiple fs-6"></i>
                      </div>
                      <div class="ms-3 align-self-center">
                        <h4 class="mb-0 fs-5">Quantity </h4>
                        <span class="text-muted">Sold</span>
                      </div>
                      <div class="ms-auto align-self-center">
                        <h2 class="fs-7 mb-0">20.0</h2>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12 col-xl-4 d-flex align-items-strech">
                <div class="card w-100">
                  <div class="card-body">
                    <div class="d-flex flex-row">
                      <div class="round-40 rounded-circle text-white d-flex align-items-center justify-content-center text-bg-danger">
                        <i class="ti ti-currency-dollar fs-6"></i>
                      </div>
                      <div class="ms-3 align-self-center">
                        <h4 class="mb-0 fs-5">Inventory </h4>
                        <span class="text-muted"><small>as of {{date('M d, Y')}}</small></span>
                      </div>
                      <div class="ms-auto align-self-center">
                        <h5 class="fs-7 mb-0">20.00</h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </section>
          <section>
            <div class="row">
              <div class="col-lg-12 col-xl-12 d-flex align-items-stretch">
                <div class="card w-100">
                  <div class="card-body">
                    <div class="d-flex mb-4 justify-content-between align-items-center">
                      <h5 class="mb-0 fw-bold">Today Transaction</h5>

                 
                    </div>

                    <div class="table-responsive" data-simplebar>
                      <table class="table table-borderless align-middle text-nowrap">
                        <thead>
                          <tr>
                              <th>Transaction No.</th>
                              <th>Quantity</th>
                              <th>Amount</th>
                              <th>Customer</th>
                              <th>Earned Points</th>
                              <th>Date</th>
                          </tr>
                      </thead>
                      <tbody>
                          <!-- Sample Purchase 1 -->
                          <tr>
                              <td>1235</td>
                              <td>2</td>
                              <td>PHP XXX.00</td>
                              <td>Juan Dela Cruz</td>
                              <td><span
                                class="badge bg-success-subtle rounded-pill text-success border-success border fs-2">2</span></td>
                              <td>{{date('M d, Y')}}</td>
                          </tr>
                          <!-- Sample Purchase 2 -->
                          <tr>
                              <td>1245</td>
                              <td>1</td>
                              <td>PHP XXX.00</td>
                              <td>Juan Dela Cruz</td>
                              <td><span
                                class="badge bg-success-subtle rounded-pill text-success border-success border fs-2">1</span></td>
                              <td>{{date('M d, Y')}}</td>
                          </tr>
                          <!-- Sample Purchase 3 -->
                          <tr>
                              <td>1255</td>
                              <td>1</td>
                              <td>PHP XXX.00</td>
                              <td>Juan Dela Cruz</td>
                              <td><span
                                class="badge bg-success-subtle rounded-pill text-success border-success border fs-2">1</span></td>
                              <td>{{date('M d, Y')}}</td>
                          </tr>
                      </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

            
            </div>
          </section>
          <!-- Educators End -->
@endsection
@section('javascript')

<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
<script>
    const qrcode = new QRCode(document.getElementById('qrcode'), {
      text: 'http://jindo.dev.naver.com/collie',
      width: 128,
      height: 128,
      colorDark : '#000',
      colorLight : '#fff',
      correctLevel : QRCode.CorrectLevel.H
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

@endsection
