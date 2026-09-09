@extends('install.layout')

@section('title', 'Database')
@section('subtitle', 'Connect MySQL and run the panel migrations')

@section('content')
    <h2>Database connection</h2>
    <p>The installer will create the database if it does not exist, then run Laravel migrations for the staff panel schema.</p>

    <form method="post" action="{{ route('install.save', ['step' => 'database']) }}" id="database-form">
        @csrf
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label" for="db_host">Host</label>
                <input class="form-control" id="db_host" name="db_host" value="{{ old('db_host', $defaults['db_host']) }}" required>
                <div class="form-text">Use <code>mysql</code> when running in Docker Compose.</div>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="db_port">Port</label>
                <input class="form-control" id="db_port" name="db_port" value="{{ old('db_port', $defaults['db_port']) }}" required>
            </div>
            <div class="col-md-12">
                <label class="form-label" for="db_database">Database name</label>
                <input class="form-control" id="db_database" name="db_database" value="{{ old('db_database', $defaults['db_database']) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="db_username">Username</label>
                <input class="form-control" id="db_username" name="db_username" value="{{ old('db_username', $defaults['db_username']) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="db_password">Password</label>
                <input class="form-control" id="db_password" name="db_password" type="password" value="{{ old('db_password', $defaults['db_password']) }}">
            </div>
        </div>

        <div class="mt-3">
            <button type="button" class="btn btn-outline-light" id="test-db">
                <i class="fa-solid fa-plug me-1"></i> Test connection
            </button>
            <span id="db-test-result" class="ms-2"></span>
        </div>

        <div class="installer-actions">
            <a href="{{ route('install.show', ['step' => 'config']) }}" class="btn btn-outline-light">Previous</a>
            <button type="submit" class="btn btn-primary">Install database <i class="fa-solid fa-arrow-right ms-1"></i></button>
        </div>
    </form>
    <script>
        document.getElementById('test-db').addEventListener('click', async () => {
            const result = document.getElementById('db-test-result');
            result.textContent = 'Testing...';
            result.className = 'ms-2 text-muted';
            const body = new URLSearchParams({
                _token: '{{ csrf_token() }}',
                db_host: document.getElementById('db_host').value,
                db_port: document.getElementById('db_port').value,
                db_database: document.getElementById('db_database').value,
                db_username: document.getElementById('db_username').value,
                db_password: document.getElementById('db_password').value,
            });
            try {
                const response = await fetch('{{ route('install.database.test') }}', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body
                });
                const data = await response.json();
                result.textContent = data.message || (data.ok ? 'Connected.' : 'Connection failed.');
                result.className = data.ok ? 'ms-2 req-ok' : 'ms-2 req-bad';
            } catch (error) {
                result.textContent = 'Could not reach the installer.';
                result.className = 'ms-2 req-bad';
            }
        });
    </script>
@endsection
