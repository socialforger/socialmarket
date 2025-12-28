<?php
namespace Social_Market\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Admin_Pages {

    public static function init() {
        add_action( 'admin_init', array( __CLASS__, 'handle_actions' ) );
    }

    public static function handle_actions() {
        // Placeholder per future azioni admin
    }
}
