<?php

/**
 * Utility methods
 *
 */
class ThreatPress_Recaptcha_Admin_Utils {

    /**
     * Display a message when recaptcha keys are missing.
     */
    public static function empty_keys() {
        $options = get_option( 'threatpress_recaptcha_settings' );

        if ( empty( $options['settings']['site_key'] ) || empty( $options['settings']['secret_key'] ) ) {
            ?>
            <div id="message" class="notice notice-error"><p><?php printf( __('Invisible reCAPTCHA API keys are missing. Please enter. <a href="%s" target="_blank">%s</a>', 'threatpress'), 'https://www.google.com/recaptcha/intro/index.html', __('Get the keys here', 'threatpress')); ?></p></div>
            <?php
        }
    }

    /**
     * Recursively trim whitespace round a string value or of string values within an array
     * Only trims strings to avoid typecasting a variable (to string)
     *
     * @static
     *
     * @param mixed $value Value to trim or array of values to trim.
     *
     * @return mixed Trimmed value or array of trimmed values
     */
    public static function trim_recursive( $value ) {
        if ( is_string( $value ) ) {
            $value = trim( $value );
        }
        elseif ( is_array( $value ) ) {
            $value = array_map( array( __CLASS__, 'trim_recursive' ), $value );
        }

        return $value;
    }

    /**
     * Validate integer
     *
     * @param $value
     * @return bool
     */
    public static function validate_int( $value ) {
        if ( filter_var( $value, FILTER_VALIDATE_INT ) !== false ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get reCAPTCHA languages.
     *
     * @return array
     */
    public static function languages() {
        return array(
            'automatically_detect' => __('Automatically detect', 'threatpress'),
            'ar' => __('Arabic', 'threatpress'),
            'af' => __('Afrikaans', 'threatpress'),
            'am' => __('Amharic', 'threatpress'),
            'hy' => __('Armenian', 'threatpress'),
            'az' => __('Azerbaijani', 'threatpress'),
            'eu' => __('Basque', 'threatpress'),
            'bn' => __('Bengali', 'threatpress'),
            'bg' => __('Bulgarian', 'threatpress'),
            'ca' => __('Catalan', 'threatpress'),
            'zh-HK' => __('Chinese(HongKong)', 'threatpress'),
            'zh-CN' => __('Chinese(Simplified)', 'threatpress'),
            'zh-TW' => __('Chinese(Traditional)', 'threatpress'),
            'hr' => __('Croatian', 'threatpress'),
            'cs' => __('Czech', 'threatpress'),
            'da' => __('Danish', 'threatpress'),
            'nl' => __('Dutch', 'threatpress'),
            'en-GB' => __('English(UK)', 'threatpress'),
            'en' => __('English(US)', 'threatpress'),
            'et' => __('Estonian', 'threatpress'),
            'fil' => __('Filipino', 'threatpress'),
            'fi' => __('Finnish', 'threatpress'),
            'fr' => __('French', 'threatpress'),
            'fr-CA' => __('French(Canadian)', 'threatpress'),
            'gl' => __('Galician', 'threatpress'),
            'ka' => __('Georgian', 'threatpress'),
            'de' => __('German', 'threatpress'),
            'de-AT' => __('German(Austria)', 'threatpress'),
            'de-CH' => __('German(Switzerland)', 'threatpress'),
            'el' => __('Greek', 'threatpress'),
            'gu' => __('Gujarati', 'threatpress'),
            'iw' => __('Hebrew', 'threatpress'),
            'hi' => __('Hindi', 'threatpress'),
            'hu' => __('Hungarain', 'threatpress'),
            'is' => __('Icelandic', 'threatpress'),
            'id' => __('Indonesian', 'threatpress'),
            'it' => __('Italian', 'threatpress'),
            'ja' => __('Japanese', 'threatpress'),
            'kn' => __('Kannada', 'threatpress'),
            'ko' => __('Korean', 'threatpress'),
            'lo' => __('Laothian', 'threatpress'),
            'lv' => __('Latvian', 'threatpress'),
            'lt' => __('Lithuanian', 'threatpress'),
            'ms' => __('Malay', 'threatpress'),
            'ml' => __('Malayalam', 'threatpress'),
            'mr' => __('Marathi', 'threatpress'),
            'mn' => __('Mongolian', 'threatpress'),
            'no' => __('Norwegian', 'threatpress'),
            'fa' => __('Persian', 'threatpress'),
            'pl' => __('Polish', 'threatpress'),
            'pt' => __('Portuguese', 'threatpress'),
            'pt-BR' => __('Portuguese(Brazil)', 'threatpress'),
            'pt-PT' => __('Portuguese(Portugal)', 'threatpress'),
            'ro' => __('Romanian', 'threatpress'),
            'ru' => __('Russian', 'threatpress'),
            'sr' => __('Serbian', 'threatpress'),
            'si' => __('Sinhalese', 'threatpress'),
            'sk' => __('Slovak', 'threatpress'),
            'sl' => __('Slovenian', 'threatpress'),
            'es' => __('Spanish', 'threatpress'),
            'es-419' => __('Spanish(LatinAmerica)', 'threatpress'),
            'sw' => __('Swahili', 'threatpress'),
            'sv' => __('Swedish', 'threatpress'),
            'ta' => __('Tamil', 'threatpress'),
            'te' => __('Telugu', 'threatpress'),
            'th' => __('Thai', 'threatpress'),
            'tr' => __('Turkish', 'threatpress'),
            'uk' => __('Ukrainian', 'threatpress'),
            'ur' => __('Urdu', 'threatpress'),
            'vi' => __('Vietnamese', 'threatpress'),
            'zu' => __('Zulu', 'threatpress')
        );
    }
}