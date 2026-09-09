@extends('install.layout')

@section('title', 'Discord')
@section('subtitle', 'Optional Discord login and webhook logging')

@section('content')
    <h2>Discord integration</h2>
    <p>You can skip this and add Discord later in settings. Enable it now if you already have a Discord application.</p>

    <form method="post" action="{{ route('install.save', ['step' => 'discord']) }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label" for="use_discord">Use Discord login</label>
                <select class="form-select" id="use_discord" name="use_discord">
                    <option value="false" @selected(old('use_discord', $defaults['use_discord']) === 'false')>Skip for now</option>
                    <option value="true" @selected(old('use_discord', $defaults['use_discord']) === 'true')>Configure now</option>
                </select>
            </div>
            <div class="col-md-6 discord-fields">
                <label class="form-label" for="discord_client_id">Client ID</label>
                <input class="form-control" id="discord_client_id" name="discord_client_id" value="{{ old('discord_client_id', $defaults['discord_client_id']) }}">
            </div>
            <div class="col-md-6 discord-fields">
                <label class="form-label" for="discord_client_secret">Client secret</label>
                <input class="form-control" id="discord_client_secret" name="discord_client_secret" value="{{ old('discord_client_secret', $defaults['discord_client_secret']) }}">
            </div>
            <div class="col-md-6 discord-fields">
                <label class="form-label" for="discord_redirect_uri">Redirect URI</label>
                <input class="form-control" id="discord_redirect_uri" name="discord_redirect_uri" value="{{ old('discord_redirect_uri', $defaults['discord_redirect_uri']) }}">
            </div>
            <div class="col-md-6 discord-fields">
                <label class="form-label" for="discord_redirect_auth">Redirect auth URL</label>
                <input class="form-control" id="discord_redirect_auth" name="discord_redirect_auth" value="{{ old('discord_redirect_auth', $defaults['discord_redirect_auth']) }}">
            </div>
            <div class="col-md-12 discord-fields">
                <label class="form-label" for="discord_bot_token">Bot token</label>
                <input class="form-control" id="discord_bot_token" name="discord_bot_token" value="{{ old('discord_bot_token', $defaults['discord_bot_token']) }}">
            </div>
            <div class="col-md-6 discord-fields">
                <label class="form-label" for="master_admin_discord_id">Master admin Discord ID</label>
                <input class="form-control" id="master_admin_discord_id" name="master_admin_discord_id" value="{{ old('master_admin_discord_id', $defaults['master_admin_discord_id']) }}">
            </div>
            <div class="col-md-6 discord-fields">
                <label class="form-label" for="master_admin_role_id">Master admin role ID</label>
                <input class="form-control" id="master_admin_role_id" name="master_admin_role_id" value="{{ old('master_admin_role_id', $defaults['master_admin_role_id']) }}">
            </div>
            <div class="col-md-12">
                <label class="form-label" for="discord_webhook_url">Logging webhook URL</label>
                <input class="form-control" id="discord_webhook_url" name="discord_webhook_url" value="{{ old('discord_webhook_url', $defaults['discord_webhook_url']) }}">
                <div class="form-text">Optional. Used for staff action logs.</div>
            </div>
        </div>

        <div class="installer-actions">
            <a href="{{ route('install.show', ['step' => 'admin']) }}" class="btn btn-outline-light">Previous</a>
            <button type="submit" class="btn btn-primary">Continue <i class="fa-solid fa-arrow-right ms-1"></i></button>
        </div>
    </form>
    <script>
        const useDiscord = document.getElementById('use_discord');
        const fields = document.querySelectorAll('.discord-fields');
        const toggle = () => fields.forEach((el) => el.style.display = useDiscord.value === 'true' ? '' : 'none');
        useDiscord.addEventListener('change', toggle);
        toggle();
    </script>
@endsection
