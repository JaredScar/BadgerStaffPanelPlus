@extends('verified.layout')

@section('icon', 'fa-sign-in-alt')
@section('title', 'Recent Joins')
@section('subtitle', 'Players sorted by last connection')

@section('content')
    <x-card title="Connection history" icon="sign-in-alt">
        <x-panel-table :headers="['Player', 'Connections', 'Playtime', 'Trust', 'Last seen']">
            @forelse ($players as $player)
                @php $pd = $playerData[$player->player_id] ?? null; @endphp
                <tr>
                    <td><a href="{{ route('PLAYER_PROFILE', ['playerId' => $player->player_id]) }}">{{ $player->last_player_name }}</a></td>
                    <td>{{ $pd->joins ?? 0 }}</td>
                    <td>{{ \App\Support\PanelActivity::playtime($pd->playtime ?? 0) }}</td>
                    <td>{{ $pd->trust_score ?? '—' }}</td>
                    <td>{{ $pd && $pd->last_join_date ? \App\Support\PanelActivity::when($pd->last_join_date) : 'Never' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">No join history yet.</td></tr>
            @endforelse
        </x-panel-table>
    </x-card>
@endsection
