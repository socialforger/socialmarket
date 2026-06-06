<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SM_Plugin_Configuration {

    /**
     * Elenco dei plugin dello stack obbligatori per il funzionamento.
     */
    public static function get_required_stack_plugins() {
        return array(
            'woocommerce'                                         => 'woocommerce/woocommerce.php',
            'wallet-system-for-woocommerce'                       => 'wallet-system-for-woocommerce/wallet-system-for-woocommerce.php',
            'membership-for-woocommerce'                          => 'membership-for-woocommerce/membership-for-woocommerce.php',
            'login-me-now'                                        => 'login-me-now/login-me-now.php',
            'delivery-drivers-manager'                            => 'delivery-drivers-manager/delivery-drivers-manager.php',
            'local-delivery-drivers-for-woocommerce'              => 'local-delivery-drivers-for-woocommerce/local-delivery-drivers-for-woocommerce.php',
            'donation-platform-for-woocommerce'                   => 'donation-platform-for-woocommerce/donation-platform-for-woocommerce.php',
            'mailerpress'                                         => 'mailerpress/mailerpress.php',
            'order-picking-for-woocommerce'                       => 'order-picking-for-woocommerce/order-picking-for-woocommerce.php',
            'woocommerce-pdf-invoices-packing-slips'              => 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packing-slips.php',
            'woocommerce-pdf-invoices-italian-add-on'             => 'woocommerce-pdf-invoices-italian-add-on/woocommerce-pdf-invoices-italian-add-on.php',
            'powerfulwp-order-delivery-scheduler-for-woocommerce' => 'powerfulwp-order-delivery-scheduler-for-woocommerce/powerfulwp-order-delivery-scheduler-for-woocommerce.php',
            'wp-all-import'                                       => 'wp-all-import/wp-all-import.php',
            'woocommerce-xml-csv-product-import'                  => 'woocommerce-xml-csv-product-import/woocommerce-xml-csv-product-import.php',
            'wp-all-export'                                       => 'wp-all-export/wp-all-export.php',
            'wc-shipping-packages'                                => 'wc-shipping-packages/wc-shipping-packages.php',
            'wp-esg'                                              => 'wp-esg/wp-esg.php'
        );
    }

    /**
     * Scansione dello stack per rilevare plugin disattivi
     */
    public static function check_missing_stack_plugins() {
        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $missing_plugins = array();
        foreach ( self::get_required_stack_plugins() as $slug => $file_path ) {
            if ( ! is_plugin_active( $file_path ) ) {
                $missing_plugins[$slug] = $file_path;
            }
        }
        return $missing_plugins;
    }

    /**
     * CONFIGURAZIONE TOTALE DI WOOCOMMERCE E DEI PLUGIN PARTNER
     */
    public static function get_authoritative_options() {
        return array(
            'woocommerce_calc_taxes'                     => 'no',
            'woocommerce_currency'                       => 'EUR',
            'woocommerce_currency_pos'                   => 'right_space',
            'woocommerce_price_thousand_sep'             => '.',
            'woocommerce_price_decimal_sep'              => ',',
            'woocommerce_price_num_decimals'             => '2',
            'woocommerce_allowed_countries'              => 'specific',
            'woocommerce_specific_allowed_countries'     => array( 'IT' ),
            'woocommerce_ship_to_countries'              => 'all',
            'woocommerce_default_customer_address'       => 'base',
            'woocommerce_ship_to_destination'            => 'shipping',
            'woocommerce_enable_signup_and_login_from_checkout' => 'no', 
            'woocommerce_enable_myaccount_registration'          => 'no', 
            'woocommerce_registration_generate_username'        => 'yes',
            'woocommerce_registration_generate_password'        => 'yes',
            'woocommerce_logout_redirect_item'                  => 'home',
            'woocommerce_cart_redirect_after_add'        => 'no',
            'woocommerce_enable_ajax_add_to_cart'        => 'yes',
            'woocommerce_manage_stock'                   => 'yes',
            'woocommerce_hold_stock_minutes'             => '60',
            'woocommerce_notify_no_stock'                => 'yes',
            'woocommerce_notify_low_stock'               => 'yes',
            'woocommerce_notify_low_stock_amount'        => '2',
            'woocommerce_stock_display_format'           => 'always',
            'woocommerce_enable_reviews'                 => 'no',
            'woocommerce_enable_review_rating'           => 'no',
            'wallet_system_enable_gateway_charge'        => 'yes',
            'wcspp_configuration_settings' => array(
                'enabled'          => 'yes',
                'group_by'         => 'product_author',
                'package_title'    => 'Prodotti da: {package_name}',
                'merge_same_rates' => 'no'
            )
        );
    }

    /**
     * INIZIALIZZAZIONE FILTRI DI PROTEZIONE ACCESSI E DB
     */
    public static function init_authoritative_hooks() {
        // Intercettatori per escludere login e registrazioni native
        add_action( 'init', array( __CLASS__, 'redirect_wordpress_native_login' ) );
        add_action( 'template_redirect', array( __CLASS__, 'redirect_woocommerce_my_account_native' ) );
        add_filter( 'login_url', array( __CLASS__, 'override_native_login_url' ), 999, 3 );
    }

    /**
     * FIREWALL WP-LOGIN.PHP: Blocca e reindirizza a /accedi
     */
    public static function redirect_wordpress_native_login() {
        global $pagenow;
        if ( 'wp-login.php' === $pagenow && ! isset( $_REQUEST['action'] ) && ! isset( $_REQUEST['loggedout'] ) ) {
            wp_redirect( home_url( '/accedi/' ) );
            exit;
        }
    }

    /**
     * FIREWALL MY-ACCOUNT WOOCOMMERCE: Rimbalza gli utenti non loggati
     */
    public static function redirect_woocommerce_my_account_native() {
        if ( is_account_page() && ! is_user_logged_in() ) {
            wp_redirect( home_url( '/accedi/' ) );
            exit;
        }
    }

    /**
     * SOVRASCRITTURA URL DI LOGIN: Sostituisce i link di sistema generati dai widget o dai temi
     */
    public static function override_native_login_url( $login_url, $redirect, $force_reauth ) {
        return home_url( '/accedi/' );
    }

    /**
     * ESEGUE L'ALLINEAMENTO FORZATO DEI PARAMETRI DEL DATABASE
     */
    public static function run_activation_setup() {
        foreach ( self::get_authoritative_options() as $key => $value ) {
            update_option( $key, $value );
        }

        if ( ! get_role( 'local_driver' ) ) {
            add_role( 'local_driver', 'MES Autista Locale', array(
                'read'         => true,
                'edit_posts'   => false,
                'upload_files' => true
            ) );
        }
        flush_rewrite_rules();
    }
}
