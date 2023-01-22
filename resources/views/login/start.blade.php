<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('_partials/_html_header', $data)
    <body>
        <section class="vh-100 gradient-custom">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-custom-dark text-white" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">

                                <div>

                                    <img src="img/badgerstaffpanel-logo.png" />
                                    <p class="text-white-50 mb-5"></p>

                                    <div class="form-outline form-white mb-4">
                                        <input type="text" id="typeUsernameX" placeholder="Username" class="form-control form-control-lg" />
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <input type="password" id="typePasswordX" placeholder="Password" class="form-control form-control-lg" />
                                    </div>

                                    <p class="small pb-lg-2"><a class="text-white" href="forgot_password">Forgot password?</a></p>

                                    <button class="btn d-block mx-auto mb-5 btn-outline-light btn-lg px-5" type="submit"><i class="fa fa-right-to-bracket"></i> Login</button>
                                    <button class="btn d-block mx-auto mt-5 bg-blurple btn-lg px-5" type="submit"><i class="fa-brands fa-discord"></i> Login via Discord</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
