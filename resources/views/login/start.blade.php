<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('_partials._html_header', $data)
    <body class="login-page">
        <section class="login-shell background-sizing gta-bg1">
            <div class="login-overlay"></div>
            <div class="container login-container">
                @if (session('status'))
                    <div class="alert alert-success login-alert">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger login-alert">
                        <ul class="mb-0 text-start">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="login-card">
                    <div class="login-brand">
                        <img src="{{ asset('img/badgerstaffpanel-logo.png') }}" alt="Badger Staff Panel">
                        <h1>BadgerStaffPanel+</h1>
                        <p>Staff tools for your FiveM server</p>
                    </div>

                    <form name="login-form" class="login-form" id="form" method="post" action="{{ route('LOGIN_SUBMIT') }}">
                        @csrf
                        <label class="login-label" for="server_id">Server</label>
                        <div class="login-field">
                            <i class="fa-solid fa-server"></i>
                            <select required id="server_id" name="server_id" class="form-select">
                                @if ($servers->isEmpty())
                                    <option value="" disabled selected>No servers available</option>
                                @else
                                    @if ($servers->count() > 1)
                                        <option value="" disabled selected>Select a server</option>
                                    @endif
                                    @foreach ($servers as $server)
                                        <option value="{{ $server->server_id }}">{{ $server->server_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <label class="login-label" for="typeUsernameX">Username</label>
                        <div class="login-field">
                            <i class="fa-solid fa-user"></i>
                            <input required type="text" id="typeUsernameX" name="username" placeholder="admin" autocomplete="username">
                        </div>

                        <label class="login-label" for="typePasswordX">Password</label>
                        <div class="login-field">
                            <i class="fa-solid fa-lock"></i>
                            <input required type="password" id="typePasswordX" name="password" placeholder="••••••••" autocomplete="current-password">
                        </div>

                        <div class="login-meta">
                            <a href="{{ route('FORGOT_PASSWORD') }}">Forgot password?</a>
                        </div>

                        <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_CAPTCHA_KEY') }}" data-size="invisible" data-callback="onSubmit"></div>

                        <button class="btn login-btn login-btn-primary" type="submit">
                            <i class="fa-solid fa-right-to-bracket"></i> Sign in
                        </button>
                    </form>

                    @if (env('DISCORD_REDIRECT_AUTH'))
                        <a class="btn login-btn login-btn-discord" href="{{ env('DISCORD_REDIRECT_AUTH') }}">
                            <i class="fa-brands fa-discord"></i> Continue with Discord
                        </a>
                    @else
                        <button class="btn login-btn login-btn-discord" type="button" disabled title="Discord login is not configured">
                            <i class="fa-brands fa-discord"></i> Continue with Discord
                        </button>
                    @endif

                    @if (config('app.debug'))
                        <div class="login-demo">
                            Demo login: <strong>admin</strong> / <strong>password</strong>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        @include('_partials._html_footer')
    </body>
</html>
