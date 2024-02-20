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
                      <li class="nav-item"><a href="#" class="nav-link active" aria-current="page"><i class="bi bi-square me-2"></i> Welcome</a></li>
                      <li><a href="#" class="nav-link text-white"><i class="bi bi-square me-2"></i> Agreements</a></li>
                      <li><a href="#" class="nav-link text-white"><i class="bi bi-square me-2"></i> Configuration</a></li>
                      <li><a href="#" class="nav-link text-white"><i class="bi bi-square me-2"></i> Database</a></li>
                      <li><a href="#" class="nav-link text-white"><i class="bi bi-square me-2"></i> Administrative User</a></li>
                      <li><a href="#" class="nav-link text-white"><i class="bi bi-square me-2"></i> Discord</a></li>
                      <li><a href="#" class="nav-link text-white"><i class="bi bi-square me-2"></i> Groups</a></li>
                      <li><a href="#" class="nav-link text-white"><i class="bi bi-square me-2"></i> Complete</a></li>
                    </ul>
                    <hr>
                    <div class="dropdown d-flex align-items-end"> 
                      <button class="btn d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="/img/profiles/no-pfp.png" alt="profilepicture" width="32" height="32" class="rounded-circle me-2">
                        <strong>{{ Auth::user()->staff_username; }}</strong>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                        <li><a class="dropdown-item" href="#">Cancel Install</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
            </div>
          <div class="col-sm m-5 align-items-start"> 
              <div class="row gy-4">
                <div class="col-12">

                </div>
                <div class="col-12">
                  <div class="row align-items-end justify-content-end">
                    <div class="col-2">
                      <button class="btn btn-lg btn-outline-secondary">Next Step</button>
                    </div>
                  </div>
                </div>
              </div>
        </div>
    </body>
</html>