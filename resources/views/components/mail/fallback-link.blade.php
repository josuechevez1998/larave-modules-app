@props([
    'href',
])

<p style="margin:0;font-family:ui-sans-serif,system-ui,sans-serif;font-size:12px;line-height:1.7;color:#78716c;">
    {{ __('auth.mail_fallback') }}
    <br>
    <a href="{{ $href }}" style="color:#115e59;word-break:break-all;text-decoration:none;border-bottom:1px solid #5eead4;">{{ $href }}</a>
</p>
