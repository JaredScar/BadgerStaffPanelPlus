<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('../_partials._html_header', $data)
    @php
      $currentPage
    @endphp
    <body class="bg-dark text-light container-fluid" data-bs-theme="dark">
        <div class="row">
            <div class="col-sm-3">
                <div class="d-flex flex-nowrap">
                  <div class="d-flex flex-column flex-fill flex-shrink-0 p-3 text-bg-dark">
                    <div class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <div class="text-center">
                            <img class="" src="/img/badgerstaffpanel-logo.png" width="64" height="64" alt="website-logo">
                            <h5>Installation Script</h5>
                        </div>
                    </div>
                    <hr>
                    <ul class="nav nav-pills flex-column"> 
                      <li class="nav-item"><a href="welcome" class="nav-link text-white"><i class="bi text-success bi-check2-square me-2"></i> Welcome</a></li>
                      <li class="nav-item"><a href="{{ url()->current()}}" class="nav-link active" aria-current="page"><i class="bi bi-square me-2"></i> Agreements</a></li>
                      <li class="nav-item"><a href="{{ url()->current()}}" class="nav-link text-white"><i class="bi bi-square me-2"></i> Configuration</a></li>
                      <li class="nav-item"><a href="{{ url()->current()}}" class="nav-link text-white"><i class="bi bi-square me-2"></i> Database</a></li>
                      <li class="nav-item"><a href="{{ url()->current()}}" class="nav-link text-white"><i class="bi bi-square me-2"></i> Administrative User</a></li>
                      <li class="nav-item"><a href="{{ url()->current()}}" class="nav-link text-white"><i class="bi bi-square me-2"></i> Discord</a></li>
                      <li class="nav-item"><a href="{{ url()->current()}}" class="nav-link text-white"><i class="bi bi-square me-2"></i> Groups</a></li>
                      <li class="nav-item"><a href="{{ url()->current()}}" class="nav-link text-white"><i class="bi bi-square me-2"></i> Complete</a></li>
                    </ul>
                    <hr>
                  </div>
                </div>
            </div>
          <div class="col-sm m-5 align-items-start"> 
              <div class="row gy-4">

                <div class="col-12">
                  <h4>Please read and accept the following Terms of Service and End User License Agreement.</h4>
                </div>
                <div class="col-12">
                  <div class="row">
                    <textarea name="tos" id="tos2embed" rows="20" disabled>{{ $tosContent }}</textarea>
                  </div>
                </div>
                <form action="" method="post">
                  <div class="form-check">
                    <input type="checkbox" value="" class="form-check-input" id="tosagreementagree">
                    <label for="tosagreementagree" class="form-check-label">
                    I agree to the above mentioned EULA and Terms of Service agreement
                    </label>
                    <div id="TOSUn"class="text-danger" hidden>You must agree to the EULA and Terms of Service agreement..</div>
                  </div>
                <div class="col-12">
                  <hr>
                </div>
                <div class="col-12">
                <div class="row align-items-end justify-content-end">
                    <div class="col-2">
                        <form action="{{ url()->previous() }}" method="get">
                          <button class="btn btn-lg btn-outline-secondary" type="submit">Previous</button>
                        </form>
                    </div>
                    <div class="col"></div>
                    <div class="col-2">
                        <form method="post" action="{{ route('moveToNextPage') }}" id="continue2agreement">
                            @csrf
                            <input type="hidden" name="currentPage" value="agreement">
                            <button type="submit" name="nextButton" class="btn btn-lg btn-outline-primary" id="continueBtn" disabled>
                                <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
                                <span id="continueBtnText" role="status">Wait...</span>
                            </button>
                        </form>
                    </div>
                </div>
                </div> 
              </div>
        </div>
        <script>
          let agreementDiv = document.getElementById('tosagreementagree');
          let continueBtn = document.getElementById('continueBtn');
          let continueBtnText = document.getElementById('continueBtnText');
          let tosun = document.getElementById('TOSUn');
          var staticThis = false;
          setInterval(()=> {
            if (agreementDiv.checked) {
              staticThis = true;
              continueBtn.innerHTML = 'Continue';
              continueBtn.disabled = false;
              TOSUn.hidden = true;
            } else if(staticThis == true && !agreementDiv.checked) {
              continueBtn.innerHTML = '<span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span><span id="continueBtnText" role="status">Wait...</span>';
              continueBtn.disabled = true;
              TOSUn.hidden = false;
            }
          }, 1*1000)
        </script>
    </body>
</html>