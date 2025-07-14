<?php
use App\Models\Ban;
use Carbon\Carbon;

if (isset($data['selected_pid']))
    $tabData = Ban::with('getPlayer')->with('getStaff')->where('player_id', $data['selected_pid'])->get();
else
    $tabData = Ban::with('getPlayer')->with('getStaff')->get();

$data['data'] = $tabData;

// Calculate statistics
$now = Carbon::now();
$activeBans = $tabData->filter(function($ban) use ($now) {
    if ($ban->expires === 'false') return true; // Permanent ban
    return $ban->expire_date && Carbon::parse($ban->expire_date)->isFuture();
})->count();

$expiredBans = $tabData->filter(function($ban) use ($now) {
    if ($ban->expires === 'false') return false; // Permanent ban never expires
    return $ban->expire_date && Carbon::parse($ban->expire_date)->isPast();
})->count();

$totalBans = $tabData->count();
?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon stat-icon-red">
                <i class="fas fa-ban"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $activeBans }}</h3>
                <p class="stat-label">Active Bans</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $expiredBans }}</h3>
                <p class="stat-label">Expired Bans</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange">
                <i class="fas fa-filter"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $totalBans }}</h3>
                <p class="stat-label">Total Bans</p>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" class="form-control" placeholder="Search bans..." id="banSearch">
        </div>
    </div>
    <div class="col-md-6">
        <select class="form-select" id="statusFilter">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="expired">Expired</option>
        </select>
    </div>
</div>

<!-- Ban Records Section -->
<div class="section-header">
    <h2>Ban Records ({{ $totalBans }})</h2>
</div>

<!-- Ban Records Table -->
<div class="table-responsive">
    <table class="table table-hover bans-table">
        <thead>
            <tr>
                <th>Player ID</th>
                <th>Player Name</th>
                <th>Discord</th>
                <th>Reason</th>
                <th>Duration</th>
                <th>Date/Time</th>
                <th>Staff Member</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data["data"] as $ban)
                @php
                    $isActive = true;
                    if ($ban->expires !== 'false' && $ban->expire_date) {
                        $isActive = Carbon::parse($ban->expire_date)->isFuture();
                    }
                    
                    // Calculate duration display
                    $duration = 'Permanent';
                    if ($ban->expires !== 'false' && $ban->expire_date && $ban->created_at) {
                        $start = Carbon::parse($ban->created_at);
                        $end = Carbon::parse($ban->expire_date);
                        $diffDays = $start->diffInDays($end);
                        
                        if ($diffDays == 1) {
                            $duration = '1 day';
                        } else if ($diffDays < 30) {
                            $duration = $diffDays . ' days';
                        } else {
                            $duration = round($diffDays / 30) . ' months';
                        }
                    }
                @endphp
                <tr>
                    <td>
                        <span class="player-id">{{ $ban->player_id }}</span>
                    </td>
                    <td>
                        <div class="player-info">
                            <div class="player-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span>{{ $ban->getPlayer->last_player_name ?? 'Unknown' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="discord-tag">{{ $ban->getPlayer->discord_id ?? 'Not Available' }}</span>
                    </td>
                    <td>
                        <span class="ban-reason">{{ $ban->reason }}</span>
                    </td>
                    <td>
                        <span class="ban-duration {{ $duration === 'Permanent' ? 'permanent' : 'temporary' }}">
                            {{ $duration }}
                        </span>
                    </td>
                    <td>
                        <span class="ban-datetime">{{ Carbon::parse($ban->created_at)->format('Y-m-d H:i:s') }}</span>
                    </td>
                    <td>
                        <span class="staff-badge">{{ $ban->getStaff->staff_username ?? 'Unknown' }}</span>
                    </td>
                    <td>
                        <span class="status-badge {{ $isActive ? 'status-active' : 'status-expired' }}">
                            {{ $isActive ? 'Active' : 'Expired' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($tabData->isEmpty())
    <div class="no-data-message">
        <div class="text-center py-5">
            <i class="fas fa-ban fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No ban records found</h4>
            <p class="text-muted">There are no ban records to display.</p>
        </div>
    </div>
@endif

<script>
    // Search functionality
    document.getElementById('banSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.bans-table tbody tr');
        
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

    // Status filter functionality
    document.getElementById('statusFilter').addEventListener('change', function(e) {
        const selectedStatus = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.bans-table tbody tr');
        
        rows.forEach(row => {
            const statusCell = row.cells[7].textContent.toLowerCase();
            
            if (selectedStatus === '' || statusCell.includes(selectedStatus)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
