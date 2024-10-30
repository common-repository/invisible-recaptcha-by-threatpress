<?php
/*
 * Plugin Name: Google Invisible reCaptcha by ThreatPress
 * Description: Integrates the new Invisible reCAPTCHA into your WordPress site.
 * Version: 0.9.2
 * Author: ThreatPress
 * Author URI: https://www.threatpress.com
 * License: GPL2+
 *
 * Text Domain: threatpress
 * Domain Path: /languages/
 */

// don't call the file directly
defined( 'ABSPATH' ) or die();

// Define constants
define( 'THREATPRESS_RECAPTCHA_VERSION', '0.9.2' );
define( 'THREATPRESS_RECAPTCHA_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'THREATPRESS_RECAPTCHA_PLUGIN_URL', trailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'THREATPRESS_RECAPTCHA_PLUGIN_FILE', __FILE__ );

require_once( dirname( THREATPRESS_RECAPTCHA_PLUGIN_FILE ) . '/bootstrap.php' );