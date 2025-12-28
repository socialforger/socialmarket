<?php
namespace Social_Market\Modules\Events;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Events_Visibility {

    public static function can_view( $event_id, $user_id ) {
        return true; // Placeholder
    }
}
