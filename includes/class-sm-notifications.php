<?php

if (!defined('ABSPATH')) {
    exit;
}

class SM_Notifications
{
    public static function email(
        $to,
        $subject,
        $message
    ) {
        return wp_mail(
            $to,
            $subject,
            $message
        );
    }
}
