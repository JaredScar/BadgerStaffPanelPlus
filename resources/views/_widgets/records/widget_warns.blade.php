<?php
use App\Models\Warn;
use Carbon\Carbon;

if (isset($data['selected_pid']))
    $tabData = Warn::with('getPlayer')->with('getStaff')->where('player_id', $data['selected_pid'])->get();
else
    $tabData = Warn::with('getPlayer')->with('getStaff')->get();

$data['data'] = $tabData;

// Calculate statistics
$highSeverityCount = $tabData->where('severity', 'High')->count();
$mediumSeverityCount = $tabData->where('severity', 'Medium')->count();
$lowSeverityCount = $tabData->where('severity', 'Low')->count();
$totalWarnings = $tabData->count();
?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-red">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $highSeverityCount }}</h3>
                <p class="stat-label">High Severity</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $mediumSeverityCount }}</h3>
                <p class="stat-label">Medium Severity</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $lowSeverityCount }}</h3>
                <p class="stat-label">Low Severity</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <i class="fas fa-list"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $totalWarnings }}</h3>
                <p class="stat-label">Total Warnings</p>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" class="form-control" placeholder="Search warnings..." id="warnSearch">
        </div>
    </div>
    <div class="col-md-6">
        <select class="form-select" id="severityFilter">
            <option value="">All Severities</option>
            <option value="High">High Severity</option>
            <option value="Medium">Medium Severity</option>
            <option value="Low">Low Severity</option>
        </select>
    </div>
</div>

<!-- Warn Records Section -->
<div class="section-header">
    <h2>Warning Records ({{ $totalWarnings }})</h2>
</div>

<!-- Warn Records Table -->
<div class="table-responsive">
    <table class="table table-hover warns-table">
        <thead>
            <tr>
                <th>Player ID</th>
                <th>Player Name</th>
                <th>Discord</th>
                <th>Reason</th>
                <th>Severity</th>
                <th>Date/Time</th>
                <th>Staff Member</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data["data"] as $warn)
                <tr>
                    <td>
                        <span class="player-id">{{ $warn->player_id }}</span>
                    </td>
                    <td>
                        <div class="player-info">
                            <div class="player-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span>{{ $warn->getPlayer->last_player_name ?? 'Unknown' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="discord-tag">{{ $warn->getPlayer->discord_id ?? 'Not Available' }}</span>
                    </td>
                    <td>
                        <span class="warn-reason">{{ $warn->reason }}</span>
                    </td>
                    <td>
                        <span class="severity-badge severity-{{ strtolower($warn->severity ?? 'medium') }}">
                            {{ $warn->severity ?? 'Medium' }}
                        </span>
                    </td>
                    <td>
                        <span class="warn-datetime">{{ Carbon::parse($warn->created_at)->format('Y-m-d H:i:s') }}</span>
                    </td>
                    <td>
                        <span class="staff-badge">{{ $warn->getStaff->staff_username ?? 'Unknown' }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($tabData->isEmpty())
    <div class="no-data-message">
        <div class="text-center py-5">
            <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No warning records found</h4>
            <p class="text-muted">There are no warning records to display.</p>
        </div>
    </div>
@endif

<script>
    // Search functionality
    document.getElementById('warnSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.warns-table tbody tr');
        
        rows.forEach(row => {
            const playerId = row.cells[0].textContent.toLowerCase();
            const playerName = row.cells[1].textContent.toLowerCase();
            const discord = row.cells[2].textContent.toLowerCase();
            const reason = row.cells[3].textContent.toLowerCase();
            const staff = row.cells[6].textContent.toLowerCase();
            
            if (playerId.includes(searchTerm) || playerName.includes(searchTerm) || 
                discord.includes(searchTerm) || reason.includes(searchTerm) || staff.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Severity filter functionality
    document.getElementById('severityFilter').addEventListener('change', function(e) {
        const selectedSeverity = e.target.value;
        const rows = document.querySelectorAll('.warns-table tbody tr');
        
        rows.forEach(row => {
            const severityCell = row.cells[4].textContent.trim();
            
            if (selectedSeverity === '' || severityCell.includes(selectedSeverity)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
