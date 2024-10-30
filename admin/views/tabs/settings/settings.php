<?php

if ( ! defined( 'THREATPRESS_RECAPTCHA_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

ThreatPress_Recaptcha_Admin_Utils::empty_keys();

$tform->table_header();

$tform->table_row(
	__( 'Your Site Key', 'threatpress' ), array( 'for' => 'site_key' ),
	$tform->textinput( 'settings', 'site_key' )
);

$tform->table_row(
	__( 'Your Secret Key', 'threatpress' ), array( 'for' => 'secret_key' ),
	$tform->textinput( 'settings', 'secret_key' )
);

$tform->table_row(
	__( 'Language', 'threatpress' ), array( 'for' => 'language' ),
	$tform->select( 'settings', 'language', ThreatPress_Recaptcha_Admin_Utils::languages() )
);

$tform->table_row(
	__( 'Badge Position', 'threatpress' ), array( 'for' => 'badge_position' ),
	$tform->select( 'settings', 'badge_position', array( 'bottom_right' => __('Bottom Right', 'threatpress'), 'bottom_left' => __('Bottom Left', 'threatpress'), 'inline' => __('Inline', 'threatpress') ) )
);

$tform->table_row(
	__( 'Badge Custom CSS', 'threatpress' ), array( 'for' => 'badge_custom_css' ),
	$tform->textarea( 'settings', 'badge_custom_css', array( 'cols' => 60, 'rows' => 5 ) )
);

$tform->table_footer();