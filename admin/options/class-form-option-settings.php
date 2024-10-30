<?php

/**
 * Option: threatpress_recaptcha_settings
 */
class ThreatPress_Recaptcha_Admin_Form_Option_Settings extends ThreatPress_Recaptcha_Admin_Option {

    /**
     * @var  string  option name
     *
     */
    public $option_name = 'threatpress_recaptcha_settings';

    /**
     * @var  array  Array of defaults for the option
     *
     */
    protected $defaults = array(
        'settings' => array(
            'site_key' => '',
            'secret_key' => '',
            'language' => '',
            'badge_position' => 'bottom_right',
            'badge_custom_css' => ''
        ),
        'wordpress' => array(
            'login_form' => '',
            'registration_form' => '',
            'comments_form' => '',
            'forgot_password_form' => ''
        ),
        'woocommerce' => array(
            'login_form' => '',
            'registration_form' => '',
            'lost_password_form' => '',
            'reset_password_form' => '',
            'product_review_form' => ''
        )
    );

    /**
     * Add the actions and filters for the option
     *
     */
    protected function __construct() {
        parent::__construct();
    }

    /**
     * Get the singleton instance of this class
     *
     * @return object
     */
    public static function get_instance() {
        if ( ! ( self::$instance instanceof self ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Fires after the value of a specific option has been successfully updated.
     *
     * @param mixed  $old_value The old option value.
     * @param mixed  $value     The new option value.
     * @param string $option    Option name.
     */
    protected function updated_option( $old_value, $value, $option ) {

    }

    /**
     * Validate the option
     *
     * @param  array $dirty New value for the option.
     * @param  array $clean Clean value for the option, normally the defaults.
     * @param  array $old   Old value of the option.
     *
     * @return  array      Validated clean value for the option to be saved to the database
     */
    protected function validate_option( $dirty, $clean ) {
        foreach ( $clean as $key => $value ) {
            if ( is_array( $clean[$key] ) ) {
                foreach ( $clean[$key] as $option_key => $val ) {

                    switch ( $option_key ) {
                        default:
                            $clean[ $key ][ $option_key ] = ( isset( $dirty[ $key ][ $option_key ] ) ? sanitize_text_field( $dirty[ $key ][ $option_key ] ) : false);
                            break;
                    }
                }
            }
        }

        return $clean;
    }
}
