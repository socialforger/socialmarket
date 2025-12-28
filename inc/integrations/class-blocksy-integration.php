<?php
namespace Social_Market\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Blocksy_Integration {

    public static function init() {
        if ( ! self::is_active() ) {
            return;
        }

        add_action( 'init', array( __CLASS__, 'register_hooks' ) );
    }

    public static function is_active() {
        return function_exists( 'blocksy_theme' );
    }

    public static function register_hooks() {
        // Hook per future integrazioni Blocksy
        // es: supporto customizer, card layout, meta, archive options
    }
}
