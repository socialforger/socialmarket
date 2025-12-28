<?php
namespace Social_Market\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Admin_Columns {

    public static function init() {
        add_filter( 'manage_gas_posts_columns', array( __CLASS__, 'gas_columns' ) );
        add_action( 'manage_gas_posts_custom_column', array( __CLASS__, 'gas_column_content' ), 10, 2 );
    }

    public static function gas_columns( $columns ) {
        $columns['members'] = __( 'Membri', 'socialmarket' );
        return $columns;
    }

    public static function gas_column_content( $column, $post_id ) {
        if ( $column === 'members' ) {
            echo '—'; // Placeholder
        }
    }
}
