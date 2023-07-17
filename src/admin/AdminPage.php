<?php

namespace ObiLearndashCustomCertificateFileName\Admin;

if (!defined('ABSPATH')) {
    exit('Unauthorized access');
}

class AdminPage
{
    private static $instance;

    private function __construct()
    {
        add_action('admin_menu', array($this, 'registerOptionsPage'));
        add_action('admin_enqueue_scripts', array($this, 'obi_enqueue_admin_scripts'));
    }

    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function registerOptionsPage()
    {
        add_menu_page(
            __('Obi LD Certs', OBI_LEARNDASH_CUSTOM_CERTIFICATE_FILE_NAME__TEXTDOMAIN),
            __('Obi LearnDash Custom Certificate File Name', OBI_LEARNDASH_CUSTOM_CERTIFICATE_FILE_NAME__TEXTDOMAIN),
            'manage_options',
            'obi-learndash-custom-certificate-file-name',
            array($this, 'obi_options_page_callback')
        );
    }

    public function obi_options_page_callback()
    {
        echo '<div id="obi-learndash-custom-certificate-file-name-options"></div>';
    }

    public function obi_enqueue_admin_scripts()
    {
        wp_enqueue_script('obi-ld-cert-options-scripts', OBI_LEARNDASH_CUSTOM_CERTIFICATE_FILE_NAME_URL . 'dist/js/obi-ld-cert-admin-options.js', array('wp-element', 'wp-api'), '1.0.0', true);

        wp_localize_script('obi-ld-cert-options-scripts', 'obiLDOptions', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_rest'),
            'root' => esc_url_raw(rest_url()),
        ));
    }
}
