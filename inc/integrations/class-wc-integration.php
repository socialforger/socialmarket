<?php
namespace Social_Market\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WC_Integration {

    public static function init() {
        if ( ! self::is_active() ) {
            return;
        }

        add_action( 'init', array( __CLASS__, 'register_hooks' ) );
    }

    public static function is_active() {
        return class_exists( 'WooCommerce' );
    }

    public static function register_hooks() {
        // Hook per future integrazioni WooCommerce
        // es: aggiungere badge, filtri, logiche GAS sui prodotti
    }
}
