<script>
    window.app = {!! json_encode([
        'user' => auth()->check() ? new \App\Http\Resources\User(auth()->user()) : null,
        'storage' => substr(Storage::url('/'), 0, -1)
    ]) !!};
</script>

<script src="{{ asset('js/app.js') }}"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('google.map') }}&libraries=places&language=en&callback=app.init">
</script>
