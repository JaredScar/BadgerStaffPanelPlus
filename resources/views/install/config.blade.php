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
              <form class="row gy-2 needs-validation">
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
                  <div id="app_name_invalid" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-4">
                  <label for="app_env" class="form-label">Application Status</label>
                  <input id="app_env" name="app_env" type="text" class="form-control">
                  <div id="app_env_invalid" class="invalid-feedback"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="col-4">
                  <label for="app_debug" class="form-label">Debugging</label>
                  <select id="app_debug" name="app_debug" class="form-select">
                    <option value="" disabled>Select one..</option>
                    <option value="true">Enabled</option>
                    <option value="false" selected>Disabled</option>
                  </select>
                </div>
                <div class="col-6">
                  <label for="app_key" class="form-label">Application Key</label>
                  <input id="app_key" type="text" class="form-control" disabled>
                  <div id="app_key_warning" hidden><i class="bi bi-braces-asterisk"></i> We hope you really know what you are doing..</div>
                </div>
                <div class="col-6">
                  <label for="app_url" class="form-label">Application URL</label>
                  <div class="input-group">
                    <button id="serverProtocolBtn"class="btn"></button>
                    <input id="app_url" type="text" class="form-control">
                    <div id="app_url_warning" class="text-warning" hidden><i class="bi bi-exclamation-circle"></i> We highly recommend installing with a secure enviornment. <a href="#">learn more</a></div>
                  </div>
                </div>
                <div class="col-12">
                  <label for="master_api_key" class="form-label">Master API Key</label>
                  <input type="text" name="master_api_key" id="master_api_key" class="form-control" disabled> 
                </div>
                <div class="col-12 my-3">
                  <h4>Captcha Settings</h4>
                  Currently we only support Googles Recaptcha services, which offers a free service for captchas so long as your website is under a monthly threshold. If you believe captcha is not 
                  necessary you may leave it at its default value of disabled, however it is pretty easy to setup. Refer to <a href="#">our documentation</a> for guided instructions on installing recaptcha.
                </div>
                <div class="col-4">
                  <label for="use_captcha" class="form-label">Capcha Use</label>
                  <select name="use_captcha" id="use_captcha" class="form-select">
                    <option value="" disabled>Select one..</option>
                    <option value="true">Enabled</option>
                    <option value="false" selected>Disabled</option>
                  </select>
                </div>
                <div class="col-4" id="cv_parent" hidden>
                  <label for="captcha_vendor" class="form-label">Captcha Vendor</label>
                  <select name="captcha_vendor" id="captcha_vendor" class="form-select">
                    <option value="" disabled>Select one..</option>
                    <option value="google" selected>Google Recaptcha</option>
                  </select>
                </div>
                <div class="col-6" id="gck_parent" hidden>
                  <label for="google_captcha_key" class="form-label">Recaptcha Key</label>
                  <input type="text" id="google_captcha_key" class="form-control">
                </div>
                <div class="col-6" id="gcs_parent" hidden>
                  <label for="google_captcha_secret" class="form-label">Recaptcha Secret</label>
                  <input type="text" id="google_captcha_secret" class="form-control">
                </div>
                <div class="col-12 my-3">
                  <h4>Discord Settings</h4>
                  We currently support login and authentication using Discord's API. This means you will be the ability to click to discord, authorize this webserver to use info like your Discord ID, 
                  Email and Guild Info. Once authorized discord would redirect you back to the dashboard. If you use Discord Integrated Groups you can simply remove the roles giving them permission 
                  and they will no longer have permission to the website.
                </div>
                <div class="col-6">
                  <label for="discord_bot_token" class="form-label">Discord Bot Token</label>
                  <input type="text" name="discord_bot_token" id="discord_bot_token" class="form-control" onblur="checkDiscordStuffs(this);">
                </div>
                <div class="col-6">
                  <label for="discord_client_id" class="form-label">Discord Client ID</label>
                  <input type="text" name="discord_client_id" id="discord_client_id" class="form-control" onblur="checkDiscordStuffs(this);">
                </div>
                <div class="col-6">
                  <label for="discord_client_secret" class="form-label">Discord Client Secret</label>
                  <input type="text" name="discord_client_secret" id="discord_client_secret" class="form-control">
                </div>
                <div class="col-6">
                  <label for="discord_redirect_uri" class="form-label">Discord Redirect URI</label>
                  <input type="text" name="discord_redirect_uri" id="discord_redirect_uri" class="form-control" onblur="checkDiscordURL(this);">
                  <div id="discord_redirect_uri_danger" class="text-danger" hidden><i class="bi bi-exclamation-circle"></i> You did not specify a proper URL</div>
                  <div id="discord_redirect_uri_warning" class="text-warning" hidden><i class="bi bi-exclamation-circle"></i> Your redirect URL should be using the same protocol as your application URL, otherwise you may get unexpected results.</div>
                </div>
                <div class="col-6">
                  <label for="discord_redirect_auth" class="form-label">Discord Redirect Auth</label>
                  <input type="text" name="discord_redirect_auth" id="discord_redirect_auth" class="form-control" onblur="checkDiscordStuffs(this);">
                </div>
                <div class="col-6">

                </div>
                <div class="col-12">
                  <h5>Administrative Users' Discord ID and Role</h5>
                    This is set into the .env as a hard coded backup, you will have the ability to add/remove groups that can do various degrees of actions on another page. This hard coded backup is going to serve as a last line of defence for managing
                    your communities staff and users when locked out in every other way.
                </div>
                <div class="col-6">
                  <label for="master_admin_discord_id" class="form-label">Master Administrative Discord ID</label>
                  <input type="text" name="master_admin_discord_id" id="master_admin_discord_id" class="form-control" onblur="checkDiscordStuffs(this);">
                </div>
                <div class="col-6">
                  <label for="master_admin_role_id" class="form-label">Master Administrative Discord Role ID</label>
                  <input type="text" name="master_admin_role_id" id="master_admin_role_id" class="form-control" onblur="checkDiscordStuffs(this);">
                </div>
              </form>
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
          var envContentVal = `{!! $envContent !!}`; 
          let app_name = document.getElementById('app_name');
          let app_env = document.getElementById('app_env');
          let app_key = document.getElementById('app_key');
          let app_debug = document.getElementById('app_debug');
          let master_api_key = document.getElementById('master_api_key');
          let use_captcha = document.getElementById('use_captcha');
          let google_captcha_key = document.getElementById('google_captcha_key');
          let google_captcha_secret = document.getElementById('google_captcha_secret');
            // other variables
          let serverProtocolBtn = document.getElementById('serverProtocolBtn');
          let appurlwarning = document.getElementById('app_url_warning');
          let gck_parent = document.getElementById('gck_parent');
          let gcs_parent = document.getElementById('gcs_parent');
          let cv_parent = document.getElementById('cv_parent');

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
          master_api_key.value.value = finalEnvContent.masterapikey ?? null;
          use_captcha.value.value = finalEnvContent.usecaptcha ?? false;
          google_captcha_key.value = finalEnvContent.googlecaptchakey ?? null;
          google_captcha_secret.value = finalEnvContent.googlecaptchasecret ?? null;
          master_admin_discord_id.value = finalEnvContent.masteradmindiscordid ?? null;
          master_admin_role_id.value = finalEnvContent.masteradminroleid ?? null;
          discord_bot_token.value = finalEnvContent.discordbottoken ?? null;
          discord_client_id.value = finalEnvContent.discordclientid ?? null;
          discord_client_secret.value = finalEnvContent.discordclientsecret ??null;
          discord_redirect_uri.value = finalEnvContent.discordredirecturi ?? null;
          discord_redirect_auth.value = finalEnvContent.discordredirectauth ?? null;
          public_bans.value = finalEnvContent.publicbans ?? null;

          // Database values //
          db_connection.value = finalEnvContent.dbconnection ?? 'mysql';
          db_host.value = finalEnvContent.dbhost ?? null;
          db_port.value = finalEnvContent.dbport ?? null;
          db_database.value = finalEnvContent.dbdatabase ?? null;
          db_username.value = finalEnvContent.dbusername ?? null;
          db_password.value = finalEnvContent.dbpassword ?? null;

          // other variables yawn //
          public_bans.value = finalEnvContent.publicbans ?? true;
          log_channel.value = finalEnvContent.logchannel ?? null;
          log_deprecations_channel.value = finalEnvContent.logdeprecationschannel ?? null;
          log_level.value = finalEnvContent.loglevel ?? null;
          bc_crypt.value = finalEnvContent.bccrypt ?? 15;
          log_channel.value = finalEnvContent. ?? null;
          log_channel.value = finalEnvContent. ?? null;
          log_channel.value = finalEnvContent. ?? null;
          log_channel.value = finalEnvContent. ?? null;
          log_channel.value = finalEnvContent. ?? null;
          log_channel.value = finalEnvContent. ?? null;
          log_channel.value = finalEnvContent. ?? null;

          console.log(finalEnvContent);

          // check if user opts into using captcha //
          function captchaOptInStatusChanged() {
            const currentOption = use_captcha.value;

            if (currentOption == 'enabled') {
              cv_parent.hidden = false;
              gck_parent.hidden = false;
              gcs_parent.hidden = false;
            } else if (currentOption == 'disabled') {
              cv_parent.hidden = true;
              gck_parent.hidden = true;
              gcs_parent.hidden = true;
            }
          }
          document.getElementById('use_captcha').addEventListener('change', captchaOptInStatusChanged);
        </script>
    </body>
</html>