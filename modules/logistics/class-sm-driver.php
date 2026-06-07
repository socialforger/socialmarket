<?php

if (!defined('ABSPATH')) {
    exit;
}

class SM_Driver
{
    public static function assign(
        $delivery_id,
        $user_id
    ) {

        update_post_meta(
            $delivery_id,
            '_sm_driver',
            $user_id
        );

    }
}
