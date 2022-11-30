<?php

declare(strict_types=1);

/*
'########:'##::::'##::'######::'##::::'##::'######:::'#######::'########::'########:
... ##..:: ##:::: ##:'##... ##: ##:::: ##:'##... ##:'##.... ##: ##.... ##: ##.....::
::: ##:::: ##:::: ##: ##:::..:: ##:::: ##: ##:::..:: ##:::: ##: ##:::: ##: ##:::::::
::: ##:::: ##:::: ##:. ######:: #########: ##::::::: ##:::: ##: ##:::: ##: ######:::
::: ##:::: ##:::: ##::..... ##: ##.... ##: ##::::::: ##:::: ##: ##:::: ##: ##...::::
::: ##:::: ##:::: ##:'##::: ##: ##:::: ##: ##::: ##: ##:::: ##: ##:::: ##: ##:::::::
::: ##::::. #######::. ######:: ##:::: ##:. ######::. #######:: ########:: ########:
:::..::::::.......::::......:::..:::::..:::......::::.......:::........:::........::                                                                                   
*/


// Config Database
define('DATABASE', [
    'driver'  => 'PDO',
    'name'    => 'bincomphptest',
    'host'    => 'localhost',
    'user'    => 'root',
    'pass'    => 'root',
    'charset' => 'utf8mb4',
]);

// SERVER TIMEZONE
date_default_timezone_set("Africa/Lagos");

define('ENVIRONMENT', 'development');

if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
    // SHOW PHP ERRORS
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

// MAINROOT
define('MAINROOT', dirname(dirname(dirname(__FILE__))));

// APPROOT
define('APPROOT', dirname(dirname(__FILE__)));

// SERVER URL ROOT
define("URLROOT", "http://localhost/bincomtest");

// database dump file path for automatic import
define('DB_DUMP_PATH', APPROOT . '/Database/import.sql');

// ENCRYPTION
define("ENCRYPT_KEY", 'DG7Gq)OJR_|?Q_[F]H$m4V8q*&^%$#%^');


// DATE & TIME
define("DATENOW", date('Y-m-d H:i:s'));
define("TIMENOW", date('H:i:s'));
define("DATEFULL", date('D, M jS Y'));

// APPLICATION CONFIGURATION
define("CONFIG", [
    'title_helper' => ' | ',
    'backup' => false,
    'minify_html' => true,
    'maintenance' => false,
    'maintenance_time' => '30 Minutes',
    'backup_dir' => APPROOT . "/Backup/",
    'lottie_js' => URLROOT . "/assets/js/lottie-player.js",
    'fingerprint_js' => URLROOT . "/assets/js/fingerprint.min.js",
    'captcha_js' => "https://www.google.com/recaptcha/api.js",
    'password_strength' => 2, // Weak
    'password_length' => 6, // 6 Characters
    'cookie_max' => 60 * 60 * 24 * 30, // 30 days cookie stored
    'token_max' => 25, // 25 minutes
    'login_max' => 4, // maximum attempts before locked out
    'next_login' => 10, // next login attempt 5 minutes
    'sendgrid' => "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",
    "APP" => [
        'name' => 'Bincom Dev Center',
        'tagline' => "Tomorrow's Technology Today",
        'favicon' => URLROOT . '/assets/images/logo/favicon.png',
        'logo_light' => URLROOT . '/assets/images/logo/logo-light.png',
        'logo_dark' => URLROOT . '/assets/images/logo/logo-dark.png',
        'logo_meta' => URLROOT . '/assets/images/logo/logo-meta.jpg',
        'phone' => '+2348100000000',
        'theme' => '#8fc74a',
        'address' => '21, Araromi Street, Off Moloney Street, Onikan, Lagos',
        'email' => 'info@nincom.com',
        'support_team' => 'Bincom Dev Team',
        'support_mail' => 'help@bincom.com',
        'copyright' => '&copy; ' . date('Y') . ' <strong>Bincom Dev Center</strong>. All Rights Reserved',
    ],
    "UPLOADS" => [
        'users_dir' => MAINROOT . '/uploads/storage/member/',
        'profile_dir' => URLROOT . '/uploads/storage/profile/',
        'upload_dir' => MAINROOT . '/uploads/storage/',
    ],
    "SMTP" => [
        'smtp_from' => "team@bincom.com",
        'smtp_name' => "Bincom Test",
        'smtp_secure' => 'ssl',
        'smtp_auth' => true,
        'smtp_host' => '****************',
        'smtp_port' => 465,
        'smtp_username' => '**********',
        'smtp_password' => '*************',
    ],
    "QUEUE" => [
        "track_open" => true,
        "track_url" => URLROOT . "/api/tracker/queue/open",
        "send_sequence" => 3,
    ],
    "SOCIAL" => [
        "fb" => "https://facebook.com/",
        "tw" => "https://twitter.com/",
        "ig" => "https://instagram.com/",
        "ln" => "https://linkedin.com/",
        "tg" => "https://tg.me/234",
        "wa" => "https://wa.me/234",
    ],
    "PUSH" => [
        "app" => "af9f5420-c66d-4109-83bc-9f8d5442fe0f",
        "safari" => "web.onesignal.auto.105cd246-aae6-4c14-8684-2fd8214524d1",
        "rest" => "MTRjN2UyZmQtYzUyNy00YzNmLWEzYjgtODA4ZTgxYWU4NzNh",
        'favicon' => URLROOT . '/assets/images/logo/favicon.png',
        'logo' => URLROOT . '/assets/images/logo/logo-dark.png',
    ],
    "PAY" => [
        "paystack" => [
            "public" => "pk_test_d592fd47b4880c5cb334a5edcf7d092326f0bf39",
            "secret" => "sk_test_9726937a0d5af30e8af3fb221f7d62bce16c158a",
        ]
    ],
    'DEV' => [
        'name' => 'Tushcode',
        'website' => 'https://tushcode.com.ng',
        'email' => 'okafor.solomon@ymail.com',
        'phone' => '+2349069977795',
    ],
]);

define("MAILER", [
    "{{site}}" => CONFIG['APP']['name'],
    "{{tagline}}" => CONFIG['APP']['tagline'],
    "{{year}}" => date("Y"),
    "{{siteurl}}" => URLROOT,
    "{{logo}}" => CONFIG['APP']['logo_dark'],
    "{{color}}" => CONFIG['APP']['theme'],
    "{{siteAddr}}" => CONFIG['APP']['address'],
    "{{support_team}}" => CONFIG['APP']['support_team'],
    "{{support_mail}}" => CONFIG['APP']['support_mail'],
    "{{site_email}}" => CONFIG['APP']['email'],
    "{{site_phone}}" => CONFIG['APP']['phone'],
    "{{copyright}}" => CONFIG['APP']['copyright'],
    "{{social_dir}}" => URLROOT . '/assets/images/social/',
    "{{fb}}" => CONFIG['SOCIAL']['fb'],
    "{{tw}}" => CONFIG['SOCIAL']['tw'],
    "{{in}}" => CONFIG['SOCIAL']['ig'],
    "{{le}}" => CONFIG['SOCIAL']['ln'],
    "{{wa}}" => CONFIG['SOCIAL']['wa'],
    "{{tg}}" => CONFIG['SOCIAL']['tg'],
    "{{dev_name}}" => CONFIG['DEV']['name'],
    "{{dev_url}}" => CONFIG['DEV']['website'],
]);

/** 
 * Error Handling Class 
 * 
 */

function error_handler(int $type, string $msg, ?string $file = null, ?int $line = null)
{
    header('Content-Type: application/json');
    echo json_encode(["type" => $type, "message" => $msg, "file" => $file, "line" => 'Found On Line ' . $line], JSON_PRETTY_PRINT);
    exit;
}

// CUSTOM INI SET
ini_set('max_input_vars', '10000');
ini_set('memory_limit', '750M');
//ini_set('max_execution_time', '0');
ini_set('upload_max_filesize', '50M');

// LOGGER
ini_set('log_errors', 'On');
ini_set('error_log', APPROOT . '/logs/system.log');
set_error_handler('error_handler', E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
