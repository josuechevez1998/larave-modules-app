@if (config('sweetalert.alwaysLoadJS') === true || Session::has('alert.config') || Session::has('alert.delete'))
    @if (config('sweetalert.animation.enable'))
        <link rel="stylesheet" href="{{ config('sweetalert.animatecss') }}">
    @endif

    @if (config('sweetalert.theme') != 'default')
        <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-{{ config('sweetalert.theme') }}" rel="stylesheet">
    @endif

    @if (config('sweetalert.neverLoadJS') === false)
        <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    @endif

    @if (Session::has('alert.delete'))
        <script>
            document.addEventListener('click', function(event) {
                var target = event.target;
                var confirmDeleteElement = target.closest('[data-confirm-delete]');

                if (confirmDeleteElement) {
                    event.preventDefault();
                    Swal.fire({!! Session::pull('alert.delete') !!}).then(function(result) {
                        if (result.isConfirmed) {
                            var form = document.createElement('form');
                            form.action = confirmDeleteElement.href;
                            form.method = 'POST';
                            form.innerHTML = `
                            @csrf
                            @method('DELETE')
                        `;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }
            });
        </script>
    @endif

    @if (Session::has('alert.config'))
        <script>
            (function () {
                var config = {!! Session::pull('alert.config') !!};

                function show() {
                    if (typeof Swal === 'undefined') {
                        setTimeout(show, 40);
                        return;
                    }
                    Swal.fire(config);
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', show);
                } else {
                    show();
                }
            })();
        </script>
    @endif
@endif
