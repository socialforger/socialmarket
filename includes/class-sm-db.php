<?php

if (!defined('ABSPATH')) {
    exit;
}

class SM_DB
{
    public static function insert(
        $table,
        array $data
    ) {
        global $wpdb;

        return $wpdb->insert(
            $wpdb->prefix . $table,
            $data
        );
    }

    public static function update(
        $table,
        array $data,
        array $where
    ) {
        global $wpdb;

        return $wpdb->update(
            $wpdb->prefix . $table,
            $data,
            $where
        );
    }

    public static function get(
        $table,
        $id
    ) {
        global $wpdb;

        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}{$table}
                 WHERE id = %d",
                $id
            )
        );
    }
}
