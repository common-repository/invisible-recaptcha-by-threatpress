<?php

/**
 * Performs the load on admin side.
 */
class ThreatPress_Recaptcha_Admin_Init {

    /**
     * Holds the options
     *
     * @var array
     */
    private $options;

    /**
     * Class constructor
     */
    function __construct() {
        $this->options = ThreatPress_Recaptcha_Admin_Options::get_option( 'threatpress_recaptcha_recaptcha' );

        $GLOBALS['threatpress_recaptcha_recaptcha_admin'] = new ThreatPress_Recaptcha_Admin_Main();

        add_action( 'admin_init', array( $this, 'admin_init' ) );
    }

    // Admin init
    function admin_init() {

    }

}