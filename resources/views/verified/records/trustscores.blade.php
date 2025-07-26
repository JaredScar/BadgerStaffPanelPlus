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
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Trust Scores
                        </h1>
                        <p class="page-description">Monitor and manage player trust scores</p>
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn btn-outline-warning me-2" data-bs-toggle="modal" data-bs-target="#resetScoreModal">
                            <i class="fas fa-redo me-2"></i>Reset Score
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateScoreModal">
                            <i class="fas fa-arrow-up me-2"></i>Update Score
                        </button>
                    </div>
                </div>

                <!-- Include the trustscores widget -->
                @include('_widgets.records.widget_trustscores')
            </div>
        </div>

        <!-- Reset Score Modal -->
        <div class="modal fade" id="resetScoreModal" tabindex="-1" aria-labelledby="resetScoreModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resetScoreModalLabel">
                            <i class="fas fa-redo me-2"></i>
                            Reset Trust Score
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="resetScoreForm">
                            @csrf
                            <div class="mb-3">
                                <label for="playerSearchReset" class="form-label">Select Players</label>
                                <div class="player-search-container">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control" id="playerSearchReset" placeholder="Search players by name or ID..." autocomplete="off">
                                    </div>
                                    <div class="search-results" id="searchResultsReset" style="display: none;"></div>
                                </div>
                                <div class="selected-players mt-3" id="selectedPlayersReset">
                                    <div class="selected-players-header">
                                        <strong>Selected Players:</strong>
                                        <span class="selected-count" id="selectedCountReset">0</span>
                                    </div>
                                    <div class="selected-players-list" id="selectedPlayersListReset">
                                        <div class="no-players-selected text-muted">
                                            <i class="fas fa-users me-2"></i>
                                            No players selected. Search and click to add players.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="resetToScore" class="form-label">Reset to Score</label>
                                    <select class="form-select" id="resetToScore" name="reset_to_score" required>
                                        <option value="">Select Reset Value</option>
                                        <option value="50">50 (Neutral)</option>
                                        <option value="75">75 (Good Standing)</option>
                                        <option value="100">100 (Excellent)</option>
                                        <option value="custom">Custom Value</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="customResetScore" class="form-label">Custom Score (0-100)</label>
                                    <input type="number" class="form-control" id="customResetScore" name="custom_score" min="0" max="100" disabled>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="resetReason" class="form-label">Reset Reason</label>
                                <textarea class="form-control" id="resetReason" name="reason" rows="3" required placeholder="Explain why the trust score is being reset..."></textarea>
                            </div>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Warning:</strong> Resetting trust scores will override the calculated scores based on player punishments and commendations. This action should be used sparingly and with proper justification.
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning" form="resetScoreForm">
                            <i class="fas fa-redo me-2"></i>
                            Reset Trust Score
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Score Modal -->
        <div class="modal fade" id="updateScoreModal" tabindex="-1" aria-labelledby="updateScoreModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateScoreModalLabel">
                            <i class="fas fa-arrow-up me-2"></i>
                            Update Trust Score
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateScoreForm">
                            @csrf
                            <div class="mb-3">
                                <label for="playerSearchUpdate" class="form-label">Select Players</label>
                                <div class="player-search-container">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control" id="playerSearchUpdate" placeholder="Search players by name or ID..." autocomplete="off">
                                    </div>
                                    <div class="search-results" id="searchResultsUpdate" style="display: none;"></div>
                                </div>
                                <div class="selected-players mt-3" id="selectedPlayersUpdate">
                                    <div class="selected-players-header">
                                        <strong>Selected Players:</strong>
                                        <span class="selected-count" id="selectedCountUpdate">0</span>
                                    </div>
                                    <div class="selected-players-list" id="selectedPlayersListUpdate">
                                        <div class="no-players-selected text-muted">
                                            <i class="fas fa-users me-2"></i>
                                            No players selected. Search and click to add players.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="updateType" class="form-label">Update Type</label>
                                    <select class="form-select" id="updateType" name="update_type" required>
                                        <option value="">Select Update Type</option>
                                        <option value="recalculate">Recalculate from Records</option>
                                        <option value="adjust">Manual Adjustment</option>
                                        <option value="set">Set Specific Value</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="scoreValue" class="form-label">Score Value</label>
                                    <input type="number" class="form-control" id="scoreValue" name="score_value" min="-50" max="100" disabled>
                                    <div class="form-text" id="scoreHelp">Enter adjustment value or specific score</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="updateReason" class="form-label">Update Reason</label>
                                <textarea class="form-control" id="updateReason" name="reason" rows="3" required placeholder="Explain the reason for this trust score update..."></textarea>
                            </div>
                            <div class="mb-3">
                                <div class="score-calculation-info p-3 bg-light rounded">
                                    <h6 class="mb-2"><i class="fas fa-calculator me-2"></i>Trust Score Calculation</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Base Score:</strong> 50
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Commends:</strong> +5 each
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Punishments:</strong> -2 to -10 each
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        Final score is capped between 0 and 100. Warnings: -2, Kicks: -3, Bans: -10
                                    </small>
                                </div>
                            </div>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Tip:</strong> Use "Recalculate from Records" to automatically compute scores based on player's punishment and commendation history.
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" form="updateScoreForm">
                            <i class="fas fa-arrow-up me-2"></i>
                            Update Trust Score
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

            .search-result-score {
                font-size: 0.75rem;
                font-weight: 600;
                padding: 2px 6px;
                border-radius: 10px;
                margin-left: auto;
            }

            .score-excellent { background: #d4edda; color: #155724; }
            .score-good { background: #cce7ff; color: #004085; }
            .score-poor { background: #f8d7da; color: #721c24; }

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

            .selected-player-score {
                font-size: 0.75rem;
                font-weight: 600;
                padding: 2px 6px;
                border-radius: 8px;
                margin-left: 8px;
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

            .score-calculation-info {
                border-left: 4px solid #007bff;
            }
        </style>

        <script>
            // Mock player data with trust scores - In a real app, this would come from your backend
            const mockPlayersWithScores = [
                { id: 1, name: "John Doe", discord: "johndoe#1234", trustScore: 85, commends: 3, warnings: 1, kicks: 0, bans: 0 },
                { id: 2, name: "Jane Smith", discord: "janesmith#5678", trustScore: 92, commends: 5, warnings: 0, kicks: 0, bans: 0 },
                { id: 3, name: "Mike Johnson", discord: "mikej#9012", trustScore: 35, commends: 1, warnings: 3, kicks: 2, bans: 1 },
                { id: 4, name: "Sarah Wilson", discord: "sarahw#3456", trustScore: 78, commends: 2, warnings: 1, kicks: 0, bans: 0 },
                { id: 5, name: "Alex Brown", discord: "alexb#7890", trustScore: 15, commends: 0, warnings: 5, kicks: 3, bans: 2 },
                { id: 10, name: "Chris Davis", discord: "chrisd#2468", trustScore: 70, commends: 1, warnings: 0, kicks: 1, bans: 0 },
                { id: 15, name: "Emma Taylor", discord: "emmat#1357", trustScore: 95, commends: 6, warnings: 0, kicks: 0, bans: 0 },
                { id: 20, name: "Ryan Miller", discord: "ryanm#8024", trustScore: 55, commends: 1, warnings: 2, kicks: 0, bans: 0 },
                { id: 25, name: "Lisa Anderson", discord: "lisaa#4680", trustScore: 88, commends: 4, warnings: 0, kicks: 0, bans: 0 },
                { id: 30, name: "David Garcia", discord: "davidg#9753", trustScore: 25, commends: 0, warnings: 4, kicks: 2, bans: 1 }
            ];

            let selectedPlayersReset = [];
            let selectedPlayersUpdate = [];
            let searchTimeoutReset, searchTimeoutUpdate;

            // Get trust score class for styling
            function getTrustScoreClass(score) {
                if (score >= 80) return 'score-excellent';
                if (score >= 60) return 'score-good';
                return 'score-poor';
            }

            // Calculate trust score based on records
            function calculateTrustScore(player) {
                let baseScore = 50;
                let calculatedScore = baseScore;
                
                // Add commends (+5 each)
                calculatedScore += player.commends * 5;
                
                // Subtract punishments
                calculatedScore -= player.warnings * 2;  // -2 each
                calculatedScore -= player.kicks * 3;     // -3 each
                calculatedScore -= player.bans * 10;     // -10 each
                
                // Cap between 0 and 100
                return Math.max(0, Math.min(100, calculatedScore));
            }

            // Player search functionality for Reset modal
            document.getElementById('playerSearchReset').addEventListener('input', function(e) {
                handlePlayerSearch(e, 'Reset', selectedPlayersReset);
            });

            // Player search functionality for Update modal
            document.getElementById('playerSearchUpdate').addEventListener('input', function(e) {
                handlePlayerSearch(e, 'Update', selectedPlayersUpdate);
            });

            // Generic player search handler
            function handlePlayerSearch(e, modalType, selectedPlayers) {
                const searchTerm = e.target.value.trim().toLowerCase();
                const searchResults = document.getElementById('searchResults' + modalType);
                const timeout = modalType === 'Reset' ? searchTimeoutReset : searchTimeoutUpdate;

                clearTimeout(timeout);

                if (searchTerm.length < 2) {
                    searchResults.style.display = 'none';
                    return;
                }

                if (modalType === 'Reset') {
                    searchTimeoutReset = setTimeout(() => filterAndDisplayPlayers(searchTerm, modalType, selectedPlayers), 300);
                } else {
                    searchTimeoutUpdate = setTimeout(() => filterAndDisplayPlayers(searchTerm, modalType, selectedPlayers), 300);
                }
            }

            // Filter and display search results
            function filterAndDisplayPlayers(searchTerm, modalType, selectedPlayers) {
                const filteredPlayers = mockPlayersWithScores.filter(player => 
                    player.name.toLowerCase().includes(searchTerm) ||
                    player.id.toString().includes(searchTerm) ||
                    player.discord.toLowerCase().includes(searchTerm)
                ).filter(player => 
                    !selectedPlayers.some(selected => selected.id === player.id)
                );

                displaySearchResults(filteredPlayers, modalType);
            }

            // Display search results
            function displaySearchResults(players, modalType) {
                const searchResults = document.getElementById('searchResults' + modalType);
                
                if (players.length === 0) {
                    searchResults.innerHTML = '<div class="no-results">No players found</div>';
                } else {
                    searchResults.innerHTML = players.map(player => `
                        <div class="search-result-item" onclick="addPlayer(${player.id}, '${modalType}')">
                            <div class="search-result-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="search-result-info">
                                <div class="search-result-name">${player.name}</div>
                                <div class="search-result-id">ID: ${player.id} • ${player.discord}</div>
                            </div>
                            <div class="search-result-score ${getTrustScoreClass(player.trustScore)}">
                                ${player.trustScore}
                            </div>
                        </div>
                    `).join('');
                }
                
                searchResults.style.display = 'block';
            }

            // Add player to selection
            function addPlayer(playerId, modalType) {
                const player = mockPlayersWithScores.find(p => p.id === playerId);
                const selectedPlayers = modalType === 'Reset' ? selectedPlayersReset : selectedPlayersUpdate;
                
                if (player && !selectedPlayers.some(selected => selected.id === playerId)) {
                    if (modalType === 'Reset') {
                        selectedPlayersReset.push(player);
                    } else {
                        selectedPlayersUpdate.push(player);
                    }
                    
                    updateSelectedPlayersDisplay(modalType);
                    
                    document.getElementById('playerSearch' + modalType).value = '';
                    document.getElementById('searchResults' + modalType).style.display = 'none';
                }
            }

            // Remove player from selection
            function removePlayer(playerId, modalType) {
                if (modalType === 'Reset') {
                    selectedPlayersReset = selectedPlayersReset.filter(player => player.id !== playerId);
                } else {
                    selectedPlayersUpdate = selectedPlayersUpdate.filter(player => player.id !== playerId);
                }
                updateSelectedPlayersDisplay(modalType);
            }

            // Update selected players display
            function updateSelectedPlayersDisplay(modalType) {
                const selectedPlayers = modalType === 'Reset' ? selectedPlayersReset : selectedPlayersUpdate;
                const selectedPlayersList = document.getElementById('selectedPlayersList' + modalType);
                const selectedCount = document.getElementById('selectedCount' + modalType);
                
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
                            <div class="selected-player-score ${getTrustScoreClass(player.trustScore)}">
                                Current: ${player.trustScore}
                            </div>
                            <button type="button" class="remove-player-btn" onclick="removePlayer(${player.id}, '${modalType}')" title="Remove player">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `).join('');
                }
            }

            // Hide search results when clicking outside
            document.addEventListener('click', function(e) {
                const searchContainers = document.querySelectorAll('.player-search-container');
                searchContainers.forEach(container => {
                    if (!container.contains(e.target)) {
                        const searchResults = container.querySelector('.search-results');
                        if (searchResults) {
                            searchResults.style.display = 'none';
                        }
                    }
                });
            });

            // Reset score form logic
            document.getElementById('resetToScore').addEventListener('change', function(e) {
                const customScoreInput = document.getElementById('customResetScore');
                if (e.target.value === 'custom') {
                    customScoreInput.disabled = false;
                    customScoreInput.required = true;
                } else {
                    customScoreInput.disabled = true;
                    customScoreInput.required = false;
                    customScoreInput.value = '';
                }
            });

            // Update score form logic
            document.getElementById('updateType').addEventListener('change', function(e) {
                const scoreValueInput = document.getElementById('scoreValue');
                const scoreHelp = document.getElementById('scoreHelp');
                
                switch(e.target.value) {
                    case 'recalculate':
                        scoreValueInput.disabled = true;
                        scoreValueInput.required = false;
                        scoreHelp.textContent = 'Score will be automatically calculated from player records';
                        break;
                    case 'adjust':
                        scoreValueInput.disabled = false;
                        scoreValueInput.required = true;
                        scoreValueInput.min = '-50';
                        scoreValueInput.max = '50';
                        scoreHelp.textContent = 'Enter adjustment value (e.g., +10 or -5)';
                        break;
                    case 'set':
                        scoreValueInput.disabled = false;
                        scoreValueInput.required = true;
                        scoreValueInput.min = '0';
                        scoreValueInput.max = '100';
                        scoreHelp.textContent = 'Enter specific score value (0-100)';
                        break;
                    default:
                        scoreValueInput.disabled = true;
                        scoreValueInput.required = false;
                        scoreHelp.textContent = 'Select an update type first';
                }
            });

            // Reset score form submission
            document.getElementById('resetScoreForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const resetToScore = document.getElementById('resetToScore').value;
                const customScore = document.getElementById('customResetScore').value;
                const reason = document.getElementById('resetReason').value;
                
                // Validate form
                if (selectedPlayersReset.length === 0) {
                    alert('Please select at least one player.');
                    return;
                }
                
                if (!resetToScore || !reason.trim()) {
                    alert('Please fill in all required fields.');
                    return;
                }
                
                if (resetToScore === 'custom' && (!customScore || customScore < 0 || customScore > 100)) {
                    alert('Please enter a valid custom score between 0 and 100.');
                    return;
                }
                
                const finalScore = resetToScore === 'custom' ? customScore : resetToScore;
                
                // Here you would typically send the data to your backend
                console.log('Resetting trust scores:', { 
                    players: selectedPlayersReset.map(p => ({ id: p.id, name: p.name, currentScore: p.trustScore })), 
                    resetToScore: finalScore,
                    reason: reason
                });
                
                // Show success message and close modal
                const playerCount = selectedPlayersReset.length;
                const playerNames = selectedPlayersReset.map(p => p.name).join(', ');
                alert(`Trust scores reset successfully for ${playerCount} player(s): ${playerNames} (New Score: ${finalScore})`);
                
                bootstrap.Modal.getInstance(document.getElementById('resetScoreModal')).hide();
                
                // Reset form and selections
                this.reset();
                selectedPlayersReset = [];
                updateSelectedPlayersDisplay('Reset');
                document.getElementById('customResetScore').disabled = true;
            });

            // Update score form submission
            document.getElementById('updateScoreForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const updateType = document.getElementById('updateType').value;
                const scoreValue = document.getElementById('scoreValue').value;
                const reason = document.getElementById('updateReason').value;
                
                // Validate form
                if (selectedPlayersUpdate.length === 0) {
                    alert('Please select at least one player.');
                    return;
                }
                
                if (!updateType || !reason.trim()) {
                    alert('Please fill in all required fields.');
                    return;
                }
                
                if ((updateType === 'adjust' || updateType === 'set') && !scoreValue) {
                    alert('Please enter a score value.');
                    return;
                }
                
                // Here you would typically send the data to your backend
                const updateData = {
                    players: selectedPlayersUpdate.map(p => ({ 
                        id: p.id, 
                        name: p.name, 
                        currentScore: p.trustScore,
                        calculatedScore: calculateTrustScore(p),
                        records: {
                            commends: p.commends,
                            warnings: p.warnings,
                            kicks: p.kicks,
                            bans: p.bans
                        }
                    })), 
                    updateType: updateType,
                    scoreValue: scoreValue,
                    reason: reason
                };
                
                console.log('Updating trust scores:', updateData);
                
                // Show success message and close modal
                const playerCount = selectedPlayersUpdate.length;
                const playerNames = selectedPlayersUpdate.map(p => p.name).join(', ');
                
                let successMessage = `Trust scores updated successfully for ${playerCount} player(s): ${playerNames}`;
                if (updateType === 'recalculate') {
                    successMessage += ' (Recalculated from records)';
                } else if (updateType === 'adjust') {
                    successMessage += ` (Adjusted by ${scoreValue})`;
                } else if (updateType === 'set') {
                    successMessage += ` (Set to ${scoreValue})`;
                }
                
                alert(successMessage);
                
                bootstrap.Modal.getInstance(document.getElementById('updateScoreModal')).hide();
                
                // Reset form and selections
                this.reset();
                selectedPlayersUpdate = [];
                updateSelectedPlayersDisplay('Update');
                document.getElementById('scoreValue').disabled = true;
            });
        </script>

        @include('_partials._html_footer')
    </body>
</html>
