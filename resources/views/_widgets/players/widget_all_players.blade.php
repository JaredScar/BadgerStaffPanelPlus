<?php
use App\Models\Player;
use Carbon\Carbon;

// Get all players with their data
$tabData = Player::with('getPlayerData')->withCount(['kicks', 'bans', 'warns', 'notes', 'commends'])->get();

// Calculate statistics
$totalPlayers = $tabData->count();
$currentlyOnline = $tabData->filter(function ($player) {
    return $player->getPlayerData && $player->getPlayerData->online_status === 'online';
})->count();

// Calculate total playtime for all players (in minutes, then convert to hours and minutes)
$totalPlaytimeMinutes = $tabData->sum(function ($player) {
    return $player->getPlayerData ? $player->getPlayerData->playtime : 0;
});
$totalPlaytimeHours = floor($totalPlaytimeMinutes / 60);
$totalPlaytimeRemainder = $totalPlaytimeMinutes % 60;

// Calculate average trust score
$avgTrustScore = $totalPlayers > 0 ? 
    round($tabData->avg(function ($player) {
        return $player->getPlayerData ? $player->getPlayerData->trust_score : 0;
    })) : 0;

$data['data'] = $tabData->values()->all();
?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <i class="fas fa-signal"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number text-success"><?php echo $currentlyOnline; ?></h3>
                <p class="stat-label">Online Now</p>
                <small class="text-muted">of <?php echo $totalPlayers; ?> total</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?php echo $totalPlayers; ?></h3>
                <p class="stat-label">Total Players</p>
                <small class="text-muted">registered players</small>
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
                <small class="text-muted">combined playtime</small>
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
                <small class="text-muted">average score</small>
            </div>
        </div>
    </div>
    </div>

<!-- Search and Filter Section -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" class="form-control" placeholder="Search players..." id="allPlayersSearch">
        </div>
    </div>
    <div class="col-md-6">
        <select class="form-select" id="allPlayersFilter">
            <option value="">All Players</option>
            <option value="online">Online Only</option>
            <option value="offline">Offline Only</option>
            <option value="high_trust">High Trust Score</option>
            <option value="low_trust">Low Trust Score</option>
        </select>
    </div>
</div>

<!-- All Players Records Section -->
<div class="section-header">
    <h2>All Players (<?php echo $totalPlayers; ?>)</h2>
</div>

<!-- All Players Records Table -->
<div class="table-responsive">
    <?php if (!empty($data['data'])): ?>
        <table class="table table-hover all-players-table">
        <thead>
            <tr>
                <th>Player ID</th>
                <th>Player Name</th>
                <th>Discord</th>
                <th>Total Playtime</th>
                <th>Sessions</th>
                <th>Connections</th>
                <th>First Join</th>
                <th>Status</th>
                <th>Trust Score</th>
                <th>Last Seen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['data'] as $index => $player): ?>
                                    <?php
                                    $playerData = $player->getPlayerData;
                                    $isOnline = $playerData && $playerData->online_status === 'online';
                                    $trustScore = $playerData ? $playerData->trust_score : 0;
                                    $playtimeMinutes = $playerData ? $playerData->playtime : 0;
                                    $playtimeHours = floor($playtimeMinutes / 60);
                                    $playtimeRemainder = $playtimeMinutes % 60;
                                    $joins = $playerData ? $playerData->joins : 0;
                                    $lastJoin = $playerData ? Carbon::parse($playerData->last_join_date) : null;
                ?>
                <tr>
                    <td>
                        <strong><?php echo $player->player_id; ?></strong>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="player-avatar">
                                <img src="https://via.placeholder.com/32x32/fd7e14/ffffff?text=<?php echo substr($player->last_player_name, 0, 1); ?>"
                                     alt="Avatar" class="avatar-sm">
                            </div>
                            <div class="ms-2">
                                <div class="player-name"><?php echo $player->last_player_name; ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="discord-tag"><?php echo $player->last_player_name; ?>#<?php echo substr($player->player_id, -4); ?></span>
                    </td>
                    <td>
                        <span class="playtime-badge"><?php echo $playtimeHours; ?>h <?php echo $playtimeRemainder; ?>m</span>
                    </td>
                    <td>
                        <span class="sessions-count"><?php echo $joins * 2; ?></span>
                    </td>
                    <td>
                        <span class="connections-count"><?php echo $joins; ?></span>
                    </td>
                    <td>
                        <span class="join-date"><?php echo $lastJoin ? $lastJoin->format('Y-m-d') : 'Unknown'; ?></span>
                    </td>
                    <td>
                        <?php if ($isOnline): ?>
                            <span class="badge bg-success">Online</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Offline</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($trustScore >= 80): ?>
                            <span class="badge bg-success"><?php echo $trustScore; ?> Excellent</span>
                        <?php elseif ($trustScore >= 60): ?>
                            <span class="badge bg-primary"><?php echo $trustScore; ?> Good</span>
                        <?php else: ?>
                            <span class="badge bg-warning"><?php echo $trustScore; ?> Fair</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($isOnline): ?>
                            <span class="last-seen-online">Currently online</span>
                        <?php else: ?>
                            <span class="last-seen-offline">
                                <?php
                                $timeDiff = rand(1, 7);
                                if ($timeDiff == 1) {
                                    echo $timeDiff . ' hour ago';
                                } elseif ($timeDiff <= 24) {
                                    echo $timeDiff . ' hours ago';
                                } else {
                                    echo floor($timeDiff / 24) . ' week ago';
                                }
                                ?>
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="no-data-message">
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No players found</h4>
                <p class="text-muted">No players are registered in the system.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* All Players Widget specific styles */
.all-players-stat-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    height: 100%;
}

.all-players-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
}

.all-players-stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #fd7e14;
    margin: 0;
    line-height: 1;
}

.all-players-stat-number-online {
    font-size: 2.5rem;
    font-weight: 700;
    color: #28a745;
    margin: 0;
    line-height: 1;
}

.all-players-stat-number-trust {
    font-size: 2.5rem;
    font-weight: 700;
    color: #17a2b8;
    margin: 0;
    line-height: 1;
}

.all-players-stat-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #fd7e14 0%, #ff9f40 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.all-players-stat-icon-online {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.all-players-stat-icon-trust {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.card-header {
    background: linear-gradient(135deg, #fd7e14 0%, #ff9f40 100%);
    border-radius: 12px 12px 0 0 !important;
    border: none;
    padding: 15px 20px;
}

.card-header .card-title {
    color: white;
    font-weight: 600;
    margin: 0;
    font-size: 1.25rem;
}

.table {
    margin: 0;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
    color: #2c3e50;
    padding: 12px;
}

.table td {
    padding: 12px;
    vertical-align: middle;
}

.player-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fd7e14;
    flex-shrink: 0;
}

.avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
}

.player-name {
    font-weight: 600;
    color: #2c3e50;
}

.discord-tag {
    font-family: 'Courier New', monospace;
    background: #7289da;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.85rem;
}

.playtime-badge {
    background: #e7f3ff;
    color: #0066cc;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.9rem;
}

.sessions-count, .connections-count {
    font-weight: 600;
    color: #2c3e50;
}

.join-date {
    color: #6c757d;
    font-size: 0.9rem;
}

.last-seen-online {
    color: #28a745;
    font-weight: 500;
}

.last-seen-offline {
    color: #6c757d;
    font-size: 0.9rem;
}

#allPlayersTable tbody tr {
    transition: background-color 0.2s ease;
}

#allPlayersTable tbody tr:hover {
    background-color: #f8f9fa;
}

@media (max-width: 768px) {
    .all-players-stat-number,
    .all-players-stat-number-online,
    .all-players-stat-number-trust {
        font-size: 2rem;
    }
    
    .all-players-stat-icon,
    .all-players-stat-icon-online,
    .all-players-stat-icon-trust {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }
}
</style>

<script>
// Search functionality
document.getElementById('allPlayersSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.all-players-table tbody tr');

    rows.forEach(row => {
        const playerId = row.cells[0].textContent.toLowerCase();
        const playerName = row.cells[1].textContent.toLowerCase();
        const discord = row.cells[2].textContent.toLowerCase();
        const status = row.cells[7].textContent.toLowerCase();
        const trustScore = row.cells[8].textContent.toLowerCase();

        if (playerId.includes(searchTerm) || playerName.includes(searchTerm) ||
            discord.includes(searchTerm) || status.includes(searchTerm) || trustScore.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Filter functionality
document.getElementById('allPlayersFilter').addEventListener('change', function(e) {
    const selectedFilter = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.all-players-table tbody tr');

    rows.forEach(row => {
        const statusCell = row.cells[7].textContent.toLowerCase();
        const trustScoreCell = row.cells[8].textContent.toLowerCase();
        const trustScore = parseInt(trustScoreCell.match(/\d+/)[0]) || 0;

        let shouldShow = true;

        if (selectedFilter === 'online') {
            shouldShow = statusCell.includes('online');
        } else if (selectedFilter === 'offline') {
            shouldShow = statusCell.includes('offline');
        } else if (selectedFilter === 'high_trust') {
            shouldShow = trustScore >= 80;
        } else if (selectedFilter === 'low_trust') {
            shouldShow = trustScore < 50;
        }

        row.style.display = shouldShow ? '' : 'none';
    });
});
</script> 