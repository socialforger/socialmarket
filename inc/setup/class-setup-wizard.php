<?php
namespace Social_Market\Setup;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Setup_Wizard {

    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'add_wizard_page' ) );
    }

    /**
     * Add hidden admin page for the wizard.
     */
    public static function add_wizard_page() {

        add_submenu_page(
            null, // hidden page
            __( 'Setup Social Market', 'socialmarket' ),
            __( 'Setup Social Market', 'socialmarket' ),
            'manage_options',
            'socialmarket-setup',
            array( __CLASS__, 'render_wizard' )
        );
    }

    /**
     * Render wizard UI.
     */
    public static function render_wizard() {

        $step = isset( $_GET['step'] ) ? sanitize_text_field( $_GET['step'] ) : 'welcome';

        echo '<div class="wrap sm-setup-wizard">';

        switch ( $step ) {

            case 'welcome':
                self::step_welcome();
                break;

            case 'gas':
                self::step_gas();
                break;

            case 'finish':
                self::step_finish();
                break;
        }

        echo '</div>';
    }

    /**
     * Step 1 — Welcome
     */
    private static function step_welcome() {
        ?>
        <h1><?php _e( 'Benvenuto in Social Market', 'socialmarket' ); ?></h1>
        <p><?php _e( 'Configuriamo insieme la tua piattaforma.', 'socialmarket' ); ?></p>

        <a href="<?php echo admin_url( 'admin.php?page=socialmarket-setup&step=gas' ); ?>"
           class="button button-primary">
            <?php _e( 'Inizia', 'socialmarket' ); ?>
        </a>
        <?php
    }

    /**
     * Step 2 — Configurazione GAS
     */
    private static function step_gas() {
        ?>
        <h1><?php _e( 'Configura il tuo primo GAS', 'socialmarket' ); ?></h1>

        <p><?php _e( 'Puoi aggiungere il tuo primo Gruppo di Acquisto ora o farlo più tardi.', 'socialmarket' ); ?></p>

        <a href="<?php echo admin_url( 'post-new.php?post_type=gas' ); ?>"
           class="button">
            <?php _e( 'Crea un GAS', 'socialmarket' ); ?>
        </a>

        <a href="<?php echo admin_url( 'admin.php?page=socialmarket-setup&step=finish' ); ?>"
           class="button button-primary">
            <?php _e( 'Continua', 'socialmarket' ); ?>
        </a>
        <?php
    }

    /**
     * Step 3 — Fine
     */
    private static function step_finish() {
        ?>
        <h1><?php _e( 'Setup completato!', 'socialmarket' ); ?></h1>

        <p><?php _e( 'La tua piattaforma Social Market è pronta.', 'socialmarket' ); ?></p>

        <a href="<?php echo admin_url( 'admin.php?page=socialmarket' ); ?>"
           class="button button-primary">
            <?php _e( 'Vai alla Dashboard', 'socialmarket' ); ?>
        </a>
        <?php
    }
}
