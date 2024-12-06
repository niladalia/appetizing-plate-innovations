<?php

use App\Kernel;

if (!defined('COMPOSER_VENDOR_DIR')) {
    define('COMPOSER_VENDOR_DIR', getenv('COMPOSER_VENDOR_DIR') ? getenv('COMPOSER_VENDOR_DIR') : dirname(__DIR__).'/vendor');
}

require_once COMPOSER_VENDOR_DIR.'/autoload_runtime.php';

return function (array $context) {
    global $kernel;
    $kernel = new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
    return $kernel;
};
