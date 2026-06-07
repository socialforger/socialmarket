<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/class-sm-delivery.php';
require_once __DIR__ . '/class-sm-delivery-session.php';
require_once __DIR__ . '/class-sm-picking.php';
require_once __DIR__ . '/class-sm-loading.php';
require_once __DIR__ . '/class-sm-driver.php';
require_once __DIR__ . '/class-sm-checkout-delivery.php';

SM_Delivery::init();
SM_Checkout_Delivery::init();
