<?php

/**
 * Plugin Name: Obi LearnDash Custom Certificate File Name
 * Description: Change the LearnDash Certificates filename to match your needs.
 * Version: 1.0.0
 * Author: Obi Juan
 * Author URI: https://obijuan.dev
 * Plugin URI: https://obijuan.dev/obi-learndash-custom-certificates-file-name
 * License: GPL2 or later
 * Textdomain: obi-learndash-custom-certificate-file-name
 * @since 1.0.0 
 */

if (!defined('ABSPATH')) {
    exit('Unauthorized access');
}

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

use ObiLearndashCustomCertificateFileName\Admin\AdminCertMetaBox;
use ObiLearndashCustomCertificateFileName\Admin\AdminPage;
use ObiLearndashCustomCertificateFileName\Activation;
use ObiLearndashCustomCertificateFileName\CertificateName;

final class Obi_LD_Cert_Init
{
    private static $instance;

    private function __construct()
    {
        self::define_constants();
    }

    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private static function define_constants()
    {
        define('OBI_LEARNDASH_CUSTOM_CERTIFICATE_FILE_NAME_VERSION', '1.0.0');
        define('OBI_LEARNDASH_CUSTOM_CERTIFICATE_FILE_NAME__TEXTDOMAIN', 'obi-learndash-custom-certificate-file-name');
        define('OBI_LEARNDASH_CUSTOM_CERTIFICATE_FILE_NAME_DIRNAME', plugin_basename(dirname(__FILE__)));
        define('OBI_LEARNDASH_CUSTOM_CERTIFICATE_FILE_NAME_FILE', __FILE__);
        define('OBI_LEARNDASH_CUSTOM_CERTIFICATE_FILE_NAME_PREFIX', 'obi_learndash_custom_certificate_file_name');
        define('OBI_LEARNDASH_CUSTOM_CERTIFICATE_FILE_NAME_PATH', plugin_dir_path(OBI_LEARNDASH_CUSTOM_CERTIFICATE_FILE_NAME_FILE));
        define('OBI_LEARNDASH_CUSTOM_CERTIFICATE_FILE_NAME_URL', plugin_dir_url(OBI_LEARNDASH_CUSTOM_CERTIFICATE_FILE_NAME_FILE));
    }

    public static function load_obi_plugin()
    {
        AdminPage::get_instance();
        AdminCertMetaBox::getInstance();
        Activation::get_instance();
        CertificateName::getInstance();

    }

    public static function activate()
    {

        Activation::obi_register_meta();

        //exit('Should work');

    }

    public static function deactivate() {}
}

$obi_plugin = Obi_LD_Cert_Init::get_instance();

register_activation_hook(OBI_LEARNDASH_CUSTOM_CERTIFICATE_FILE_NAME_FILE, array($obi_plugin, 'activate'));
add_action('plugins_loaded', array($obi_plugin, 'load_obi_plugin'));
