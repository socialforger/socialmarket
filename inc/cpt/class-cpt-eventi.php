<?php
namespace Social_Market\CPT;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Eventi {

    const POST_TYPE = 'sm_evento';

    public static function register() {

        $labels = array(
            'name'               => __( 'Eventi', 'socialmarket' ),
            'singular_name'      => __( 'Evento', 'socialmarket' ),
            'menu_name'          => __( 'Eventi', 'socialmarket' ),
            'add_new'            => __( 'Aggiungi nuovo', 'socialmarket' ),
            'add_new_item'       => __( 'Aggiungi Evento', 'socialmarket' ),
            'edit_item'          => __( 'Modifica Evento', 'socialmarket' ),
            'new_item'           => __( 'Nuovo Evento', 'socialmarket' ),
            'view_item'          => __( 'Visualizza Evento', 'socialmarket' ),
            'search_items'       => __( 'Cerca Eventi', 'socialmarket' ),
            'not_found'          => __( 'Nessun Evento trovato', 'socialmarket' ),
        );

        $args = array(
            'label'              => __( 'Eventi', 'socialmarket' ),
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'supports'           => array( 'title', 'editor', 'thumbnail' ),
            'has_archive'        => true,
            'rewrite'            => array( 'slug' => 'eventi' ),
        );

        register_post_type( self::POST_TYPE, $args );
    }
}
