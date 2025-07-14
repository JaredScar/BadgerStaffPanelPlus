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
                        <div class="page-header d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h1 class="page-title">
                                    <i class="fas fa-ban me-2"></i>
                                    Bans
                                </h1>
                                <p class="page-description">Manage and view player bans</p>
                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBanModal">
                                <i class="fas fa-plus me-2"></i>
                                Add Ban
                            </button>
                        </div>

                        <!-- Include the bans widget -->
                        @include('_widgets.records.widget_bans')
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Ban Modal -->
        <div class="modal fade" id="addBanModal" tabindex="-1" aria-labelledby="addBanModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBanModalLabel">
                            <i class="fas fa-ban me-2"></i>
                            Add Ban Record
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addBanForm">
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
                                    <label for="banDuration" class="form-label">Duration</label>
                                    <select class="form-select" id="banDuration" required>
                                        <option value="">Select Duration</option>
                                        <option value="1 day">1 Day</option>
                                        <option value="3 days">3 Days</option>
                                        <option value="7 days">7 Days</option>
                                        <option value="14 days">14 Days</option>
                                        <option value="30 days">30 Days</option>
                                        <option value="Permanent">Permanent</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="banDateTime" class="form-label">Date/Time</label>
                                    <input type="datetime-local" class="form-control" id="banDateTime" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="banReason" class="form-label">Reason</label>
                                <textarea class="form-control" id="banReason" rows="3" placeholder="Enter the reason for the ban..." required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="expireDate" class="form-label">Expire Date (Auto-calculated)</label>
                                <input type="datetime-local" class="form-control" id="expireDate" readonly>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" form="addBanForm">
                            <i class="fas fa-ban me-2"></i>
                            Add Ban
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @include('_partials._html_footer')

        <script>
            // Set current date/time as default
            document.getElementById('banDateTime').value = new Date().toISOString().slice(0, 16);

            // Update expire date based on duration and start date
            function updateExpireDate() {
                const duration = document.getElementById('banDuration').value;
                const startDate = new Date(document.getElementById('banDateTime').value);
                const expireDateInput = document.getElementById('expireDate');
                
                if (duration === 'Permanent') {
                    expireDateInput.value = '';
                    expireDateInput.placeholder = 'Permanent ban - no expiration';
                } else if (duration && startDate) {
                    const days = parseInt(duration.split(' ')[0]);
                    const expireDate = new Date(startDate);
                    expireDate.setDate(expireDate.getDate() + days);
                    expireDateInput.value = expireDate.toISOString().slice(0, 16);
                    expireDateInput.placeholder = '';
                }
            }

            // Add event listeners for duration and date changes
            document.getElementById('banDuration').addEventListener('change', updateExpireDate);
            document.getElementById('banDateTime').addEventListener('change', updateExpireDate);

            // Add ban form submission
            document.getElementById('addBanForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form values
                const playerId = document.getElementById('playerId').value;
                const playerName = document.getElementById('playerName').value;
                const discordId = document.getElementById('discordId').value;
                const staffMember = document.getElementById('staffMember').value;
                const duration = document.getElementById('banDuration').value;
                const reason = document.getElementById('banReason').value;
                const dateTime = document.getElementById('banDateTime').value;
                const expireDate = document.getElementById('expireDate').value;
                
                // Here you would typically send the data to your backend
                console.log('Adding ban record:', { playerId, playerName, discordId, staffMember, duration, reason, dateTime, expireDate });
                
                // Show success message and close modal
                alert('Ban record added successfully!');
                document.getElementById('addBanModal').querySelector('.btn-close').click();
                
                // Reset form
                this.reset();
                document.getElementById('banDateTime').value = new Date().toISOString().slice(0, 16);
                document.getElementById('expireDate').value = '';
            });
        </script>
    </body>
</html>
