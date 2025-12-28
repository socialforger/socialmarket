<?php
namespace Social_Market\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Newspack_Integration {

    public static function init() {
        if ( ! self::is_active() ) {
            return;
        }

        add_action( 'init', array( __CLASS__, 'register_hooks' ) );
    }

    public static function is_active() {
        return class_exists( 'Newspack_Newsletters' );
    }

    public static function register_hooks() {
        // Hook per future integrazioni Newspack
        // es: segmentazione newsletter, automazioni, eventi
    }
}
