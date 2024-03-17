<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('../_partials._html_header', $data)
    @php
      $currentPage
    @endphp
    <head>
      <meta name="csrf-token" content="{{ csrf_token()}}">
    </head>
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
                      <li class="nav-item"><a href="agreements" class="nav-link text-white"><i class="bi text-success bi-check2-square me-2"></i> Agreements</a></li>
                      <li class="nav-item"><a href="config" class="nav-link text-white"><i class="bi text-success bi-check2-square me-2"></i> Configuration</a></li>
                      <li class="nav-item"><a href="database" class="nav-link active" aria-current="page"><i class="bi bi-square me-2"></i> Database</a></li>
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
              <form id="env_creation_tool" class="row gy-2">
                <div class="col-12">
                  <h4>Database Migrations</h4>
                    The next step in installing the panel on your server is going to be migrating the database. This panel depdends on a database server like mysql to save userdata, panel settings among other things. 
                    The previous configuration page was settings that aren't modified very often and should generally remain the same throughout the lifetime of this panel. Everything else may be changed more often 
                    therefore we need a place we can request and store data long term we will access very often.
                </div>
                <div class="col-12 m-4">
                  <button id="begin" class="btn btn-lg btn-primary" disabled>Begin Migration</button>
                </div>
                <div class="col-8">
                  <div class="card">
                    <div class="card-header">Database Migration Progress</div>
                    <div class="card-body placeholder-glow">
                      <h4 id="progress-title"class="card-title "><span class="col-4 placeholder"></span></h4>
                      <div class="card-subtitle text-body-secondary mb-2">
                        <div id="progress-bar-loader" class="placeholder-glow">
                          <div id="progress-bar-itself"class="progress" role="progressbar" aria-label="" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" hidden><div id="database-progress-bar" class="progress-bar bg-success" style="width: 0%"></div></div>
                          <span class="placeholder col-12"></span>
                        </div>
                      <div class="placeholder-glow" id="progress-description"><span class="col-8 placeholder"></span></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-4 h-25">
                  <div class="card">
                    <div class="card-header">Task Information</div>
                    <div class="row g-0">
                      <div class="col-2 align-self-center text-center" id="progress-icon">
                          <h1 class="placeholder-glow"><span class="placeholder col-10"></span></h1>
                      </div>
                      <div class="col-10">
                        <div class="card-body">
                          <h5 id="progress-task-title" class="card-title placeholder-glow"><span class="col-3 placeholder"></span></h5>
                          <div id="progress-task-description" class="card-text text-body-secondary placeholder-glow"><span class="col-10 placeholder"></span></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <div class="col-12">
                <div class="card">
                  <div class="card-header">Output Information</div>
                  <div class="row g-0">
                    <div class="card-body">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th scope="col">Action #</th>
                            <th scope="col">SQL Function #</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody id="infolist" class="">
                          <tr id="removeMe" class="placeholder-glow">
                            <th scope="row"><span class="placeholder col-3 placeholder-lg"></span></th>
                            <td><span class="placeholder col-8 placeholder-lg"></span></td>
                            <td><span class="placeholder col-10 placeholder-lg"></span>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    
                  </div>
                </div>
              </div>
              </form>
                <div class="col-12">
                  <hr>
                </div>
                <div class="col-12">
                <div class="row align-items-end justify-content-end">
                    <div class="col-2">
                        <button class="btn btn-lg btn-outline-secondary" type="button" action="">Previous</button>
                    </div>
                    <div class="col"></div>
                    <div class="col-2">
                        <form method="post" action="{{ route('moveToNextPage') }}" id="continue2agreement">
                            @csrf
                            <input type="hidden" name="currentPage" value="database">
                            <button type="submit" name="nextButton" class="btn btn-lg btn-outline-primary" type="button" id="continueBtn" disabled>
                                <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
                                <span id="continueBtnText" role="status">Wait...</span>
                            </button>
                        </form>
                    </div>
                </div>
              </div>
        </div>
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
          <div id="errorToast" class="toast bg-danger" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="30000">
            <div class="toast-header">
              <strong class="me-auto"><span class="error-count"></span> Errors found</strong>
              <small class="text-body-secondary"><i class="bi bi-motherboard"></i> System</small>
              <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
              <span class="error-count">?</span> Errors Found in your entry. Please revise the following fields:
              <ul class="error-list"></ul>

            </div>
          </div>
          <div id="successToast" class="toast bg-success" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="30000">
            <div class="toast-header">
              <strong class="me-auto">Successfully Created</strong>
              <small class="text-body-secondary"><i class="bi bi-motherboard"></i> System</small>
              <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
              We were able to successfully create your ENV file. We are now unlocking the continue button.

            </div>
          </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script>
          const startMigration = document.getElementById('begin');
          const progressTitle = document.getElementById('progress-title');
          const progressDescription = document.getElementById('progress-description');
          const progressTaskTitle = document.getElementById('progress-task-title');
          const progressTaskDescription = document.getElementById('progress-task-description');
          const progressIcon = document.getElementById('progress-icon');
          const progressBarLoader = document.getElementById('progress-bar-loader');
          const infolist = document.getElementById('infolist');
          const removeme = document.getElementById('removeMe');
          const progressBar = document.getElementById('database-progress-bar')
          const pro = document.getElementById('progress-bar-itself');
          // initialize the script removing placeholders
          setTimeout( async function() {
            progressTitle.innerText = 'Idle.';
            progressTaskTitle.innerText = 'Ready';
            progressDescription.innerText = 'Waiting on user-provided input.';
            progressIcon.innerHTML = '<h1><i class="bi bi-database-fill-gear"></i></h1>';
            progressTaskDescription.innerText = 'Click "Begin Migration" to start. This should take 5 minutes.';
            progressBarLoader.hidden = false; 
            let thisplaceholderremove = progressBarLoader.querySelectorAll('.placeholder');
            thisplaceholderremove.forEach( function (element) {
              console.log(element);
              element.remove();
            });
            progressBar.hidden = false; pro.hidden = false;
            startMigration.disabled = false;
            removeme.hidden = true;
            infolist.insertAdjacentHTML(`beforeEnd`, `<tr><th scope="row">0</th><td>None</td><td>Awaiting User to begin Migration.</td></tr>`)
          }, 1000);
          
          
          // this is the function managing the beginning of the DB migration.
          startMigration.addEventListener('click', (event) => {
            event.preventDefault();
            //button
            startMigration.innerText = "Starting Migration";
            startMigration.disabled = false;

            // function init database.
            progressDescription.innerText = `Creating Database {{ $_ENV['DB_DATABASE']; }} `;
            progressBar.style.width = '2%';
            progressTitle.innerText = progressBar.style.width;
            progressIcon.innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
            progressTaskTitle.innerText = 'Creating Database';
            progressTaskDescription.innerText = `We're creating your database/utilizing your database.`
            infolist.insertAdjacentHTML(`beforeEnd`, `<tr><th scope="row">1</th><td>Creating Database</td><td>Logging into Configured Database Server <code><div class="col d-flex flex-column mb-3"><div>Host: {{ $_ENV["DB_HOST"]; }}</div><div>Port: {{ $_ENV["DB_PORT"]; }}</div><div>Username: {{ $_ENV["DB_USERNAME"]; }}</div><div>Password: *******</div></div></code></td></tr>`)
            attemptAndReact();
            
            function attemptAndReact() {
              console.log('start');
              var eventSource = new EventSource('/web/install/create-db');
              console.log(eventSource);
              eventSource.onmessage = function(event) {
                console.log(event);
                  var response = JSON.parse(event.data);
                  response.forEach(function(status) {
                    console.log(status);
                    // report the changes in status live
                    for (var res in status) {
                      console.log(res, status[res]);
                      if (res == 'status') {
                        //report status bar color
                        progressBar.classList.remove('bg-danger', 'bg-warning', 'bg-success'); 
                        if (status[res] == 'success') { 
                          progressBar.classList.add('bg-success');
                          progressIcon.innerHTML = '<h1><i class="bi bi-check-circle"></i></h1>';
                        }
                        else if (status[res] == 'warning') { 
                          progressBar.classList.add('bg-warning'); 
                          progressIcon.innerHTML = '<h1><i class="bi bi-dash-square"></i></h1>';
                        }
                        else if (status[res] == 'error') { 
                          progressBar.classList.add('bg-danger'); 
                          progressIcon.innerHTML = '<h1><i class="bi bi-exclamation-square"></i></h1>';
                        }
                        
                        // report percent change
                        progressBar.style.width = `${status['percent']}%`;
                        progressTitle.innerText = progressBar.style.width;
                        
                        //progressIcon.innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>';
                      } else {
                      }
                    }
                    })
                }
              }

            });
        </script>
    </body>
</html>