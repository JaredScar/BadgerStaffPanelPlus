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
                    <div class="row flex-fill">
                        <div class="full-page-header d-flex align-items-center">
                            Trustscores
                        </div>
                    </div>
                    <div class="row">
                        <div class="subpage">
                            @include('_widgets.records.widget_trustscores')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('_partials._html_footer')
    </body>
</html>
