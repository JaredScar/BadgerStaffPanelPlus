@extends('verified.layout')

@php
    use App\Support\PanelActivity;
    $name = $player->last_player_name ?: 'Unknown player';
    $trust = $playerData->trust_score ?? null;
    $trustClass = $trust === null ? 'none' : ($trust >= 70 ? 'high' : ($trust >= 40 ? 'mid' : 'low'));
    $playtime = PanelActivity::playtime($playerData->playtime ?? 0);
    $joins = $playerData->joins ?? 0;
    $lastSeen = $playerData && $playerData->last_join_date
        ? PanelActivity::when($playerData->last_join_date)
        : 'Never';
@endphp

@section('header')
    <a href="{{ route('PLAYERS') }}" class="profile-back">
        <i class="fas fa-arrow-left"></i> Back to Players
    </a>
@endsection

@section('content')
    <div class="profile-hero">
        <div class="profile-hero-left">
            <div class="profile-avatar">{{ PanelActivity::initials($name) }}</div>
            <div class="profile-hero-info">
                <div class="profile-name">{{ $name }}</div>
                <div class="profile-ids">
                    Panel #{{ $player->player_id }}
                    @if ($player->game_license) · license:{{ $player->game_license }} @endif
                    @if ($player->discord_id) · discord:{{ $player->discord_id }} @endif
                </div>
                <div class="profile-chips">
                    <span class="profile-chip warn">{{ $warns->count() }} Warns</span>
                    <span class="profile-chip kick">{{ $kicks->count() }} Kicks</span>
                    <span class="profile-chip ban">{{ $bans->count() }} Bans</span>
                    <span class="profile-chip commend">{{ $commends->count() }} Commends</span>
                    <span class="profile-chip note">{{ $notes->count() }} Notes</span>
                </div>
            </div>
        </div>
        <div class="trust-ring">
            <div class="trust-ring-circle {{ $trustClass }}">
                <span class="trust-ring-num">{{ $trust ?? '—' }}</span>
                <span class="trust-ring-sub">Trust</span>
            </div>
        </div>
    </div>

    <div class="bsp-stat-grid">
        <x-stat icon="clock" tone="orange" :value="$playtime" label="Playtime" />
        <x-stat icon="sign-in-alt" tone="blue" :value="$joins" label="Connections" />
        <x-stat icon="gavel" tone="red" :value="$warns->count() + $kicks->count() + $bans->count()" label="Infractions" />
        <x-stat icon="thumbs-up" tone="green" :value="$commends->count()" label="Commends" />
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <x-card title="Take Action" icon="gavel">
                <form method="post" action="{{ route('PLAYER_ACTION') }}" class="p-3">
                    @csrf
                    <input type="hidden" name="player_id" value="{{ $player->player_id }}">
                    <input type="hidden" name="action" id="actionType" value="warn">

                    <div class="action-grid mb-3">
                        @foreach (['warn' => 'Warn', 'kick' => 'Kick', 'ban' => 'Ban', 'commend' => 'Commend', 'note' => 'Note', 'trustscore' => 'Trust'] as $key => $label)
                            <button type="button" class="action-chip {{ $key === 'warn' ? 'active' : '' }}" data-action="{{ $key }}">{{ $label }}</button>
                        @endforeach
                    </div>

                    <div id="durationWrap" class="mb-3 d-none">
                        <label class="form-label">Duration</label>
                        <select name="duration" class="form-select">
                            <option value="perm">Permanent</option>
                            <option value="1h">1 hour</option>
                            <option value="1d">1 day</option>
                            <option value="7d">7 days</option>
                            <option value="30d">30 days</option>
                        </select>
                    </div>

                    <div id="scoreWrap" class="mb-3 d-none">
                        <label class="form-label">Trust score (0–100)</label>
                        <input type="number" min="0" max="100" name="trust_score" class="form-control" value="{{ $trust ?? 50 }}">
                    </div>

                    <div id="reasonWrap" class="mb-3">
                        <label class="form-label">Reason / note</label>
                        <textarea name="reason" class="form-control" rows="3" maxlength="255" placeholder="Brief reason for the audit log..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i> Submit action
                    </button>
                </form>
            </x-card>

            <x-card title="Watchlist" icon="eye" class="mt-4">
                <form method="post" action="{{ route('WATCHLIST_ADD') }}" class="p-3">
                    @csrf
                    <input type="hidden" name="player_id" value="{{ $player->player_id }}">
                    <div class="mb-3">
                        <label class="form-label">Flag this player</label>
                        <input type="text" name="reason" class="form-control" maxlength="255" placeholder="Why should staff watch them?">
                    </div>
                    <button class="btn btn-outline-warning" type="submit"><i class="fas fa-eye me-1"></i> Add to watchlist</button>
                </form>
            </x-card>

            <x-card title="Identifiers" icon="fingerprint" class="mt-4">
                <div class="p-3 identifier-grid">
                    <div><span>License</span>{{ $player->game_license ?: '—' }}</div>
                    <div><span>Steam</span>{{ $player->steam_id ?: '—' }}</div>
                    <div><span>Discord</span>{{ $player->discord_id ?: '—' }}</div>
                    <div><span>Live</span>{{ $player->live ?: '—' }}</div>
                    <div><span>Xbox</span>{{ $player->xbl ?: '—' }}</div>
                    <div><span>IP</span>{{ $player->ip ?: '—' }}</div>
                </div>
            </x-card>
        </div>

        <div class="col-lg-5 mb-4">
            <x-card title="Recent Activity" icon="clock-rotate-left">
                <div class="activity-feed">
                    @forelse ($activity as $event)
                        <x-activity-item :badge="$event['badge']" :type="$event['type']" :time="$event['time']">
                            <strong>{{ $event['admin'] }}</strong> {{ $event['verb'] }} this player
                            <div class="activity-reason">{{ $event['reason'] }}</div>
                        </x-activity-item>
                    @empty
                        <x-empty-state icon="stream" title="No activity recorded yet" />
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>

    @foreach ([
        ['Warnings', $warns, 'reason', 'warn', 'Remove'],
        ['Kicks', $kicks, 'reason', 'kick', 'Remove'],
        ['Bans', $bans, 'reason', 'ban', 'Unban'],
        ['Commends', $commends, 'reason', 'commend', 'Remove'],
        ['Notes', $notes, 'note', 'note', 'Remove'],
    ] as [$title, $rows, $textKey, $type, $actionLabel])
        <x-card :title="$title.' ('.$rows->count().')'" class="mb-4">
            <x-panel-table :headers="['Detail', 'Staff', 'When', '']">
                @forelse ($rows as $row)
                    <tr>
                        <td>{{ $row->{$textKey} ?: '—' }}@if($title === 'Bans') · {{ $row->expires ? ($row->expiredDate ?: 'Temporary') : 'Permanent' }}@endif</td>
                        <td>
                            @if (!empty($staffNames[$row->staff_id]))
                                <a href="{{ route('STAFF_PROFILE', ['staffId' => $row->staff_id]) }}">{{ $staffNames[$row->staff_id] }}</a>
                            @else
                                Staff
                            @endif
                        </td>
                        <td>{{ PanelActivity::when($row->created_at) }}</td>
                        <td>@include('verified._partials.revoke_form', ['type' => $type, 'id' => $row->id, 'label' => $actionLabel])</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-muted">No {{ strtolower($title) }} yet.</td></tr>
                @endforelse
            </x-panel-table>
        </x-card>
    @endforeach
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.action-chip').forEach((btn) => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.action-chip').forEach((el) => el.classList.remove('active'));
                btn.classList.add('active');
                const action = btn.dataset.action;
                document.getElementById('actionType').value = action;
                document.getElementById('durationWrap').classList.toggle('d-none', action !== 'ban');
                document.getElementById('scoreWrap').classList.toggle('d-none', action !== 'trustscore');
                document.getElementById('reasonWrap').classList.toggle('d-none', action === 'trustscore');
            });
        });
    </script>
@endsection
