<x-mail.layout :title="__('Reset Password Notification')">
    <p style="margin:0 0 18px;font-family:Georgia,'Times New Roman',serif;font-size:18px;color:#1c1917;">
        {{ $greeting }}
    </p>

    <p style="margin:0 0 24px;font-family:ui-sans-serif,system-ui,sans-serif;font-size:15px;line-height:1.7;color:#44403c;">
        {{ __('auth.mail_reset_intro') }}
    </p>

    <x-mail.button :href="$url">
        {{ __('auth.mail_reset_action') }}
    </x-mail.button>

    <p style="margin:0 0 18px;font-family:ui-sans-serif,system-ui,sans-serif;font-size:13px;line-height:1.7;color:#78716c;">
        {{ __('auth.mail_reset_expire', ['count' => $expire]) }}
    </p>

    <p style="margin:0 0 24px;font-family:ui-sans-serif,system-ui,sans-serif;font-size:13px;line-height:1.7;color:#78716c;">
        {{ __('auth.mail_reset_ignore') }}
    </p>

    <x-mail.fallback-link :href="$url" />
</x-mail.layout>
