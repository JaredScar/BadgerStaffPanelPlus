<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('_partials._html_header', $data)
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
                    <h4>Welcome to BadgerStaffPanelPlus</h4>
                    <div>
                        Greetings from the developers of your new FiveM player management system. This comprehensive system has a plethora of features and planned features that will make managing your FiveM server a breeze. 
                        This panel aims to make managing your servers players as easy as possible. To encourage an open forum of feature requests, creating trust and improving on this software we have decided to make this 
                        panel open sourced on our GitHub. We have a vast list of features currently available to you, and plan to expand on those features and intoduce more in the future. We thank you for your support. 

                    </div>
                </div>
                <div class="col-12">
                    <h4>You are about to install this web panel</h4>
                    <div>
                        These installation pages have been created to make installing and configuring this website easy and straight forward. On the next pages some options may be pre-configured with recommended options, these
                        options may be changed however it may produce unwanted results. This web panel is intended to be used with the FiveM Server Resource BadgerStaffPanelPlus, and depends on this being installed and running 
                        on the FiveM server before attempting installation. For best performance we recommend running this web panel on the same server as the web panel.
                    </div>
                </div>
                <div class="col-12">
                    <h4>System Requirements</h4>
                    <div>
                        <div class="row">
                            <div class="col">This Web Panel requires the following stack/system software to be installed.
                              <ul>
                                <li>PHP 8.1 <span class="text-secondary">or higher</span><br>
                                  <span class="custom-font-tiny">
                                    <span class="text-secondary" id="phpstats">
                                      <i class="bi bi-question-square"></i> - We did noy verify your version.
                                    </span>
                                  </span>
                                </li>
                                <li>Laravel 10.x <span class="text-secondary">or higher</span><br>
                                  <span class="custom-font-tiny">
                                    <span class="text-secondary" id="laravelstats">
                                      <i class="bi bi-question-square"></i> - We did not verify your version.
                                    </span>
                                  </span>
                                </li>
                                <li>MySQL 8.0.36 <span class="text-secondary">or higher</span><br>
                                  <span class="custom-font-tiny">
                                    <span class="text-secondary" id="mysqlstats">
                                      <i class="bi bi-question-square"></i> - We did not verify your version.
                                    </span>
                                  </span>
                                </li>
                              </ul>
                            </div>
                            <div class="col">This install requires the following PHP extensions to work as expected.
                              <div class="row" id="extensions-list">
                                <div class="col">
                                  <ul>
                                    <li id="ext_ctype">Ctype</li>
                                    <li id="ext_curl">cURL</li>
                                    <li id="ext_dom">DOM</li>
                                    <li id="ext_fileinfo">Fileinfo</li>
                                    <li id="ext_filter">Filter</li>
                                    <li id="ext_hash">Hash</li>
                                    <li id="ext_mbstring">MBString</li>
                                  </ul>
                                </div>
                                <div class="col">
                                  <ul>
                                    <li id="ext_openssl">OpenSSL</li>
                                    <li id="ext_pcre">PCRE</li>
                                    <li id="ext_pdo">PDO</li>
                                    <li id="ext_session">Session</li>
                                    <li id="ext_tokenizer">Tokenizer</li>
                                    <li id="ext_xml">XML</li>
                                  </ul>
                                </div>
                              </div>
                              <div id="extensions-final"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <h4>Helpful Resources</h4>
                    <div>
                        Should you run into any problems installing this Web Panel for managing your BadgerStaffPanelPlus, you may utilize the following resources provided to seek assistance or report bugs. 
                        <div class="row">
                          <div class="col-md">
                            <div class="list-group">
                              <a href="#" class="list-group-item">GitHub <i class="bi bi-dash"></i> For bug reporting and information including documentation</a>
                              <a href="#" class="list-group-item">Support Discord <i class="bi bi-dash"></i> For support involving installs, reinstalls, news and information, and status.</a>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
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
                            <input type="hidden" name="currentPage" value="welcome">
                            <button type="submit" name="nextButton" class="btn btn-lg btn-outline-primary" type="button" id="continueBtn" disabled>
                                <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
                                <span id="continueBtnText" role="status">Wait...</span>
                            </button>
                        </form>
                    </div>
                </div>
              </div>
        </div>
        <script>
          const phpdiv = document.getElementById("phpstats");
          const laraveldiv = document.getElementById("laravelstats");
          const mysqldiv = document.getElementById("mysqlstats");
          const continueBtn = document.getElementById("continueBtn")
          const extensionsDiv = document.getElementById('extensions-list');
          let laravel = '{{ $laravelV }}' ?? null;
          let php = '{{ $phpV }}' ?? null;
          let extensions = @json($data);
          console.log(extensions);
          function checkLaravelVersion(laravel) {
            let version = laravel;
            let v = laravel.split('.');
            if (v[0] == '10') {
              if (v[1] >= '40') {
                return true;
              } else { return false;}
            } else {
              return true;
            }
          }
          function checkPhpVersion(php) {
            let version = php;
            let v = php.split('.');
            if (v[0] >= '8') {
              if (v[1] >= '1') {
                return true;
              } else {
                return false;
              }
            } else {
              return true;
            }
          }
          function checkExtensions(extensions) {
            let extfinal = document.getElementById('extensions-final');
            let extstatsArray = new Array();
            for (var ext in extensions) {
              var failedExtensions = "";
              if (ext.includes('ext')) {
                // now we have names to check true or false values for.
                if (extensions[ext] == true) {
                  let successDiv = document.getElementById(ext).className = 'text-success';
                }
                if (extensions[ext] == false) {

                  console.log(ext);
                  
                  extstatsArray.push(ext.slice(4));
                  let failedDiv = document.getElementById(ext).className = 'text-danger';
                  var failedExtensions = extstatsArray.join(', ');
                  console.log('Teh Array' + extstatsArray);
                  if (extstatsArray.length > 0) {
                    extfinal.innerHTML = `<span class="custom-font-tiny text-danger"><i id="failedExt"></i> - ${failedExtensions} does not appear to be installed. Please review your installation and ensure it is installed.</span>`;
                    document.getElementById("failedExt").className = "bi bi-exclamation-square";
                    if (extensions[extensions.length - 1]) {
                      return false;
                    }
                  }
                }
              }
              if(extstatsArray.length == 0) {
                extfinal.innerHTML = `<span class="custom-font-tiny text-success"><i id="successExt"></i> - All Extensions required for this panel are installed and verified to be enabled.</span>`;
                document.getElementById("successExt").className = "bi bi-check2-square";
                if (extensions[extensions.length - 1]) {
                      return true;
                }
              }
            }
          }
          var continueExt, continuePhp, continueLaravel = false;
          if (checkLaravelVersion(laravel) == true) {
            laraveldiv.innerHTML = `<i class="bi bi-check2-square"></i> ${laravel} - Compatable version found.`;
            laraveldiv.className = 'text-success';
            var continueLaravel = true;
          } else if (checkLaravelVersion(laravel) == false) {
            laraveldiv.innerHTML = `<i class="bi bi-exclamation-square"></i> ${laravel} - Does not meet requirments.`;
            laraveldiv.className = 'text-danger';
          } else {
            laraveldiv.innerHTML = `<i class="bi bi-dash-square"></i> ${laravel} - Unable to determine.`;
            laraveldiv.className = 'text-warning';
            var continueLaravel = true;
          }
          if (checkPhpVersion(php) == true) {
            phpdiv.innerHTML = `<i class="bi bi-check2-square"></i> ${php} - Compatable version found.`;
            phpdiv.className = 'text-success';
            var continuePhp = true;
          } else if (checkPhpVersion(php) == false) {
            `<i class="bi bi-exclamation-square"></i> ${php} - Does not meet requirements.`;
            phpdiv.className = 'text-danger';
          } else {
            phpdiv.innerHTML = `<i class="bi bi-dash-square"></i> ${laravel} - Unable to determine.`;
            phpdiv.className = 'text-warning';
            var continuePhp = true;
          }
          if (checkExtensions(extensions) == true) {
            var continueExt = true;
          } else if (checkExtensions(extensions) == false) {
            var continueExt = false;
          } else {
            var continueExt = true;
          }
          let Btn = document.getElementById('continueBtn');
          let btnText = document.getElementById('continueBtnText');
          if (continueExt == true && continueLaravel == true && continuePhp == true) {  
            Btn.disabled = false;
            Btn.className = 'btn btn-lg btn-primary'
            Btn.innerText = 'Continue';
          } else {
            Btn.className = 'btn btn-lg btn-outline-danger';
            Btn.innerText = 'Install Failed';
          }
          //if (checkMySQLVersion(mysql) == true) {
            //mysqldiv.innerHTML = `<i class="bi bi-check2-square"></i> ${mysql} - Compatable version found.`;
            //mysqldiv.className = 'text-success';
          //} else if (checkPhpVersion(php) == false) {
            //`<i class="bi bi-exclaimation-square"></i> ${mysql} - Does not meet requirements.`;
            //mysqldiv.className = 'text-danger';
          //} else {
            //mysqldiv.innerHTML = `<i class="bi bi-dash-square"></i> ${mysql} - Unable to determine.`;
            //mysqldiv.className = 'text-warning';
          //}
        </script>
    </body>
</html>
