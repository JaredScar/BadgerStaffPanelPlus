<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('_partials._html_header')
<body class="background-sizing gta-bg1">
    <div class="container-fluid master-contain">
        @include('_partials._toast')
        @include('_partials._sidebar')
        <div class="content-wrapper">
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

            <a href="{{ route('PLAYERS') }}" class="profile-back">
                <i class="fas fa-arrow-left"></i> Back to Players
            </a>

            <div class="page-header d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-user me-2"></i>{{ $name }}
                    </h1>
                    <p class="page-description">Player #{{ $player->player_id }} · last seen {{ $lastSeen }}</p>
                </div>
            </div>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

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

            <div class="row mb-4">
                <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-orange"><i class="fas fa-clock"></i></div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $playtime }}</h3>
                            <p class="stat-label">Playtime</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-blue"><i class="fas fa-sign-in-alt"></i></div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $joins }}</h3>
                            <p class="stat-label">Connections</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-red"><i class="fas fa-gavel"></i></div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $warns->count() + $kicks->count() + $bans->count() }}</h3>
                            <p class="stat-label">Infractions</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-green"><i class="fas fa-thumbs-up"></i></div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $commends->count() }}</h3>
                            <p class="stat-label">Commends</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7 mb-4">
                    <div class="panel-card">
                        <div class="panel-card-header"><i class="fas fa-gavel me-2"></i>Take Action</div>
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
                    </div>

                    <div class="panel-card mt-4">
                        <div class="panel-card-header"><i class="fas fa-fingerprint me-2"></i>Identifiers</div>
                        <div class="p-3 identifier-grid">
                            <div><span>License</span>{{ $player->game_license ?: '—' }}</div>
                            <div><span>Steam</span>{{ $player->steam_id ?: '—' }}</div>
                            <div><span>Discord</span>{{ $player->discord_id ?: '—' }}</div>
                            <div><span>Live</span>{{ $player->live ?: '—' }}</div>
                            <div><span>Xbox</span>{{ $player->xbl ?: '—' }}</div>
                            <div><span>IP</span>{{ $player->ip ?: '—' }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 mb-4">
                    <div class="panel-card">
                        <div class="panel-card-header"><i class="fas fa-clock-rotate-left me-2"></i>Recent Activity</div>
                        <div class="activity-feed">
                            @forelse ($activity as $event)
                                <div class="activity-row">
                                    <span class="badge {{ $event['badge'] }}">{{ $event['type'] }}</span>
                                    <div>
                                        <strong>{{ $event['admin'] }}</strong> {{ $event['verb'] }} this player
                                        <div class="activity-reason">{{ $event['reason'] }}</div>
                                        <small>{{ $event['time'] }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted">No activity recorded yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            @foreach ([
                ['Warnings', $warns, 'reason'],
                ['Kicks', $kicks, 'reason'],
                ['Bans', $bans, 'reason'],
                ['Commends', $commends, 'reason'],
                ['Notes', $notes, 'note'],
            ] as [$title, $rows, $textKey])
                <div class="panel-card mb-4">
                    <div class="panel-card-header">{{ $title }} ({{ $rows->count() }})</div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 profile-table">
                            <thead>
                                <tr>
                                    <th>Detail</th>
                                    <th>Staff</th>
                                    <th>When</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rows as $row)
                                    <tr>
                                        <td>{{ $row->{$textKey} ?: '—' }}@if($title === 'Bans') · {{ $row->expires ? ($row->expiredDate ?: 'Temporary') : 'Permanent' }}@endif</td>
                                        <td>{{ $staffNames[$row->staff_id] ?? 'Staff' }}</td>
                                        <td>{{ PanelActivity::when($row->created_at) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-muted">No {{ strtolower($title) }} yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @include('_partials._html_footer')
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
        @if (session('status'))
            showToast('success_toast', @json(session('status')));
        @endif
        @if (session('error'))
            showToast('error_toast', @json(session('error')));
        @endif
    </script>
</body>
</html>
