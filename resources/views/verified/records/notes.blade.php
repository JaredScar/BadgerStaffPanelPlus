<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('_partials._html_header')
<body class="background-sizing gta-bg1">
    <div class="container-fluid master-contain">
        @include('_partials._toast')
        @include('_partials._sidebar')
        <div class="content-wrapper">
            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="page-title"><i class="fas fa-sticky-note me-2"></i>Notes</h1>
                    <p class="page-description">Staff notes attached to players</p>
                </div>
            </div>
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @include('_widgets.records.widget_notes')
        </div>
    </div>
    @include('_partials._html_footer')
</body>
</html>
