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
                            <i class="fas fa-wrench me-2"></i>
                            Settings
                        </h1>
                        <p class="page-description">Configure server and panel settings</p>
                    </div>
                    <div class="header-actions">
                        <button type="button" class="btn btn-outline-secondary me-2" id="resetDefaults">
                            <i class="fas fa-undo me-2"></i>
                            Reset to Defaults
                        </button>
                        <button type="button" class="btn btn-primary" id="saveChanges">
                            <i class="fas fa-save me-2"></i>
                            Save Changes
                        </button>
                    </div>
                </div>

                        <!-- Tab Navigation -->
                        <div class="settings-tabs mb-4">
                            <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="server-tab" data-bs-toggle="tab" data-bs-target="#server" type="button" role="tab" aria-controls="server" aria-selected="true">
                                        <i class="fas fa-server me-2"></i>
                                        Server
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                                        <i class="fas fa-shield-alt me-2"></i>
                                        Security
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab" aria-controls="notifications" aria-selected="false">
                                        <i class="fas fa-bell me-2"></i>
                                        Notifications
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="database-tab" data-bs-toggle="tab" data-bs-target="#database" type="button" role="tab" aria-controls="database" aria-selected="false">
                                        <i class="fas fa-database me-2"></i>
                                        Database
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Tab Content -->
                        <div class="tab-content" id="settingsTabContent">
                            <!-- Server Tab -->
                            <div class="tab-pane fade show active" id="server" role="tabpanel" aria-labelledby="server-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <i class="fas fa-server me-2"></i>
                                            Server Configuration
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="serverName" class="form-label">Server Name</label>
                                                    <input type="text" class="form-control" id="serverName" value="CollectiveM FiveM Server">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="maxPlayers" class="form-label">Max Players</label>
                                                    <select class="form-select" id="maxPlayers">
                                                        <option value="32">32 Players</option>
                                                        <option value="48">48 Players</option>
                                                        <option value="64" selected>64 Players</option>
                                                        <option value="96">96 Players</option>
                                                        <option value="128">128 Players</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="welcomeMessage" class="form-label">Welcome Message</label>
                                            <textarea class="form-control" id="welcomeMessage" rows="4" placeholder="Enter welcome message for new players...">Welcome to CollectiveM! Please read the rules and have fun!</textarea>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="autoSave" checked>
                                            <label class="form-check-label" for="autoSave">
                                                Enable auto-save every 5 minutes
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Tab -->
                            <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <i class="fas fa-shield-alt me-2"></i>
                                            Security Settings
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="twoFactorAuth" checked>
                                            <label class="form-check-label" for="twoFactorAuth">
                                                Enable Two-Factor Authentication
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="sessionTimeout" checked>
                                            <label class="form-check-label" for="sessionTimeout">
                                                Auto-logout after inactivity
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="ipWhitelist">
                                            <label class="form-check-label" for="ipWhitelist">
                                                Enable IP whitelist
                                            </label>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="sessionLength" class="form-label">Session Length (minutes)</label>
                                            <input type="number" class="form-control" id="sessionLength" value="60" min="15" max="480">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notifications Tab -->
                            <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <i class="fas fa-bell me-2"></i>
                                            Notification Settings
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="enableNotifications" checked>
                                            <label class="form-check-label" for="enableNotifications">
                                                Enable notifications
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="discordNotifications" checked>
                                            <label class="form-check-label" for="discordNotifications">
                                                Send notifications to Discord
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="emailNotifications">
                                            <label class="form-check-label" for="emailNotifications">
                                                Send email notifications
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="notifyBans" checked>
                                            <label class="form-check-label" for="notifyBans">
                                                Notify on new bans
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="notifyWarnings" checked>
                                            <label class="form-check-label" for="notifyWarnings">
                                                Notify on new warnings
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="notifyServerEvents" checked>
                                            <label class="form-check-label" for="notifyServerEvents">
                                                Notify on server events
                                            </label>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="discordWebhook" class="form-label">Discord Webhook URL</label>
                                            <input type="url" class="form-control" id="discordWebhook" value="https://discord.com/api/webhooks/..." placeholder="Enter Discord webhook URL">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Database Tab -->
                            <div class="tab-pane fade" id="database" role="tabpanel" aria-labelledby="database-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <i class="fas fa-database me-2"></i>
                                            Database Settings
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="autoBackup" checked>
                                            <label class="form-check-label" for="autoBackup">
                                                Enable automatic backups
                                            </label>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="backupInterval" class="form-label">Backup Interval</label>
                                            <select class="form-select" id="backupInterval">
                                                <option value="daily" selected>Daily</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="retentionDays" class="form-label">Retention Days</label>
                                            <input type="number" class="form-control" id="retentionDays" value="30" min="1" max="365">
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-warning">
                                                <i class="fas fa-download me-2"></i>
                                                Create Backup
                                            </button>
                                            <button type="button" class="btn btn-danger">
                                                <i class="fas fa-trash me-2"></i>
                                                Clear Old Data
                                            </button>
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
    </body>
</html>

<style>
/* Settings page specific styles */
.content-wrapper {
    padding: 20px;
}

.page-header {
    margin-bottom: 30px;
}

.page-title {
    font-size: 32px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
}

.page-title i {
    color: #fd7e14;
    margin-right: 10px;
}

.page-description {
    color: #6c757d;
    margin: 0;
    font-size: 16px;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 10px 20px;
}

.btn-primary {
    background: linear-gradient(135deg, #fd7e14 0%, #ff9f40 100%);
    border: none;
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #e96b00 0%, #fd7e14 100%);
    transform: translateY(-1px);
}

.btn-outline-secondary {
    border: 2px solid #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
}

.settings-tabs .nav-tabs {
    border-bottom: 3px solid #e9ecef;
}

.settings-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 15px 25px;
    margin-right: 10px;
    border-radius: 8px 8px 0 0;
    transition: all 0.3s ease;
}

.settings-tabs .nav-link:hover {
    background: #f8f9fa;
    color: #fd7e14;
}

.settings-tabs .nav-link.active {
    background: #fd7e14;
    color: white;
    border-bottom: 3px solid #fd7e14;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.card-header {
    background: linear-gradient(135deg, #fd7e14 0%, #ff9f40 100%);
    border-radius: 12px 12px 0 0 !important;
    border: none;
    padding: 15px 20px;
}

.card-header .card-title {
    color: white;
    font-weight: 600;
    margin: 0;
    font-size: 1.25rem;
}

.card-header .card-title i {
    color: white;
}

.card-body {
    padding: 25px;
}

.form-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e3e6f0;
    padding: 12px 15px;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #fd7e14;
    box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
}

.form-select {
    border-radius: 8px;
    border: 2px solid #e3e6f0;
    padding: 12px 15px;
    transition: border-color 0.3s ease;
}

.form-select:focus {
    border-color: #fd7e14;
    box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
}

.form-check-input {
    width: 3em;
    height: 1.5em;
    border-radius: 1.5em;
    background-color: #e9ecef;
    border: none;
    transition: all 0.3s ease;
}

.form-check-input:checked {
    background-color: #fd7e14;
    border-color: #fd7e14;
}

.form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
}

.form-check-label {
    font-weight: 500;
    color: #2c3e50;
    margin-left: 10px;
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ffed4e 100%);
    border: none;
    color: #000;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e6ac00 0%, #ffc107 100%);
    transform: translateY(-1px);
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
    border: none;
    color: white;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
    transform: translateY(-1px);
}

.master-contain {
    padding: 0;
    margin: 0;
    height: 100vh;
    overflow: hidden;
}

.page-contain {
    height: 100vh;
    overflow-y: auto;
    padding: 0;
}

@media (max-width: 768px) {
    .header-actions {
        flex-direction: column;
        gap: 5px;
    }
    
    .settings-tabs .nav-link {
        padding: 10px 15px;
        margin-right: 5px;
    }
    
    .page-title {
        font-size: 24px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle save changes
    document.getElementById('saveChanges').addEventListener('click', function() {
        // Collect all form data
        const formData = {
            serverName: document.getElementById('serverName').value,
            maxPlayers: document.getElementById('maxPlayers').value,
            welcomeMessage: document.getElementById('welcomeMessage').value,
            autoSave: document.getElementById('autoSave').checked,
            twoFactorAuth: document.getElementById('twoFactorAuth').checked,
            sessionTimeout: document.getElementById('sessionTimeout').checked,
            ipWhitelist: document.getElementById('ipWhitelist').checked,
            sessionLength: document.getElementById('sessionLength').value,
            enableNotifications: document.getElementById('enableNotifications').checked,
            discordNotifications: document.getElementById('discordNotifications').checked,
            emailNotifications: document.getElementById('emailNotifications').checked,
            notifyBans: document.getElementById('notifyBans').checked,
            notifyWarnings: document.getElementById('notifyWarnings').checked,
            notifyServerEvents: document.getElementById('notifyServerEvents').checked,
            discordWebhook: document.getElementById('discordWebhook').value,
            autoBackup: document.getElementById('autoBackup').checked,
            backupInterval: document.getElementById('backupInterval').value,
            retentionDays: document.getElementById('retentionDays').value
        };

        // Show success message
        alert('Settings saved successfully!');
        console.log('Settings saved:', formData);
    });

    // Handle reset to defaults
    document.getElementById('resetDefaults').addEventListener('click', function() {
        if (confirm('Are you sure you want to reset all settings to default values?')) {
            // Reset all form fields to defaults
            document.getElementById('serverName').value = 'CollectiveM FiveM Server';
            document.getElementById('maxPlayers').value = '64';
            document.getElementById('welcomeMessage').value = 'Welcome to CollectiveM! Please read the rules and have fun!';
            document.getElementById('autoSave').checked = true;
            document.getElementById('twoFactorAuth').checked = true;
            document.getElementById('sessionTimeout').checked = true;
            document.getElementById('ipWhitelist').checked = false;
            document.getElementById('sessionLength').value = '60';
            document.getElementById('enableNotifications').checked = true;
            document.getElementById('discordNotifications').checked = true;
            document.getElementById('emailNotifications').checked = false;
            document.getElementById('notifyBans').checked = true;
            document.getElementById('notifyWarnings').checked = true;
            document.getElementById('notifyServerEvents').checked = true;
            document.getElementById('discordWebhook').value = 'https://discord.com/api/webhooks/...';
            document.getElementById('autoBackup').checked = true;
            document.getElementById('backupInterval').value = 'daily';
            document.getElementById('retentionDays').value = '30';
            
            alert('Settings reset to defaults!');
        }
    });
});
</script>
