@extends('verified.layout')

@section('icon', 'fa-chart-line')
@section('title', 'Overview')
@section('subtitle', 'Server snapshot, recent joins, and staff activity')

@section('content')
    <div class="bsp-stat-grid">
        <x-stat icon="users" tone="orange" :value="$stats['players']" label="Players" />
        <x-stat icon="circle" tone="green" :value="$stats['online']" label="Seen recently" />
        <x-stat icon="calendar-day" tone="blue" :value="$stats['today']" label="Joined today" />
        <x-stat icon="star" tone="orange" :value="$stats['avg_trust']" label="Avg trust" />
        <x-stat icon="clock" tone="blue" :value="$stats['playtime']" label="Playtime" />
        <x-stat icon="exclamation-triangle" tone="orange" :value="$stats['warns']" label="Warns" />
        <x-stat icon="ban" tone="red" :value="$stats['bans']" label="Bans" />
        <x-stat icon="eye" tone="blue" :value="$stats['watchlist']" label="Watchlist" />
    </div>

    <div class="hub-quick-links">
        <x-pill :href="route('SERVER_INFO')" icon="server">Server</x-pill>
        <x-pill :href="route('PLAYERS_ONLINE')" icon="circle">Online</x-pill>
        <x-pill :href="route('LOOKUP')" icon="search">Lookup</x-pill>
        <x-pill :href="route('WATCHLIST')" icon="eye">Watchlist</x-pill>
        <x-pill :href="route('RECENT_JOINS')" icon="sign-in-alt">Recent joins</x-pill>
        <x-pill :href="route('ACTIVITY')" icon="stream">Activity</x-pill>
        <x-pill :href="route('STAFF_PERFORMANCE')" icon="trophy">Staff stats</x-pill>
        <x-pill :href="route('BANS')" icon="gavel">Bans</x-pill>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <x-card title="Recent joins" icon="sign-in-alt">
                <x-panel-table :headers="['Player', 'Trust', 'Last seen']">
                    @forelse ($recentJoins as $player)
                        @php $pd = $playerData[$player->player_id] ?? null; @endphp
                        <tr>
                            <td><a href="{{ route('PLAYER_PROFILE', ['playerId' => $player->player_id]) }}">{{ $player->last_player_name }}</a></td>
                            <td>{{ $pd->trust_score ?? '—' }}</td>
                            <td>{{ $pd && $pd->last_join_date ? \App\Support\PanelActivity::when($pd->last_join_date) : 'Never' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-muted">No join history yet.</td></tr>
                    @endforelse
                </x-panel-table>
            </x-card>
        </div>
        <div class="col-lg-6 mb-4">
            <x-card title="Latest staff actions" icon="stream">
                <div class="activity-feed">
                    @forelse ($activity as $event)
                        <x-activity-item :badge="$event['badge']" :type="$event['type']" :time="$event['time']">
                            <strong>{{ $event['admin'] }}</strong> {{ $event['verb'] }}
                            <a href="{{ route('PLAYER_PROFILE', ['playerId' => $event['player_id']]) }}">{{ $event['player'] }}</a>
                            <div class="activity-reason">{{ $event['reason'] }}</div>
                        </x-activity-item>
                    @empty
                        <x-empty-state icon="stream" title="No activity yet" text="Staff actions will show up here." />
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>

    <x-card title="Low trust players" icon="user-shield">
        <x-panel-table :headers="['Player', 'Trust', '']">
            @forelse ($lowTrust as $player)
                <tr>
                    <td>{{ $player->last_player_name }}</td>
                    <td>{{ optional($playerData[$player->player_id] ?? null)->trust_score ?? '—' }}</td>
                    <td><a href="{{ route('PLAYER_PROFILE', ['playerId' => $player->player_id]) }}">Open profile</a></td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-muted">No low-trust players on file.</td></tr>
            @endforelse
        </x-panel-table>
    </x-card>
@endsection
