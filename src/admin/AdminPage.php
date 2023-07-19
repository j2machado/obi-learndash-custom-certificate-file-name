<?php

namespace ObiLearndashCustomCertificateFileName\Admin;

use WP_REST_Request;
use WP_REST_Response;

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
        add_action('rest_api_init', array(__CLASS__, 'obi_register_rest_route'));

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
        wp_enqueue_script('obi-ld-cert-options-scripts', OBI_LEARNDASH_CUSTOM_CERTIFICATE_FILE_NAME_URL . 'dist/js/obi-options.js', array('wp-element', 'wp-api'), '1.0.0', true);

        wp_localize_script('obi-ld-cert-options-scripts', 'obiLDOptions', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_rest'),
            'root' => esc_url_raw(rest_url()),
        ));
    }
    public static function obi_register_rest_route()
    {
        register_rest_route('obi-ld-cert/v1', '/settings', array(
            'methods' => 'GET,POST',
            'callback' => array('ObiLearndashCustomCertificateFileName\Admin\AdminPage', 'handle_settings_endpoint'),
            'permission_callback' => function() {
                return current_user_can('manage_options'); // ensure user has admin capability
            }
        ));
    }

    public static function handle_settings_endpoint(WP_REST_Request $request)
{
    // Add a nonce check
    $headers = getallheaders();
    if (!isset($headers['X-WP-Nonce']) || !wp_verify_nonce($headers['X-WP-Nonce'], 'wp_rest')) {
        return new WP_REST_Response('Invalid nonce', 403);
    }

    if ($request->get_method() === 'GET') {
        // Get options from the database
        $options = get_option('obiLDCert_filename_global_setting', '');

        // Decode the JSON back to an object format
        $options = json_decode($options, true);

        // If no options or decoding failed, default to an empty array
        if (!is_array($options)) {
            $options = array();
        }

        return new WP_REST_Response($options, 200);
    } else if ($request->get_method() === 'POST') {
        $options = $request->get_json_params();

        // Save the options as JSON
        update_option('obiLDCert_filename_global_setting', json_encode($options));

        return new WP_REST_Response(null, 200);
    }
}



}
