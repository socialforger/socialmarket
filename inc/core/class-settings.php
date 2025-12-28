<?php
namespace Social_Market;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Settings {

    const OPTION_KEY = 'socialmarket_settings';

    public static function init() {
        add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
    }

    public static function register_settings() {

        register_setting(
            'socialmarket_settings_group',
            self::OPTION_KEY,
            array(
                'type'              => 'array',
                'sanitize_callback' => array( __CLASS__, 'sanitize' ),
                'default'           => array(),
            )
        );

        add_settings_section(
            'socialmarket_main_section',
            __( 'Impostazioni Generali', 'socialmarket' ),
            '__return_false',
            'socialmarket'
        );

        add_settings_field(
            'association_name',
            __( 'Nome Associazione', 'socialmarket' ),
            array( __CLASS__, 'field_association_name' ),
            'socialmarket',
            'socialmarket_main_section'
        );
    }

    public static function sanitize( $input ) {
        $output = array();

        $output['association_name'] = sanitize_text_field( $input['association_name'] ?? '' );

        return $output;
    }

    public static function field_association_name() {
        $options = get_option( self::OPTION_KEY );
        $value   = $options['association_name'] ?? '';
        ?>
        <input type="text" name="<?php echo self::OPTION_KEY; ?>[association_name]"
               value="<?php echo esc_attr( $value ); ?>" class="regular-text" />
        <?php
    }
}
