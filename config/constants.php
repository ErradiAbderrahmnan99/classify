<?php

//Sandbox
defined('business') or define('business', 'sb-uefcv23946367@business.example.com');

//Live
// defined('PAYPAL_LIVE_BUSINESS_EMAIL') or define('PAYPAL_LIVE_BUSINESS_EMAIL', '');
// defined('PAYPAL_CURRENCY') or define('PAYPAL_CURRENCY', 'USD');

$SYSTEM_VERSION = "2.5.0";
return [
    'RESPONSE_CODE'    => [
        'LOGIN_SUCCESS'    => 100,
        'VALIDATION_ERROR' => 102,
        'EXCEPTION_ERROR'  => 103,
        'SUCCESS'          => 200,
    ],
    'CACHE'            => [
        'LANGUAGE' => 'languages',
        'SETTINGS' => 'settings'
    ],
    'DEFAULT_SETTINGS' => [
        ['name' => 'currency_symbol', 'value' => '$', 'type' => 'string'],
        ['name' => 'ios_version', 'value' => '1.0.0', 'type' => 'string'],
        ['name' => 'default_language', 'value' => 'en', 'type' => 'string'],
        ['name' => 'force_update', 'value' => '0', 'type' => 'string'],
        ['name' => 'android_version', 'value' => '1.0.0', 'type' => 'string'],
        ['name' => 'number_with_suffix', 'value' => '0', 'type' => 'string'],
        ['name' => 'maintenance_mode', 'value' => 0, 'type' => 'string'],
        ['name' => 'privacy_policy', 'value' => '', 'type' => 'string'],
        ['name' => 'contact_us', 'value' => '', 'type' => 'string'],
        ['name' => 'terms_conditions', 'value' => '', 'type' => 'string'],
        ['name' => 'about_us', 'value' => '', 'type' => 'string'],
        ['name' => 'company_tel1', 'value' => '', 'type' => 'string'],
        ['name' => 'company_tel2', 'value' => '', 'type' => 'string'],
        ['name' => 'system_version', 'value' => $SYSTEM_VERSION, 'type' => 'string'],
        ['name' => 'company_email', 'value' => '', 'type' => 'string'],
        ['name' => 'company_name', 'value' => 'Eclassify', 'type' => 'string'],
        ['name' => 'company_logo', 'value' => 'assets/images/logo/sidebar_logo.png', 'type' => 'file'],
        ['name' => 'favicon_icon', 'value' => 'assets/images/logo/favicon.png', 'type' => 'file'],
        ['name' => 'login_image', 'value' => 'assets/images/bg/login.jpg', 'type' => 'file'],

        ['name' => 'banner_ad_id_android', 'value' => '', 'type' => 'string'],
        ['name' => 'banner_ad_id_ios', 'value' => '', 'type' => 'string'],
        ['name' => 'banner_ad_status', 'value' => '', 'type' => 'string'],

        ['name' => 'interstitial_ad_id_ios', 'value' => '', 'type' => 'string'],
        ['name' => 'interstitial_ad_id_android', 'value' => '', 'type' => 'string'],
        ['name' => 'interstitial_ad_status', 'value' => '', 'type' => 'string'],

        ['name' => 'pinterest_link', 'value' => '', 'type' => 'string'],
        ['name' => 'linkedin_link', 'value' => '', 'type' => 'string'],
        ['name' => 'facebook_link', 'value' => '', 'type' => 'string'],
        ['name' => 'x_link', 'value' => '', 'type' => 'string'],
        ['name' => 'instagram_link', 'value' => '', 'type' => 'string'],
        ['name' => 'google_map_iframe_link', 'value' => '', 'type' => 'string'],
        ['name' => 'app_store_link', 'value' => '', 'type' => 'string'],
        ['name' => 'play_store_link', 'value' => '', 'type' => 'string'],

        ['name' => 'footer_description', 'value' => '', 'type' => 'string'],
        ['name' => 'web_theme_color', 'value' => '#00B2CA', 'type' => 'string'],
        ['name' => 'firebase_project_id', 'value' => '', 'type' => 'string'],
        ['name' => 'company_address', 'value' => '', 'type' => 'string'],
        ['name' => 'place_api_key', 'value' => '', 'type' => 'string'],
        ['name' => 'placeholder_image', 'value' => 'assets/images/logo/placeholder.png', 'type' => 'file'],
        ['name' => 'header_logo', 'value' => 'assets/images/logo/Header Logo.svg', 'type' => 'file'],
        ['name' => 'footer_logo', 'value' => 'assets/images/logo/Footer Logo.svg', 'type' => 'file'],
        ['name' => 'default_latitude', 'value' => '-23.2420', 'type' => 'string'],
        ['name' => 'default_longitude', 'value' => '-69.6669', 'type' => 'string'],
        ['name' => 'file_manager', 'value' => 'public', 'type' => 'string'],
        ['name' => 'show_landing_page', 'value' => '1', 'type' => 'boolean'],
        ['name' => 'mobile_authentication', 'value' => '1', 'type' => 'boolean'],
        ['name' => 'google_authentication', 'value' => '1', 'type' => 'boolean'],
        ['name' => 'email_authentication', 'value' => '1', 'type' => 'boolean'],
        ['name' => 'min_length', 'value' => '0', 'type' => 'number'],
        ['name' => 'max_length', 'value' => '100', 'type' => 'number'],
        ['name' => 'currency_symbol_position', 'value' => 'right', 'type' => 'string'],
        ['name' => 'free_ad_listing', 'value' => '0', 'type' => 'boolean'],
        ['name' => 'auto_approve_item', 'value' => '0', 'type' => 'boolean'],
        ['name' => 'auto_approve_edited_item', 'value' => '0', 'type' => 'boolean'],
        ['name' => 'mail_mailer', 'value' => 'smtp', 'type' => 'string'],
        ['name' => 'mail_host', 'value' => 'mailhog', 'type' => 'string'],
        ['name' => 'mail_port', 'value' => '1025', 'type' => 'string'],
        ['name' => 'mail_username', 'value' => '', 'type' => 'string'],
        ['name' => 'mail_password', 'value' => '', 'type' => 'string'],
        ['name' => 'mail_encryption', 'value' => 'tls', 'type' => 'string'],
        ['name' => 'mail_from_address', 'value' => 'hello@example.com', 'type' => 'string'],
        ['name' => 'depp_link_scheme', 'value' => '', 'type' => 'string'],
        ['name' => 'otp_service_provider', 'value' => '', 'type' => 'string'],
    ]
];
