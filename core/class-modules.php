<?php

/**
 * Loads the modules.
 */
class ThreatPress_Recaptcha_Core_Modules {

    /**
     * Class constructor
     */
    public function __construct() {
        self::load_modules();
    }

    private static function load_modules() {
        $options = ThreatPress_Recaptcha_Admin_Options::get_all();

        new ThreatPress_Recaptcha_Module_Core( $options );

    }

}