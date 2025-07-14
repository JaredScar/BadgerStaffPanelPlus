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
                        <div class="content-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h1 class="content-title">
                                        <i class="fas fa-database text-warning me-2"></i>
                                        All Records
                                    </h1>
                                    <p class="content-subtitle">View all player records in one place</p>
                                </div>
                            </div>
                        </div>
                        <div class="content-body">
                            @include('_widgets.records.widget_records')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('_partials._html_footer')
    </body>
</html>
