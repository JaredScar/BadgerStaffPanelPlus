<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('_partials/_html_header', $data)
    <body class="background-sizing gta-bg@php echo rand(1, 3); @endphp">
        <div class="container d-flex flex-column">
            <div class="row align-items-center justify-content-center
                  min-vh-100">
                <div class="col-12 col-md-8 col-lg-4">
                    <div class="card bg-custom-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body bg-custom-dark" style="border-radius: 1rem;">
                            <div class="mb-4">
                                <h3 class="text-center">Forgot Password?</h3>
                                <hr />
                                <p class="mb-2 text-white text-center" style="font-size: 12px;">Enter your account email to reset your password</p>
                            </div>
                            <form>
                                <div class="mb-3">
                                    <input type="email" id="email" class="form-control" name="email" placeholder="Enter Your Email"
                                           required>
                                </div>
                                <div class="mb-3 d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        Reset Password
                                    </button>
                                </div>
                                <span class="small pb-lg-2">Remembered your password? <a href="/" target="_self" class="text-white">Sign In</a></span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('_partials/_html_footer')
    </body>
</html>
