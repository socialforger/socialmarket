<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SM_Configuration {

    private static $province_cache = null;
    private static $dependencies_cache = null;

    /**
     * Inizializzatore statico: fa partire l'orchestrazione e i controlli di sicurezza
     */
    public static function init() {
        // Avvia la verifica dei requisiti di sistema nell'area amministrativa di WordPress
        add_action( 'admin_init', array( __CLASS__, 'verifica_requisiti_di_sistema' ) );
        
        // Pre-carica le dipendenze in memoria all'avvio del plugin
        self::carica_dependencies_da_json();
    }

    /**
     * 📁 PARSER PROVINCE: Legge data/province.json una sola volta per sessione
     * @return array Le 110 province italiane (Sigla => Nome)
     */
    public static function get_province_attive() {
        if ( self::$province_cache !== null ) {
            return self::$province_cache;
        }

        $json_path = plugin_dir_path( dirname( __FILE__ ) ) . 'data/province.json';
        if ( file_exists( $json_path ) ) {
            $json_data = file_get_contents( $json_path );
            self::$province_cache = json_decode( $json_data, true );
        }

        if ( ! is_array( self::$province_cache ) ) {
            self::$province_cache = array(); // Fallback di sicurezza se il file è corrotto
        }

        return self::$province_cache;
    }

    /**
     * 📁 PARSER DIPENDENZE: Legge data/dependencies.json e mappa lo stato dei 21 plugin operativi
     */
    private static function carica_dependencies_da_json() {
        if ( self::$dependencies_cache !== null ) {
            return self::$dependencies_cache;
        }

        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $json_path = plugin_dir_path( dirname( __FILE__ ) ) . 'data/dependencies.json';
        if ( file_exists( $json_path ) ) {
            $json_data = file_get_contents( $json_path );
            $raw_deps = json_decode( $json_data, true );

            if ( is_array( $raw_deps ) ) {
                self::$dependencies_cache = array();
                foreach ( $raw_deps as $file => $info ) {
                    self::$dependencies_cache[$file] = array(
                        'name'     => $info['name'],
                        'required' => isset( $info['required'] ) ? $info['required'] : false,
                        'active'   => is_plugin_active( $file )
                    );
                }
            }
        }

        if ( ! is_array( self::$dependencies_cache ) ) {
            self::$dependencies_cache = array();
        }

        return self::$dependencies_cache;
    }

    /**
     * 🌐 API DI ORCHESTRAZIONE: Verifica al volo se un plugin dello stack è attivo
     * Utilizzabile ovunque nel codice (es. if ( SM_Configuration::is_plugin_ready('wp-esg/wp-esg.php') ))
     */
    public static function is_plugin_ready( $plugin_file ) {
        self::carica_dependencies_da_json();
        if ( isset( self::$dependencies_cache[$plugin_file] ) ) {
            return self::$dependencies_cache[$plugin_file]['active'];
        }
        return false;
    }

    /**
     * ⚙️ ENGINE DI SICUREZZA: Blocca l'esecuzione se mancano i pilastri (WooCommerce o Wallet)
     */
    public static function verifica_requisiti_di_sistema() {
        self::carica_dependencies_da_json();
        $bloccanti_mancanti = array();

        foreach ( self::$dependencies_cache as $file => $info ) {
            if ( $info['required'] && ! $info['active'] ) {
                $bloccanti_mancanti[] = $info['name'];
            }
        }

        if ( ! empty( $bloccanti_mancanti ) ) {
            add_action( 'admin_notices', function() use ( $bloccanti_mancanti ) {
                $lista = '<li>' . implode( '</li><li>', array_map( 'esc_html', $mancanti ) ) . '</li>';
                echo '<div class="notice notice-error">';
                echo '<p><strong>🚨 CRITICO - Social Market Engine:</strong> Orchestrazione sospesa per prevenire Fatal Error. Attiva i pilastri strutturali del mercato:</p>';
                echo '<ul>' . $lista . '</ul>';
                echo '</div>';
            } );
        }
    }
}

// Inizializza immediatamente la configurazione all'inclusione del file
SM_Configuration::init();
