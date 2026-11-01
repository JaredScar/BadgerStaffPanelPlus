@extends('verified.layout')

@section('icon', 'fa-stream')
@section('title', 'Staff Activity')
@section('subtitle', 'Recent warns, kicks, bans, commends, and notes on this server')

@section('content')
    <div class="bsp-stat-grid">
        <x-stat icon="list" tone="orange" :value="$activity->count()" label="Recent Events" />
        <x-stat icon="ban" tone="red" :value="$activity->where('type', 'Ban')->count()" label="Bans" />
        <x-stat icon="exclamation-triangle" tone="orange" :value="$activity->where('type', 'Warn')->count()" label="Warns" />
        <x-stat icon="thumbs-up" tone="green" :value="$activity->where('type', 'Commend')->count()" label="Commends" />
    </div>

    <x-card title="Activity log" icon="stream">
        <x-panel-table :headers="['Type', 'Player', 'Staff', 'Detail', 'When']">
            @forelse ($activity as $event)
                <tr>
                    <td><span class="badge {{ $event['badge'] }}">{{ $event['type'] }}</span></td>
                    <td>
                        <a href="{{ route('PLAYER_PROFILE', ['playerId' => $event['player_id']]) }}">
                            {{ $event['player'] }}
                        </a>
                    </td>
                    <td>{{ $event['admin'] }}</td>
                    <td>{{ $event['reason'] }}</td>
                    <td>{{ $event['time'] }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">No staff activity yet.</td></tr>
            @endforelse
        </x-panel-table>
    </x-card>
@endsection
