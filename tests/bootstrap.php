<?php

use Symfony\Component\Dotenv\Dotenv;

if (!defined('COMPOSER_VENDOR_DIR')) {
    define('COMPOSER_VENDOR_DIR', getenv('COMPOSER_VENDOR_DIR') ? getenv('COMPOSER_VENDOR_DIR') : dirname(__DIR__).'/vendor');
}

require COMPOSER_VENDOR_DIR.'/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}
