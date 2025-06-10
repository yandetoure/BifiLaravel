<?php declare(strict_types=1); 

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration BIFI
    |--------------------------------------------------------------------------
    |
    | Configuration spécifique pour l'application BIFI
    |
    */

    'company' => [
        'name' => env('BIFI_COMPANY_NAME', 'B!consulting'),
        'email' => env('BIFI_COMPANY_EMAIL', 'contact@biconsulting.biz'),
        'phone' => env('BIFI_COMPANY_PHONE', '+221 XX XXX XX XX'),
        'address' => env('BIFI_COMPANY_ADDRESS', 'Dakar, Sénégal'),
        'website' => env('BIFI_COMPANY_WEBSITE', 'https://www.biconsulting.biz'),
    ],

    'payment' => [
        'default_fee_rate' => env('PAYMENT_FEE_RATE', 0.01), // 1%
        'supported_methods' => ['wizall', 'wave', 'orange_money', 'cash'],
    ],

    'receipt' => [
        'number_prefix' => env('BIFI_RECEIPT_PREFIX', 'BIFI'),
        'format' => env('BIFI_RECEIPT_FORMAT', 'pdf'),
        'auto_send_email' => env('BIFI_AUTO_SEND_EMAIL', false),
        'auto_send_whatsapp' => env('BIFI_AUTO_SEND_WHATSAPP', false),
    ],

    'ocr' => [
        'enabled' => env('BIFI_OCR_ENABLED', true),
        'api_key' => env('BIFI_OCR_API_KEY'),
        'api_url' => env('BIFI_OCR_API_URL', 'https://api.ocr.space/parse/image'),
        'language' => env('BIFI_OCR_LANGUAGE', 'fre'),
    ],

    'balance' => [
        'default_wizall_start' => env('BIFI_DEFAULT_WIZALL_BALANCE', 0),
        'default_wave_start' => env('BIFI_DEFAULT_WAVE_BALANCE', 0),
        'auto_initialize_daily' => env('BIFI_AUTO_INITIALIZE_DAILY', false),
        'alert_low_balance' => env('BIFI_ALERT_LOW_BALANCE', 10000),
    ],

    'notifications' => [
        'email' => [
            'enabled' => env('BIFI_EMAIL_NOTIFICATIONS', true),
            'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@biconsulting.biz'),
            'from_name' => env('MAIL_FROM_NAME', 'Bifi by B!consulting'),
        ],
        'whatsapp' => [
            'enabled' => env('BIFI_WHATSAPP_ENABLED', false),
            'api_key' => env('BIFI_WHATSAPP_API_KEY'),
            'api_url' => env('BIFI_WHATSAPP_API_URL'),
        ],
    ],

    'roles' => [
        'admin' => [
            'name' => 'Administrateur',
            'permissions' => ['*'],
        ],
        'supervisor' => [
            'name' => 'Superviseur',
            'permissions' => ['manage_bills', 'manage_payments', 'manage_balances', 'view_reports'],
        ],
        'agent' => [
            'name' => 'Agent',
            'permissions' => ['process_bills', 'process_payments', 'view_own_stats'],
        ],
        'client' => [
            'name' => 'Client',
            'permissions' => ['create_bills', 'view_own_bills'],
        ],
    ],

    'limits' => [
        'max_bill_amount' => env('BIFI_MAX_BILL_AMOUNT', 1000000), // 1 million FCFA
        'max_cash_payment' => env('BIFI_MAX_CASH_PAYMENT', 500000), // 500k FCFA
        'max_daily_transactions' => env('BIFI_MAX_DAILY_TRANSACTIONS', 1000),
        'file_upload_max_size' => env('BIFI_MAX_FILE_SIZE', 10240), // 10MB en KB
    ],

    'ui' => [
        'theme' => env('BIFI_UI_THEME', 'blue'),
        'logo_path' => env('BIFI_LOGO_PATH', 'images/logobi.png'),
        'favicon_path' => env('BIFI_FAVICON_PATH', 'images/favicon.ico'),
        'items_per_page' => env('BIFI_ITEMS_PER_PAGE', 20),
    ],

    'security' => [
        'session_timeout' => env('BIFI_SESSION_TIMEOUT', 7200), // 2 heures
        'max_login_attempts' => env('BIFI_MAX_LOGIN_ATTEMPTS', 5),
        'lockout_time' => env('BIFI_LOCKOUT_TIME', 900), // 15 minutes
        'require_2fa' => env('BIFI_REQUIRE_2FA', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Méthodes de paiement acceptées
    |--------------------------------------------------------------------------
    */
    'payment_methods' => [
        'wizall' => [
            'name' => 'Wizall',
            'enabled' => env('BIFI_WIZALL_ENABLED', true),
            'fee_percentage' => env('BIFI_WIZALL_FEE', 0),
        ],
        'wave' => [
            'name' => 'Wave',
            'enabled' => env('BIFI_WAVE_ENABLED', true),
            'fee_percentage' => env('BIFI_WAVE_FEE', 0),
        ],
        'orange_money' => [
            'name' => 'Orange Money',
            'enabled' => env('BIFI_ORANGE_MONEY_ENABLED', true),
            'fee_percentage' => env('BIFI_ORANGE_MONEY_FEE', 0),
        ],
        'cash' => [
            'name' => 'Espèces',
            'enabled' => env('BIFI_CASH_ENABLED', true),
            'fee_percentage' => env('BIFI_CASH_FEE', 0),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration OCR
    |--------------------------------------------------------------------------
    */
]; 