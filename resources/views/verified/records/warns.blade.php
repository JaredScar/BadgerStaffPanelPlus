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
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Warnings
                        </h1>
                        <p class="page-description">Manage and view player warnings</p>
                    </div>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addWarningModal">
                        <i class="fas fa-plus me-2"></i>Add Warning
                    </button>
                </div>

                <!-- Include the warns widget -->
                @include('_widgets.records.widget_warns')
            </div>
        </div>

        @include('_partials._html_footer')
    </body>
</html>
