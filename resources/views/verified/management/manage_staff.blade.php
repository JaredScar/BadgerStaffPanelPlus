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
                            <i class="fas fa-users me-2"></i>
                            Manage Staff
                        </h1>
                        <p class="page-description">Manage staff members and their permissions</p>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                        <i class="fas fa-plus me-2"></i>
                        Add Staff Member
                    </button>
                </div>

                        <!-- Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon stat-icon-red">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3 class="stat-number">1</h3>
                                        <p class="stat-label">Admins</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon stat-icon-blue">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3 class="stat-number">2</h3>
                                        <p class="stat-label">Moderators</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon stat-icon-green">
                                        <i class="fas fa-hands-helping"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3 class="stat-number">1</h3>
                                        <p class="stat-label">Helpers</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon stat-icon-orange">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3 class="stat-number">3</h3>
                                        <p class="stat-label">Active Staff</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Search and Filter Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input type="text" class="form-control" placeholder="Search staff..." id="staffSearch">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" id="roleFilter">
                                    <option value="">All Roles</option>
                                    <option value="admin">Admin</option>
                                    <option value="moderator">Moderator</option>
                                    <option value="helper">Helper</option>
                                </select>
                            </div>
                        </div>

                        <!-- Staff Members Section -->
                        <div class="section-header">
                            <h2>Staff Members (4)</h2>
                        </div>

                        <!-- Staff Table -->
                        <div class="table-responsive">
                            <table class="table table-hover staff-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Discord</th>
                                        <th>Role</th>
                                        <th>Join Date</th>
                                        <th>Last Active</th>
                                        <th>Actions Count</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <span>AdminUser</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="discord-tag">AdminUser#0001</span>
                                        </td>
                                        <td>
                                            <span class="role-badge role-admin">
                                                <i class="fas fa-crown me-1"></i>
                                                Admin
                                            </span>
                                        </td>
                                        <td>2023-06-01</td>
                                        <td>
                                            <span class="status-online">Currently online</span>
                                        </td>
                                        <td>
                                            <span class="actions-count">245</span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-active">Active</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <span>ModUser</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="discord-tag">ModUser#0002</span>
                                        </td>
                                        <td>
                                            <span class="role-badge role-moderator">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                Moderator
                                            </span>
                                        </td>
                                        <td>2023-08-15</td>
                                        <td>2 hours ago</td>
                                        <td>
                                            <span class="actions-count">156</span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-active">Active</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <span>HelperUser</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="discord-tag">HelperUser#0003</span>
                                        </td>
                                        <td>
                                            <span class="role-badge role-helper">
                                                <i class="fas fa-hands-helping me-1"></i>
                                                Helper
                                            </span>
                                        </td>
                                        <td>2023-11-20</td>
                                        <td>1 day ago</td>
                                        <td>
                                            <span class="actions-count">89</span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-active">Active</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <span>FormerMod</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="discord-tag">FormerMod#0004</span>
                                        </td>
                                        <td>
                                            <span class="role-badge role-moderator">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                Moderator
                                            </span>
                                        </td>
                                        <td>2023-05-10</td>
                                        <td>2 weeks ago</td>
                                        <td>
                                            <span class="actions-count">78</span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-inactive">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Staff Modal -->
        <div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStaffModalLabel">
                            <i class="fas fa-user-plus me-2"></i>
                            Add Staff Member
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addStaffForm">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="staffName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="staffName" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="discordTag" class="form-label">Discord Tag</label>
                                    <input type="text" class="form-control" id="discordTag" placeholder="Username#0000" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="staffRole" class="form-label">Role</label>
                                    <select class="form-select" id="staffRole" required>
                                        <option value="">Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="moderator">Moderator</option>
                                        <option value="helper">Helper</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="joinDate" class="form-label">Join Date</label>
                                    <input type="date" class="form-control" id="joinDate" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="staffNotes" class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" id="staffNotes" rows="3" placeholder="Additional notes about this staff member..."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" form="addStaffForm">
                            <i class="fas fa-plus me-2"></i>
                            Add Staff Member
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @include('_partials._html_footer')

        <script>
            // Search functionality
            document.getElementById('staffSearch').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('.staff-table tbody tr');
                
                rows.forEach(row => {
                    const name = row.cells[0].textContent.toLowerCase();
                    const discord = row.cells[1].textContent.toLowerCase();
                    const role = row.cells[2].textContent.toLowerCase();
                    
                    if (name.includes(searchTerm) || discord.includes(searchTerm) || role.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Role filter functionality
            document.getElementById('roleFilter').addEventListener('change', function(e) {
                const selectedRole = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('.staff-table tbody tr');
                
                rows.forEach(row => {
                    const roleCell = row.cells[2].textContent.toLowerCase();
                    
                    if (selectedRole === '' || roleCell.includes(selectedRole)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Add staff form submission
            document.getElementById('addStaffForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form values
                const name = document.getElementById('staffName').value;
                const discord = document.getElementById('discordTag').value;
                const role = document.getElementById('staffRole').value;
                const joinDate = document.getElementById('joinDate').value;
                const notes = document.getElementById('staffNotes').value;
                
                // Here you would typically send the data to your backend
                console.log('Adding staff member:', { name, discord, role, joinDate, notes });
                
                // Show success message and close modal
                alert('Staff member added successfully!');
                document.getElementById('addStaffModal').querySelector('.btn-close').click();
                
                // Reset form
                this.reset();
            });

            // Delete confirmation
            document.querySelectorAll('.btn-outline-danger').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this staff member?')) {
                        const row = this.closest('tr');
                        row.style.animation = 'fadeOut 0.3s ease-out';
                        setTimeout(() => {
                            row.remove();
                        }, 300);
                    }
                });
            });
        </script>
    </body>
</html>
