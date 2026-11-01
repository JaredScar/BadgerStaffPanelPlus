<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('_partials._html_header')
<body class="background-sizing gta-bg1">
    <div class="container-fluid master-contain">
        @include('_partials._toast')
        @include('_partials._sidebar')
        <div class="content-wrapper">
            <div class="page-header mb-4">
                <h1 class="page-title"><i class="fas fa-stream me-2"></i>Staff Activity</h1>
                <p class="page-description">Recent warns, kicks, bans, commends, and notes on this server</p>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-orange"><i class="fas fa-list"></i></div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $activity->count() }}</h3>
                            <p class="stat-label">Recent Events</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-red"><i class="fas fa-ban"></i></div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $activity->where('type', 'Ban')->count() }}</h3>
                            <p class="stat-label">Bans</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-orange"><i class="fas fa-exclamation-triangle"></i></div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $activity->where('type', 'Warn')->count() }}</h3>
                            <p class="stat-label">Warns</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-green"><i class="fas fa-thumbs-up"></i></div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $activity->where('type', 'Commend')->count() }}</h3>
                            <p class="stat-label">Commends</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 profile-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Player</th>
                                <th>Staff</th>
                                <th>Detail</th>
                                <th>When</th>
                            </tr>
                        </thead>
                        <tbody>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('_partials._html_footer')
</body>
</html>
