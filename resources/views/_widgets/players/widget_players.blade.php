<?php
use App\Models\Player;
use App\Support\PanelActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

$serverId = Session::get('server_id');
$query = Player::with('getPlayerData')->withCount(['kicks', 'bans', 'warns', 'notes', 'commends']);
if (isset($data['selected_pid'])) {
    $query->where('player_id', $data['selected_pid']);
}
if ($serverId) {
    $query->where('server_id', $serverId);
}
$tabData = $query->get();

$viewName = $data['view_name'] ?? 'PLAYERS_TODAY';
$todayPlayers = collect($tabData)->filter(function ($player) use ($viewName) {
    if (!$player->getPlayerData || !$player->getPlayerData->last_join_date) {
        return false;
    }
    $date = Carbon::parse($player->getPlayerData->last_join_date);
    if ($viewName === 'PLAYERS_WEEKLY') {
        return $date->isCurrentWeek();
    }
    if ($viewName === 'PLAYERS_MONTHLY') {
        return $date->isCurrentMonth();
    }
    return $date->isToday();
});

// Calculate statistics
$totalPlayers = $todayPlayers->count();
$currentlyOnline = $todayPlayers->filter(function ($player) {
    return $player->getPlayerData
        && $player->getPlayerData->last_join_date
        && Carbon::parse($player->getPlayerData->last_join_date)->gt(now()->subMinutes(15));
})->count();

// Calculate total playtime for today (in minutes, then convert to hours and minutes)
$totalPlaytimeMinutes = $todayPlayers->sum(function ($player) {
    return $player->getPlayerData ? $player->getPlayerData->playtime : 0;
});
$totalPlaytimeHours = floor($totalPlaytimeMinutes / 60);
$totalPlaytimeRemainder = $totalPlaytimeMinutes % 60;

// Calculate average trust score
$avgTrustScore = $todayPlayers->count() > 0 ? 
    round($todayPlayers->avg(function ($player) {
        return $player->getPlayerData ? $player->getPlayerData->trust_score : 0;
    })) : 0;

$data['data'] = $todayPlayers->values()->all();
?>

<div class="row mb-4">
    <div class="col-md-3 col-6 mb-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?php echo $totalPlayers; ?></h3>
                <p class="stat-label">Total Players</p>
                <small class="text-muted">in this range</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <i class="fas fa-circle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number text-success"><?php echo $currentlyOnline; ?></h3>
                <p class="stat-label">Currently Online</p>
                <small class="text-muted">active now</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?php echo $totalPlaytimeHours; ?>h <?php echo $totalPlaytimeRemainder; ?>m</h3>
                <p class="stat-label">Total Playtime</p>
                <small class="text-muted">combined</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?php echo $avgTrustScore; ?></h3>
                <p class="stat-label">Avg Trust Score</p>
                <small class="text-muted">all players</small>
            </div>
        </div>
    </div>
</div>

<div class="panel-toolbar">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" placeholder="Search players..." id="playersSearch">
    </div>
    <select class="form-select" id="playersStatusFilter">
        <option value="">All Status</option>
        <option value="online">Online</option>
        <option value="offline">Offline</option>
    </select>
</div>

<div class="panel-card">
    <div class="panel-card-header">
        <i class="fas fa-users me-2"></i>Players (<?php echo $totalPlayers; ?>)
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 profile-table players-table">
        <thead>
            <tr>
                <th>Player</th>
                <th>Status</th>
                <th>Join Time</th>
                <th>Playtime</th>
                <th>Discord</th>
                <th>Trust Score</th>
                <th>Warnings</th>
                <th>Commends</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data['data'])): ?>
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <div class="no-data-message">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No players found</h4>
                            <p class="text-muted">No players match this time range.</p>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($data['data'] as $index => $player): ?>
                                        <?php
                                        $playerData = $player->getPlayerData;
                                        $lastJoin = $playerData && $playerData->last_join_date ? Carbon::parse($playerData->last_join_date) : null;
                                        $isOnline = $lastJoin && $lastJoin->gt(now()->subMinutes(15));
                                        $playerTrustScore = $playerData->trust_score ?? 0;
                                        $playerWarnings = $player->warns_count;
                                        $playerCommends = $player->commends_count;
                    ?>
                    <tr class="clickable-row" onclick="window.location='/web/verified/players/<?php echo $player->player_id; ?>'">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="player-avatar me-3">
                                    <span><?php echo htmlspecialchars(PanelActivity::initials($player->last_player_name)); ?></span>
                                </div>
                                <div class="player-info">
                                    <div class="player-name"><?php echo htmlspecialchars($player->last_player_name ?: 'Unknown'); ?></div>
                                    <div class="player-steam-id">#<?php echo $player->player_id; ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge <?php echo $isOnline ? 'status-online' : 'status-offline'; ?>">
                                <?php echo $isOnline ? 'Online' : 'Offline'; ?>
                            </span>
                        </td>
                        <td>
                            <div class="join-time"><?php echo $lastJoin ? $lastJoin->format('M j, g:ia') : '—'; ?></div>
                        </td>
                        <td>
                            <div class="playtime"><?php echo PanelActivity::playtime($playerData->playtime ?? 0); ?></div>
                        </td>
                        <td>
                            <div class="location"><?php echo $player->discord_id ?: '—'; ?></div>
                        </td>
                        <td>
                            <div class="trust-score trust-score-<?php echo $playerTrustScore >= 80 ? 'high' : ($playerTrustScore >= 60 ? 'medium' : 'low'); ?>">
                                <?php echo $playerTrustScore; ?>
                            </div>
                        </td>
                        <td>
                            <?php if ($playerWarnings > 0): ?>
                                <span class="badge warning-badge"><?php echo $playerWarnings; ?></span>
                            <?php else: ?>
                                <span class="text-muted">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($playerCommends > 0): ?>
                                <span class="badge commend-badge"><?php echo $playerCommends; ?></span>
                            <?php else: ?>
                                <span class="text-muted">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="/web/verified/players/<?php echo $player->player_id; ?>" onclick="event.stopPropagation();" title="View Player">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<style>
/* Players-specific styles */
.players-stat-card {
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.players-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.players-stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #fd7e14;
    margin-bottom: 0;
}

.players-stat-number-online {
    font-size: 2rem;
    font-weight: 700;
    color: #28a745;
    margin-bottom: 0;
}

.players-stat-number-trust {
    font-size: 2rem;
    font-weight: 700;
    color: #ffc107;
    margin-bottom: 0;
}

.players-stat-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #fd7e14 0%, #fd9843 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.players-stat-icon-online {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.players-stat-icon-trust {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #ffc107 0%, #ffdb4d 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.status-online,
.status-offline,
.trust-score,
.warning-badge,
.commend-badge {
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.72rem;
    letter-spacing: 0.02em;
    padding: 0.28rem 0.6rem;
}

.player-avatar img {
    width: 40px;
    height: 40px;
    object-fit: cover;
}

.player-info {
    line-height: 1.2;
}

.player-name {
    font-weight: 600;
    color: #333;
}

.player-steam-id {
    font-size: 0.8rem;
    color: #666;
}

.join-time {
    font-size: 0.9rem;
    color: #333;
    font-weight: 500;
}

.playtime {
    font-size: 0.9rem;
    color: #666;
}

.location {
    font-size: 0.9rem;
    color: #666;
}

.trust-score {
    font-size: 1rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 5px;
    display: inline-block;
}

.trust-score-high {
    background: #d4edda;
    color: #155724;
}

.trust-score-medium {
    background: #fff3cd;
    color: #856404;
}

.trust-score-low {
    background: #f8d7da;
    color: #721c24;
}

.warning-badge {
    background: #dc3545;
    color: white;
    padding: 0.3rem 0.6rem;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 500;
}

.commend-badge {
    background: #28a745;
    color: white;
    padding: 0.3rem 0.6rem;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 500;
}

.action-buttons .btn {
    margin-right: 0.25rem;
}

.action-buttons .btn:last-child {
    margin-right: 0;
}

.empty-state {
    padding: 3rem 1rem;
}

#playersTable tbody tr {
    transition: background-color 0.3s ease;
}

#playersTable tbody tr:hover {
    background-color: rgba(253, 126, 20, 0.1);
}
</style>

<script>
// Search functionality
document.getElementById('playersSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.players-table tbody tr');

    rows.forEach(row => {
        const playerName = row.cells[0].textContent.toLowerCase();
        const steamId = row.cells[0].textContent.toLowerCase();
        const status = row.cells[1].textContent.toLowerCase();
        const location = row.cells[4].textContent.toLowerCase();

        if (playerName.includes(searchTerm) || steamId.includes(searchTerm) ||
            status.includes(searchTerm) || location.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Status filter functionality
document.getElementById('playersStatusFilter').addEventListener('change', function(e) {
    const selectedStatus = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.players-table tbody tr');

    rows.forEach(row => {
        const statusCell = row.cells[1].textContent.toLowerCase();

        if (selectedStatus === '' || statusCell.includes(selectedStatus)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
