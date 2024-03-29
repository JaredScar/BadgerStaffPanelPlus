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
                    <div class="card-body overflow-auto d-flex" style="max-height: 150px; flex-direction: column-reverse;">
                      <table class="table table-hover"> 
                        <thead>
                          <tr>
                            <th scope="col">Action #</th>
                            <th scope="col">SQL Function #</th>
                            <th scope="col">Action/Result</th>
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
            progressDescription.innerText = `Logging into {{ $_ENV['DB_DATABASE']; }} `;
            progressBar.style.width = '2%';
            progressTitle.innerText = progressBar.style.width;
            progressIcon.innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
            progressTaskTitle.innerText = 'Logging In';
            progressTaskDescription.innerText = `We're Logging in then creating/utilizing your database.`
            infolist.insertAdjacentHTML(`beforeEnd`, `<tr><th scope="row">1</th><td>Creating Database</td><td>Logging into Configured Database Server <code><div class="col d-flex flex-column mb-3"><div>Host: {{ $_ENV["DB_HOST"]; }}</div><div>Port: {{ $_ENV["DB_PORT"]; }}</div><div>Username: {{ $_ENV["DB_USERNAME"]; }}</div><div>Password: *******</div></div></code></td></tr>`)
            attemptAndReact('login');

            });
            async function attemptAndReact(executeThis) {
              console.log('start');
              try {
                const response = await fetch('/web/install/create-db', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')}, body: JSON.stringify({ function_to_execute: executeThis})});
                const data = await response;
                const htmlData = await response.text();
                for (const pair of data.headers.entries()) {
                  console.log(pair[0] + ' ' + pair[1]);
                }
                if (typeof data === 'object') {
                  // response is not json
                  let currentIcon = document.getElementById('progress-icon');
                  if (data['status'] === 500) {
                    const errorMsg = await extractError(htmlData);
                    console.log(errorMsg);
                    if (errorMsg == "Unknown error occured") {
                    console.log(response.headers.get('Content-Type'));
                    console.log(htmlData);
                    }
                    if (errorMsg.includes('database exists')) {
                      reportStats('success', 'Successfully Logged Into Database Server', '5', 'Successfully Logged Into Database Server', 'Success', 'Initiating Database', '<h1 class="text-success"><i class="bi bi-database-fill-check"></h1>', errorMsg);
                      reportStats('warning', errorMsg, '6', 'table-staff', 'Warning', 'Unable to create database as it already exists, instead we will use the database if its empty.', '<h1 class="text-warning"><i class="bi bi-database-fill"></i></h1>', errorMsg);
                      reportStats('info', 'Attempting to create Database Table "table-staff".', '7', 'Creating Table "staff"', 'Creating Staff', 'Creating "staff" Table within the database', currentIcon.innerHTML, 'Attempting to create Database Tables');
                      setTimeout( function() {
                        attemptAndReact('table-staff');
                      }, 2000);
                    } else {
                      reportStats('error', errorMsg, 'x', `table-staff`, 'Error', 'Unable to complete Database Migration', '<h1 class="text-danger"><i class="bi bi-database-fill-x"></i></h1>', errorMsg);
                    }
                  }
                  
                  if(data['status'] === 200) {
                    let newData = JSON.parse(htmlData);
                    console.log(newData.percent);
                    const spinnerDiv = '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>';
                    reportStats('success', newData.taskdescription, newData.percent, newData.sqlfunction, newData.tasktitle, newData.description, newData.taskicon, newData.taskdescription)
                    setTimeout( function() {
                      if (newData.sqlfunction == "table-staff") {
                        reportStats('info', 'Creating Database Table "users".', newData.percent, 'table-users', 'Creating users', 'Attempting to create the Database Table "users" within the database server.', spinnerDiv, 'Creating Database Table "users".');
                      } else if (newData.sqlfunction == 'table-users') {
                        reportStats('info', 'Altering Database Table "users".', newData.percent, 'table-users-two', 'Altering Users', 'Attempting to alter the Database Table "users" within the database server.', spinnerDiv, 'Altering Database Table "users".');
                      } else if (newData.sqlfunction == 'table-users-two') {
                        reportStats('info', 'Creating Database Table "warns".', newData.percent, 'table-warns', 'Creating Warns', 'Attempting to create the Database Table "warns" within the database server.', spinnerDiv, 'Creating Database Table "servers".');
                      } else if (newData.sqlfunction == 'table-warns') {
                        reportStats('info', 'Creating Database Table "bans".', newData.percent, 'table-bans', 'Creating Bans', 'Attempting to create the Database Table "bans" within the database server.', spinnerDiv, 'Creating Database Table "bans".');
                      } else if (newData.sqlfunction == 'table-bans') {
                        reportStats('info', 'Creating Database Table "commends".', newData.percent, 'table-commends', 'Creating Commends', 'Attempting to create the Database Table "commends" within the database server.', spinnerDiv, 'Creating Database Table "commends".');
                      } else if (newData.sqlfunction == 'table-commends') {
                        reportStats('info', 'Creating Database Table "failed_jobs".', newData.percent, 'table-failed-jobs', 'Creating Failed_jobs', 'Attempting to create the Database Table "failed_jobs" within the database server.', spinnerDiv, 'Creating Database Table "failed_jobs".');
                      } else if (newData.sqlfunction == 'table-failed-jobs') {
                        reportStats('info', 'Altering Database Table "failed_jobs".', newData.percent, 'table-failed-jobs-two', 'Altering Failed_jobs', 'Attempting to alter the Database Table "failed_jobs" within the database server.', spinnerDiv, 'Altering Database Table "failed_jobs".');
                      } else if (newData.sqlfunction == 'table-failed-jobs-two') {
                        reportStats('info', 'Creating Database Table "kicks".', newData.percent, 'table-kicks', 'Creating Kicks', 'Attempting to create the Database Table "kicks" within the database server.', spinnerDiv, 'Creating Database Table "kicks".');
                      } else if (newData.sqlfunction == 'table-kicks') {
                        reportStats('info', 'Creating Database Table "layouts".', newData.percent, 'table-layouts', 'Creating Layouts', 'Attempting to create the Database Table "layouts" within the database server.', spinnerDiv, 'Creating Database Table "layouts".');
                      } else if (newData.sqlfunction == 'table-layouts') {
                        reportStats('info', 'Creating Database Table "migrations".', newData.percent, 'table-migrations', 'Creating Migrations', 'Attempting to create the Database Table "migrations" within the database server.', spinnerDiv, 'Creating Database Table "migrations".');
                      } else if (newData.sqlfunction == 'table-migrations') {
                        reportStats('info', 'Creating Database Table "notes".', newData.percent, 'table-notes', 'Creating Notes', 'Attempting to create the Database Table "notes" within the database server.', spinnerDiv, 'Creating Database Table "notes".');
                      } else if (newData.sqlfunction == 'table-notes') {
                        reportStats('info', 'Creating Database Table "password_resets".', newData.percent, 'table-password-resets', 'Creating Password_resets', 'Attempting to create the Database Table "password_resets" within the database server.', spinnerDiv, 'Creating Database Table "password_resets".');
                      } else if (newData.sqlfunction == 'table-password-resets') {
                        reportStats('info', 'Creating Database Table "personal_access_tokens".', newData.percent, 'table-personal-access-tokens', 'Creating Personal_access_tokens', 'Attempting to create the Database Table "personal_access_tokens" within the database server.', spinnerDiv, 'Creating Database Table "personal_access_tokens".');
                      } else if (newData.sqlfunction == 'table-personal-access-tokens') {
                        reportStats('info', 'Altering Database Table "personal_access_tokens".', newData.percent, 'table-personal_access_tokens_two', 'Altering Personal_access_tokens', 'Attempting to alter the Database Table "personal_access_tokens" within the database server.', spinnerDiv, 'Altering Database Table "personal_access_tokens".');
                      } else if (newData.sqlfunction == 'table-personal-access-tokens-two') {
                        reportStats('info', 'Creating Database Table "players".', newData.percent, 'table-players', 'Creating Players', 'Attempting to create the Database Table "players" within the database server.', spinnerDiv, 'Creating Database Table "players".');
                      } else if (newData.sqlfunction == 'table-players') {
                        reportStats('info', 'Altering Database Table "players".', newData.percent, 'table-players-two', 'Altering Players', 'Attempting to alter the Database Table "players" within the database server.', spinnerDiv, 'Altering Database Table "players".');
                      } else if (newData.sqlfunction == 'table-players-two') {
                        reportStats('info', 'Creating Database Table "player_data".', newData.percent, 'table-player-data', 'Creating Player_data', 'Attempting to create the Database Table "player_data" within the database server.', spinnerDiv, 'Creating Database Table "player_data".');
                      } else if (newData.sqlfunction == 'table-player-data') {
                        reportStats('info', 'Altering Database Table "player_data".', newData.percent, 'table-player-data-two', 'Altering Player_data', 'Attempting to alter the Database Table "player_data" within the database server.', spinnerDiv, 'Creating Database Table "player_data".');
                      } else if (newData.sqlfunction == 'table-player-data-two') {
                        reportStats('info', 'Creating Database Table "servers".', newData.percent, 'table-servers', 'Creating servers', 'Attempting to create the Database Table "servers" within the database server.', spinnerDiv, 'Creating Database Table "servers".');
                      } else if (newData.sqlfunction == 'table-servers') {
                        reportStats('info', 'Verifying Database', newData.percent, 'completed', 'Verifying DB', 'Attempting to verify the database configuration (this may take a long time).', spinnerDiv, 'Verifying Database');
                      } else if (newData.sqlfunction == 'completed') {
                        reportStats('completed', 'Database Install Complete".', newData.percent, '', 'Database Install Complete', 'The Database Installation has been completed, you should now be able to continue to the next step.', spinnerDiv, 'Database Install Complete');
                      }
                      attemptAndReact(newData.newfunction);
                    }, 2000);
                  }
                }
              } catch (error) {
                return console.log(error); 
              }
            }
            function extractError(htmlString) {
              console.log(htmlString);
              const fetchDoc = new DOMParser().parseFromString(htmlString, 'text/html');
              const errorElement = fetchDoc.querySelector('title');
              return errorElement ? errorElement.textContent : 'Unknown error occured';
            }
            function reportStats(res, result, percentVal, sqlfunction, progressTaskTitleVal, progressDescriptionVal, progressIconVal, progressTaskDescriptionVal) {
              let outputTable = document.getElementById('infolist');
              let databaseProgressBar = document.getElementById('database-progress-bar');
              let rowsNum = outputTable.rows.length - 1;
              if (res == "error") {
                outputTable.insertAdjacentHTML(`beforeEnd`, `<tr><th scope="row">${rowsNum} </th><td>${sqlfunction} \n <div class="my-2"><span class="bg-dark text-danger p-1 border border-danger"><b>Error</b></span></div></td><td>${result}</td></tr>`);

                progressTaskTitle.innerText = progressTaskTitleVal;
                progressDescription.innerText = progressDescriptionVal;
                progressIcon.innerHTML = progressIconVal;
                progressTaskDescription.innerText = progressTaskDescriptionVal;
                databaseProgressBar.classList.remove('bg-success', 'bg-warning');
                databaseProgressBar.classList.add('bg-danger');
                progressBar.style.width = percentVal+'%';
                progressTitle.innerText = progressBar.style.width;
              } else if (res == "warning") {
                outputTable.insertAdjacentHTML(`beforeEnd`, `<tr><th scope="row">${rowsNum} </th><td>${sqlfunction} \n <div class="my-2"><span class="bg-dark text-warning p-1 border border-warning"><b>Warning</b></span></div></td><td>${result}</td></tr>`)

                progressTaskTitle.innerText = progressTaskTitleVal;
                progressDescription.innerText = progressDescriptionVal;
                progressIcon.innerHTML = progressIconVal;
                progressTaskDescription.innerText = progressTaskDescriptionVal;
                progressBar.style.width = percentVal+'%';
                progressTitle.innerText = progressBar.style.width;
                databaseProgressBar.classList.remove('bg-success', 'bg-danger');
                databaseProgressBar.classList.add('bg-success');
              } else if (res == "info") {
                outputTable.insertAdjacentHTML(`beforeEnd`, `<tr><th scope="row">${rowsNum} </th><td>${sqlfunction} \n <div class="my-2"><span class="bg-dark text-info p-1 border border-info">Info</span></div></td><td>${result}</td></tr>`);

                progressTaskTitle.innerText = progressTaskTitleVal;
                progressDescription.innerText = progressDescriptionVal;
                progressIcon.innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>';
                progressTaskDescription.innerText = progressTaskDescriptionVal;
                progressBar.style.width = percentVal+'%';
                progressTitle.innerText = progressBar.style.width;

              } else if (res == "success") {
                outputTable.insertAdjacentHTML(`beforeEnd`, `<tr><th scope="row">${rowsNum}</th><td>${sqlfunction} \n <div class="my-2"><span class="bg-dark text-success p-1 border border-success">Success</span></div></td><td>${result}</td></tr>`);

                progressTaskTitle.innerText = progressTaskTitleVal;
                progressDescription.innerText = progressDescriptionVal;
                progressIcon.innerHTML = `<h1 class="text-success"><i class="bi bi-${progressIconVal}"></i></h1>`;
                progressTaskDescription.innerText = progressTaskDescriptionVal;
                databaseProgressBar.classList.remove('bg-danger', 'bg-warning');
                databaseProgressBar.classList.add('bg-success');
                progressBar.style.width = percentVal+'%';
                progressTitle.innerText = progressBar.style.width;
              }
              

            }
        </script>
    </body>
</html>