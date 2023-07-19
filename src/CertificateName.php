<?php

namespace ObiLearndashCustomCertificateFileName;

class CertificateName {

    private static $instance = null;

    private function __construct() {
        
        add_action('init', array(__CLASS__, 'buildName'));


    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new CertificateName();
        }

        return self::$instance;
    }

    public static function buildName(){
        $options = get_option('obiLDCert_filename_global_setting', '');

        // Decode the JSON back to an array format
        $options = json_decode($options, true);

        /*

        echo '<pre>';
        var_dump($options[0]['value']);
        echo '</pre>';
        exit();
*/
    }

}