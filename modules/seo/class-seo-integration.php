<?php
namespace Social_Market\Modules\SEO;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SEO_Integration {

    public static function init() {
        add_action( 'wp_head', array( __CLASS__, 'inject_meta' ) );
    }

    public static function inject_meta() {
        // Placeholder SEO
    }
}
