@extends('verified.layout')

@section('icon', 'fa-id-badge')
@section('title', 'My Profile')
@section('subtitle', 'Your staff account and recent actions')

@section('content')
    <div class="profile-hero">
        <div class="profile-hero-left">
            <div class="profile-avatar">{{ \App\Support\PanelActivity::initials($staff->staff_username) }}</div>
            <div class="profile-hero-info">
                <div class="profile-name">{{ $staff->staff_username }}</div>
                <div class="profile-ids">{{ $staff->role ?: 'staff' }} · {{ $staff->staff_email }}</div>
                <div class="profile-chips">
                    <span class="profile-chip warn">{{ $counts['warns'] }} Warns</span>
                    <span class="profile-chip kick">{{ $counts['kicks'] }} Kicks</span>
                    <span class="profile-chip ban">{{ $counts['bans'] }} Bans</span>
                    <span class="profile-chip commend">{{ $counts['commends'] }} Commends</span>
                    <span class="profile-chip note">{{ $counts['notes'] }} Notes</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bsp-stat-grid">
        @foreach ($counts as $label => $value)
            <x-stat :value="$value" :label="ucfirst($label)" />
        @endforeach
    </div>

    <x-card title="Your recent actions" icon="stream">
        <div class="activity-feed">
            @forelse ($activity as $event)
                <x-activity-item :badge="$event['badge']" :type="$event['type']" :time="$event['time']">
                    You {{ $event['verb'] }}
                    <a href="{{ route('PLAYER_PROFILE', ['playerId' => $event['player_id']]) }}">{{ $event['player'] }}</a>
                    <div class="activity-reason">{{ $event['reason'] }}</div>
                </x-activity-item>
            @empty
                <x-empty-state icon="user-shield" title="You have not taken any actions yet" />
            @endforelse
        </div>
    </x-card>
@endsection
