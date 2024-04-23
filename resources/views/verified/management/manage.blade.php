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
                <div class="col col-auto flex-fill">
                    <div class="row flex-fill">
                        <div class="full-page-header d-flex align-items-center">
                            MANAGEMENT
                        </div>
                    </div>
                    <div class="row">
                        <div class="subpage d-flex justify-content-center">
                            <div class="generate-token-form col-12 justify-center" id="tokens">
                                <div class="row text-center">
                                    <div class="col-12 justify-center mt-4">
                                        <button class="btn bg-zap text-white fw-bold">Request new API token</button>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="input-group">
                                            <span class="input-group-text">Note</span>
                                            <input class="form-control" name="note" type="text" placeholder="What's this token for?" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="input-group">
                                            <span class="input-group-text">Token expires in</span>
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
