@extends('verified.layout')

@section('icon', 'fa-user-shield')
@section('title', 'Low Trust')
@section('subtitle', 'Players under 50 trust, lowest first')

@section('content')
    <div class="bsp-stat-grid">
        <x-stat icon="user-shield" tone="orange" :value="$players->count()" label="Under 50 trust" />
    </div>

    <x-card title="Review queue" icon="list">
        <x-panel-table :headers="['Player', 'Trust', 'Last warn', 'Last seen', '']">
            @forelse ($players as $player)
                @php
                    $pd = $playerData[$player->player_id] ?? null;
                    $warn = $lastWarns[$player->player_id] ?? null;
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('PLAYER_PROFILE', ['playerId' => $player->player_id]) }}">{{ $player->last_player_name ?: 'Unknown' }}</a>
                        <div class="text-muted small">#{{ $player->player_id }}</div>
                    </td>
                    <td>{{ $pd->trust_score ?? '—' }}</td>
                    <td>{{ $warn->reason ?? 'No warns' }}</td>
                    <td>{{ $pd && $pd->last_join_date ? \App\Support\PanelActivity::when($pd->last_join_date) : 'Never' }}</td>
                    <td><a href="{{ route('PLAYER_PROFILE', ['playerId' => $player->player_id]) }}">Open profile</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">No players are under 50 trust.</td></tr>
            @endforelse
        </x-panel-table>
    </x-card>
@endsection
