<?php
namespace Social_Market\Modules\Newsletter;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Newsletter_Manager {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'register_hooks' ) );
    }

    public static function register_hooks() {
        // Hook per future funzioni newsletter
    }

    public static function send_newsletter( $segment, $content ) {
        // Placeholder per invio newsletter
    }
}
