<?php

if ( ! defined( 'THREATPRESS_RECAPTCHA_VERSION' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

$tform->table_header();

$tform->table_row(
    __( 'Login Form Protection', 'threatpress' ), array( 'for' => 'login_form' ),
    $tform->light_switch( 'wordpress', 'login_form' )
);

$tform->table_row(
    __( 'Registration Form Protection', 'threatpress' ), array( 'for' => 'registration_form' ),
    $tform->light_switch( 'wordpress', 'registration_form' )
);

$tform->table_row(
    __( 'Comments Form Protection', 'threatpress' ), array( 'for' => 'comments_form' ),
    $tform->light_switch( 'wordpress', 'comments_form' )
);

$tform->table_row(
    __( 'Forgot Password Form Protection', 'threatpress' ), array( 'for' => 'forgot_password_form' ),
    $tform->light_switch( 'wordpress', 'forgot_password_form' )
);

$tform->table_footer();