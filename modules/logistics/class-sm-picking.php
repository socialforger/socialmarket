<?php

if (!defined('ABSPATH')) {
    exit;
}

class SM_Picking
{
    public static function generate(
        $delivery_id
    ) {

        do_action(
            'sm_generate_picking',
            $delivery_id
        );

    }
}
