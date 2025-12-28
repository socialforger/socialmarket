<?php
namespace Social_Market;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Roles {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'add_roles' ) );
    }

    public static function add_roles() {

        // Referente GAS
        add_role(
            'sm_referente_gas',
            __( 'Referente GAS', 'socialmarket' ),
            array(
                'read'                   => true,
                'edit_posts'             => false,
                'manage_socialmarket_gas' => true,
            )
        );

        // Volontario Logistica
        add_role(
            'sm_volontario_logistica',
            __( 'Volontario Logistica', 'socialmarket' ),
            array(
                'read'                   => true,
                'manage_socialmarket_logistica' => true,
            )
        );
    }

    public static function remove_roles() {
        remove_role( 'sm_referente_gas' );
        remove_role( 'sm_volontario_logistica' );
    }
}
