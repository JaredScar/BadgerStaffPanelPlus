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
                      <li><a href="#" class="nav-link text-white"><i class="bi bi-square me-2"></i> Notes</a></li>
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
                                <li>PHP 8.1 <span class="text-secondary">or higher</span></li>
                                <li>MySQL 8.0.36 <span class="text-secondary">or higher</span></li>
                                <li>
                                  A Webserver Software
                                    <br>
                                    <span class=""><u>Servers similar to</u></span>
                                    <ul>
                                      <li>PHP Artisan</li>
                                      <li>Apache2 - With a properly configured virtual host</li>
                                      <li>Nginx - With a properly configured virtual host</li>
                                      <li class="text-secondary">Other server that can run on port 80,</br> configured to run Laravel</li>
                                    </ul>
                                </li>
                              </ul>
                            </div>
                            <div class="col">This install requires the following PHP extensions to work as expected.
                              <div class="row">
                                <div class="col">
                                  <ul>
                                    <li>Ctype</li>
                                    <li>cURL</li>
                                    <li>DOM</li>
                                    <li>Fileinfo</li>
                                    <li>Filter</li>
                                    <li>Hash</li>
                                    <li>MBString</li>
                                  </ul>
                                </div>
                                <div class="col">
                                  <ul>
                                    <li>OpenSSL</li>
                                    <li>PCRE</li>
                                    <li>PDO</li>
                                    <li>Session</li>
                                    <li>Tokenizer</li>
                                    <li>XML</li>
                                  </ul>
                                </div>
                              </div>
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
                      <button class="btn btn-lg btn-outline-secondary">Next Step</button>
                    </div>
                  </div>
                </div>
              </div>
        </div>
    </body>
</html>
