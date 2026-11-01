@php
    $prompt = $type === 'ban' ? 'Lift this ban?' : 'Remove this ' . $type . '?';
@endphp
<form method="post" action="{{ route('PLAYER_REVOKE') }}" class="d-inline" onsubmit="return confirm(@json($prompt));">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">
    <input type="hidden" name="id" value="{{ $id }}">
    <button type="submit" class="btn btn-sm btn-outline-danger">{{ $label }}</button>
</form>
