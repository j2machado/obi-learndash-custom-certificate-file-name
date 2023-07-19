<?php

namespace ObiLearndashCustomCertificateFileName;

use WP_User;

class CertificateName {

    private static $instance = null;

    private function __construct() {
        add_action('updated_option', array($this, 'onOptionUpdate'), 10, 3);
        add_filter('learndash_certificate_builder_pdf_name', array($this, 'filterCertificateName'), 10, 2);
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new CertificateName();
        }

        return self::$instance;
    }

    public function onOptionUpdate($option, $old_value, $new_value){
        if ($option === 'obiLDCert_filename_global_setting') {
            error_log('obiLDCert_filename_global_setting updated. New value: ' . print_r($new_value, true)); // Log the new value

            if (empty($new_value)) {
                error_log('New value is empty. Not updating the certificate filename.');
                return;
            }

            $custom_filename = $this->buildName();

            error_log('Newly built filename: ' . $custom_filename); // Log the new filename

            $updated = update_option('obiLDCert_filename_custom_name', $custom_filename . '.pdf');

            if ($updated) {
                error_log('obiLDCert_filename_custom_name successfully updated.');
            } else {
                error_log('Failed to update obiLDCert_filename_custom_name.');
            }
        }
    }

    public function filterCertificateName($default_filename, $context){
        $custom_filename = get_option('obiLDCert_filename_custom_name', '');

        return !empty($custom_filename) ? $custom_filename : $default_filename;
    }

    public function buildName() {
        $options = get_option('obiLDCert_filename_global_setting', '');
        $options = json_decode($options, true);

        if(!is_array($options)) {
            error_log("Decoded options is not an array. Something went wrong.");
            return '';
        }

        $builtString = '';
        foreach($options as $option) {
            switch ($option['value']) {
                case 'option1':
                    $builtString .= $this->getCourseName();
                    break;
                case 'option6':
                    $currentUser = wp_get_current_user();
                    if (!($currentUser instanceof WP_User)) {
                        break;
                    }
                    $builtString .= $currentUser->display_name;
                    break;
                default:
                    break;
            }
        }

        return $builtString;
    }

    private function getCourseName(){
        // Some logic to fetch course name
        return "Some Course";
    }
}
