<?php
/**
 * Plugin Name: Social Market Core
 * Description: Architettura modulare disaccoppiata con System Health Check integrato per il controllo dello stack MES.
 * Version: 1.4.1
 * Author: MES Engineering
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class SM_Core_Orchestrator {

    private $missing_stack = array();

    public function __construct() {
        // Configurazione iniziale del database all'attivazione del plugin
        register_activation_hook( __FILE__, array( $this, 'run_activation_blueprint' ) );
        
        // Controllo dello stato di integrità del sistema al caricamento dei plugin
        add_action( 'plugins_loaded', array( $this, 'verify_system_integrity' ), 5 );
        
        // Se siamo nel pannello amministrativo, riallinea forzatamente le opzioni contro modifiche manuali errate
        if ( is_admin() ) {
            add_action( 'admin_init', array( $this, 'run_activation_blueprint' ) );
        }
    }

    /**
     * Aggancia il file statico di configurazione e inietta le opzioni nel database
     */
    public function run_activation_blueprint() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/configuration.php';
        if ( class_exists( 'SM_Plugin_Configuration' ) ) {
            SM_Plugin_Configuration::run_activation_setup();
        }
    }

    /**
     * Controlla se lo stack è completo. Se mancano pezzi, si ferma e attiva l'allarme visivo.
     */
    public function verify_system_integrity() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/configuration.php';

        if ( class_exists( 'SM_Plugin_Configuration' ) ) {
            $this->missing_stack = SM_Plugin_Configuration::check_missing_stack_plugins();

            if ( ! empty( $this->missing_stack ) ) {
                // Allarme: componenti mancanti. Iniettiamo l'avviso nel backend.
                add_action( 'admin_notices', array( $this, 'render_integrity_failure_notice' ) );
                return; // INTERRUZIONE DI SICUREZZA: previene Fatal Error non caricando i moduli successivi
            }
        }

        // Se lo stack è integro al 100%, esegue il boot sicuro dei moduli operativi
        $this->bootstrap_modules();
    }

    /**
     * Stampa un box di errore critico nel pannello amministrativo di WordPress
     */
    public function render_integrity_failure_notice() {
        ?>
        <div class="notice notice-error is-dismissible" style="border-left-color: #dc3232; padding: 15px;">
            <p style="font-size: 15px; margin: 0 0 10px 0; font-weight: bold; color: #b52828;">
                ⚠️ ARCHITETTURA INCOMPLETA — Il plugin "Social Market Core" è in modalità provvisoria.
            </p>
            <p style="margin: 0 0 10px 0;">
                I seguenti componenti fondamentali dello stack tecnologico sono disattivi o non installati. 
                Attivali per ripristinare il corretto funzionamento del circuito economico e della cassa:
            </p>
            <ul style="list-style: square; margin-left: 20px; font-family: monospace; background: #fff; padding: 10px; border: 1px solid #ccd0d4; display: inline-block;">
                <?php foreach ( array_keys( $this->missing_stack ) as $slug ) : ?>
                    <li><strong><?php echo esc_html( $slug ); ?></strong></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }

    /**
     * Boot protetto dei motori di business (verranno agganciati nei prossimi step)
     */
    public function bootstrap_modules() {
        if ( file_exists( plugin_dir_path( __FILE__ ) . 'includes/onboarding.php' ) ) {
            require_once plugin_dir_path( __FILE__ ) . 'includes/onboarding.php';
        }
        if ( file_exists( plugin_dir_path( __FILE__ ) . 'includes/checkout-firewall.php' ) ) {
            require_once plugin_dir_path( __FILE__ ) . 'includes/checkout-firewall.php';
        }
        if ( file_exists( plugin_dir_path( __FILE__ ) . 'includes/producer-engine.php' ) ) {
            require_once plugin_dir_path( __FILE__ ) . 'includes/producer-engine.php';
        }
    }
}

new SM_Core_Orchestrator();
