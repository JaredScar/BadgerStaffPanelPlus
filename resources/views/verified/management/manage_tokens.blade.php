@php use App\Models\Token;use App\Models\TokenPerms;use Illuminate\Support\Facades\Session; @endphp
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
                            <i class="fas fa-key me-2"></i>
                            Manage Tokens
                        </h1>
                        <p class="page-description">Create and manage API tokens</p>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTokenModal">
                        <i class="fas fa-plus me-2"></i>
                        Generate Token
                    </button>
                </div>

                @php
                    $tokens = Token::where('staff_id', Session::get('staff_id'))->get();
                    $currentDate = date('Y-m-d H:i:s');
                    
                    // Calculate statistics
                    $totalTokens = $tokens->count();
                    $activeTokens = $tokens->filter(function($token) use ($currentDate) {
                        return $token->expires > $currentDate && $token->active_flg;
                    })->count();
                    $expiredTokens = $tokens->filter(function($token) use ($currentDate) {
                        return $token->expires < $currentDate;
                    })->count();
                    $recentlyUsed = $tokens->filter(function($token) {
                        return $token->last_used && strtotime($token->last_used) > strtotime('-7 days');
                    })->count();
                @endphp

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-blue">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number">{{ $totalTokens }}</h3>
                                <p class="stat-label">Total Tokens</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-green">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number">{{ $activeTokens }}</h3>
                                <p class="stat-label">Active Tokens</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-red">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number">{{ $expiredTokens }}</h3>
                                <p class="stat-label">Expired Tokens</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-orange">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number">{{ $recentlyUsed }}</h3>
                                <p class="stat-label">Recently Used</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control" placeholder="Search tokens..." id="tokenSearch">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="expired">Expired</option>
                            <option value="unused">Never Used</option>
                        </select>
                    </div>
                </div>

                <!-- API Tokens Section -->
                <div class="section-header">
                    <h2>API Tokens ({{ $totalTokens }})</h2>
                </div>

                <!-- Tokens Table -->
                <div class="table-responsive">
                    <table class="table table-hover tokens-table">
                        <thead>
                            <tr>
                                <th>Token ID</th>
                                <th>Note</th>
                                <th>Permissions</th>
                                <th>Status</th>
                                <th>Expiration</th>
                                <th>Last Used</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tokens as $token)
                                @php
                                    $token_id = $token->token_id;
                                    $tokenPerms = TokenPerms::where('token_id', $token_id)->get();
                                    $expires = $token->expires;
                                    $active = $token->active_flg;
                                    $note = $token->note;
                                    $expired = $expires < $currentDate;
                                    $lastUsed = $token->last_used ?? 'Never';
                                    $neverUsed = $token->last_used === null;
                                @endphp
                                <tr id="token-{{ $token_id }}" data-status="{{ $expired ? 'expired' : ($neverUsed ? 'unused' : 'active') }}">
                                    <td>
                                        <div class="token-info">
                                            <div class="token-avatar">
                                                <i class="fas fa-key"></i>
                                            </div>
                                            <div>
                                                <span class="token-id">***{{ substr($token_id, -4) }}</span>
                                                <button class="btn btn-sm btn-outline-secondary ms-2 p-1" onclick="copyToClipboard('{{ $token_id }}')" title="Copy token">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="token-note">{{ $note }}</span>
                                    </td>
                                    <td>
                                        <div class="permissions-list">
                                            @php $permCount = 0; @endphp
                                            @foreach($tokenPerms as $perm)
                                                @if($perm->allowed)
                                                    @if($permCount < 2)
                                                        <span class="permission-badge">{{ $perm->permission }}</span>
                                                    @endif
                                                    @php $permCount++; @endphp
                                                @endif
                                            @endforeach
                                            @if($permCount > 2)
                                                <span class="permission-badge permission-more">+{{ $permCount - 2 }} more</span>
                                            @endif
                                            @if($permCount === 0)
                                                <span class="text-muted">No permissions</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($expired)
                                            <span class="status-badge status-expired">Expired</span>
                                        @elseif($neverUsed)
                                            <span class="status-badge status-unused">Never Used</span>
                                        @else
                                            <span class="status-badge status-active">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="token-expires {{ $expired ? 'text-danger' : 'text-success' }}">
                                            {{ date('Y-m-d H:i', strtotime($expires)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="token-lastused">
                                            @if($lastUsed === 'Never')
                                                <span class="text-muted">Never</span>
                                            @else
                                                {{ date('Y-m-d H:i', strtotime($lastUsed)) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button onclick="deleteToken({{ $token_id }})" class="btn btn-sm btn-outline-danger" title="Delete token">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($tokens->isEmpty())
                    <div class="no-data-message">
                        <div class="text-center py-5">
                            <i class="fas fa-key fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No API tokens found</h4>
                            <p class="text-muted">Create your first token to get started.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    <!-- Create Token Modal -->
    <div class="modal fade" id="createTokenModal" tabindex="-1" aria-labelledby="createTokenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTokenModalLabel">
                        <i class="fas fa-plus me-2"></i>
                        Request New API Token
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="tokens">
                    @csrf
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Token Note -->
                        <div class="mb-4">
                            <label for="tokenNote" class="form-label fw-semibold">Token Description</label>
                            <input type="text" class="form-control" id="tokenNote" name="note" placeholder="What's this token for?" required>
                            <div class="form-text">Enter a descriptive name for this token to help you remember its purpose.</div>
                        </div>

                        <!-- Token Expiration -->
                        <div class="mb-4">
                            <label for="tokenExpiration" class="form-label fw-semibold">Token Expiration</label>
                            <select class="form-select" id="tokenExpiration" name="expiration" required>
                                <option value="7">7 days</option>
                                <option value="30" selected>30 days</option>
                                <option value="60">60 days</option>
                                <option value="90">90 days</option>
                                <option value="custom">Custom...</option>
                                <option value="noexp">No Expiration</option>
                            </select>
                            <input type="text" id="custom_exp" class="form-control mt-2 d-none" placeholder="Select custom expiration date"/>
                        </div>

                        <!-- Permissions -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Permissions</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card border-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                <i class="fas fa-user-plus me-1"></i>
                                                Registry
                                            </h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="registrySwitch" name="register">
                                                <label class="form-check-label" for="registrySwitch">Register player</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                <i class="fas fa-users me-1"></i>
                                                Staff
                                            </h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="createStaffSwitch" name="staff_create">
                                                <label class="form-check-label" for="createStaffSwitch">Create</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="editStaffSwitch" name="staff_edit">
                                                <label class="form-check-label" for="editStaffSwitch">Edit</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="deleteStaffSwitch" name="staff_delete">
                                                <label class="form-check-label" for="deleteStaffSwitch">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                <i class="fas fa-ban me-1"></i>
                                                Bans
                                            </h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="createBansSwitch" name="ban_create">
                                                <label class="form-check-label" for="createBansSwitch">Create</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="deleteBansSwitch" name="ban_delete">
                                                <label class="form-check-label" for="deleteBansSwitch">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Warns
                                            </h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="createWarnsSwitch" name="warn_create">
                                                <label class="form-check-label" for="createWarnsSwitch">Create</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="deleteWarnsSwitch" name="warn_delete">
                                                <label class="form-check-label" for="deleteWarnsSwitch">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                <i class="fas fa-shoe-prints me-1"></i>
                                                Kicks
                                            </h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="createKicksSwitch" name="kick_create">
                                                <label class="form-check-label" for="createKicksSwitch">Create</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="deleteKicksSwitch" name="kick_delete">
                                                <label class="form-check-label" for="deleteKicksSwitch">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                <i class="fas fa-thumbs-up me-1"></i>
                                                Commends
                                            </h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="createCommendsSwitch" name="commend_create">
                                                <label class="form-check-label" for="createCommendsSwitch">Create</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="deleteCommendsSwitch" name="commend_delete">
                                                <label class="form-check-label" for="deleteCommendsSwitch">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                <i class="fas fa-sticky-note me-1"></i>
                                                Notes
                                            </h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="createNotesSwitch" name="note_create">
                                                <label class="form-check-label" for="createNotesSwitch">Create</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="deleteNotesSwitch" name="note_delete">
                                                <label class="form-check-label" for="deleteNotesSwitch">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                                                <div class="col-md-6">
                                    <div class="card border-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                Trust Scores
                                            </h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="updateTrustScoresSwitch" name="trustscore_update">
                                                <label class="form-check-label" for="updateTrustScoresSwitch">Update</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="resetTrustScoresSwitch" name="trustscore_reset">
                                                <label class="form-check-label" for="resetTrustScoresSwitch">Reset</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                <i class="fas fa-user-shield me-1"></i>
                                                Roles
                                            </h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="editRolesSwitch" name="role_edit">
                                                <label class="form-check-label" for="editRolesSwitch">Edit</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Create Token
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('_partials._html_footer')

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Search functionality
        document.getElementById('tokenSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.tokens-table tbody tr');
            
            rows.forEach(row => {
                const tokenId = row.cells[0].textContent.toLowerCase();
                const note = row.cells[1].textContent.toLowerCase();
                const permissions = row.cells[2].textContent.toLowerCase();
                
                if (tokenId.includes(searchTerm) || note.includes(searchTerm) || permissions.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Status filter functionality
        document.getElementById('statusFilter').addEventListener('change', function(e) {
            const selectedStatus = e.target.value;
            const rows = document.querySelectorAll('.tokens-table tbody tr');
            
            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                
                if (selectedStatus === '' || status === selectedStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Custom expiration date picker
        $('#custom_exp').datepicker({
            isMobile: true,
            range: true,
            position: 'left center',
            language: 'en',
            multipleDatesSeparator: ' -> '
        });

        // Show/hide custom expiration input
        $('#tokenExpiration').on('change', function() {
            if (this.value === 'custom') {
                $('#custom_exp').removeClass('d-none');
            } else {
                $('#custom_exp').addClass('d-none');
            }
        });

        // Copy to clipboard function
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success toast or notification
                console.log('Token copied to clipboard');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }

        // Delete token function
        function deleteToken(tokenId) {
            if (confirm('Are you sure you want to delete this token? This action cannot be undone.')) {
                $.ajax({
                    url: 'tokens/' + tokenId,
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        console.log("Token deleted successfully", response);
                        $('#token-' + tokenId).fadeOut(300, function() {
                            $(this).remove();
                        });
                    },
                    error: function(xhr, status, err) {
                        console.error("Error deleting token", xhr, status, err);
                        alert('Error deleting token. Please try again.');
                    }
                });
            }
        }

        // Show modal if there are errors
        @if ($errors->any())
            $('#createTokenModal').modal('show');
        @endif
    </script>
</body>
</html>
