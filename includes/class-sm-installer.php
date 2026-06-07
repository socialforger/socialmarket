<?php

if (!defined('ABSPATH')) {
    exit;
}

class SM_Installer
{
    public static function activate()
    {
        self::create_tables();

        flush_rewrite_rules();
    }

    private static function create_tables()
    {
        global $wpdb;

        require_once ABSPATH .
            'wp-admin/includes/upgrade.php';

        $charset = $wpdb->get_charset_collate();

        $tables = [];

        $tables[] = "
        CREATE TABLE {$wpdb->prefix}sm_delivery (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            producer_id BIGINT UNSIGNED NOT NULL,
            delivery_date DATE NULL,
            status VARCHAR(50) NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id)
        ) $charset;
        ";

        $tables[] = "
        CREATE TABLE {$wpdb->prefix}sm_sgs (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NOT NULL,
            points INT NOT NULL,
            reason VARCHAR(255),
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id)
        ) $charset;
        ";

        $tables[] = "
        CREATE TABLE {$wpdb->prefix}sm_fund (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            type VARCHAR(50),
            amount DECIMAL(12,2),
            notes TEXT,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id)
        ) $charset;
        ";

        foreach ($tables as $sql) {
            dbDelta($sql);
        }
    }
}
