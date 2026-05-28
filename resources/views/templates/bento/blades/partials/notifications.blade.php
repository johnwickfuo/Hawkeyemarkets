{{-- show Notification --}}
{{-- success --}}
@if (session()->has('success'))
    <script>
        window.addEventListener('load', function() {
            if (typeof toastNotification === 'function') {
                toastNotification("{{ session('success') }}", 'success');
            }
        });
    </script>
@endif

{{-- error --}}
@if (session()->has('error'))
    <script>
        window.addEventListener('load', function() {
            if (typeof toastNotification === 'function') {
                toastNotification("{{ session('error') }}", 'error');
            }
        });
    </script>
@endif

{{-- warning --}}
@if (session()->has('warning'))
    <script>
        window.addEventListener('load', function() {
            if (typeof toastNotification === 'function') {
                toastNotification("{{ session('warning') }}", 'warning');
            }
        });
    </script>
@endif


{{-- info --}}
@if (session()->has('info'))
    <script>
        window.addEventListener('load', function() {
            if (typeof toastNotification === 'function') {
                toastNotification("{{ session('info') }}", 'info');
            }
        });
    </script>
@endif
