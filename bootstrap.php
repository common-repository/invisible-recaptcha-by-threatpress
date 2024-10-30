<?php

/* ***************************** CLASS AUTOLOADING *************************** */

/**
 * Auto load admin class files
 *
 * @param string $class_name Class name.
 *
 * @return void
 */
function threatpress_recaptcha_autoload_admin( $class_name ) {

    /**
     * If the class being requested does not start with our prefix,
     * we know it's not one in our project
     */
    if ( 0 !== strpos( $class_name, 'ThreatPress_Recaptcha_Admin_' ) ) {
        return;
    }

    $file_name = str_replace( array('threatpress_recaptcha_admin_', '_'), array('class-', '-'), strtolower( $class_name ) );

    /*
     * Load admin and options classes
     */
    if ( strpos( $class_name, 'ThreatPress_Recaptcha_Admin_Form_Option_' ) === false ) {
        // Admin classes path
        $file = dirname(THREATPRESS_RECAPTCHA_PLUGIN_FILE) . '/admin/' . $file_name . '.php';
    } else {
        // Options classes path
        $file = dirname(THREATPRESS_RECAPTCHA_PLUGIN_FILE) . '/admin/options/' . $file_name . '.php';
    }

    if ( file_exists( $file ) ) {
        require( $file );
    }
}

if ( function_exists( 'spl_autoload_register' ) ) {
    spl_autoload_register( 'threatpress_recaptcha_autoload_admin' );
}

/**
 * Auto load core class files
 *
 * @param string $class_name Class name.
 *
 * @return void
 */
function threatpress_recaptcha_autoload_core( $class_name ) {

    /**
     * If the class being requested does not start with our prefix,
     * we know it's not one in our project
     */
    if ( 0 !== strpos( $class_name, 'ThreatPress_Recaptcha_Core_' ) ) {
        return;
    }

    $file_name = str_replace( array('threatpress_recaptcha_core_', '_'), array('class-', '-'), strtolower( $class_name ) );

    $file = dirname(THREATPRESS_RECAPTCHA_PLUGIN_FILE) . '/core/' . $file_name . '.php';

    if ( file_exists( $file ) ) {
        require( $file );
    }

}

if ( function_exists( 'spl_autoload_register' ) ) {
    spl_autoload_register( 'threatpress_recaptcha_autoload_core' );
}

/**
 * Auto load modules class files
 *
 * @param string $class_name Class name.
 *
 * @return void
 */
function threatpress_recaptcha_autoload_modules( $class_name ) {

    /**
     * If the class being requested does not start with our prefix,
     * we know it's not one in our project
     */
    if ( 0 !== strpos( $class_name, 'ThreatPress_Recaptcha_Module_' ) ) {
        return;
    }

    $file_name = str_replace( array('threatpress_recaptcha_module_', '_'), array('class-', '-'), strtolower( $class_name ) );

    $file = dirname(THREATPRESS_RECAPTCHA_PLUGIN_FILE) . '/core/modules/' . $file_name . '.php';

    if ( file_exists( $file ) ) {
        require( $file );
    }

}

if ( function_exists( 'spl_autoload_register' ) ) {
    spl_autoload_register( 'threatpress_recaptcha_autoload_modules' );
}


/**
 * Load translations
 */
function threatpress_recaptcha_load_textdomain() {
    load_plugin_textdomain( 'threatpress', false, dirname( plugin_basename( THREATPRESS_RECAPTCHA_PLUGIN_FILE ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'threatpress_recaptcha_load_textdomain' );

/**
 * Init
 */
function threatpress_recaptcha_init() {
    // Make sure our option and meta value validation routines and default values are always registered and available.
    ThreatPress_Recaptcha_Admin_Options::get_instance();

    // Load ThreatPress modules
    new ThreatPress_Recaptcha_Core_Modules();
}

/**
 * Used to load the required files on the plugins_loaded hook.
 */
function threatpress_recaptcha_admin_init() {
    new ThreatPress_Recaptcha_Admin_Init();
}

/* ***************************** HOOK INTO WP *************************** */
$spl_autoload_exists = function_exists( 'spl_autoload_register' );

if ( ! $spl_autoload_exists ) {
    add_action( 'admin_init', 'threatpress_recaptcha_missing_spl', 1 );
}

if ( ! function_exists( 'wp_installing' ) ) {
    /**
     * We need to define wp_installing in WordPress versions older than 4.4
     *
     * @return bool
     */
    function wp_installing() {
        return defined( 'WP_INSTALLING' );
    }
}

if ( ! wp_installing() && ( $spl_autoload_exists ) ) {
    add_action( 'plugins_loaded', 'threatpress_recaptcha_init', 14 );

    if (is_admin()) {
        add_action( 'plugins_loaded', 'threatpress_recaptcha_admin_init', 15 );
    }
}

// Activation and deactivation hook.
register_activation_hook( THREATPRESS_RECAPTCHA_PLUGIN_FILE, 'threatpress_recaptcha_activate' );
register_deactivation_hook( THREATPRESS_RECAPTCHA_PLUGIN_FILE, 'threatpress_recaptcha_deactivate' );

/**
 * Runs on activation of the plugin.
 */
function threatpress_recaptcha_activate() {
    threatpress_recaptcha_load_textdomain();

    ThreatPress_Recaptcha_Admin_Options::get_instance();
    ThreatPress_Recaptcha_Admin_Options::ensure_options_exist();
}

/**
 * Runs on deactivation of the plugin.
 */
function threatpress_recaptcha_deactivate() {

}

/**
 * Throw an error if the PHP SPL extension is disabled (prevent white screens) and self-deactivate plugin
 *
 * @return void
 */
function threatpress_recaptcha_missing_spl() {
    if ( is_admin() ) {
        add_action( 'admin_notices', 'threatpress_recaptcha_missing_spl_notice' );

        threatpress_recaptcha_self_deactivate();
    }
}

/**
 * Returns the notice in case of missing spl extension.
 */
function threatpress_recaptcha_missing_spl_notice() {
    $message = esc_html__( 'The Standard PHP Library (SPL) extension seem to be unavailable. Please ask your web host to enable it.', 'threatpress' );
    threatpress_recaptcha_activation_failed_notice( $message );
}

/**
 * Echo's the Activation failed notice with any given message.
 *
 * @param string $message Message string.
 */
function threatpress_recaptcha_activation_failed_notice( $message ) {
    echo '<div class="error"><p>' . __( 'Activation failed:', 'threatpress' ) . ' ' . $message . '</p></div>';
}
/**
 * The function will deactivate the plugin, but only once, done by the static $is_deactivated
 */
function threatpress_recaptcha_self_deactivate() {
    static $is_deactivated;
    if ( $is_deactivated === null ) {
        $is_deactivated = true;
        deactivate_plugins( plugin_basename( THREATPRESS_RECAPTCHA_PLUGIN_FILE ) );
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}