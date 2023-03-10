<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('_partials._html_header', $data)

    <body>
        <section class="min-vh-100 pt-5 background-sizing gta-bg@php echo rand(1, 3); @endphp">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form name="login-form" class="login-form" id="form" method="post" action="{{ route('LOGIN_SUBMIT') }}">
                @csrf
                <div class="container h-100">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                            <div class="card bg-custom-dark text-white" style="border-radius: 1rem;">
                                <div class="card-body p-5 text-center">
                                    <div>
                                        <img src="img/badgerstaffpanel-logo.png" />
                                        <p class="text-white-50 mb-5"></p>

                                        <div class="form-outline form-white mb-4">
                                            <select required id="server_id" name="server_id" class="form-control form-control-lg">
                                                @php
                                                $servers = \App\Models\Server::all();
                                                if (sizeof($servers) > 1)
                                                    echo '<option value="" disabled selected hidden>Server Selection</option>';

                                                foreach ($servers as $server) {
                                                    echo "<option value='" . $server['server_id'] . "'>" . $server['server_slug'] . "</option>";
                                                }
                                                @endphp
                                            </select>
                                        </div>

                                        <div class="form-outline form-white mb-4">
                                            <input required type="text" id="typeUsernameX" name="username" placeholder="Username" class="form-control form-control-lg" />
                                        </div>

                                        <div class="form-outline form-white mb-4">
                                            <input required type="password" id="typePasswordX" name="password" placeholder="Password" class="form-control form-control-lg" />
                                        </div>

                                        <p class="small pb-lg-2"><a class="text-white" href="/forgot_password">Forgot password?</a></p>

                                        <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_CAPTCHA_KEY')}}" data-size="invisible" data-callback="onSubmit"></div>

                                        <button class="btn d-block mx-auto mb-5 btn-outline-light btn-lg px-5" type="submit"><i class="fa fa-right-to-bracket"></i> Login</button>
                                        <button href="" class="btn d-block mx-auto mt-5 bg-blurple btn-lg px-5 g-recaptcha" data-sitekey="{{env('GOOGLE_CAPTCHA_KEY')}}" data-callback="onSubmit" type="submit"><i class="fa-brands fa-discord"></i> Login via Discord</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
        @include('_partials._html_footer')
    </body>
</html>
