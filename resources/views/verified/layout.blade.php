<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('_partials._html_header')
<body class="background-sizing gta-bg1">
    <div class="container-fluid master-contain">
        @include('_partials._toast')
        @include('_partials._sidebar')
        <div class="content-wrapper">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            @hasSection('header')
                @yield('header')
            @else
                <div class="page-header d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h1 class="page-title">
                            <i class="fas @yield('icon', 'fa-layer-group') me-2"></i>
                            @yield('title')
                        </h1>
                        <p class="page-description">@yield('subtitle')</p>
                    </div>
                    <div>@yield('actions')</div>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
    @yield('modals')
    @include('_partials._html_footer')
    @if (session('status'))
        <script>showToast('success_toast', @json(session('status')));</script>
    @endif
    @if (session('error'))
        <script>showToast('error_toast', @json(session('error')));</script>
    @endif
    @yield('scripts')
</body>
</html>
