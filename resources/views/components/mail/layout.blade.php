@props([
    'title' => null,
    'brandName' => null,
    'brandTagline' => null,
    'brandLogoUrl' => null,
    'footerEmail' => null,
    'footerPhone' => null,
    'footerMobile' => null,
])

@php
    $settings = institution_setting();
    $brandName = $brandName ?: institution_name();
    $brandTagline = $brandTagline ?: institution_tagline();
    $brandLogoUrl = $brandLogoUrl ?: institution_logo_url();
    $footerEmail = $footerEmail
        ?: ($settings?->resolvedSupportEmail() ?: config('brand.support_email'));
    $footerPhone = $footerPhone ?: ($settings?->phone);
    $footerMobile = $footerMobile ?: ($settings?->mobile);
    $title = $title ?: $brandName;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
</head>
<body style="margin:0;padding:0;background:#f0ebe3;font-family:'Source Sans 3',Georgia,'Times New Roman',serif;color:#1c1917;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f0ebe3;padding:36px 14px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:#fffdf8;border:1px solid #d6cfc4;">
                <tr>
                    <td style="padding:36px 36px 28px;text-align:center;background:#115e59;">
                        @if (filled($brandLogoUrl))
                            <img
                                src="{{ $brandLogoUrl }}"
                                alt="{{ $brandName }}"
                                width="40"
                                height="40"
                                style="display:inline-block;margin:0 0 16px;border:0;border-radius:8px;"
                            >
                        @endif
                        <p style="margin:0;font-family:Georgia,'Times New Roman',serif;font-size:22px;font-weight:700;letter-spacing:0.02em;color:#faf7f2;line-height:1.3;">
                            {{ $brandName }}
                        </p>
                        @if (filled($brandTagline))
                            <p style="margin:12px 0 0;font-family:ui-sans-serif,system-ui,sans-serif;font-size:11px;font-weight:500;letter-spacing:0.18em;text-transform:uppercase;color:#99f6e4;">
                                {{ $brandTagline }}
                            </p>
                        @endif
                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin:20px auto 0;">
                            <tr>
                                <td style="width:48px;height:1px;background:#5eead4;font-size:0;line-height:0;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:36px 36px 32px;">
                        {{ $slot }}
                    </td>
                </tr>

                <tr>
                    <td style="padding:0 36px;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="height:1px;background:#e7e0d6;font-size:0;line-height:0;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:22px 36px 28px;text-align:center;font-family:ui-sans-serif,system-ui,sans-serif;font-size:11px;line-height:1.7;color:#78716c;">
                        <p style="margin:0;font-family:Georgia,'Times New Roman',serif;font-size:12px;color:#44403c;">
                            {{ $brandName }}
                        </p>
                        @if (filled($footerEmail))
                            <p style="margin:6px 0 0;">{{ $footerEmail }}</p>
                        @endif
                        @if (filled($footerPhone))
                            <p style="margin:2px 0 0;">{{ $footerPhone }}</p>
                        @endif
                        @if (filled($footerMobile))
                            <p style="margin:2px 0 0;">{{ $footerMobile }}</p>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
