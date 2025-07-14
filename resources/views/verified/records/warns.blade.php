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
                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                        Warnings
                                    </h1>
                                    <p class="content-subtitle">Manage and view player warnings</p>
                                </div>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addWarningModal">
                                    <i class="fas fa-plus me-2"></i>Add Warning
                                </button>
                            </div>
                        </div>
                        <div class="content-body">
                            @include('_widgets.records.widget_warns')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('_partials._html_footer')
    </body>
</html>
