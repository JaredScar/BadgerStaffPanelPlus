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
                                Monthly Players
                            </h1>
                            <p class="page-description">View players who joined the server this month</p>
                        </div>

                        <!-- Search Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" id="monthlyPlayersSearch" placeholder="Search players...">
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card monthly-stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title text-muted mb-0">Online Now</h5>
                                                <h2 class="monthly-stat-number-online">1</h2>
                                                <small class="text-muted">of 3 monthly</small>
                                            </div>
                                            <div class="monthly-stat-icon-online">
                                                <i class="fas fa-chart-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card monthly-stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title text-muted mb-0">Monthly Players</h5>
                                                <h2 class="monthly-stat-number-orange">3</h2>
                                                <small class="text-muted">unique players</small>
                                            </div>
                                            <div class="monthly-stat-icon-orange">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card monthly-stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title text-muted mb-0">Total Playtime</h5>
                                                <h2 class="monthly-stat-number-blue">251h 5m</h2>
                                                <small class="text-muted">combined this month</small>
                                            </div>
                                            <div class="monthly-stat-icon-blue">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card monthly-stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title text-muted mb-0">Avg Trust Score</h5>
                                                <h2 class="monthly-stat-number-yellow">84</h2>
                                                <small class="text-muted">average score</small>
                                            </div>
                                            <div class="monthly-stat-icon-yellow">
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Players Table -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title monthly-players-title">This Month's Players (3)</h5>
                                        <div class="table-responsive">
                                            <table id="monthlyPlayersTable" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Player ID</th>
                                                        <th>Player Name</th>
                                                        <th>Discord</th>
                                                        <th>Total Playtime</th>
                                                        <th>Sessions</th>
                                                        <th>Avg Session</th>
                                                        <th>First Join</th>
                                                        <th>Status</th>
                                                        <th>Trust Score</th>
                                                        <th>Last Seen</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>12345</td>
                                                        <td>VeteranPlayer</td>
                                                        <td>VeteranPlayer#1234</td>
                                                        <td>120h 30m</td>
                                                        <td>45</td>
                                                        <td>2h 40m</td>
                                                        <td>2024-01-01</td>
                                                        <td>
                                                            <span class="badge status-online">Online</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge trust-excellent">95</span>
                                                            <small class="text-muted d-block">Excellent</small>
                                                        </td>
                                                        <td>Currently online</td>
                                                    </tr>
                                                    <tr>
                                                        <td>67890</td>
                                                        <td>RegularUser</td>
                                                        <td>RegularUser#5678</td>
                                                        <td>85h 15m</td>
                                                        <td>32</td>
                                                        <td>2h 39m</td>
                                                        <td>2024-01-03</td>
                                                        <td>
                                                            <span class="badge status-offline">Offline</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge trust-good">82</span>
                                                            <small class="text-muted d-block">Good</small>
                                                        </td>
                                                        <td>5 hours ago</td>
                                                    </tr>
                                                    <tr>
                                                        <td>11111</td>
                                                        <td>CasualPlayer</td>
                                                        <td>CasualPlayer#1111</td>
                                                        <td>45h 20m</td>
                                                        <td>18</td>
                                                        <td>2h 31m</td>
                                                        <td>2024-01-15</td>
                                                        <td>
                                                            <span class="badge status-offline">Offline</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge trust-good">75</span>
                                                            <small class="text-muted d-block">Good</small>
                                                        </td>
                                                        <td>2 days ago</td>
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
        /* Monthly Players Specific Styles */
        .monthly-stat-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .monthly-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .monthly-stat-number-online {
            font-size: 2rem;
            font-weight: 700;
            color: #28a745;
            margin-bottom: 0;
        }

        .monthly-stat-number-orange {
            font-size: 2rem;
            font-weight: 700;
            color: #fd7e14;
            margin-bottom: 0;
        }

        .monthly-stat-number-blue {
            font-size: 2rem;
            font-weight: 700;
            color: #007bff;
            margin-bottom: 0;
        }

        .monthly-stat-number-yellow {
            font-size: 2rem;
            font-weight: 700;
            color: #ffc107;
            margin-bottom: 0;
        }

        .monthly-stat-icon-online {
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

        .monthly-stat-icon-orange {
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

        .monthly-stat-icon-blue {
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

        .monthly-stat-icon-yellow {
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

        .monthly-players-title {
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

        #monthlyPlayersTable tbody tr {
            transition: background-color 0.3s ease;
        }

        #monthlyPlayersTable tbody tr:hover {
            background-color: rgba(253, 126, 20, 0.1);
        }

        #monthlyPlayersTable th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        #monthlyPlayersTable td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
        }

        /* Responsive adjustments for monthly table */
        @media (max-width: 1200px) {
            #monthlyPlayersTable {
                font-size: 0.85rem;
            }
            
            #monthlyPlayersTable th,
            #monthlyPlayersTable td {
                padding: 0.5rem 0.4rem;
            }
        }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('monthlyPlayersSearch');
            const table = document.getElementById('monthlyPlayersTable');
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
