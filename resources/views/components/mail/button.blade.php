@props([
    'href',
])

<p style="margin:0 0 28px;text-align:center;">
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['style' => "display:inline-block;background:#115e59;color:#faf7f2;text-decoration:none;font-family:ui-sans-serif,system-ui,sans-serif;font-weight:600;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;padding:14px 28px;border:1px solid #115e59;border-radius:6px;"]) }}
    >
        {{ $slot }}
    </a>
</p>
