<?php

namespace ObiLearndashCustomCertificateFileName;

use WP_User;
use WP_REST_Request;
use WP_Error;
use WP_REST_Response;

class CertificateName {

    private static $instance = null;

    private function __construct() {
        add_action('updated_option', array($this, 'onOptionUpdate'), 10, 3);
        add_action('updated_option', array($this, 'onSeparatorUpdate'), 10, 3); // Added line
        add_filter('learndash_certificate_builder_pdf_name', array($this, 'filterCertificateName'), 10, 2);
        add_action('rest_api_init', array($this, 'register_api_endpoints'));
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


    public function onSeparatorUpdate($option, $old_value, $new_value){
    if ($option === 'obiLDCert_filename_separator') {
        error_log('obiLDCert_filename_separator updated. New value: ' . print_r($new_value, true)); // Log the new value

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
    
        // Fetch separator from options. Default to space if not set.
        $separator = get_option('obiLDCert_filename_separator', ' ');
    
        if(!is_array($options)) {
            error_log("Decoded options is not an array. Something went wrong.");
            return '';
        }
    
        $builtString = '';
        foreach($options as $option) {
            if(isset($option['__isNew__']) && $option['__isNew__']) {
                $builtString .= $option['value'] . $separator;
            } else {
                switch ($option['value']) {
                    case 'option1':
                        $builtString .= $this->getCourseName() . $separator;
                        break;
                    case 'option6':
                        $currentUser = wp_get_current_user();
                        if (!($currentUser instanceof WP_User)) {
                            break;
                        }
                        $builtString .= $currentUser->display_name . $separator;
                        break;
                    default:
                        break;
                }
            }
        }
    
        // Remove trailing separator
        $builtString = rtrim($builtString, $separator);
    
        return $builtString;
    }
    
    

    private function getCourseName(){
        // Some logic to fetch course name
        return "Some Course";
    }

    public function register_api_endpoints() {
        register_rest_route( 'obi-ld-cert/v1', '/separator', array(
            'methods'  => 'POST',
            'callback' => array($this, 'save_separator'),
            'permission_callback' => function() {
                return current_user_can( 'manage_options' );
            },
        ));

        register_rest_route( 'obi-ld-cert/v1', '/separator', array(
            'methods'  => 'GET',
            'callback' => array($this, 'get_separator'),
            'permission_callback' => function() {
              return current_user_can( 'manage_options' );
            },
          ));
          
    }

    public function save_separator(WP_REST_Request $request) {
        if ( ! $request->has_param( 'separator' ) ) {
            return new WP_Error( 'invalid_request', 'Missing separator parameter', array( 'status' => 400 ) );
        }

        $separator = $request->get_param( 'separator' );

        update_option( 'obiLDCert_filename_separator', $separator );

        return new WP_REST_Response( 'Separator saved successfully', 200 );
    }

    public function get_separator() {
        $separator = get_option( 'obiLDCert_filename_separator', ' ' );
        return new WP_REST_Response( $separator, 200 );
      }
      

}
