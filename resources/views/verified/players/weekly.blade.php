<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('_partials._html_header')
    <body class="background-sizing gta-bg1">
        <div class="container-fluid master-contain">
            @include('_partials._toast')
            <div class="row">
                <div class="col col-auto px-0">
                    @include('_partials._sidebar')
                </div>
                <div class="col col-auto flex-fill page-contain">
                    <div class="content-wrapper">
                        <!-- Header Section -->
                        <div class="page-header mb-4">
                            <h1 class="page-title">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Weekly Players
                            </h1>
                            <p class="page-description">View players who joined the server this week</p>
                        </div>

                        <!-- Search Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" id="weeklyPlayersSearch" placeholder="Search players...">
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card weekly-stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title text-muted mb-0">Online Now</h5>
                                                <h2 class="weekly-stat-number-online">1</h2>
                                                <small class="text-muted">of 3 weekly</small>
                                            </div>
                                            <div class="weekly-stat-icon-online">
                                                <i class="fas fa-chart-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card weekly-stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title text-muted mb-0">Weekly Players</h5>
                                                <h2 class="weekly-stat-number-orange">3</h2>
                                                <small class="text-muted">unique players</small>
                                            </div>
                                            <div class="weekly-stat-icon-orange">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card weekly-stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title text-muted mb-0">Total Playtime</h5>
                                                <h2 class="weekly-stat-number-blue">76h 30m</h2>
                                                <small class="text-muted">combined this week</small>
                                            </div>
                                            <div class="weekly-stat-icon-blue">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card weekly-stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title text-muted mb-0">Avg Trust Score</h5>
                                                <h2 class="weekly-stat-number-yellow">85</h2>
                                                <small class="text-muted">average score</small>
                                            </div>
                                            <div class="weekly-stat-icon-yellow">
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Weekly Players Table -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title weekly-players-title">This Week's Players (3)</h5>
                                        <div class="table-responsive">
                                            <table id="weeklyPlayersTable" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Player ID</th>
                                                        <th>Player Name</th>
                                                        <th>Discord</th>
                                                        <th>Total Playtime</th>
                                                        <th>Sessions</th>
                                                        <th>Avg Session</th>
                                                        <th>Status</th>
                                                        <th>Trust Score</th>
                                                        <th>Last Seen</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>12345</td>
                                                        <td>ActivePlayer</td>
                                                        <td>ActivePlayer#1234</td>
                                                        <td>25h 30m</td>
                                                        <td>12</td>
                                                        <td>2h 7m</td>
                                                        <td>
                                                            <span class="badge status-online">Online</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge trust-excellent">92</span>
                                                            <small class="text-muted d-block">Excellent</small>
                                                        </td>
                                                        <td>Currently online</td>
                                                    </tr>
                                                    <tr>
                                                        <td>67890</td>
                                                        <td>RegularUser</td>
                                                        <td>RegularUser#5678</td>
                                                        <td>18h 45m</td>
                                                        <td>8</td>
                                                        <td>2h 20m</td>
                                                        <td>
                                                            <span class="badge status-offline">Offline</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge trust-good">78</span>
                                                            <small class="text-muted d-block">Good</small>
                                                        </td>
                                                        <td>2 hours ago</td>
                                                    </tr>
                                                    <tr>
                                                        <td>11111</td>
                                                        <td>WeekendWarrior</td>
                                                        <td>WeekendWarrior#1111</td>
                                                        <td>32h 15m</td>
                                                        <td>6</td>
                                                        <td>5h 22m</td>
                                                        <td>
                                                            <span class="badge status-offline">Offline</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge trust-good">85</span>
                                                            <small class="text-muted d-block">Good</small>
                                                        </td>
                                                        <td>1 day ago</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('_partials._html_footer')

        <style>
        /* Weekly Players Specific Styles */
        .weekly-stat-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .weekly-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .weekly-stat-number-online {
            font-size: 2rem;
            font-weight: 700;
            color: #28a745;
            margin-bottom: 0;
        }

        .weekly-stat-number-orange {
            font-size: 2rem;
            font-weight: 700;
            color: #fd7e14;
            margin-bottom: 0;
        }

        .weekly-stat-number-blue {
            font-size: 2rem;
            font-weight: 700;
            color: #007bff;
            margin-bottom: 0;
        }

        .weekly-stat-number-yellow {
            font-size: 2rem;
            font-weight: 700;
            color: #ffc107;
            margin-bottom: 0;
        }

        .weekly-stat-icon-online {
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

        .weekly-stat-icon-orange {
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

        .weekly-stat-icon-blue {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .weekly-stat-icon-yellow {
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

        .weekly-players-title {
            color: #fd7e14;
            font-weight: 600;
            margin-bottom: 1rem;
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
            background: #6c757d;
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .trust-excellent {
            background: #28a745;
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .trust-good {
            background: #007bff;
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .trust-poor {
            background: #dc3545;
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        #weeklyPlayersTable tbody tr {
            transition: background-color 0.3s ease;
        }

        #weeklyPlayersTable tbody tr:hover {
            background-color: rgba(253, 126, 20, 0.1);
        }

        #weeklyPlayersTable th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        #weeklyPlayersTable td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
        }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('weeklyPlayersSearch');
            const table = document.getElementById('weeklyPlayersTable');
            const tbody = table.getElementsByTagName('tbody')[0];
            const rows = Array.from(tbody.getElementsByTagName('tr'));

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();

                rows.forEach(row => {
                    const playerId = row.cells[0].textContent.toLowerCase();
                    const playerName = row.cells[1].textContent.toLowerCase();
                    const discord = row.cells[2].textContent.toLowerCase();
                    
                    const matchesSearch = playerId.includes(searchTerm) || 
                                        playerName.includes(searchTerm) ||
                                        discord.includes(searchTerm);
                    
                    if (matchesSearch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('input', filterTable);
        });
        </script>
    </body>
</html>
