<?php

if ( ! defined( 'THREATPRESS_RECAPTCHA_VERSION' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

$tform->table_header();

$tform->table_row(
    __( 'Login Form Protection', 'threatpress' ), array( 'for' => 'login_form' ),
    $tform->light_switch( 'woocommerce', 'login_form' )
);

$tform->table_row(
    __( 'Registration Form Protection', 'threatpress' ), array( 'for' => 'registration_form' ),
    $tform->light_switch( 'woocommerce', 'registration_form' )
);

$tform->table_row(
    __( 'Lost Password Form Protection', 'threatpress' ), array( 'for' => 'lost_password_form' ),
    $tform->light_switch( 'woocommerce', 'lost_password_form' )
);

$tform->table_row(
    __( 'Reset Password Form Protection', 'threatpress' ), array( 'for' => 'reset_password_form' ),
    $tform->light_switch( 'woocommerce', 'reset_password_form' )
);

$tform->table_row(
    __( 'Product Review Form Protection', 'threatpress' ), array( 'for' => 'product_review_form' ),
    $tform->light_switch( 'woocommerce', 'product_review_form' )
);

$tform->table_footer();