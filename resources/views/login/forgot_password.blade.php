<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('_partials._html_header', $data)
    <body class="login-page">
        <section class="login-shell background-sizing gta-bg1">
            <div class="login-overlay"></div>
            <div class="container login-container">
                <div class="login-card">
                    <div class="login-brand">
                        <img src="{{ asset('img/badgerstaffpanel-logo.png') }}" alt="Badger Staff Panel">
                        <h1>Reset password</h1>
                        <p>Enter the email on your staff account</p>
                    </div>

                    <form method="post" action="{{ route('FORGOT_PASSWORD_SUBMIT') }}">
                        @csrf
                        <label class="login-label" for="email">Email</label>
                        <div class="login-field">
                            <i class="fa-solid fa-envelope"></i>
                            <input required type="email" id="email" name="email" placeholder="admin@example.com" autocomplete="email">
                        </div>
                        <button type="submit" class="btn login-btn login-btn-primary">
                            Send reset link
                        </button>
                    </form>

                    <div class="login-meta text-center mt-3">
                        <a href="{{ route('START') }}">Back to sign in</a>
                    </div>
                </div>
            </div>
        </section>
        @include('_partials._html_footer')
    </body>
</html>
