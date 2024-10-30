<?php
/**
 * Module name: Invisible Recaptcha
 *
 */
class ThreatPress_Recaptcha_Module_Core {

    CONST HOLDER_CLASS_NAME = 'threatpress-holder';

    private $options;

    /**
     * Class constructor
     */
    public function __construct( $options ) {
        $this->options = $options;

        if ( empty( $this->options['settings']['site_key'] ) || empty( $this->options['settings']['secret_key'] ) )
            return;

        add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
        add_action( 'login_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
        add_action( 'wp_head', array( $this, 'wp_head_scripts' ) );

        add_filter( 'script_loader_tag', array( $this, 'add_async_attribute' ), 10, 2 );
        add_filter( 'threatpress_google_ir_is_valid', array( $this, 'is_ir_token_valid' ) );

        /*
        * WordPress
        *
        */
        if ( $this->options['wordpress']['login_form'] == 'on' ) {
            add_action( 'login_form', array( $this, 'render_ir_html' ) );
            add_action( 'authenticate', array( $this, 'authenticate' ), 10, 3 );
            add_filter( 'wp_authenticate_user', array( $this, 'authenticate_user' ), 10, 2 );
        }

        if ( $this->options['wordpress']['forgot_password_form'] == 'on' ) {
            add_action( 'lostpassword_form', array( $this, 'render_ir_html' ) );
            add_action( 'allow_password_reset', array( $this, 'allow_password_reset' ), 21, 1 );
        }

        if ( $this->options['wordpress']['registration_form'] == 'on' ) {
            if ( ! is_multisite() ) {
                add_action( 'register_form', array( $this, 'render_ir_html' ), 99 );
                add_action( 'registration_errors', array( $this, 'allow_password_reset' ) );
            } else {
                add_action( 'signup_extra_fields', array( $this, 'render_ir_html' ) );
                add_action( 'signup_blogform', array( $this, 'render_ir_html' ) );
                add_filter( 'wpmu_validate_user_signup', array( $this, 'signup_blogform' ) );
            }
        }

        if ( $this->options['wordpress']['comments_form'] == 'on' ) {
            add_action( 'comment_form', array( $this, 'render_ir_html' ) );
            add_filter( 'preprocess_comment', array( $this, 'preprocess_comment' ) );
        }

        /*
         * WooCommerce
         *
         */
        empty( $_POST['threatpress_wc_ir_login'] ) ?: $_POST['login'] = 'login';
        empty( $_POST['threatpress_wc_ir_register'] ) ?: $_POST['register'] = 'register';

        if ( $this->options['woocommerce']['login_form'] == 'on' ) {
            add_action( 'woocommerce_login_form_end', array( $this, 'woocommerce_login_render_ir_html' ) );
            add_filter( 'woocommerce_process_login_errors', array( $this, 'woocommerce_process_login_errors' ) );
        }

        if ( $this->options['woocommerce']['registration_form'] == 'on' ) {
            add_action( 'woocommerce_register_form_end', array( $this, 'woocommerce_register_render_ir_html' ) );
            add_filter( 'woocommerce_process_registration_errors', array( $this, 'woocommerce_process_registration_errors' ), 10, 4 );
        }

        if ( $this->options['woocommerce']['lost_password_form'] == 'on' ) {
            add_action( 'woocommerce_lostpassword_form', array( $this, 'render_ir_html' ) );
            add_filter( 'allow_password_reset', array( $this, 'allow_password_reset' ), 10, 4 );
        }

        if ( $this->options['woocommerce']['reset_password_form'] == 'on' ) {
            add_action( 'woocommerce_resetpassword_form', array( $this, 'render_ir_html' ) );
            add_action( 'validate_password_reset', array( $this, 'validate_password_reset' ) );
        }

        if ( $this->options['woocommerce']['product_review_form'] == 'on' &&
            $this->options['wordpress']['comments_form'] !== 'on'
        ) {
            add_action( 'comment_form', array( $this, 'render_ir_html' ) );
            add_filter( 'preprocess_comment', array( $this, 'preprocess_comment' ) );
        }
    }

    /* ********* WooCommerce ********* */

    /**
     * Process login form.
     *
     * @param $wpError
     * @return WP_Error
     */
    public function woocommerce_process_login_errors($wpError) {
        if ( self::is_ir_valid() ) {
            return $wpError;
        } else {
            return new WP_Error( 'threatpress_recaptcha_error', self::error_message() );
        }
    }

    /**
     * Process registration.
     *
     * @param $wpError
     * @param $userName
     * @param $password
     * @param $emailAddress
     * @return WP_Error
     */
    public function woocommerce_process_registration_errors($wpError, $userName, $password, $emailAddress) {
        if ( self::is_ir_valid() ) {
            return $wpError;
        } else {
            return new WP_Error( 'threatpress_recaptcha_error', self::error_message() );
        }
    }

    /**
     * Validate password reset.
     *
     * @return void|WP_Error
     */
    public function validate_password_reset($errors) {
        if(empty($_POST) || function_exists('retrieve_password')) // we are in wp-login.php The request is for WP password reset
            return;

        if ( ! self::is_ir_valid() )
            $errors->add( 'threatpress_recaptcha_error', self::error_message() );

    }

    /* ********* WordPress ********* */

    /**
     * Authenticate.
     *
     * @param $user
     * @param $userName
     * @param $password
     * @return WP_Error
     */
    public function authenticate( $user, $userName, $password ) {
        if ( !$userName )
            return $user;

        if ( is_wp_error( $user ) && in_array( $user->get_error_code(), array( 'empty_username', 'empty_password' ) ) ) {
            return $user;
        }

        if ( ! function_exists( 'login_header' ) )
            return $user;

        if ( self::is_ir_valid() ) {
            return $user;
        } else {
            return new WP_Error( 'threatpress_recaptcha_error', self::error_message() );
        }
    }

    /**
     * Authenticate user.
     *
     * @param $wpUser
     * @param $password
     * @return WP_Error
     */
    public function authenticate_user($wpUser, $password) {
        if ( self::is_ir_valid() ) {
            return $wpUser;
        } else {
            return new WP_Error( 'threatpress_recaptcha_error', self::error_message() );
        }
    }

    /**
     * Password reset.
     *
     * @param $allow
     * @return WP_Error
     */
    public function allow_password_reset($allow) {
        if ( self::is_ir_valid() ) {
            return $allow;
        } else {
            return new WP_Error( 'threatpress_recaptcha_error', self::error_message() );
        }
    }

    /**
     * Signup.
     *
     * @param $results
     * @return WP_Error
     */
    public function signup_blogform($results) {
        global $current_user;

        if ( is_admin() && ! defined( 'DOING_AJAX' ) && ! empty( $current_user->data->ID ) )
            return $results;

        if ( self::is_ir_valid() ) {
            return $results;
        } else {
            $error = $results['errors'];
            $error->add( 'threatpress_recaptcha_error', self::error_message() );

            return $results;
        }
    }

    /**
     * Preprocess comment.
     *
     * @param $arrComment
     * @return WP_Error
     */
    public function preprocess_comment($arrComment) {

        if ( is_admin() && current_user_can( 'moderate_comments' ) )
            return $arrComment;
        
        if ( self::is_ir_valid() ) {
            return $arrComment;
        } else {
            return new WP_Error( 'threatpress_recaptcha_error', self::error_message() );
        }
    }

    /**
     * Enqueue scripts for WordPress v4.5+
     *
     */
    public function wp_enqueue_scripts() {
        if ( version_compare( get_bloginfo('version'),'4.5', '>' ) ) {

            $api_url = add_query_arg( array(
                'onload'   => 'threatpress_render_ir',
                'render' => 'explicit',
                'hl' => $this->options['settings']['language'],
            ), 'https://www.google.com/recaptcha/api.js' );

            wp_enqueue_script( 'google-invisible-recaptcha', $api_url, array(), null, true );
        }

        wp_add_inline_script( 'google-invisible-recaptcha', $this->inline_ir_script(), 'before' );
    }

    /**
     * Enqueue scripts for WordPress below v4.5
     *
     * Add custom CSS
     */
    public function wp_head_scripts() {
        if( version_compare( get_bloginfo('version'),'4.5', '<' ) ) {

            $api_url = add_query_arg( array(
                'onload'   => 'threatpress_render_ir',
                'render' => 'explicit',
                'hl' => $this->options['settings']['language'],
            ), 'https://www.google.com/recaptcha/api.js' );

            echo '<script src="' . $api_url . '" async defer></script>';
        }

        if ( ! empty( $this->options['settings']['badge_custom_css'] ) ) {
            echo '<style type="text/css">' . wp_specialchars_decode( $this->options['settings']['badge_custom_css'], ENT_QUOTES ) . '</style>';
        }
    }

    /**
     * Add the "async" attribute to Google invisible recaptcha API script.
     */
    public function add_async_attribute( $tag, $handle ) {
        if ( 'google-invisible-recaptcha' == $handle ) {
            $tag = str_replace( ' src', ' async defer src', $tag );
        }

        return $tag;
    }

    /**
     * Check if invisible recaptcha token is valid.
     *
     */
    public function is_ir_token_valid() {

        static $is_valid = -1;
        if ( -1 !== $is_valid )
            return $is_valid;

        if ( empty( $_POST['g-recaptcha-response'] ) )
            return false;

        $response = wp_remote_retrieve_body( wp_remote_get( add_query_arg( array(
            'secret'   => $this->options['settings']['secret_key'],
            'response' => $_POST['g-recaptcha-response'],
            'remoteip' => $_SERVER['REMOTE_ADDR'],
        ), 'https://www.google.com/recaptcha/api/siteverify' ) ) );

        if ( empty( $response ) || ! ( $json = json_decode( $response ) ) || empty( $json->success ) ) {
            $is_valid = false;
        }

        $is_valid = true;

        return $is_valid;
    }

    /**
     * Check if invisible recaptcha is valid.
     *
     * @return bool
     */
    public static function is_ir_valid() {
        return (bool) apply_filters( 'threatpress_google_ir_is_valid', false );
    }

    /**
     * Error message.
     *
     */
    public static function error_message() {
        return apply_filters( 'threatpress_recaptcha_error_message', __( 'You have entered an incorrect reCAPTCHA value.', 'threatpress' ) );
    }

    /**
     * Get invisible recaptcha holder HTML.
     *
     * @return string
     */
    public static function get_ir_html() {
        $html = '<div class="' . self::HOLDER_CLASS_NAME . '"></div>';

        return $html;
    }

    /**
     * Get invisible recaptcha holder HTML for WooCommerce login.
     *
     * @return string
     */
    public static function woocommerce_login_render_ir_html() {
        $html = '<div class="' . self::HOLDER_CLASS_NAME . '"></div>';
        $html .= '<input type="hidden" name="threatpress_wc_ir_login" value="1" />';

        echo $html;
    }

    /**
     * Get invisible recaptcha holder HTML for WooCommerce registration.
     *
     * @return string
     */
    public static function woocommerce_register_render_ir_html() {
        $html = '<div class="' . self::HOLDER_CLASS_NAME . '"></div>';
        $html .= '<input type="hidden" name="threatpress_wc_ir_register" value="1" />';

        echo $html;
    }

    /**
     * Render invisible recaptcha holder HTML.
     */
    public function render_ir_html() {
        echo self::get_ir_html();
    }

    /**
     * Invisible recaptcha callback script.
     *
     * @return string
     */
    public function inline_ir_script() {
        $holderClassName = self::HOLDER_CLASS_NAME;

        return "
        var threatpress_render_ir = function() {
            for (var i = 0; i < document.forms.length; ++i) {
                var form = document.forms[i];
                var holder = form.querySelector('.{$holderClassName}');
                if (null === holder) continue;
                    holder.innerHTML = '';
                (function(frm){
                    var holderId = grecaptcha.render(holder,{
                        'sitekey': '{$this->options['settings']['site_key']}', 'size': 'invisible', 'badge' : '{$this->options['settings']['badge_position']}',
                        'callback' : function (recaptchaToken) {
                            HTMLFormElement.prototype.submit.call(frm);
                        },
                        'expired-callback' : function(){grecaptcha.reset(holderId);}
                    });
                     frm.onsubmit = function (evt){evt.preventDefault();grecaptcha.execute(holderId);};
                })(form);
            }
        };
        ";
    }
}