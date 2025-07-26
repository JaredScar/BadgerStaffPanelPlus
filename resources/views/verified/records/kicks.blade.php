<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('_partials._html_header')
    <body class="background-sizing gta-bg1">
        <div class="container-fluid master-contain">
            @include('_partials._toast')
            @include('_partials._sidebar')
            <div class="content-wrapper">
                <!-- Header Section -->
                <div class="page-header d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-user-times me-2"></i>
                            Kicks
                        </h1>
                        <p class="page-description">Manage and view player kicks</p>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKickModal">
                        <i class="fas fa-plus me-2"></i>
                        Add Kick
                    </button>
                </div>

                <!-- Include the kicks widget -->
                @include('_widgets.records.widget_kicks')
            </div>
        </div>

        <!-- Add Kick Modal -->
        <div class="modal fade" id="addKickModal" tabindex="-1" aria-labelledby="addKickModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addKickModalLabel">
                            <i class="fas fa-user-times me-2"></i>
                            Add Kick Record
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addKickForm">
                            @csrf
                            <div class="mb-3">
                                <label for="playerSearch" class="form-label">Select Players</label>
                                <div class="player-search-container">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control" id="playerSearch" placeholder="Search players by name or ID..." autocomplete="off">
                                    </div>
                                    <div class="search-results" id="searchResults" style="display: none;"></div>
                                </div>
                                <div class="selected-players mt-3" id="selectedPlayers">
                                    <div class="selected-players-header">
                                        <strong>Selected Players:</strong>
                                        <span class="selected-count" id="selectedCount">0</span>
                                    </div>
                                    <div class="selected-players-list" id="selectedPlayersList">
                                        <div class="no-players-selected text-muted">
                                            <i class="fas fa-users me-2"></i>
                                            No players selected. Search and click to add players.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="kickReason" class="form-label">Reason</label>
                                <textarea class="form-control" id="kickReason" rows="3" placeholder="Enter the reason for the kick..." required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="kickDateTime" class="form-label">Date/Time</label>
                                <input type="datetime-local" class="form-control" id="kickDateTime" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" form="addKickForm">
                            <i class="fas fa-user-times me-2"></i>
                            Add Kick
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .player-search-container {
                position: relative;
            }

            .search-results {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                border: 1px solid #dee2e6;
                border-top: none;
                border-radius: 0 0 6px 6px;
                max-height: 300px;
                overflow-y: auto;
                z-index: 1000;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .search-result-item {
                padding: 12px 15px;
                cursor: pointer;
                border-bottom: 1px solid #f8f9fa;
                display: flex;
                align-items: center;
                gap: 10px;
                transition: background-color 0.2s ease;
            }

            .search-result-item:hover {
                background-color: #f8f9fa;
            }

            .search-result-item:last-child {
                border-bottom: none;
            }

            .search-result-avatar {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: linear-gradient(135deg, #fd7e14 0%, #e8590c 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 14px;
                flex-shrink: 0;
            }

            .search-result-info {
                flex: 1;
            }

            .search-result-name {
                font-weight: 600;
                color: #333;
                margin-bottom: 2px;
            }

            .search-result-id {
                font-size: 0.85rem;
                color: #6c757d;
            }

            .selected-players {
                border: 1px solid #dee2e6;
                border-radius: 6px;
                padding: 15px;
                background: #f8f9fa;
            }

            .selected-players-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
                color: #495057;
            }

            .selected-count {
                background: #fd7e14;
                color: white;
                padding: 2px 8px;
                border-radius: 12px;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .selected-player-item {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 8px 12px;
                background: white;
                border: 1px solid #dee2e6;
                border-radius: 6px;
                margin-bottom: 8px;
                transition: all 0.2s ease;
            }

            .selected-player-item:last-child {
                margin-bottom: 0;
            }

            .selected-player-avatar {
                width: 28px;
                height: 28px;
                border-radius: 50%;
                background: linear-gradient(135deg, #fd7e14 0%, #e8590c 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 12px;
                flex-shrink: 0;
            }

            .selected-player-info {
                flex: 1;
            }

            .selected-player-name {
                font-weight: 600;
                color: #333;
                font-size: 0.9rem;
            }

            .selected-player-id {
                font-size: 0.75rem;
                color: #6c757d;
            }

            .remove-player-btn {
                background: none;
                border: none;
                color: #dc3545;
                cursor: pointer;
                padding: 4px;
                border-radius: 4px;
                transition: all 0.2s ease;
            }

            .remove-player-btn:hover {
                background: #dc3545;
                color: white;
            }

            .no-players-selected {
                text-align: center;
                padding: 20px;
                font-style: italic;
            }

            .no-results {
                padding: 15px;
                text-align: center;
                color: #6c757d;
                font-style: italic;
            }
        </style>

        @include('_partials._html_footer')

        <script>
            // Mock player data - In a real app, this would come from your backend
            const mockPlayers = [
                { id: 1, name: "John Doe", discord: "johndoe#1234" },
                { id: 2, name: "Jane Smith", discord: "janesmith#5678" },
                { id: 3, name: "Mike Johnson", discord: "mikej#9012" },
                { id: 4, name: "Sarah Wilson", discord: "sarahw#3456" },
                { id: 5, name: "Alex Brown", discord: "alexb#7890" },
                { id: 10, name: "Chris Davis", discord: "chrisd#2468" },
                { id: 15, name: "Emma Taylor", discord: "emmat#1357" },
                { id: 20, name: "Ryan Miller", discord: "ryanm#8024" },
                { id: 25, name: "Lisa Anderson", discord: "lisaa#4680" },
                { id: 30, name: "David Garcia", discord: "davidg#9753" }
            ];

            let selectedPlayers = [];
            let searchTimeout;

            // Player search functionality
            document.getElementById('playerSearch').addEventListener('input', function(e) {
                const searchTerm = e.target.value.trim().toLowerCase();
                const searchResults = document.getElementById('searchResults');

                clearTimeout(searchTimeout);

                if (searchTerm.length < 2) {
                    searchResults.style.display = 'none';
                    return;
                }

                searchTimeout = setTimeout(() => {
                    const filteredPlayers = mockPlayers.filter(player => 
                        player.name.toLowerCase().includes(searchTerm) ||
                        player.id.toString().includes(searchTerm) ||
                        player.discord.toLowerCase().includes(searchTerm)
                    ).filter(player => 
                        !selectedPlayers.some(selected => selected.id === player.id)
                    );

                    displaySearchResults(filteredPlayers);
                }, 300);
            });

            // Display search results
            function displaySearchResults(players) {
                const searchResults = document.getElementById('searchResults');
                
                if (players.length === 0) {
                    searchResults.innerHTML = '<div class="no-results">No players found</div>';
                } else {
                    searchResults.innerHTML = players.map(player => `
                        <div class="search-result-item" onclick="addPlayer(${player.id})">
                            <div class="search-result-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="search-result-info">
                                <div class="search-result-name">${player.name}</div>
                                <div class="search-result-id">ID: ${player.id} • ${player.discord}</div>
                            </div>
                        </div>
                    `).join('');
                }
                
                searchResults.style.display = 'block';
            }

            // Add player to selection
            function addPlayer(playerId) {
                const player = mockPlayers.find(p => p.id === playerId);
                if (player && !selectedPlayers.some(selected => selected.id === playerId)) {
                    selectedPlayers.push(player);
                    updateSelectedPlayersDisplay();
                    
                    document.getElementById('playerSearch').value = '';
                    document.getElementById('searchResults').style.display = 'none';
                }
            }

            // Remove player from selection
            function removePlayer(playerId) {
                selectedPlayers = selectedPlayers.filter(player => player.id !== playerId);
                updateSelectedPlayersDisplay();
            }

            // Update selected players display
            function updateSelectedPlayersDisplay() {
                const selectedPlayersList = document.getElementById('selectedPlayersList');
                const selectedCount = document.getElementById('selectedCount');
                
                selectedCount.textContent = selectedPlayers.length;

                if (selectedPlayers.length === 0) {
                    selectedPlayersList.innerHTML = `
                        <div class="no-players-selected text-muted">
                            <i class="fas fa-users me-2"></i>
                            No players selected. Search and click to add players.
                        </div>
                    `;
                } else {
                    selectedPlayersList.innerHTML = selectedPlayers.map(player => `
                        <div class="selected-player-item">
                            <div class="selected-player-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="selected-player-info">
                                <div class="selected-player-name">${player.name}</div>
                                <div class="selected-player-id">ID: ${player.id} • ${player.discord}</div>
                            </div>
                            <button type="button" class="remove-player-btn" onclick="removePlayer(${player.id})" title="Remove player">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `).join('');
                }
            }

            // Hide search results when clicking outside
            document.addEventListener('click', function(e) {
                const searchContainer = document.querySelector('.player-search-container');
                if (!searchContainer.contains(e.target)) {
                    document.getElementById('searchResults').style.display = 'none';
                }
            });

            <script>
            // Set current date/time as default
            document.getElementById('kickDateTime').value = new Date().toISOString().slice(0, 16);

            // Add kick form submission
            document.getElementById('addKickForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form values
                const reason = document.getElementById('kickReason').value;
                const dateTime = document.getElementById('kickDateTime').value;
                
                // Validate form
                if (selectedPlayers.length === 0) {
                    alert('Please select at least one player to kick.');
                    return;
                }
                
                if (!reason.trim()) {
                    alert('Please fill in the reason field.');
                    return;
                }
                
                // Here you would typically send the data to your backend
                console.log('Adding kick records for players:', { 
                    players: selectedPlayers.map(p => ({ id: p.id, name: p.name })), 
                    reason: reason,
                    dateTime: dateTime
                });
                
                // Show success message and close modal
                const playerCount = selectedPlayers.length;
                const playerNames = selectedPlayers.map(p => p.name).join(', ');
                alert(`Kick issued successfully to ${playerCount} player(s): ${playerNames}`);
                
                bootstrap.Modal.getInstance(document.getElementById('addKickModal')).hide();
                
                // Reset form and selections
                this.reset();
                selectedPlayers = [];
                updateSelectedPlayersDisplay();
                document.getElementById('kickDateTime').value = new Date().toISOString().slice(0, 16);
            });
        </script>
    </body>
</html>
