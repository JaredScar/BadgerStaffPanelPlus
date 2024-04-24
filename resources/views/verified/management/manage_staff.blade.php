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
                            STAFF MANAGEMENT
                        </div>
                    </div>
                    <div class="row">
                        <div class="subpage d-flex justify-content-center">
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
