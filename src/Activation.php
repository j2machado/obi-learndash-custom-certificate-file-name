<?php

namespace ObiLearndashCustomCertificateFileName;

if (!defined('ABSPATH')) {
    exit('Unauthorized access');
}

class Activation
{
    private static $instance;

    private function __construct()
    {
        // Silence is golden...
    }

    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function obi_register_meta()
    {
        register_post_meta('sfwd-certificates', 'obi_custom_meta_box_select', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string', // changed type to 'string'
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            }
        ));

        register_post_meta('sfwd-certificates', 'obi_custom_meta_box_use_global', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            }
        ));
    }
}
