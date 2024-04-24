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
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fw-bold">Note</span>
                                                <input class="form-control" name="note" type="text" placeholder="What's this token for?" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <span class="input-group-text fw-bold">Token expires in</span>
                                                <select class="form-select" name="expiration" id="token_exp_select">
                                                    <option value="7">7 days</option>
                                                    <option value="30">30 days</option>
                                                    <option value="60">60 days</option>
                                                    <option value="90">90 days</option>
                                                    <option value="custom">Custom...</option>
                                                    <option value="noexp">No Expiration</option>
                                                </select>
                                                <input type="text" id="custom_exp" class="d-none w-35" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4 permissions-sect">
                                        <div class="col-6">
                                            <!-- Registry -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="registry">
                                                <label class="form-check-label" for="registry">
                                                    Registry - Register player
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <!-- Staff -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="createStaff">
                                                <label class="form-check-label" for="createStaff">
                                                    Staff - Create
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <!-- Bans -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="createBans">
                                                <label class="form-check-label" for="createBans">
                                                    Bans - Create
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="deleteBans">
                                                <label class="form-check-label" for="deleteBans">
                                                    Bans - Delete
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <!-- Kicks -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="createKicks">
                                                <label class="form-check-label" for="createKicks">
                                                    Kicks - Create
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="deleteKicks">
                                                <label class="form-check-label" for="deleteKicks">
                                                    Kicks - Delete
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <!-- Warns -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="createWarns">
                                                <label class="form-check-label" for="createWarns">
                                                    Warns - Create
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="deleteWarns">
                                                <label class="form-check-label" for="deleteWarns">
                                                    Warns - Delete
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <!-- Commends -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="createCommends">
                                                <label class="form-check-label" for="createCommends">
                                                    Commends - Create
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="deleteCommends">
                                                <label class="form-check-label" for="deleteCommends">
                                                    Commends - Delete
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <!-- Notes -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="createNotes">
                                                <label class="form-check-label" for="createNotes">
                                                    Notes - Create
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="deleteNotes">
                                                <label class="form-check-label" for="deleteNotes">
                                                    Notes - Delete
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <!-- TrustScores -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="resetTrustScores">
                                                <label class="form-check-label" for="resetTrustScores">
                                                    TrustScores - Reset
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4 text-center">
                                        <div class="col-12 justify-center">
                                            <button class="btn bg-zap text-white fw-bold">Request new API token</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="tokens-table col-12 justify-center" id="token_table">
                                    <!-- Token Table -->
                                    <table id="table_players" class="table table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>Token ID</th>
                                            <th>Note</th>
                                            <th>Permissions</th>
                                            <th>Expiration Date</th>
                                            <th>Expired?</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <!-- TODO Get the data from PHP and put it here -->
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
    <script type="module">
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
    </script>
</html>
