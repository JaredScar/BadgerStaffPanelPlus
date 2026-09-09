@extends('install.layout')

@section('title', 'Administrator')
@section('subtitle', 'Create the first server and admin login')

@section('content')
    <h2>Administrative user</h2>
    <p>This account can sign into the web panel and manage staff, tokens, and settings.</p>

    <form method="post" action="{{ route('install.save', ['step' => 'admin']) }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="server_name">Server name</label>
                <input class="form-control" id="server_name" name="server_name" value="{{ old('server_name', $defaults['server_name']) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="server_slug">Server slug</label>
                <input class="form-control" id="server_slug" name="server_slug" value="{{ old('server_slug', $defaults['server_slug']) }}" required>
                <div class="form-text">Lowercase letters, numbers, and dashes only.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="staff_username">Admin username</label>
                <input class="form-control" id="staff_username" name="staff_username" value="{{ old('staff_username', $defaults['staff_username']) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="staff_email">Admin email</label>
                <input class="form-control" id="staff_email" name="staff_email" type="email" value="{{ old('staff_email', $defaults['staff_email']) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="staff_password">Password</label>
                <input class="form-control" id="staff_password" name="staff_password" type="password" required minlength="8">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="staff_password_confirmation">Confirm password</label>
                <input class="form-control" id="staff_password_confirmation" name="staff_password_confirmation" type="password" required minlength="8">
            </div>
            <div class="col-md-12">
                <label class="form-label" for="staff_discord">Admin Discord ID</label>
                <input class="form-control" id="staff_discord" name="staff_discord" value="{{ old('staff_discord', $defaults['staff_discord']) }}">
                <div class="form-text">Optional. Needed later for Discord login and in-game staff matching.</div>
            </div>
        </div>

        <div class="installer-actions">
            <a href="{{ route('install.show', ['step' => 'database']) }}" class="btn btn-outline-light">Previous</a>
            <button type="submit" class="btn btn-primary">Continue <i class="fa-solid fa-arrow-right ms-1"></i></button>
        </div>
    </form>
@endsection
