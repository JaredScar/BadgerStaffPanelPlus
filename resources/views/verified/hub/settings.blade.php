@extends('verified.layout')

@section('icon', 'fa-sliders')
@section('title', 'Panel Settings')
@section('subtitle', 'Server name and Discord webhook for this panel')

@section('content')
    <x-card title="Server" icon="server">
        <form method="post" action="{{ route('SETTINGS_SAVE') }}" class="p-4">
            @csrf
            <div class="mb-3">
                <label class="form-label">Server name</label>
                <input type="text" name="server_name" class="form-control" value="{{ old('server_name', $server->server_name ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Discord webhook URL</label>
                <input type="url" name="discord_webhook_url" class="form-control" value="{{ old('discord_webhook_url', $server->discord_webhook_url ?? '') }}" placeholder="https://discord.com/api/webhooks/...">
            </div>
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="webhook_enabled" value="1" id="webhookEnabled" {{ old('webhook_enabled', $server->webhook_enabled ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="webhookEnabled">Enable Discord logging</label>
            </div>
            <button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i> Save settings</button>
        </form>
    </x-card>
@endsection