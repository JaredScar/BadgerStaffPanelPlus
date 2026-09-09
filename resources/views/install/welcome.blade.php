@extends('install.layout')

@section('title', 'Welcome')
@section('subtitle', 'Check that this server can run BadgerStaffPanel+')

@section('content')
    <h2>Welcome to BadgerStaffPanel+</h2>
    <p>
        This installer configures the web panel the same way the rest of the app is built:
        environment settings, database schema, your first server, and the admin account.
        The FiveM resource can be connected after this panel is installed.
    </p>
    @if ($alreadyInstalled)
        <div class="alert alert-warning">
            Demo data is already seeded. You can review every setup step from this portal without leaving the panel.
        </div>
    @endif

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <h4>Runtime</h4>
            <ul class="req-list">
                <li class="{{ $requirements['php'] ? 'req-ok' : 'req-bad' }}">
                    <i class="fa-solid {{ $requirements['php'] ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                    PHP 8.1+ &mdash; {{ $requirements['php_version'] }}
                </li>
                <li class="{{ $requirements['laravel'] ? 'req-ok' : 'req-bad' }}">
                    <i class="fa-solid {{ $requirements['laravel'] ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                    Laravel 10+ &mdash; {{ $requirements['laravel_version'] }}
                </li>
                <li class="text-muted">
                    <i class="fa-solid fa-circle-info"></i>
                    MySQL 8 is verified on the Database step.
                </li>
            </ul>

            <h4 class="mt-4">Writable paths</h4>
            <ul class="req-list">
                @foreach ($requirements['writable'] as $path => $ok)
                    <li class="{{ $ok ? 'req-ok' : 'req-bad' }}">
                        <i class="fa-solid {{ $ok ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                        {{ $path }}
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-lg-6">
            <h4>PHP extensions</h4>
            <ul class="req-list">
                @foreach ($requirements['extensions'] as $extension => $ok)
                    <li class="{{ $ok ? 'req-ok' : 'req-bad' }}">
                        <i class="fa-solid {{ $ok ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                        {{ $extension }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <form method="post" action="{{ route('install.save', ['step' => 'welcome']) }}">
        @csrf
        <div class="installer-actions">
            <span></span>
            <button type="submit" class="btn btn-primary" {{ $requirements['ok'] ? '' : 'disabled' }}>
                Continue <i class="fa-solid fa-arrow-right ms-1"></i>
            </button>
        </div>
    </form>
@endsection
