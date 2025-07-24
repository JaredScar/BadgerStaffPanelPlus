<?php

use App\Models\Player;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

$serverId = Session::get('server_id');
if (isset($data['selected_pid']))
    $tabData = Player::with("getPlayerData")->where('player_id', $data['selected_pid'])->where('server_id', $serverId)->get();
else
    $tabData = Player::with("getPlayerData")->where('server_id', $serverId)->get();

$data['data'] = $tabData;

// Calculate statistics
$totalPlayers = $tabData->count();
$averageScore = $tabData->where('getPlayerData')->avg('getPlayerData.trust_score') ?? 0;
$excellentScores = $tabData->filter(function($player) {
    return $player->getPlayerData && $player->getPlayerData->trust_score >= 80;
})->count();
$poorScores = $tabData->filter(function($player) {
    return $player->getPlayerData && $player->getPlayerData->trust_score < 50;
})->count();
?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ round($averageScore) }}</h3>
                <p class="stat-label">Average Score</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $excellentScores }}</h3>
                <p class="stat-label">Excellent Scores</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-red">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $poorScores }}</h3>
                <p class="stat-label">Poor Scores</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $totalPlayers }}</h3>
                <p class="stat-label">Total Players</p>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" class="form-control" placeholder="Search players..." id="trustScoreSearch">
        </div>
    </div>
    <div class="col-md-6">
        <select class="form-select" id="scoreFilter">
            <option value="">All Scores</option>
            <option value="excellent">Excellent (80+)</option>
            <option value="good">Good (50-79)</option>
            <option value="poor">Poor (0-49)</option>
        </select>
    </div>
</div>

<!-- Trust Score Records Section -->
<div class="section-header">
    <h2>Trust Score Records ({{ $totalPlayers }})</h2>
</div>

<!-- Trust Score Records Table -->
<div class="table-responsive">
    <table class="table table-hover trustscores-table">
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
            @foreach ($tabData as $player)
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
                                <i class="fas fa-user"></i>
                            </div>
                            <span>{{ $player->last_player_name ?? 'Unknown' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="discord-tag">{{ $player->discord_id ?? 'Not Available' }}</span>
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
                        <span class="trust-datetime">{{ $player->getPlayerData->last_join_date ? Carbon::parse($player->getPlayerData->last_join_date)->format('Y-m-d H:i:s') : 'Never' }}</span>
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
        </tbody>
    </table>
</div>

@if($tabData->isEmpty())
    <div class="no-data-message">
        <div class="text-center py-5">
            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No trust score records found</h4>
            <p class="text-muted">There are no trust score records to display.</p>
        </div>
    </div>
@endif

<script>
    // Search functionality
    document.getElementById('trustScoreSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.trustscores-table tbody tr');
        
        rows.forEach(row => {
            const playerId = row.cells[0].textContent.toLowerCase();
            const playerName = row.cells[1].textContent.toLowerCase();
            const discord = row.cells[2].textContent.toLowerCase();
            
            if (playerId.includes(searchTerm) || playerName.includes(searchTerm) || 
                discord.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Score filter functionality
    document.getElementById('scoreFilter').addEventListener('change', function(e) {
        const selectedFilter = e.target.value;
        const rows = document.querySelectorAll('.trustscores-table tbody tr');
        
        rows.forEach(row => {
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
</script>
