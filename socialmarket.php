<?php
/**
 * Plugin Name: SocialMarket
 * Plugin URI: https://socialmarket.local
 * Description: Orchestratore GAS APS.
 * Version: 1.0.0
 * Author: Socialforge
 * Text Domain: socialmarket
 */

if (!defined('ABSPATH')) {
    exit;
}

define('SM_VERSION', '1.0.0');
define('SM_PLUGIN_FILE', __FILE__);
define('SM_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('SM_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once SM_PLUGIN_PATH . 'includes/class-sm-loader.php';
require_once SM_PLUGIN_PATH . 'includes/class-sm-installer.php';

register_activation_hook(
    __FILE__,
    ['SM_Installer', 'activate']
);

SM_Loader::init();
