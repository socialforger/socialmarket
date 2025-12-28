<?php
namespace Social_Market\CPT;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Organizzazione {

    const POST_TYPE = 'organizzazione';

    public static function register() {

        $labels = array(
            'name'               => __( 'Organizzazioni', 'socialmarket' ),
            'singular_name'      => __( 'Organizzazione', 'socialmarket' ),
            'menu_name'          => __( 'Organizzazioni', 'socialmarket' ),
            'add_new'            => __( 'Aggiungi nuova', 'socialmarket' ),
            'add_new_item'       => __( 'Aggiungi Organizzazione', 'socialmarket' ),
            'edit_item'          => __( 'Modifica Organizzazione', 'socialmarket' ),
            'new_item'           => __( 'Nuova Organizzazione', 'socialmarket' ),
            'view_item'          => __( 'Visualizza Organizzazione', 'socialmarket' ),
            'search_items'       => __( 'Cerca Organizzazioni', 'socialmarket' ),
            'not_found'          => __( 'Nessuna Organizzazione trovata', 'socialmarket' ),
        );

        $args = array(
            'label'              => __( 'Organizzazioni', 'socialmarket' ),
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'supports'           => array( 'title', 'editor', 'thumbnail' ),
            'has_archive'        => true,
            'rewrite'            => array( 'slug' => 'organizzazioni' ),
        );

        register_post_type( self::POST_TYPE, $args );
    }
}
