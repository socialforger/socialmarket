<?php

if (!defined('ABSPATH')) {
    exit;
}

class SM_Delivery
{
    const PLANNED      = 'planned';
    const OPEN         = 'open';
    const CLOSED       = 'closed';
    const PICKING      = 'picking';
    const LOADED       = 'loaded';
    const IN_DELIVERY  = 'in_delivery';
    const COMPLETED    = 'completed';
    const CANCELLED    = 'cancelled';

    public static function init()
    {
    }

    public static function create(
        $producer_id,
        $delivery_date
    ) {

        SM_DB::insert(
            'sm_delivery',
            [
                'producer_id'  => $producer_id,
                'delivery_date'=> $delivery_date,
                'status'       => self::PLANNED,
                'created_at'   => sm_now()
            ]
        );
    }

    public static function set_status(
        $delivery_id,
        $status
    ) {

        SM_DB::update(
            'sm_delivery',
            [
                'status' => $status
            ],
            [
                'id' => $delivery_id
            ]
        );

        SM_State_Machine::transition(
            'delivery',
            $delivery_id,
            $status
        );
    }
}
