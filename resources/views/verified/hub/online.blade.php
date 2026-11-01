@extends('verified.layout')

@section('icon', 'fa-circle')
@section('title', 'Online')
@section('subtitle', 'Players seen in the last 15 minutes')

@section('content')
    <div class="bsp-stat-grid">
        <x-stat icon="circle" tone="green" :value="$players->count()" label="Online now" />
    </div>

    <x-card title="Currently online" icon="signal">
        <x-panel-table :headers="['Player', 'Playtime', 'Trust', 'Last seen']">
            @forelse ($players as $player)
                @php $pd = $playerData[$player->player_id] ?? null; @endphp
                <tr>
                    <td>
                        <a href="{{ route('PLAYER_PROFILE', ['playerId' => $player->player_id]) }}">{{ $player->last_player_name ?: 'Unknown' }}</a>
                        <div class="text-muted small">#{{ $player->player_id }}</div>
                    </td>
                    <td>{{ \App\Support\PanelActivity::playtime($pd->playtime ?? 0) }}</td>
                    <td>{{ $pd->trust_score ?? '—' }}</td>
                    <td>{{ $pd && $pd->last_join_date ? \App\Support\PanelActivity::when($pd->last_join_date) : 'Never' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-muted">Nobody has connected in the last 15 minutes.</td></tr>
            @endforelse
        </x-panel-table>
    </x-card>
@endsection
