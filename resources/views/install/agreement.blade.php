@extends('install.layout')

@section('title', 'Agreements')
@section('subtitle', 'Read and accept the Terms of Service')

@section('content')
    <h2>Terms of Service</h2>
    <p>Please read the agreement below before continuing with installation.</p>
    <textarea class="tos-box" readonly>{{ $tosContent }}</textarea>

    <form method="post" action="{{ route('install.save', ['step' => 'agreement']) }}" class="mt-3">
        @csrf
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="agree" value="1" id="agree" {{ old('agree') ? 'checked' : '' }}>
            <label class="form-check-label" for="agree">
                I agree to the Terms of Service and End User License Agreement.
            </label>
        </div>
        <div class="installer-actions">
            <a href="{{ route('install.show', ['step' => 'welcome']) }}" class="btn btn-outline-light">Previous</a>
            <button type="submit" class="btn btn-primary" id="continueBtn" disabled>
                Continue <i class="fa-solid fa-arrow-right ms-1"></i>
            </button>
        </div>
    </form>
    <script>
        const agree = document.getElementById('agree');
        const continueBtn = document.getElementById('continueBtn');
        const sync = () => { continueBtn.disabled = !agree.checked; };
        agree.addEventListener('change', sync);
        sync();
    </script>
@endsection
