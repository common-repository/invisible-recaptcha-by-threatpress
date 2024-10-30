<?php

/**
 * The main admin class.
 */
class ThreatPress_Recaptcha_Admin_Main {

    /** The page identifier used in WordPress to register the admin page */
    const PAGE_IDENTIFIER = 'threatpress_recaptcha_settings';

    /**
     * Holds the options
     *
     * @var array
     */
    private $options;

    /**
     * Class constructor
     */
    public function __construct() {

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        add_action( 'admin_menu', array( $this, 'admin_pages' ), 10 );


    }

    /**
     * Register the menu item and its sub menu's.
     *
     * @global array $submenu used to change the label on the first item.
     */
    public function admin_pages() {

        if ( function_exists( 'threatpress_init' ) ) {
            add_submenu_page('threatpress_dashboard', 'ThreatPress: ' . __('Invisible reCAPTCHA', 'og'), __('Invisible reCAPTCHA', 'og'), 'manage_options', 'threatpress_recaptcha_settings', array($this, 'load_page'));
        } else {
            add_submenu_page('tools.php', 'ThreatPress: ' . __('Invisible reCAPTCHA', 'og'), __('Invisible reCAPTCHA', 'og'),
                'manage_options', 'threatpress_recaptcha_settings', array($this, 'load_page'));
        }
    }

    /**
     * Load the form for a ThreatPress admin page
     */
    public function load_page() {
        require_once( THREATPRESS_RECAPTCHA_PLUGIN_DIR . 'admin/pages/settings.php' );
    }

    /**
     * Load CSS and JavaScript files
     */
    public function admin_enqueue_scripts() {
        global $pagenow;

        $page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

        $page = ( isset( $page ) ) ? $page : '';

        if ( strpos( $page, 'threatpress_recaptcha_' ) === false && $pagenow !== 'admin.php' )
            return;

        wp_enqueue_style( 'threatpress-recaptcha-admin', THREATPRESS_RECAPTCHA_PLUGIN_URL . 'admin/assets/css/threatpress-admin.css' );
        wp_enqueue_script( 'threatpress-recaptcha-admin', THREATPRESS_RECAPTCHA_PLUGIN_URL . 'admin/assets/js/threatpress-admin.js' );
    }

}