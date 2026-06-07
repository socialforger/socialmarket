<?php

if (!defined('ABSPATH')) {
    exit;
}

class SM_Loader
{
    public static function init()
    {
        self::load_core();
        self::load_integrations();
        self::load_modules();
    }

    private static function load_core()
    {
        require_once SM_PLUGIN_PATH . 'includes/class-sm-db.php';
        require_once SM_PLUGIN_PATH . 'includes/class-sm-state-machine.php';
        require_once SM_PLUGIN_PATH . 'includes/class-sm-notifications.php';
        require_once SM_PLUGIN_PATH . 'includes/functions.php';
    }

    private static function load_integrations()
    {
        require_once SM_PLUGIN_PATH . 'integrations/class-sm-woocommerce.php';
        require_once SM_PLUGIN_PATH . 'integrations/class-sm-wallet.php';
        require_once SM_PLUGIN_PATH . 'integrations/class-sm-membership.php';
        require_once SM_PLUGIN_PATH . 'integrations/class-sm-myaccount.php';
        require_once SM_PLUGIN_PATH . 'integrations/class-sm-mailerpress.php';
        require_once SM_PLUGIN_PATH . 'integrations/class-sm-wp-esg.php';
    }

    private static function load_modules()
    {
        $modules = [
            'onboarding',
            'logistics',
            'producer',
            'pgs',
            'sgs',
            'fund',
            'food-recovery'
        ];

        foreach ($modules as $module) {

            $bootstrap = SM_PLUGIN_PATH .
                "modules/{$module}/bootstrap.php";

            if (file_exists($bootstrap)) {
                require_once $bootstrap;
            }
        }
    }
}
