<?php
namespace Social_Market\CPT;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class GAS {

    const POST_TYPE = 'gas';

    public static function register() {

        $labels = array(
            'name'               => __( 'GAS', 'socialmarket' ),
            'singular_name'      => __( 'GAS', 'socialmarket' ),
            'menu_name'          => __( 'GAS', 'socialmarket' ),
            'add_new'            => __( 'Aggiungi nuovo', 'socialmarket' ),
            'add_new_item'       => __( 'Aggiungi GAS', 'socialmarket' ),
            'edit_item'          => __( 'Modifica GAS', 'socialmarket' ),
            'new_item'           => __( 'Nuovo GAS', 'socialmarket' ),
            'view_item'          => __( 'Visualizza GAS', 'socialmarket' ),
            'search_items'       => __( 'Cerca GAS', 'socialmarket' ),
            'not_found'          => __( 'Nessun GAS trovato', 'socialmarket' ),
        );

        $args = array(
            'label'              => __( 'GAS', 'socialmarket' ),
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'supports'           => array( 'title', 'editor', 'thumbnail' ),
            'has_archive'        => true,
            'rewrite'            => array( 'slug' => 'gas' ),
        );

        register_post_type( self::POST_TYPE, $args );
    }
}
