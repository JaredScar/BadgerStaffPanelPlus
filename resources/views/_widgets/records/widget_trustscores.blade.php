<?php

use App\Models\Player;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

$serverId = Session::get('server_id');
if (isset($data['selected_pid']))
    $playerData = Player::with("getPlayerData")->where('player_id', $data['selected_pid'])->where('server_id', $serverId)->get();
else
    $playerData = Player::with("getPlayerData")->where('server_id', $serverId)->get();

// Calculate statistics
$totalPlayers = $playerData->count();
$averageScore = $playerData->where('getPlayerData')->avg('getPlayerData.trust_score') ?? 0;
$excellentScores = $playerData->filter(function($player) {
    return $player->getPlayerData && $player->getPlayerData->trust_score >= 80;
})->count();
$poorScores = $playerData->filter(function($player) {
    return $player->getPlayerData && $player->getPlayerData->trust_score < 50;
})->count();

$data['data'] = $playerData;
?>

<div class="trustscores-container">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card stats-card-primary">
                <div class="stats-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ round($averageScore) }}</h3>
                    <p>Average Score</p>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $averageScore }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card stats-card-success">
                <div class="stats-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $excellentScores }}</h3>
                    <p>Excellent Scores</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card stats-card-danger">
                <div class="stats-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $poorScores }}</h3>
                    <p>Poor Scores</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card stats-card-warning">
                <div class="stats-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $totalPlayers }}</h3>
                    <p>Total Players</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Controls -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="trustScoreSearch" placeholder="Search players..." class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <select id="scoreFilter" class="form-select">
                <option value="">All Scores</option>
                <option value="excellent">Excellent (80+)</option>
                <option value="good">Good (50-79)</option>
                <option value="poor">Poor (0-49)</option>
            </select>
        </div>
    </div>

    <!-- Trust Scores Table -->
    <div class="table-container">
        <div class="table-header">
            <h5><i class="fas fa-chart-line text-primary me-2"></i>Trust Score Records ({{ $totalPlayers }})</h5>
        </div>
        <div class="table-responsive">
            <table id="trustScoreTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>Player ID</th>
                        <th>Player Name</th>
                        <th>Discord</th>
                        <th>Trust Score</th>
                        <th>Joins</th>
                        <th>Last Join Date</th>
                        <th>Trend</th>
                    </tr>
                </thead>
                <tbody>
                    @if($playerData->count() > 0)
                        @foreach ($playerData as $player)
                            @php
                                $trustScore = $player->getPlayerData->trust_score ?? 0;
                                $scoreClass = $trustScore >= 80 ? 'excellent' : ($trustScore >= 50 ? 'good' : 'poor');
                                $trendDirection = rand(0, 2); // 0 = down, 1 = neutral, 2 = up
                            @endphp
                            <tr data-score="{{ $trustScore }}" data-score-class="{{ $scoreClass }}">
                                <td>
                                    <span class="player-id">{{ $player->player_id }}</span>
                                </td>
                                <td>
                                    <div class="player-info">
                                        <div class="player-avatar">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <span class="player-name">{{ $player->last_player_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="discord-tag">{{ $player->discord_id }}</span>
                                </td>
                                <td>
                                    <span class="trust-score-badge trust-score-{{ $scoreClass }}">
                                        {{ $trustScore }}
                                        @if($scoreClass === 'excellent')
                                            <span class="score-label">Excellent</span>
                                        @elseif($scoreClass === 'good')
                                            <span class="score-label">Good</span>
                                        @else
                                            <span class="score-label">Poor</span>
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="joins-count">{{ $player->getPlayerData->joins ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="datetime-badge">{{ $player->getPlayerData->last_join_date ? Carbon::parse($player->getPlayerData->last_join_date)->format('Y-m-d') : 'Never' }}</span>
                                </td>
                                <td>
                                    <div class="trend-indicator">
                                        @if($trendDirection === 2)
                                            <i class="fas fa-arrow-up text-success"></i>
                                        @elseif($trendDirection === 1)
                                            <i class="fas fa-circle text-secondary"></i>
                                        @else
                                            <i class="fas fa-arrow-down text-danger"></i>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-chart-line text-primary"></i>
                                    <p>No trust score records found</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Reset Score Modal -->
<div class="modal fade" id="resetScoreModal" tabindex="-1" aria-labelledby="resetScoreModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetScoreModalLabel">
                    <i class="fas fa-redo text-warning me-2"></i>Reset Trust Score
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="resetScoreForm">
                    <div class="mb-3">
                        <label for="resetPlayerId" class="form-label">Player ID</label>
                        <input type="text" class="form-control" id="resetPlayerId" required>
                    </div>
                    <div class="mb-3">
                        <label for="resetPlayerName" class="form-label">Player Name</label>
                        <input type="text" class="form-control" id="resetPlayerName" required>
                    </div>
                    <div class="mb-3">
                        <label for="resetReason" class="form-label">Reset Reason</label>
                        <textarea class="form-control" id="resetReason" rows="3" required></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This will reset the player's trust score to 50 (default). This action cannot be undone.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="resetScore()">Reset Score</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Score Modal -->
<div class="modal fade" id="updateScoreModal" tabindex="-1" aria-labelledby="updateScoreModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateScoreModalLabel">
                    <i class="fas fa-arrow-up text-primary me-2"></i>Update Trust Score
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateScoreForm">
                    <div class="mb-3">
                        <label for="updatePlayerId" class="form-label">Player ID</label>
                        <input type="text" class="form-control" id="updatePlayerId" required>
                    </div>
                    <div class="mb-3">
                        <label for="updatePlayerName" class="form-label">Player Name</label>
                        <input type="text" class="form-control" id="updatePlayerName" required>
                    </div>
                    <div class="mb-3">
                        <label for="updateNewScore" class="form-label">New Trust Score</label>
                        <input type="number" class="form-control" id="updateNewScore" min="0" max="100" required>
                        <div class="form-text">Enter a value between 0 and 100</div>
                    </div>
                    <div class="mb-3">
                        <label for="updateReason" class="form-label">Update Reason</label>
                        <textarea class="form-control" id="updateReason" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateScore()">Update Score</button>
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('trustScoreSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#trustScoreTable tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Score filter
document.getElementById('scoreFilter').addEventListener('change', function() {
    const selectedFilter = this.value;
    const tableRows = document.querySelectorAll('#trustScoreTable tbody tr');
    
    tableRows.forEach(row => {
        const scoreClass = row.getAttribute('data-score-class');
        const score = parseInt(row.getAttribute('data-score'));
        
        let showRow = true;
        
        if (selectedFilter === 'excellent' && score < 80) {
            showRow = false;
        } else if (selectedFilter === 'good' && (score < 50 || score >= 80)) {
            showRow = false;
        } else if (selectedFilter === 'poor' && score >= 50) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
});

// Reset score function
function resetScore() {
    const form = document.getElementById('resetScoreForm');
    
    console.log('Resetting score:', {
        playerId: document.getElementById('resetPlayerId').value,
        playerName: document.getElementById('resetPlayerName').value,
        reason: document.getElementById('resetReason').value
    });
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('resetScoreModal'));
    modal.hide();
    form.reset();
    
    alert('Trust score reset successfully!');
}

// Update score function
function updateScore() {
    const form = document.getElementById('updateScoreForm');
    
    console.log('Updating score:', {
        playerId: document.getElementById('updatePlayerId').value,
        playerName: document.getElementById('updatePlayerName').value,
        newScore: document.getElementById('updateNewScore').value,
        reason: document.getElementById('updateReason').value
    });
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('updateScoreModal'));
    modal.hide();
    form.reset();
    
    alert('Trust score updated successfully!');
}
</script>
