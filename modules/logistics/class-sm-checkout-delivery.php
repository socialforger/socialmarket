<?php

if (!defined('ABSPATH')) {
    exit;
}

class SM_Checkout_Delivery
{
    public static function init()
    {
        add_action(
            'woocommerce_review_order_before_payment',
            [self::class,'field']
        );

        add_action(
            'woocommerce_checkout_create_order',
            [self::class,'save'],
            10,
            2
        );
    }

    public static function field()
    {
        ?>

        <div id="sm-delivery-type">

            <h3>Consegna</h3>

            <p>
                <label>
                    <input
                        type="radio"
                        name="sm_delivery_type"
                        value="collective"
                        checked
                    >
                    Consegna collettiva
                </label>
            </p>

            <p>
                <label>
                    <input
                        type="radio"
                        name="sm_delivery_type"
                        value="individual"
                    >
                    Consegna individuale
                </label>
            </p>

        </div>

        <?php
    }

    public static function save(
        $order,
        $data
    ) {

        if (
            !empty($_POST['sm_delivery_type'])
        ) {

            $order->update_meta_data(
                '_sm_delivery_type',
                sanitize_text_field(
                    $_POST['sm_delivery_type']
                )
            );

        }
    }
}
