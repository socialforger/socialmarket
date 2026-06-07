<?php

if (!defined('ABSPATH')) {
    exit;
}

class SM_State_Machine
{
    public static function transition(
        $entity,
        $id,
        $new_state
    ) {

        do_action(
            'sm_before_transition',
            $entity,
            $id,
            $new_state
        );

        do_action(
            "sm_{$entity}_state_changed",
            $id,
            $new_state
        );

        do_action(
            'sm_after_transition',
            $entity,
            $id,
            $new_state
        );
    }
}
