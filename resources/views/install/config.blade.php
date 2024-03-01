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
                      <li class="nav-item"><a href="welcome" class="nav-link text-white"><i class="bi text-success bi-check2-square me-2"></i> Agreements</a></li>
                      <li class="nav-item"><a href="{{ url()->current()}}" class="nav-link active" aria-current="page"><i class="bi bi-square me-2"></i> Configuration</a></li>
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
              <form id="env_creation_tool" class="row gy-2 needs-validation">
                @csrf
                <div class="col-12">
                    <h4>Configure your install</h4>
                    <div>
                        Now we get to making important changes to your installation making it functional. Below you will see a lot of settings to configure. In some places there pre-filled options we made as recommendations based
                        on the decisions you make in the first section, and based on automatically detected system parameters. If you are unsure about what to set for an option it may be best left to the recommended options, or 
                        should you have trouble with one not pre-filled, you may refer to our documentation or support discord. 
                    </div>
                </div>
                <div class="col-12 my-3">  
                  <h4>General Settings</h4>
                  Below you can configure generic settings for this application
                </div>
                <div class="col-4">
                  <label for="app_name" class="form-label">Application Name</label>
                  <input id="app_name" name="app_name" type="text" class="form-control">
                  <div id="error_1" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-4">
                  <label for="app_env" class="form-label">Application Enviornment</label>
                  <input id="app_env" name="app_env" type="text" class="form-control" aria-describedby="appenvhelp">
                  <div id="error_2" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                  <div class="form-text" id="appenvhelp">If you are not sure leave it as the default value.</div>
                </div>
                <div class="col-4">
                  <label for="app_debug" class="form-label">Debugging</label>
                  <select id="app_debug" name="app_debug" class="form-select">
                    <option value="" disabled>Select one..</option>
                    <option value="true">Enabled</option>
                    <option value="false" selected>Disabled</option>
                  </select>
                  <div id="error_3" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6">
                  <label for="app_key" class="form-label">Application Key</label>
                  <input id="app_key" type="text" class="form-control" disabled>
                  <div id="app_key_warning" hidden><i class="bi bi-braces-asterisk"></i> We hope you really know what you are doing..</div>
                  <div id="error_4" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6">
                  <label for="app_url" class="form-label">Application URL</label>
                  <div class="input-group">
                    <button id="serverProtocolBtn"class="btn"></button>
                    <input id="app_url" type="text" class="form-control">
                    <div id="app_url_warning" class="text-warning" hidden><i class="bi bi-exclamation-circle"></i> We highly recommend installing with a secure enviornment. <a href="#">learn more</a></div>
                    <div id="error_5" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                  </div>
                </div>
                <div class="col-12">
                  <label for="master_api_key" class="form-label">Master API Key</label>
                  <input type="text" name="master_api_key" id="master_api_key" class="form-control" disabled> 
                  <div id="error_6" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6">
                  <div class="form-check">
                    <input type="checkbox" value="" class="form-check-input" id="advanced_user" onclick="advancedUserToggle()">
                    <label for="advanced_user" class="form-check-label">
                      I am an advanced developer that wishes to further customize my enviorment variables. <span class="text-warning">(not recommended) </span>
                    </label>
                  </div>
                </div>
                <div class="col-12 my-3">
                  <h4>Captcha Settings</h4>
                  Currently we only support Googles Recaptcha services, which offers a free service for captchas so long as your website is under a monthly threshold. If you believe captcha is not 
                  necessary you may leave it at its default value of disabled, however it is pretty easy to setup. Refer to <a href="#">our documentation</a> for guided instructions on installing recaptcha.
                </div>
                <div class="col-4">
                  <label for="use_captcha" class="form-label">Use Captcha</label>
                  <select name="use_captcha" id="use_captcha" class="form-select">
                    <option disabled>Select one..</option>
                    <option value="true">Enabled</option>
                    <option value="false">Disabled</option>
                  </select>
                  <div id="error_7" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-4" id="cv_parent" hidden>
                  <label for="captcha_vendor" class="form-label">Captcha Vendor</label>
                  <select name="captcha_vendor" id="captcha_vendor" class="form-select">
                    <option disabled>Select one..</option>
                    <option value="google" selected>Google Recaptcha</option>
                  </select>
                  <div id="error_8" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6" id="gck_parent" hidden>
                  <label for="google_captcha_key" class="form-label">Recaptcha Key</label>
                  <input type="text" id="google_captcha_key" class="form-control">
                  <div id="error_9" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6" id="gcs_parent" hidden>
                  <label for="google_captcha_secret" class="form-label">Recaptcha Secret</label>
                  <input type="text" id="google_captcha_secret" class="form-control">
                  <div id="error_10" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-12 my-3">
                  <h4>Discord Settings</h4>
                  We currently support login and authentication using Discord's API. This means you will be the ability to click to discord, authorize this webserver to use info like your Discord ID, 
                  Email and Guild Info. Once authorized discord would redirect you back to the dashboard. If you use Discord Integrated Groups you can simply remove the roles giving them permission 
                  and they will no longer have permission to the website. 

                  You must invite the discord bot to your server for this to work.
                </div>
                <div class="col-6">
                  <label for="use_discord" class="form-label">Use Discord</label>
                  <select name="use_discord" id="use_discord" class="form-select">
                    <option value="" disabled>Select one..</option>
                    <option value="true">Enabled</option>
                    <option value="false">Disabled</option>
                  </select>
                  <div id="error_11" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6 discord-user" hidden>
                  <label for="discord_use_case" class="form-label">Discord Use Case</label>
                  <select name="discord_use_case" id="discord_use_case" class="form-select">
                    <option value="" disabled>Select one..</option>
                    <option value="full" selected>Full</option>
                    <option value="logging">Logging Only</option>
                  </select>
                  <div id="error_12" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6 discord-user" hidden>
                  <label for="discord_redirect_uri" class="form-label">Discord Redirect URI</label>
                  <input type="text" name="discord_redirect_uri" id="discord_redirect_uri" class="form-control" onblur="checkDiscordURL(this);">
                  <div id="discord_redirect_uri_danger" class="text-danger" hidden><i class="bi bi-exclamation-circle"></i> You did not specify a proper URL</div>
                  <div id="discord_redirect_uri_warning" class="text-warning" hidden><i class="bi bi-exclamation-circle"></i> Your redirect URL should be using the same protocol as your application URL, otherwise you may get unexpected results.</div>
                  <div id="error_16" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6 discord-user" hidden>
                  <label for="discord_redirect_auth" class="form-label">Discord Redirect Auth</label>
                  <input type="text" name="discord_redirect_auth" id="discord_redirect_auth" class="form-control" onblur="checkDiscordStuffs(this);">
                  <div id="error_17" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6 discord-user" hidden>
                  <!-- not used atm -->
                </div>
                <div class="col-12">
                  <h5>Administrative Users' Discord ID and Role</h5>
                    This is set into the .env as a hard coded backup, you will have the ability to add/remove groups that can do various degrees of actions on another page. This hard coded backup is going to serve as a last line of defence for managing
                    your communities staff and users when locked out in every other way.
                </div>
                <div class="col-6">
                  <label for="master_admin_discord_id" class="form-label">Master Administrative Discord ID</label>
                  <input type="text" name="master_admin_discord_id" id="master_admin_discord_id" class="form-control" onblur="checkDiscordStuffs(this);">
                  <div id="error_18" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6">
                  <label for="master_admin_role_id" class="form-label">Master Administrative Discord Role ID</label>
                  <input type="text" name="master_admin_role_id" id="master_admin_role_id" class="form-control" onblur="checkDiscordStuffs(this);">
                  <div id="error_19" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-12 my-3">
                  <h4>Database</h4>
                </div>
                <div class="col-6">
                  <label for="db_connection" class="form-label">Database Driver</label>
                  <input type="text" class="form-control" id="db_connection">
                  <div id="error_20" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6">
                  <label for="db_host" class="form-label">Database Host</label>
                  <input type="text" class="form-control" id="db_host">
                  <div id="error_21" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6">
                  <label for="db_port" class="form-label">Database Port</label>
                  <input type="text" class="form-control" id="db_port">
                  <div id="error_22" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6">
                  <label for="db_database" class="form-label">Database Name</label>
                  <input type="text" class="form-control" id="db_database">
                  <div id="error_23" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6">
                  <label for="db_username" class="form-label">Database Username</label>
                  <input type="text" class="form-control" id="db_username">
                  <div id="error_24" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6">
                  <label for="db_password" class="form-label">Database Password</label>
                  <input type="password" class="form-control" id="db_password">
                  <div id="error_25" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-12 my-3">
                  <h4>Mail Options</h4>
                    Below are options related to the emailing services provided for emailing between the panel and users.
                </div>
                <div class="col-3">
                  <label for="mail_mailer" class="form-label">Mail Mailer</label>
                  <select name="mail_mailer" id="mail_mailer" class="form-select">
                    <option value="" disabled>Select one..</option>
                    <option value="smtp" selected>SMTP</option>
                    <option value="imap">IMAP</option>
                    <option value="dovecot">Dovecot</option>
                  </select>
                  <div id="error_26" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-3">
                  <label for="mail_host" class="form-label">Mail Host</label>
                  <input type="text" class="form-control" id="mail_host">
                  <div id="error_27" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-3">
                  <label for="mail_port" class="form-label">Mail Port</label>
                  <input type="text" class="form-control" id="mail_port">
                  <div id="error_28" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-3">
                  <label for="mail_encryption" class="form-label">Mail Encryption</label>
                  <input type="text" class="form-control" id="mail_encryption">
                  <div id="error_29" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6">
                  <label for="mail_username" class="form-label">Mail Username</label>
                  <input type="text" class="form-control" id="mail_username">
                  <div id="error_30" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-6">
                  <label for="mail_password" class="form-label">Mail Password</label>
                  <input type="password" class="form-control" id="mail_password">
                  <div id="error_31" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-4">
                  <label for="mail_form_address" class="form-label">Mail From Address</label>
                  <input type="text" class="form-control" id="mail_from_address">
                  <div id="error_32" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-4">
                  <label for="mail_from_name" class="form-label">Mail From Name</label>
                  <input type="text" class="form-control" id="mail_from_name">
                  <div id="error_33" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-12 my-3 advanced-user" hidden>
                  <h4>Advanced Options</h4>
                    You are seeing this section as you have enabled the advanced options checkbox. Below are settings we normally do not recommend installers change from the vaules set by default however, to provide a more customizable installation you 
                    may modify these settings to your preference. The panel has only been tested with the default values of the options below therefore you may not get a working panel. We will still attempt to provide support for these should you have 
                    trouble however it is not garenteed we will have a ready-to-use fix for you.
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="bcrypt_rounds" class="form-label">BCrypt Rounds</label>
                  <input type="text" class="form-control" id="bcrypt_rounds" aria-describedby="bccrypthelp">
                  <div class="form-text" id="bcrypthelp">Recommended value: <u>15</u></div>
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="broadcast_driver" class="form-label">Broadcast Driver</label>
                  <input type="text" class="form-control" id="broadcast_driver" aria-describedby="broadcastdriverhelp">
                  <div class="form-text" id="broadcastdriverhelp">Recommended value: <u>log</u></div>
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="cache_driver" class="form-label">Cache Driver</label>
                  <input type="text" class="form-control" id="cache_driver" aria-describedby="cachedriverhelp">
                  <div class="form-text" id="cachedriverhelp">Recommended value: <u>file</u></div>
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="filesystem_disk" class="form-label">Filesystem Disk</label>
                  <input type="text" class="form-control" id="filesystem_disk" aria-describedby="filesystemdiskhelp">
                  <div class="form-text" id="filesystemdiskhelp">Recommended value: <u>local</u></div>
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="queue_connection" class="form-label">Queue Connection</label>
                  <input type="text" class="form-control" id="queue_connection" aria-describedby="queueconnectionhelp">
                  <div class="form-text" id="queueconnectionhelp">Recommended value: <u>sync</u></div>
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="session_driver" class="form-label">Session Driver</label>
                  <input type="text" class="form-control" id="session_driver" aria-describedby="sessiondriverhelp">
                  <div class="form-text" id="sessiondriverhelp">Recommended value: <u>file</u></div>
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="session_lifetime" class="form-label">Session Lifetime</label>
                  <input type="text" class="form-control" id="session_lifetime" aria-describedby="sessionlifetimehelp">
                  <div class="form-text" id="sessionlifetimehelp">Recommended value: <u>600</u></div>
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="memcached_host" class="form-label">Memcached Host</label>
                  <input type="text" class="form-control" id="memcached_host" aria-describedby="memcachedhosthelp">
                  <div class="form-text" id="memcachedhosthelp">Recommended value: <u>127.0.0.1</u></div>
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="redis_host" class="form-label">Redis Host</label>
                  <input type="text" class="form-control" id="redis_host" aria-describedby="redishosthelp">
                  <div class="form-text" id="redishosthelp">Recommended value: <u>127.0.0.1</u></div>
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="redis_password" class="form-label">Redis Password</label>
                  <input type="text" class="form-control" id="redis_password" aria-describedby="redispasswordhelp">
                  <div class="form-text" id="redispasswordhelp">Recommended value: <i>null</i></div>
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="redis_port" class="form-label">Redis Port</label>
                  <input type="text" class="form-control" id="redis_port" aria-describedby="redisporthelp">
                  <div class="form-text" id="redisporthelp">Recommended value: <u>6379</u></div>
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="aws_access_key_id" class="form-label">Amazon Access Key</label>
                  <input type="text" class="form-control" id="aws_access_key_id">
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="aws_secret_access_key" class="form-label">Amazon Secret Key</label>
                  <input type="text" class="form-control" id="aws_secret_access_key">
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="aws_default_region" class="form-label">Amazon Default Region</label>
                  <input type="text" class="form-control" id="aws_default_region">
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="aws_bucket" class="form-label">Amazon Bucket</label>
                  <input type="text" class="form-control" id="aws_bucket">
                </div>
                <div class="col-12 advanced-user" hidden>
                  <label for="aws_use_path_style_endpoint" class="form-label">Amazon Use Path Style Endpoint</label>
                  <input type="text" class="form-control" id="aws_use_path_style_endpoint">
                </div>
                <div class="col-12 my-3">
                  <h4>Other Options</h4>
                  Below are options not specific to any category therefore they belong here, configure them how you'd like.
                </div>
                <div class="col-6">
                  <label for="public_bans" class="form-label">Public Bans</label>
                  <input type="text" class="form-control" id="public_bans">
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="log_channel" class="form-label">Log Channel</label>
                  <input type="text" class="form-control" id="log_channel">
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="log_deprecations_channel" class="form-label">Log Deprecations Channel</label>
                  <input type="text" class="form-control" id="log_deprecations_channel">
                </div>
                <div class="col-6 advanced-user" hidden>
                  <label for="log_level" class="form-label">Log Level</label>
                  <input type="text" class="form-control" id="log_level">
                </div>
                <div class="col-12 my-3">
                    <h4>Creating the ENV</h4>
                    <div>
                        Looks like you have reached the end of the page, we just need to do one last thing before we continue to the next step. 
                        That last thing is going to be to actually create your ENV file. As long as all the prep work has been successfully 
                        completed this step should be clockwork. The installer is going to evaluate the input you provided and show you anything 
                        you may of missed. If you have nothing to fix it will unlock that sparkling continue button, if you do it will show you
                         what isn't satisfactory to the installer so you can fix it then try again.
                    </div>

                    <div class="col-12 my-3">
                      <div class="row justify-content-center align-items-center">
                        <div class="col">
                          <button id="envSubmitButton" class="btn btn-lg btn-success" type="button" action="submit">Submit</button>
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
          var envContentVal = `{!! $envContent !!}`; 
          let app_name = document.getElementById('app_name');
          let app_env = document.getElementById('app_env');
          let app_key = document.getElementById('app_key');
          let app_debug = document.getElementById('app_debug');
          let master_api_key = document.getElementById('master_api_key');
          let use_captcha = document.getElementById('use_captcha');
          let google_captcha_key = document.getElementById('google_captcha_key');
          let google_captcha_secret = document.getElementById('google_captcha_secret');
          let captcha_vendor = document.getElementById('captcha_vendor');
            // other variables
          let serverProtocolBtn = document.getElementById('serverProtocolBtn');
          let appurlwarning = document.getElementById('app_url_warning');
          let gck_parent = document.getElementById('gck_parent');
          let gcs_parent = document.getElementById('gcs_parent');
          let cv_parent = document.getElementById('cv_parent');
          let use_discord = document.getElementById('use_discord');
          let discord_use_case = document.getElementById('discord_use_case');
          let discord_redirect_uri = document.getElementById('discord_redirect_uri');
          let discord_redirect_auth = document.getElementById('discord_redirect_auth');
          let log_channel = document.getElementById('log_channel');
          let log_deprecation_channel = document.getElementById('log_deprecations_channel');
          let log_level = document.getElementById('log_level');
          let db_connection = document.getElementById('db_connection');
          let db_host = document.getElementById('db_host');
          let db_database = document.getElementById('db_database');
          let db_username = document.getElementById('db_username');
          let db_password = document.getElementById('db_password');
          let db_port = document.getElementById('db_port');
          let bcrypt_rounds = document.getElementById('bcrypt_rounds');
          let broadcast_driver = document.getElementById('broadcast_driver');
          let cache_driver = document.getElementById('cache_driver');
          let filesystem_disk = document.getElementById('filesystem_disk');
          let queue_connection = document.getElementById('queue_connection');
          let session_driver = document.getElementById('session_driver');
          let session_lifetime = document.getElementById('session_lifetime');
          let memcached_host = document.getElementById('memcached_host');
          let redis_host = document.getElementById('redis_host');
          let redis_password = document.getElementById('redis_password');
          let redis_port = document.getElementById('redis_port');
          // get the php .env variables from install controller //
          function parseExampleEnv(envContentVal) {
            const envLines = envContentVal.split('\n');
            const envObject = {};
            for (const line of envLines) {
              if (line.trim() === '' || line.trim().startsWith('#')) { continue; }

              const [key, value] = line.split('=');
              const fkey = key.trim().toLowerCase().replace(/_/g, '');

              envObject[fkey] = value.trim().replace(/\$\{([^}]+)\}/g, '$1');
            }
            console.log(envObject);
            return envObject;
          }
          // check if written characters would break the .env construction.. //
          function checkNoFunnyChars(input) {
            const regex = /[+!*\(\)[\/]/;
            return !regex.test(input);
          }

          //discord check for proper data//
          function checkDiscordStuffs(input) {
            const cleanItUp = input.value.replace(/\D/g, '');
            input.value = cleanItUp;
          }
          function checkDiscordURL(input) {
            if (input.value.includes('http')) {
              if (input.value.includes(':')) {
                discord_redirect_uri_danger.hidden = true;
                if (input.value.includes(window.location.protocol)) {
                  discord_redirect_uri_warning.hidden = true;
                  return true
                } else {
                  discord_redirect_uri_warning.hidden = false;
                  return true;
                }
              } else {
                discord_redirect_uri_danger.hidden = false;
                return false;
              }
            } else {
              discord_redirect_uri_danger.hidden = false;
              return false;
            }
          }

          // check protocol //
          let serverProtocol = window.location.protocol;
          let serverAddress = window.location.host;
          if (serverProtocol == "https:") {
            if(serverProtocolBtn.classList.contains('bg-danger')) {
              serverProtocolBtn.classList.remove('bg-danger');
            }
            serverProtocolBtn.classList.add('bg-success')
            serverProtocolBtn.innerHTML = '<span class=""><i class="bi bi-lock-fill"></i> https://</span>';
          } else {
            if(serverProtocolBtn.classList.contains('bg-success')) {
              serverProtocolBtn.classList.remove('bg-success');
            }
            appurlwarning.hidden = false;
            serverProtocolBtn.classList.add('bg-danger')
            serverProtocolBtn.innerHTML = '<span class=""><i class="bi bi-unlock"></i> http://</span>';

          }

          // get env variables //
          const finalEnvContent = parseExampleEnv(envContentVal);
          app_name.value = finalEnvContent.appname ?? 'BadgerStaffPanel+';
          app_env.value = finalEnvContent.appenv ?? 'local';
          app_key.value = finalEnvContent.appkey ?? null;
          app_debug.value = finalEnvContent.appdebug ?? false;
          app_url.value = serverAddress ?? null;
          master_api_key.value = finalEnvContent.masterapikey ?? null;
          use_captcha.value = finalEnvContent.usecaptcha ?? false;
          captcha_vendor.value = finalEnvContent.captchavendor ?? 'google'
          google_captcha_key.value = finalEnvContent.googlecaptchakey ?? null;
          google_captcha_secret.value = finalEnvContent.googlecaptchasecret ?? null;
          master_admin_discord_id.value = finalEnvContent.masteradmindiscordid ?? null;
          master_admin_role_id.value = finalEnvContent.masteradminroleid ?? null;
          use_discord.value = finalEnvContent.usediscord ?? null;
          discord_use_case.value = finalEnvContent.discordusecase ?? 'full';
          discord_redirect_uri.value = finalEnvContent.discordredirecturi ?? null;
          discord_redirect_auth.value = finalEnvContent.discordredirectauth ?? null;

          // Database values //
          db_connection.value = finalEnvContent.dbconnection ?? 'mysql';
          db_host.value = finalEnvContent.dbhost ?? null;
          db_port.value = finalEnvContent.dbport ?? '3306';
          db_database.value = finalEnvContent.dbdatabase ?? null;
          db_username.value = finalEnvContent.dbusername ?? null;
          db_password.value = finalEnvContent.dbpassword ?? null;

          // other variables yawn //
          public_bans.value = finalEnvContent.publicbans ?? true;
          log_channel.value = finalEnvContent.logchannel ?? null;
          log_deprecations_channel.value = finalEnvContent.logdeprecationschannel ?? null;
          log_level.value = finalEnvContent.loglevel ?? null;

          // options not normally modifiable unless they check disclaimer they know //
          // what they are doing //
          bcrypt_rounds.value = finalEnvContent.bccrypt ?? 15; 
          broadcast_driver.value = finalEnvContent.broadcastdriver ?? 'log';
          cache_driver.value = finalEnvContent.cachedriver ?? 'file';
          filesystem_disk.value = finalEnvContent.filesystemdisk ?? 'local';
          queue_connection.value = finalEnvContent.queueconnection ?? 'sync';
          session_driver.value = finalEnvContent.sessiondriver ?? 'file';
          session_lifetime.value = finalEnvContent.sessionlifetime ?? '600';
          memcached_host.value = finalEnvContent.memcachedhost ?? '127.0.0.1';
          redis_host.value = finalEnvContent.redishost ?? '127.0.0.1';
          redis_password.value = finalEnvContent.redispassword ?? null;
          redis_port.value = finalEnvContent.redisport ?? '6379';

          // config for email
          mail_mailer.value = finalEnvContent.mailmailer ?? 'smtp';
          mail_host.value = finalEnvContent.mailhost ?? 'mailhog';
          mail_port.value = finalEnvContent.mailport ?? '1025';
          mail_username.value = finalEnvContent.mailusername ?? null;
          mail_password.value = finalEnvContent.mailpassword ?? null;
          mail_encryption.value = finalEnvContent.mailencryption ?? null;
          mail_from_address.value = finalEnvContent.mailfromaddress ?? 'hi@mydomain.com';
          mail_from_name.value = finalEnvContent.mailfromname ?? null;
          use_captcha.value = 'false'; //fix odd behavior captcha not following my orders.

          console.log(finalEnvContent);

          // check if user opts into using captcha //
          function captchaOptInStatusChanged() {
            const currentOption = use_captcha.value;

            if (currentOption == 'true') {
              cv_parent.hidden = false;
              gck_parent.hidden = false;
              gcs_parent.hidden = false;
            } else if (currentOption == 'false') {
              cv_parent.hidden = true;
              gck_parent.hidden = true;
              gcs_parent.hidden = true;
            }
          }

          // check if user opts into using discord //
          function discordOptInStatusChanged() {
            const currentOption = use_discord.value;
            let discorduserstuffs = document.querySelectorAll('.discord-user');
            if (currentOption == 'true') {
              console.log('true');
              discorduserstuffs.forEach(function (element) {
                element.hidden = false;
              })
            } else if (currentOption == 'false') {
              discorduserstuffs.forEach(function (element) {
                element.hidden = true;
              })
            }
          }
          // final check before creating .env THIS is also .env referer //
            const formSelector = document.getElementById('envSubmitButton');
            formSelector.addEventListener('click', (event) => {
              let appurl20 = `${window.location.protocol}//` + document.getElementById('app_url').value;
              const data = {
                app_name: app_name.value,
                app_env: app_env.value,
                app_key: app_key.value,
                app_debug: app_debug.value,
                app_url: appurl20,
                master_api_key: master_api_key.value,
                use_captcha: use_captcha.value,
                captcha_vendor: captcha_vendor.value,
                google_captcha_key: google_captcha_key.value,
                google_captcha_secret: google_captcha_secret.value,
                use_discord: use_discord.value,
                discord_use_case: discord_use_case.value,
                discord_redirect_uri: discord_redirect_uri.value,
                discord_redirect_auth: discord_redirect_auth.value,
                master_admin_discord_id: master_admin_discord_id.value,
                master_admin_role_id: master_admin_role_id.value,
                log_channel: log_channel.value,
                log_deprecation_channel: log_deprecation_channel.value,
                log_level: log_level.value,
                db_connection: db_connection.value,
                db_host: db_host.value,
                db_database: db_database.value,
                db_username: db_username.value,
                db_password: db_password.value,
                db_port: db_port.value,
                bcrypt_rounds: bcrypt_rounds.value,
                broadcast_driver: broadcast_driver.value,
                cache_driver: cache_driver.value,
                filesystem_disk: filesystem_disk.value,
                queue_connection: queue_connection.value,
                session_driver: session_driver.value,
                session_lifetime: session_lifetime.value,
                memcached_host: memcached_host.value,
                redis_host: redis_host.value,
                redis_password: redis_password.value,
                redis_port: redis_port.value,
                mail_mailer: mail_mailer.value,
                mail_host: mail_host.value,
                mail_username: mail_username.value,
                mail_port: mail_port.value,
                mail_password: mail_password.value,
                mail_from_address: mail_from_address.value,
                mail_from_name: mail_from_name.value,
                aws_access_key_id: aws_access_key_id.value,
                aws_secret_access_key: aws_secret_access_key.value,
                aws_default_region: aws_default_region.value,
                aws_bucket: aws_bucket.value,
                aws_use_path_style_endpoint: aws_use_path_style_endpoint.value
              }
              event.preventDefault();
              fetch('/web/install/create-env', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
              }).then(response => response.json()).then(jsonData => {
                if (jsonData.status === 'success') {
                  console.log(jsonData);
                  // all is good now unlock continue button.
                } else {
                  console.log(jsonData);
                }
              })
          });
        document.getElementById('use_captcha').addEventListener('change', captchaOptInStatusChanged);
        document.getElementById('use_discord').addEventListener('change', discordOptInStatusChanged);
        let advancedUserCheckbox = document.getElementById('advanced_user');
        function advancedUserToggle() {
          let advancedUserClass = document.querySelectorAll('.advanced-user');
          advancedUserClass.forEach(function (element) {
            element.hidden = !advancedUserCheckbox.checked;
          });
        }
        </script>
    </body>
</html>