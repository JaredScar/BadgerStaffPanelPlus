<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('_partials._html_header', $data)
<body class="background-sizing gta-bg1">
    <div class="sidebar-container sidebar-expanded installer-sidebar">
        <div class="sidebar-header">
            <a class="sidebar-brand" href="{{ route('install.show', ['step' => 'welcome']) }}">
                <img src="{{ asset('img/badgerstaffpanel-logo.png') }}" alt="Badger Staff Panel">
                <span>Staff Panel Setup</span>
            </a>
        </div>
        <nav class="sidebar-nav">
            @php
                $labels = [
                    'welcome' => ['Welcome', 'fa-solid fa-house'],
                    'agreement' => ['Agreements', 'fa-solid fa-file-contract'],
                    'config' => ['Configuration', 'fa-solid fa-sliders'],
                    'database' => ['Database', 'fa-solid fa-database'],
                    'admin' => ['Administrator', 'fa-solid fa-user-shield'],
                    'discord' => ['Discord', 'fa-brands fa-discord'],
                    'complete' => ['Complete', 'fa-solid fa-circle-check'],
                ];
            @endphp
            @foreach ($steps as $index => $name)
                @php
                    $done = $index < $stepIndex;
                    $active = $name === $step;
                    $reachable = $alreadyInstalled || $done || $active || session('install.' . $name);
                @endphp
                <div class="nav-item">
                    @if ($reachable)
                        <a href="{{ route('install.show', ['step' => $name]) }}" class="nav-link {{ $active ? 'active' : '' }}">
                            <i class="nav-icon {{ $done ? 'fa-solid fa-square-check' : $labels[$name][1] }}"></i>
                            <span class="nav-text">{{ $labels[$name][0] }}</span>
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="nav-icon fa-regular fa-square"></i>
                            <span class="nav-text">{{ $labels[$name][0] }}</span>
                        </span>
                    @endif
                </div>
            @endforeach
            @if ($authenticated)
                <div class="nav-item mt-3">
                    <a href="{{ route('DASHBOARD') }}" class="nav-link">
                        <i class="nav-icon fa-solid fa-arrow-left"></i>
                        <span class="nav-text">Back to panel</span>
                    </a>
                </div>
            @endif
        </nav>
    </div>

    <div class="content-wrapper installer-content">
        <div class="container-fluid">
            <div class="page-header">
                <h1 class="page-title"><i class="fa-solid fa-screwdriver-wrench me-2"></i>@yield('title')</h1>
                <p class="page-description">@yield('subtitle')</p>
            </div>

            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger installer-alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="installer-card">
                @yield('content')
            </div>
        </div>
    </div>
    @include('_partials._html_footer')
</body>
</html>
