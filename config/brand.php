<?php

return [
    /** Nombre institucional por defecto (visible en UI). */
    'name' => env('BRAND_NAME', env('APP_NAME', 'Modular App')),
    'support_email' => env('BRAND_SUPPORT_EMAIL', env('MAIL_FROM_ADDRESS')),
    'tagline' => env('BRAND_TAGLINE'),
    /**
     * URL absoluta opcional. Si está vacío, se usa logo_path bajo public/.
     */
    'logo_url' => env('BRAND_LOGO_URL'),
    'logo_path' => env('BRAND_LOGO_PATH'),
];
