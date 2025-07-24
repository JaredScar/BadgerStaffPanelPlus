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
                            <i class="fas fa-thumbs-up text-success me-2"></i>
                            Commends
                        </h1>
                        <p class="page-description">Manage and view player commendations</p>
                    </div>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCommendModal">
                        <i class="fas fa-plus me-2"></i>
                        Add Commend
                    </button>
                </div>

                <!-- Include the commends widget -->
                @include('_widgets.records.widget_commends')
            </div>
        </div>

        <!-- Add Commend Modal -->
        <div class="modal fade" id="addCommendModal" tabindex="-1" aria-labelledby="addCommendModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCommendModalLabel">
                            <i class="fas fa-star me-2"></i>
                            Add Commend Record
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addCommendForm">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="playerId" class="form-label">Player ID</label>
                                    <input type="text" class="form-control" id="playerId" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="playerName" class="form-label">Player Name</label>
                                    <input type="text" class="form-control" id="playerName" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="discordId" class="form-label">Discord ID</label>
                                    <input type="text" class="form-control" id="discordId" placeholder="DiscordUser#0000">
                                </div>
                                <div class="col-md-6">
                                    <label for="staffMember" class="form-label">Staff Member</label>
                                    <select class="form-select" id="staffMember" required>
                                        <option value="">Select Staff Member</option>
                                        <option value="AdminUser">AdminUser</option>
                                        <option value="ModUser">ModUser</option>
                                        <option value="HelperUser">HelperUser</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="commendType" class="form-label">Commend Type</label>
                                    <select class="form-select" id="commendType" required>
                                        <option value="">Select Type</option>
                                        <option value="Excellent Roleplay">Excellent Roleplay</option>
                                        <option value="Helpful Behavior">Helpful Behavior</option>
                                        <option value="Good Sportsmanship">Good Sportsmanship</option>
                                        <option value="Community Contribution">Community Contribution</option>
                                        <option value="Leadership">Leadership</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="commendDateTime" class="form-label">Date/Time</label>
                                    <input type="datetime-local" class="form-control" id="commendDateTime" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="commendReason" class="form-label">Reason</label>
                                <textarea class="form-control" id="commendReason" rows="3" placeholder="Enter the reason for the commendation..." required></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" form="addCommendForm">
                            <i class="fas fa-star me-2"></i>
                            Add Commend
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @include('_partials._html_footer')

        <script>
            // Set current date/time as default
            document.getElementById('commendDateTime').value = new Date().toISOString().slice(0, 16);

            // Add commend form submission
            document.getElementById('addCommendForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form values
                const playerId = document.getElementById('playerId').value;
                const playerName = document.getElementById('playerName').value;
                const discordId = document.getElementById('discordId').value;
                const staffMember = document.getElementById('staffMember').value;
                const type = document.getElementById('commendType').value;
                const reason = document.getElementById('commendReason').value;
                const dateTime = document.getElementById('commendDateTime').value;
                
                // Here you would typically send the data to your backend
                console.log('Adding commend record:', { playerId, playerName, discordId, staffMember, type, reason, dateTime });
                
                // Show success message and close modal
                alert('Commend record added successfully!');
                document.getElementById('addCommendModal').querySelector('.btn-close').click();
                
                // Reset form
                this.reset();
                document.getElementById('commendDateTime').value = new Date().toISOString().slice(0, 16);
            });
        </script>
    </body>
</html>
