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
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Trust Scores
                        </h1>
                        <p class="page-description">Monitor and manage player trust scores</p>
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

                <!-- Include the trustscores widget -->
                @include('_widgets.records.widget_trustscores')
            </div>
        </div>

        @include('_partials._html_footer')
    </body>
</html>
