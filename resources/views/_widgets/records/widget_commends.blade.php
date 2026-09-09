<?php
use App\Models\Commend;
use Carbon\Carbon;

if (isset($data['selected_pid']))
    $tabData = Commend::with('getPlayer')->with('getStaff')->where('player_id', $data['selected_pid'])->get();
else
    $tabData = Commend::with('getPlayer')->with('getStaff')->get();

$data['data'] = $tabData;

// Calculate statistics
$today = Carbon::today();
$thisWeek = Carbon::now()->startOfWeek();
$thisMonth = Carbon::now()->startOfMonth();

$todayCommends = collect($tabData)->filter(function ($commend) use ($today) {
    return Carbon::parse($commend->created_at)->isToday();
})->count();

$weekCommends = collect($tabData)->filter(function ($commend) use ($thisWeek) {
    return Carbon::parse($commend->created_at)->greaterThanOrEqualTo($thisWeek);
})->count();

$monthCommends = collect($tabData)->filter(function ($commend) use ($thisMonth) {
    return Carbon::parse($commend->created_at)->greaterThanOrEqualTo($thisMonth);
})->count();

$totalCommends = collect($tabData)->count();
?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $todayCommends }}</h3>
                <p class="stat-label">Today's Commends</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <i class="fas fa-calendar-week"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $weekCommends }}</h3>
                <p class="stat-label">This Week</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $monthCommends }}</h3>
                <p class="stat-label">This Month</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <i class="fas fa-thumbs-up"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $totalCommends }}</h3>
                <p class="stat-label">Total Commends</p>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" class="form-control" placeholder="Search commends..." id="commendSearch">
        </div>
    </div>
    <div class="col-md-6">
        <select class="form-select" id="typeFilter">
            <option value="">All Types</option>
            <option value="Excellent Roleplay">Excellent Roleplay</option>
            <option value="Helpful Behavior">Helpful Behavior</option>
            <option value="Good Sportsmanship">Good Sportsmanship</option>
            <option value="Community Contribution">Community Contribution</option>
            <option value="Leadership">Leadership</option>
            <option value="Other">Other</option>
        </select>
    </div>
</div>

<!-- Commend Records Section -->
<div class="section-header">
    <h2>Commend Records ({{ $totalCommends }})</h2>
</div>

<!-- Commend Records Table -->
<div class="table-responsive">
    <table class="table table-hover commends-table">
        <thead>
            <tr>
                <th>Player ID</th>
                <th>Player Name</th>
                <th>Discord</th>
                <th>Type</th>
                <th>Reason</th>
                <th>Date/Time</th>
                <th>Staff Member</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tabData as $commend)
                <tr>
                    <td>
                        <span class="player-id">{{ $commend->player_id }}</span>
                    </td>
                    <td>
                        <div class="player-info">
                            <div class="player-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span>{{ $commend->getPlayer->last_player_name ?? 'Unknown' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="discord-tag">{{ $commend->getPlayer->discord_id ?? 'Not Available' }}</span>
                    </td>
                    <td>
                        <span class="commend-type">{{ $commend->type ?? 'General' }}</span>
                    </td>
                    <td>
                        <span class="commend-reason">{{ $commend->reason ?? 'No reason provided' }}</span>
                    </td>
                    <td>
                        <span class="commend-datetime">{{ Carbon::parse($commend->created_at)->format('Y-m-d H:i:s') }}</span>
                    </td>
                    <td>
                        <span class="staff-badge">{{ $commend->getStaff->staff_username ?? 'Unknown' }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if(collect($tabData)->isEmpty())
    <div class="no-data-message">
        <div class="text-center py-5">
            <i class="fas fa-star fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No commend records found</h4>
            <p class="text-muted">There are no commend records to display.</p>
        </div>
    </div>
@endif

<script>
    // Search functionality
    document.getElementById('commendSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.commends-table tbody tr');
        
        rows.forEach(row => {
            const playerId = row.cells[0].textContent.toLowerCase();
            const playerName = row.cells[1].textContent.toLowerCase();
            const discord = row.cells[2].textContent.toLowerCase();
            const type = row.cells[3].textContent.toLowerCase();
            const reason = row.cells[4].textContent.toLowerCase();
            const staff = row.cells[6].textContent.toLowerCase();
            
            if (playerId.includes(searchTerm) || playerName.includes(searchTerm) || 
                discord.includes(searchTerm) || type.includes(searchTerm) || 
                reason.includes(searchTerm) || staff.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Type filter functionality
    document.getElementById('typeFilter').addEventListener('change', function(e) {
        const selectedType = e.target.value;
        const rows = document.querySelectorAll('.commends-table tbody tr');
        
        rows.forEach(row => {
            const typeCell = row.cells[3].textContent.trim();
            
            if (selectedType === '' || typeCell.includes(selectedType)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
