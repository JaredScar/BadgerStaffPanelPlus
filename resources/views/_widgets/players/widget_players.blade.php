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
            <div class="card players-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-muted mb-0">Total Players</h5>
                            <h2 class="players-stat-number"><?php echo $totalPlayers; ?></h2>
                            <small class="text-muted">Today</small>
                        </div>
                        <div class="players-stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card players-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-muted mb-0">Currently Online</h5>
                            <h2 class="players-stat-number-online"><?php echo $currentlyOnline; ?></h2>
                            <small class="text-muted">Active now</small>
                        </div>
                        <div class="players-stat-icon-online">
                            <i class="fas fa-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card players-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-muted mb-0">Total Playtime</h5>
                            <h2 class="players-stat-number"><?php echo $totalPlaytimeHours; ?>h <?php echo $totalPlaytimeRemainder; ?>m</h2>
                            <small class="text-muted">Combined today</small>
                        </div>
                        <div class="players-stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card players-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-muted mb-0">Avg Trust Score</h5>
                            <h2 class="players-stat-number-trust"><?php echo $avgTrustScore; ?></h2>
                            <small class="text-muted">All players</small>
                        </div>
                        <div class="players-stat-icon-trust">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" class="form-control" id="playersSearch" placeholder="Search by name or Steam ID...">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="playersStatusFilter">
                <option value="">All Status</option>
                <option value="online">Online</option>
                <option value="offline">Offline</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="playersJoinTimeFilter">
                <option value="">Join Time</option>
                <option value="recent">Last 2 hours</option>
                <option value="morning">Morning (6-12)</option>
                <option value="afternoon">Afternoon (12-18)</option>
                <option value="evening">Evening (18-24)</option>
            </select>
        </div>
    </div>

    <!-- Players Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Players (<?php echo $totalPlayers; ?>)</h5>
                    <div class="table-responsive">
                        <table id="playersTable" class="table table-hover">
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
                                            <div class="empty-state">
                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No players found</h5>
                                                <p class="text-muted">No players have joined today</p>
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
                </div>
            </div>
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
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('playersSearch');
    const statusFilter = document.getElementById('playersStatusFilter');
    const joinTimeFilter = document.getElementById('playersJoinTimeFilter');
    const table = document.getElementById('playersTable');
    const tbody = table.getElementsByTagName('tbody')[0];
    const rows = Array.from(tbody.getElementsByTagName('tr'));

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const joinTimeValue = joinTimeFilter.value;

        rows.forEach(row => {
            if (row.cells.length === 1) return; // Skip empty state row
            
            const playerName = row.cells[0].textContent.toLowerCase();
            const status = row.cells[1].textContent.trim().toLowerCase();
            const joinTime = row.cells[2].textContent.trim();
            const location = row.cells[4].textContent.toLowerCase();
            
            const matchesSearch = playerName.includes(searchTerm) || 
                                location.includes(searchTerm);
            const matchesStatus = !statusValue || status.includes(statusValue);
            
            let matchesJoinTime = true;
            if (joinTimeValue) {
                const hour = parseInt(joinTime.split(':')[0]);
                const isPM = joinTime.includes('PM');
                const hour24 = isPM && hour !== 12 ? hour + 12 : (!isPM && hour === 12 ? 0 : hour);
                
                switch (joinTimeValue) {
                    case 'recent':
                        const now = new Date();
                        const twoHoursAgo = now.getHours() - 2;
                        matchesJoinTime = hour24 >= twoHoursAgo;
                        break;
                    case 'morning':
                        matchesJoinTime = hour24 >= 6 && hour24 < 12;
                        break;
                    case 'afternoon':
                        matchesJoinTime = hour24 >= 12 && hour24 < 18;
                        break;
                    case 'evening':
                        matchesJoinTime = hour24 >= 18 || hour24 < 6;
                        break;
                }
            }
            
            if (matchesSearch && matchesStatus && matchesJoinTime) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
    joinTimeFilter.addEventListener('change', filterTable);
});
</script>
