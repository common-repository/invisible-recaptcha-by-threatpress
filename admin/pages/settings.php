<?php

$tform = ThreatPress_Recaptcha_Admin_Form::get_instance();

$tform->admin_header( true, 'threatpress_recaptcha_settings' );

$tabs = new ThreatPress_Recaptcha_Admin_Option_Tabs( 'settings' );
$tabs->add_tab( new ThreatPress_Recaptcha_Admin_Option_Tab( 'settings', __( 'Settings', 'threatpress' ) ) );
$tabs->add_tab( new ThreatPress_Recaptcha_Admin_Option_Tab( 'wordpress', __( 'WordPress', 'threatpress' ) ) );
$tabs->add_tab( new ThreatPress_Recaptcha_Admin_Option_Tab( 'woocommerce', __( 'WooCommerce', 'threatpress' ) ) );

$tabs->display( $tform );

$tform->admin_footer();

