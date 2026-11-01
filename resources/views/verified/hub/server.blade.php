@extends('verified.layout')

@section('icon', 'fa-server')
@section('title', $server->server_name ?? 'Server')
@section('subtitle', 'Snapshot of this server and its panel')

@section('actions')
    <a class="btn btn-primary" href="{{ route('SETTINGS') }}"><i class="fas fa-sliders me-1"></i> Settings</a>
@endsection

@section('content')
    <div class="bsp-stat-grid">
        <x-stat icon="users" tone="orange" :value="$stats['players']" label="Players" />
        <x-stat icon="circle" tone="green" :value="$stats['online']" label="Online" />
        <x-stat icon="user-shield" tone="blue" :value="$stats['staff']" label="Staff" />
        <x-stat icon="ban" tone="red" :value="$stats['bans']" label="Bans" />
        <x-stat icon="exclamation-triangle" tone="orange" :value="$stats['warns']" label="Warns" />
        <x-stat icon="eye" tone="blue" :value="$stats['watchlist']" label="Watchlist" />
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <x-card title="Server" icon="server">
                <div class="p-4 identifier-grid">
                    <div><span>Name</span>{{ $server->server_name ?? '—' }}</div>
                    <div><span>Slug</span>{{ $server->server_slug ?? '—' }}</div>
                    <div><span>Discord logging</span>{{ $server && $server->isWebhookEnabled() ? 'Enabled' : 'Off' }}</div>
                    <div><span>Panel ID</span>{{ $server->server_id ?? '—' }}</div>
                </div>
            </x-card>
        </div>
        <div class="col-lg-6 mb-4">
            <x-card title="Jump to" icon="arrow-right">
                <div class="hub-quick-links p-4 mb-0">
                    <x-pill :href="route('PLAYERS_ONLINE')" icon="circle">Online</x-pill>
                    <x-pill :href="route('ACTIVE_BANS')" icon="ban">Active bans</x-pill>
                    <x-pill :href="route('STAFF_DIRECTORY')" icon="user-shield">Staff</x-pill>
                    <x-pill :href="route('LOOKUP')" icon="search">Lookup</x-pill>
                    <x-pill :href="route('WATCHLIST')" icon="eye">Watchlist</x-pill>
                </div>
            </x-card>
        </div>
    </div>
@endsection
