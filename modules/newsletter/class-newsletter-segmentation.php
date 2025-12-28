<?php
namespace Social_Market\Modules\Newsletter;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Newsletter_Segmentation {

    public static function get_segments() {
        return array(
            'global' => __( 'Tutti gli utenti', 'socialmarket' ),
            'gas'    => __( 'Membri GAS', 'socialmarket' ),
            'staff'  => __( 'Staff / Volontari', 'socialmarket' ),
        );
    }
}
