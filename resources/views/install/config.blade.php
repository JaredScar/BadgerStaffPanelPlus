@extends('install.layout')

@section('title', 'Configuration')
@section('subtitle', 'Panel URL, environment, and optional captcha')

@section('content')
    <h2>Application settings</h2>
    <p>These values are written to your <code>.env</code> file. Docker users can keep the defaults unless you are changing the public URL.</p>

    <form method="post" action="{{ route('install.save', ['step' => 'config']) }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="app_name">Application name</label>
                <input class="form-control" id="app_name" name="app_name" value="{{ old('app_name', $defaults['app_name']) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="app_url">Application URL</label>
                <input class="form-control" id="app_url" name="app_url" value="{{ old('app_url', $defaults['app_url']) }}" required>
                <div class="form-text">Example: http://localhost:8080</div>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="app_env">Environment</label>
                <select class="form-select" id="app_env" name="app_env">
                    <option value="local" @selected(old('app_env', $defaults['app_env']) === 'local')>local</option>
                    <option value="production" @selected(old('app_env', $defaults['app_env']) === 'production')>production</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="app_debug">Debug mode</label>
                <select class="form-select" id="app_debug" name="app_debug">
                    <option value="true" @selected(old('app_debug', $defaults['app_debug']) === 'true')>Enabled</option>
                    <option value="false" @selected(old('app_debug', $defaults['app_debug']) === 'false')>Disabled</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="public_bans">Public bans page</label>
                <select class="form-select" id="public_bans" name="public_bans">
                    <option value="true" @selected(old('public_bans', $defaults['public_bans']) === 'true')>Enabled</option>
                    <option value="false" @selected(old('public_bans', $defaults['public_bans']) === 'false')>Disabled</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="use_captcha">Google reCAPTCHA</label>
                <select class="form-select" id="use_captcha" name="use_captcha">
                    <option value="false" @selected(old('use_captcha', $defaults['use_captcha']) === 'false')>Disabled</option>
                    <option value="true" @selected(old('use_captcha', $defaults['use_captcha']) === 'true')>Enabled</option>
                </select>
            </div>
            <div class="col-md-6 captcha-fields">
                <label class="form-label" for="google_captcha_key">reCAPTCHA site key</label>
                <input class="form-control" id="google_captcha_key" name="google_captcha_key" value="{{ old('google_captcha_key', $defaults['google_captcha_key']) }}">
            </div>
            <div class="col-md-6 captcha-fields">
                <label class="form-label" for="google_captcha_secret">reCAPTCHA secret</label>
                <input class="form-control" id="google_captcha_secret" name="google_captcha_secret" value="{{ old('google_captcha_secret', $defaults['google_captcha_secret']) }}">
            </div>
            <div class="col-12">
                <label class="form-label" for="master_api_key">Master API key</label>
                <input class="form-control" id="master_api_key" name="master_api_key" value="{{ old('master_api_key', $defaults['master_api_key']) }}">
                <div class="form-text">Leave blank to generate a new key during install.</div>
            </div>
        </div>

        <div class="installer-actions">
            <a href="{{ route('install.show', ['step' => 'agreement']) }}" class="btn btn-outline-light">Previous</a>
            <button type="submit" class="btn btn-primary">Continue <i class="fa-solid fa-arrow-right ms-1"></i></button>
        </div>
    </form>
    <script>
        const captchaSelect = document.getElementById('use_captcha');
        const captchaFields = document.querySelectorAll('.captcha-fields');
        const toggle = () => captchaFields.forEach((el) => el.style.display = captchaSelect.value === 'true' ? '' : 'none');
        captchaSelect.addEventListener('change', toggle);
        toggle();
    </script>
@endsection
