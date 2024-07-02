@php use App\Models\Token;use App\Models\TokenPerms;use Illuminate\Support\Facades\Session; @endphp
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
            <div class="row">
                <div class="full-page-header d-flex align-items-center fw-bolder">
                    TOKEN MANAGEMENT
                </div>
            </div>
            <div class="row">
                <div class="col subpage justify-content-center">
                    <div class="row">
                        <div class="generate-token-form col-12 justify-center" id="tokens">
                            @if ($errors->any())
                                <div class="alert alert-danger mt-4">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form method="post" action="tokens">
                                @csrf
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">Note</span>
                                            <input required class="form-control" name="note" type="text"
                                                   placeholder="What's this token for?"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">Token expires in</span>
                                            <select required class="form-select" name="expiration"
                                                    id="token_exp_select">
                                                <option value="7">7 days</option>
                                                <option value="30">30 days</option>
                                                <option value="60">60 days</option>
                                                <option value="90">90 days</option>
                                                <option value="custom">Custom...</option>
                                                <option value="noexp">No Expiration</option>
                                            </select>
                                            <input type="text" id="custom_exp" class="d-none w-35"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4 permissions-sect">
                                    <div class="col-6">
                                        <!-- Registry -->
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="registrySwitch">
                                                Registry - Register player
                                            </label>
                                            <input class="form-check-input" name="register" type="checkbox"
                                                   id="registrySwitch">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <!-- Staff -->
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="createStaffSwitch">
                                                Staff - Create
                                            </label>
                                            <input class="form-check-input" name="staff_create" type="checkbox"
                                                   id="createStaffSwitch">
                                        </div>
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="deleteStaffSwitch">
                                                Staff - Delete
                                            </label>
                                            <input class="form-check-input" name="staff_delete" type="checkbox"
                                                   id="deleteStaffSwitch">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <!-- Bans -->
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="createBansSwitch">
                                                Bans - Create
                                            </label>
                                            <input class="form-check-input" name="ban_create" type="checkbox"
                                                   id="createBansSwitch">
                                        </div>
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="deleteBansSwitch">
                                                Bans - Delete
                                            </label>
                                            <input class="form-check-input" name="ban_delete" type="checkbox"
                                                   id="deleteBansSwitch">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <!-- Kicks -->
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="createKicksSwitch">
                                                Kicks - Create
                                            </label>
                                            <input class="form-check-input" name="kick_create" type="checkbox"
                                                   id="createKicksSwitch">
                                        </div>
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="deleteKicksSwitch">
                                                Kicks - Delete
                                            </label>
                                            <input class="form-check-input" name="kick_delete" type="checkbox"
                                                   id="deleteKicksSwitch">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <!-- Warns -->
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="createWarnsSwitch">
                                                Warns - Create
                                            </label>
                                            <input class="form-check-input" name="warn_create" type="checkbox"
                                                   id="createWarnsSwitch">
                                        </div>
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="deleteWarnsSwitch">
                                                Warns - Delete
                                            </label>
                                            <input class="form-check-input" name="warn_delete" type="checkbox"
                                                   id="deleteWarnsSwitch">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <!-- Commends -->
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="createCommendsSwitch">
                                                Commends - Create
                                            </label>
                                            <input class="form-check-input" name="commend_create" type="checkbox"
                                                   id="createCommendsSwitch">
                                        </div>
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="deleteCommendsSwitch">
                                                Commends - Delete
                                            </label>
                                            <input class="form-check-input" name="commend_delete" type="checkbox"
                                                   id="deleteCommendsSwitch">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <!-- Notes -->
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="createNotesSwitch">
                                                Notes - Create
                                            </label>
                                            <input class="form-check-input" name="note_create" type="checkbox"
                                                   id="createNotesSwitch">
                                        </div>
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="deleteNotesSwitch">
                                                Notes - Delete
                                            </label>
                                            <input class="form-check-input" name="note_delete" type="checkbox"
                                                   id="deleteNotesSwitch">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <!-- TrustScores -->
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="resetTrustScoresSwitch">
                                                TrustScores - Create
                                            </label>
                                            <input class="form-check-input" name="trustscore_create" type="checkbox"
                                                   id="resetTrustScoresSwitch">
                                        </div>
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="resetTrustScoresSwitch">
                                                TrustScores - Delete
                                            </label>
                                            <input class="form-check-input" name="trustscore_delete" type="checkbox"
                                                   id="resetTrustScoresSwitch">
                                        </div>
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="resetTrustScoresSwitch">
                                                TrustScores - Reset
                                            </label>
                                            <input class="form-check-input" name="trustscore_reset" type="checkbox"
                                                   id="resetTrustScoresSwitch">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4 text-center">
                                    <div class="col-12 justify-center">
                                        <button type="submit" class="btn bg-zap text-white fw-bold">Request new API
                                            token
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="tokens-table col-12 justify-center" id="token_table">
                            <!-- Token Table -->
                            <table id="table_players" class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Token ID</th>
                                    <th></th>
                                    <th>Note</th>
                                    <th>Permissions</th>
                                    <th>Expiration</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Get the data from PHP and put it here -->
                                <?php
                                $tokens = Token::where('staff_id', Session::get('staff_id'))->get();
                                $currentDate = date('Y-m-d H:i:s');
                                foreach ($tokens as $token) {
                                    $token_id = $token->token_id;
                                    $dataRow = '<tr id="token-' . $token_id . '">';
                                    $tokenPerms = TokenPerms::where('token_id', $token_id)->get();
                                    $expires = $token->expires;
                                    $active = $token->active_flg;
                                    $note = $token->note;
                                    $dataRow .= "<td>$token_id</td>";
                                    if ($active)
                                        $dataRow .= "<td><span class='badge bg-success'>Active</span></td>";
                                    else
                                        $dataRow .= "<td><span class='badge bg-danger'>Inactive</span></td>";
                                    $dataRow .= "<td>$note</td><td>";
                                    $hasPerms = false;
                                    foreach ($tokenPerms as $perm) {
                                        $permName = $perm->permission;
                                        $allowed = $perm->allowed;
                                        if ($allowed) {
                                            $dataRow .= "<span class='badge rounded-pill bg-secondary'>$permName</span>";
                                            $hasPerms = true;
                                        }
                                    }
                                    $expired = $expires < $currentDate;
                                    $expires = date('m/d/Y - h:i a'); // TODO Maybe the time should be a tooltip?
                                    if (!$expired)
                                        $dataRow .= "<td><span class='badge bg-success'>$expires</span></td>";
                                    else
                                        $dataRow .= "<td><span class='badge bg-danger'>$expires</span></td>";
                                    $dataRow .= "<td><button onclick='deleteToken($token_id)' class='btn btn-outline btn-danger' type='button'><i class='fa-solid fa-trash text-white'></i></button></td>";
                                    $dataRow .= "</tr>";
                                    echo $dataRow;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('_partials._html_footer')
</body>
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // We want to make a datepicker when custom gets selected for token_exp_select
    $('#custom_exp').datepicker({
        isMobile: true,
        range: true,
        position: 'left center',
        language: 'en',
        multipleDatesSeparator: ' -> '
    });
    $('#token_exp_select').on('change', (event) => {
        if (event.target)
            if (event.target.value === 'custom')
                $('#custom_exp').removeClass('d-none');
            else
                $('#custom_exp').addClass('d-none');
    });
    function deleteToken(tokenId) {
        $.ajax({
            url: 'tokens/' + tokenId,
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            success: (response) => {
                console.log("Token deleted successfully", response);
                $('#token-' + tokenId).remove();
            },
            error: (xhr, status, err) => {
                console.error("Error deleting token", xhr, status, err);
                // TODO Error message
            }
        })
    }
</script>
</html>
