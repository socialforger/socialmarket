<?php
namespace Social_Market\Modules\Newsletter;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Newsletter_Newspack {

    public static function is_active() {
        return class_exists( 'Newspack_Newsletters' );
    }

    public static function send_via_newspack( $subject, $content, $segment ) {
        if ( ! self::is_active() ) {
            return false;
        }

        // Placeholder integrazione Newspack
        return true;
    }
}
