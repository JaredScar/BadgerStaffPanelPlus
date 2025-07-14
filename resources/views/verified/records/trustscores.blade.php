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
                                        <i class="fas fa-chart-line text-primary me-2"></i>
                                        Trust Scores
                                    </h1>
                                    <p class="content-subtitle">Monitor and manage player trust scores</p>
                                </div>
                                <div class="button-group">
                                    <button type="button" class="btn btn-outline-warning me-2" data-bs-toggle="modal" data-bs-target="#resetScoreModal">
                                        <i class="fas fa-redo me-2"></i>Reset Score
                                    </button>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateScoreModal">
                                        <i class="fas fa-arrow-up me-2"></i>Update Score
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="content-body">
                            @include('_widgets.records.widget_trustscores')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('_partials._html_footer')
    </body>
</html>
