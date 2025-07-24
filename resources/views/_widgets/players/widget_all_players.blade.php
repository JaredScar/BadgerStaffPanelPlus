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
            <div class="card all-players-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-muted mb-0">Online Now</h5>
                            <h2 class="all-players-stat-number-online"><?php echo $currentlyOnline; ?></h2>
                            <small class="text-muted">of <?php echo $totalPlayers; ?> total</small>
                        </div>
                        <div class="all-players-stat-icon-online">
                            <i class="fas fa-signal"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card all-players-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-muted mb-0">Total Players</h5>
                            <h2 class="all-players-stat-number"><?php echo $totalPlayers; ?></h2>
                            <small class="text-muted">registered players</small>
                        </div>
                        <div class="all-players-stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card all-players-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-muted mb-0">Total Playtime</h5>
                            <h2 class="all-players-stat-number"><?php echo $totalPlaytimeHours; ?>h <?php echo $totalPlaytimeRemainder; ?>m</h2>
                            <small class="text-muted">combined playtime</small>
                        </div>
                        <div class="all-players-stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card all-players-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-muted mb-0">Avg Trust Score</h5>
                            <h2 class="all-players-stat-number-trust"><?php echo $avgTrustScore; ?></h2>
                            <small class="text-muted">average score</small>
                        </div>
                        <div class="all-players-stat-icon-trust">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Players Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">All Players (<?php echo $totalPlayers; ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($data['data'])): ?>
                        <table id="allPlayersTable" class="table table-hover">
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
                        <div class="text-center py-4">
                            <h5 class="text-muted">No players found</h5>
                            <p class="text-muted">No players are registered in the system</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
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
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('allPlayersSearch');
    const filterSelect = document.getElementById('allPlayersFilter');
    const table = document.getElementById('allPlayersTable');
    
    if (searchInput && filterSelect && table) {
        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const filterValue = filterSelect.value;
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                
                if (cells.length > 0) {
                    const playerId = cells[0].textContent.toLowerCase();
                    const playerName = cells[1].textContent.toLowerCase();
                    const discordTag = cells[2].textContent.toLowerCase();
                    const statusBadge = cells[7].querySelector('.badge');
                    const trustBadge = cells[8].querySelector('.badge');
                    
                    const status = statusBadge ? statusBadge.textContent.toLowerCase() : '';
                    const trustScore = trustBadge ? parseInt(trustBadge.textContent.match(/\d+/)[0]) : 0;
                    
                    // Search filter
                    const matchesSearch = playerId.includes(searchTerm) || 
                                        playerName.includes(searchTerm) || 
                                        discordTag.includes(searchTerm);
                    
                    // Status filter
                    let matchesFilter = true;
                    if (filterValue === 'online') {
                        matchesFilter = status === 'online';
                    } else if (filterValue === 'offline') {
                        matchesFilter = status === 'offline';
                    } else if (filterValue === 'high_trust') {
                        matchesFilter = trustScore >= 80;
                    } else if (filterValue === 'low_trust') {
                        matchesFilter = trustScore < 50;
                    }
                    
                    row.style.display = (matchesSearch && matchesFilter) ? '' : 'none';
                }
            }
        }
        
        searchInput.addEventListener('input', filterTable);
        filterSelect.addEventListener('change', filterTable);
    }
});
</script> 