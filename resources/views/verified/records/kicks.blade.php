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

        @include('_partials._html_footer')

        <script>
            // Set current date/time as default
            document.getElementById('kickDateTime').value = new Date().toISOString().slice(0, 16);

            // Add kick form submission
            document.getElementById('addKickForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form values
                const playerId = document.getElementById('playerId').value;
                const playerName = document.getElementById('playerName').value;
                const discordId = document.getElementById('discordId').value;
                const staffMember = document.getElementById('staffMember').value;
                const reason = document.getElementById('kickReason').value;
                const dateTime = document.getElementById('kickDateTime').value;
                
                // Here you would typically send the data to your backend
                console.log('Adding kick record:', { playerId, playerName, discordId, staffMember, reason, dateTime });
                
                // Show success message and close modal
                alert('Kick record added successfully!');
                document.getElementById('addKickModal').querySelector('.btn-close').click();
                
                // Reset form
                this.reset();
                document.getElementById('kickDateTime').value = new Date().toISOString().slice(0, 16);
            });
        </script>
    </body>
</html>
