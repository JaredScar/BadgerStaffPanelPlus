<?php
use App\Models\Player;
use Carbon\Carbon;

if (isset($data['selected_pid']))
    $tabData = Player::with('getPlayerData')->withCount(['kicks', 'bans', 'warns', 'notes', 'commends'])
        ->where('player_id', $data['selected_pid'])->get();
else
    $tabData = Player::with('getPlayerData')->withCount(['kicks', 'bans', 'warns', 'notes', 'commends'])->get();

// Filter players who joined today
$today = Carbon::today();
$todayPlayers = collect($tabData)->filter(function ($player) use ($today) {
    return $player->getPlayerData && Carbon::parse($player->getPlayerData->last_join_date)->isToday();
});

// Calculate statistics
$totalPlayers = $todayPlayers->count();
$currentlyOnline = $todayPlayers->filter(function ($player) {
    return $player->getPlayerData && $player->getPlayerData->online_status === 'online';
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

// Sample locations for demo
$locations = ['Los Santos', 'Sandy Shores', 'Paleto Bay', 'Vinewood', 'Mirror Park'];

$data['data'] = $todayPlayers->values()->all();
?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?php echo $totalPlayers; ?></h3>
                <p class="stat-label">Total Players</p>
                <small class="text-muted">joined today</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
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
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?php echo $totalPlaytimeHours; ?>h <?php echo $totalPlaytimeRemainder; ?>m</h3>
                <p class="stat-label">Total Playtime</p>
                <small class="text-muted">combined today</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
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

<!-- Search and Filter Section -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" class="form-control" placeholder="Search players..." id="playersSearch">
        </div>
    </div>
    <div class="col-md-6">
        <select class="form-select" id="playersStatusFilter">
            <option value="">All Status</option>
            <option value="online">Online</option>
            <option value="offline">Offline</option>
        </select>
    </div>
</div>

<!-- Players Records Section -->
<div class="section-header">
    <h2>Players (<?php echo $totalPlayers; ?>)</h2>
</div>

<!-- Players Records Table -->
<div class="table-responsive">
    <table class="table table-hover players-table">
        <thead>
            <tr>
                <th>Player</th>
                <th>Status</th>
                <th>Join Time</th>
                <th>Playtime</th>
                <th>Location</th>
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
                            <p class="text-muted">No players have joined today.</p>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($data['data'] as $index => $player): ?>
                                        <?php
                                        // Demo data for mockup
                                        $isOnline = $index < 3; // First 3 players are online
                                        $joinTimes = ['03:30 AM', '04:15 AM', '05:00 AM', '06:30 AM', '07:45 AM'];
                                        $playtimes = ['5h 30m', '4h 15m', '3h 45m', '2h 20m', '1h 15m'];
                                        $location = $locations[array_rand($locations)];
                                        $trustScores = [85, 92, 78, 95, 88];
                                        $warnings = [0, 1, 2, 0, 1];
                                        $commends = [12, 8, 5, 15, 9];
                                        
                                        $playerJoinTime = $joinTimes[$index] ?? '08:00 AM';
                                        $playerPlaytime = $playtimes[$index] ?? '1h 0m';
                                        $playerTrustScore = $trustScores[$index] ?? ($player->getPlayerData ? $player->getPlayerData->trust_score : 75);
                                        $playerWarnings = $warnings[$index] ?? $player->warns_count;
                                        $playerCommends = $commends[$index] ?? $player->commends_count;
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="player-avatar me-3">
                                    <img src="https://via.placeholder.com/40x40?text=<?php echo substr($player->getPlayerData->last_player_name ?? 'P', 0, 1); ?>"
                                         alt="Player Avatar" class="rounded-circle">
                                </div>
                                <div class="player-info">
                                    <div class="player-name"><?php echo $player->getPlayerData->last_player_name ?? 'PlayerName'; ?></div>
                                    <div class="player-steam-id"><?php echo $player->player_id; ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge <?php echo $isOnline ? 'status-online' : 'status-offline'; ?>">
                                <?php echo $isOnline ? 'Online' : 'Offline'; ?>
                            </span>
                        </td>
                        <td>
                            <div class="join-time"><?php echo $playerJoinTime; ?></div>
                        </td>
                        <td>
                            <div class="playtime"><?php echo $playerPlaytime; ?></div>
                        </td>
                        <td>
                            <div class="location"><?php echo $location; ?></div>
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
                            <div class="btn-group action-buttons" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary" title="View Player">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info" title="Message">
                                    <i class="fas fa-comments"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning" title="Warn">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Kick">
                                    <i class="fas fa-user-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
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

.status-online {
    background: #28a745;
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-offline {
    background: #ffc107;
    color: #212529;
    padding: 0.4rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
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
