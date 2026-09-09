@extends('install.layout')

@section('title', 'Complete')
@section('subtitle', 'Finish setup and open the login page')

@section('content')
    <h2>Ready to launch</h2>
    <p>The web panel is configured. Finish install to lock the installer and go to the same login screen the staff panel already uses.</p>

    <dl class="install-summary">
        <dt>Application</dt>
        <dd>{{ session('install.app_name', env('APP_NAME', 'BadgerStaffPanel+')) }}</dd>
        <dt>Admin username</dt>
        <dd>{{ session('install.admin_username', 'the account you just created') }}</dd>
        <dt>Next step</dt>
        <dd>Sign in, then connect the FiveM resource to this panel URL.</dd>
    </dl>

    <form method="post" action="{{ route('install.save', ['step' => 'complete']) }}">
        @csrf
        <div class="installer-actions">
            <a href="{{ route('install.show', ['step' => 'discord']) }}" class="btn btn-outline-light">Previous</a>
            <button type="submit" class="btn btn-primary">
                Finish and go to login <i class="fa-solid fa-right-to-bracket ms-1"></i>
            </button>
        </div>
    </form>
@endsection
