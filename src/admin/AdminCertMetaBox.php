<?php

namespace ObiLearndashCustomCertificateFileName\Admin;

class AdminCertMetaBox {
    private static $instance = null;

    private function __construct() {
        add_action('add_meta_boxes', array($this, 'add'));
        //add_action('enqueue_block_editor_assets', array($this, 'enqueue_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new AdminCertMetaBox();
        }

        return self::$instance;
    }

    public function add() {
        add_meta_box('my-meta-box-id', 'Obi Custom Certificate Name', array($this, 'display'), 'sfwd-certificates', 'normal', 'high');
    }

    public function display() {
        echo '<div id="obi-custom-meta-box-id"></div>';
    }

    public function enqueue_assets() {
        $screen = get_current_screen();
        
        /*
        echo '<pre>';
        var_dump($screen);
        echo '</pre>';
        //exit();
        */
        if ($screen->id === 'sfwd-certificates') {
            //exit ('we are on condition');
            if (wp_script_is('wp-blocks', 'enqueued')) {
                wp_enqueue_script('obi-ld-cert-meta-box-ui', OBI_LEARNDASH_CUSTOM_CERTIFICATE_FILE_NAME_URL . 'dist/js/obi-meta-box-ui.js', array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-api', 'wp-edit-post', 'wp-data'), '1.0.0', true);
            }            
        }
    }
}

