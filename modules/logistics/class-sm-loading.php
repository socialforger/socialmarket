<?php

if (!defined('ABSPATH')) {
    exit;
}

class SM_Loading
{
    public static function start(
        $delivery_id
    ) {

        SM_Delivery::set_status(
            $delivery_id,
            SM_Delivery::LOADED
        );

    }
}
