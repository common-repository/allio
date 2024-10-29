<?php
/**
 * Scripts
 *
 * @package     ALLIOC
 * @subpackage  Functions
 * @copyright   Copyright (c) 2017, Daniel Powney
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Load Scripts
 *
 * Enqueues the required scripts.
 *
 * @since 0.1
 * @return void
 */
function allioc_load_scripts() {

	$js_dir = ALLIOC_PLUGIN_URL . 'assets/js/';

	// Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix = ''; //( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_register_script( 'allioc-script', $js_dir . 'frontend' . $suffix . '.js', array( 'jquery' ), ALLIOC_VERSION );
	wp_enqueue_script( 'allioc-script' );

	$general_settings = (array) get_option( 'allioc_general_settings' );
	$overlay_settings = (array) get_option( 'allioc_overlay_settings' );
	$session_id = null;
	if ( isset( $_COOKIE['allioc_session_id'] ) && strlen( $_COOKIE['allioc_session_id'] ) > 0 ) {
		$session_id = $_COOKIE['allioc_session_id'];
	} else {
		$session_id = md5( uniqid( 'allioc-' ) ); // do not set cookie here as headers have already been set
	}

	$user_icon = $overlay_settings['user_icon'] ? wp_get_attachment_url( $overlay_settings['user_icon'] ) : ALLIOC_PLUGIN_URL . 'assets/images/allioc.png';

	wp_localize_script( 'allioc-script', 'allioc_script_vars', apply_filters( 'allioc_script_vars', array(
			//'access_token' 			=> apply_filters( 'allioc_script_access_token', $general_settings['allioc_access_token'] ),
			'enable_welcome_event' 	=> apply_filters( 'allioc_script_enable_welcome_event',  $overlay_settings['overlay_default_open'] ),
			'messaging_platform' 	=> apply_filters( 'allioc_script_messaging_platform', $general_settings['messaging_platform'] ),
			'base_url' 				=> admin_url( 'admin-ajax.php' ),//ALLIOC_PLUGIN_URL . "dialogflow/index.php",
			'security'				=>  wp_create_nonce( "allioc-!@#$%^" ),
			'user_icon'				=> $user_icon,
			'messages' 				=> array(
					'internal_error' 		=> __( 'Oops... Can you try me one more time?', 'Allio' ),
					'input_unknown' 		=> __( 'I\'m sorry I do not understand.', 'Allio' )
			),
			'session_id' 			=> apply_filters( 'allioc_script_session_id', $session_id ),
			'show_time' 			=> apply_filters( 'allioc_script_show_time', $general_settings['show_time'] ),
			'show_loading' 			=> apply_filters( 'allioc_script_show_loading', $general_settings['show_loading'] ),
			'response_delay' 		=> apply_filters( 'allioc_script_response_delay', $general_settings['response_delay'] ),
			'language' 				=> apply_filters( 'allioc_language', $general_settings['language'] ),
			'worker_url'            => ALLIOC_PLUGIN_URL . 'assets/js/reload.js',
			'stripe_pk' 			=>  $general_settings['Stripe_PK'], 
      'welcome_msg' => $general_settings['allioc_welcome_message'], //allioc_get_query_result($session_id, "hi", "WELCOME", "en")
	) ) );

}
add_action( 'wp_enqueue_scripts', 'allioc_load_scripts' );


/**
 * Register Styles
 *
 * Checks the styles option and hooks the required filter.
 *
 * @since 0.1
 * @return void
*/
function allioc_register_styles() {

	$general_settings = (array) get_option( 'allioc_general_settings' );
	$overlay_settings = (array) get_option( 'allioc_overlay_settings' );
	
	$disable_css_styles = isset( $general_settings['disable_css_styles'] ) ? $general_settings['disable_css_styles'] : false;

	if ( $disable_css_styles ) {
		return;
	}

	$css_dir = ALLIOC_PLUGIN_URL . 'assets/css/';

	// Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix = ''; //( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_register_style( 'allioc-style', $css_dir . 'frontend' . $suffix . '.css', array(), ALLIOC_VERSION, 'all' );
	$bg_url = $overlay_settings['background_img'] ? wp_get_attachment_url( $overlay_settings['background_img'] ) : ALLIOC_PLUGIN_URL . 'assets/images/bg.png';
	$custom_css = '
		.allioc-conversation-area .allioc-icon-loading-dot {
			color: ' . $general_settings['loading_dots_color'] . ';
		}
		.allioc-conversation-response, .allioc-conversation-response:after {
			background-color: ' . $general_settings['response_background_color'] . ';
			color: ' . $general_settings['response_font_color'] . ';
		}
		.allioc-conversation-request, .allioc-conversation-request:before  {
			background-color: ' . $general_settings['request_background_color'] . ';
			color: ' . $general_settings['request_font_color'] . ';
		}
		.allioc-content-overlay-header {		
			color: ' . $overlay_settings['overlay_header_font_color'] . ';
			position: relative;
			background: rgb(255, 255, 255);
			background: linear-gradient(135deg,rgb(255, 255, 255) 0%,rgb(204, 204, 204) 100%);
			color: #fff;
			-webkit-transition: height 160ms ease-out;
			transition: height 160ms ease-out;
		}

		.allioc-content-overlay-header:before{
			content: "";
			background-image: url(' . $bg_url . ');' . ';
			background-size: 832px 439px,cover;
			position: absolute;
			width: 100%;
			height: 100%;
			left:0 ;
			top: 0;
			opacity: 0.35;
		}

		.allioc-content-logo.opend:before {			
			background-color: ' . $overlay_settings['overlay_header_background_color'] . ';
			color: ' . $overlay_settings['overlay_header_font_color'] . ';
		}

		#allioc-input-area .allioc-send-button svg path {
			fill: ' . $overlay_settings['overlay_header_background_color'] . ';
		}

		.allioc-micro-button.micro-active {
			background-color: ' . $overlay_settings['overlay_header_background_color'] . ';
		}

		.allioc-conversation-bubble {
			opacity: ' . $general_settings['non_current_opacity'] . ';
    		filter: alpha(opacity=' . 100 * intval( $general_settings['non_current_opacity'] ) . ' ); /* For IE8 and earlier */
		}

		.allioc-content-overlay {
			right: '. $overlay_settings['position_right']  . 'px;
			bottom: '. (intval($overlay_settings['position_bottom']) + 80) .'px;
		}

		.allioc-is-active {
			opacity: 1.0;
    		filter: alpha(opacity=100); /* For IE8 and earlier */
		}

		.allioc-content-overlay-container {
			background: #fff;
		}

		input.allioc-text:focus, 
        input.allioc-text:active,  {	
	        background-color: #fff!important;
       }
	';

	if ( $overlay_settings['overlay_default_open'] ) {
		$custom_css .= '
			.allioc-content-overlay .allioc-toggle-closed {
				display: none;
			}
		';
	}
	
	if ($general_settings["allioc_custom_css"]) {
		$custom_css .= $general_settings["allioc_custom_css"];
	}
	wp_add_inline_style( 'allioc-style', $custom_css );
	wp_enqueue_style( 'allioc-style' );

	wp_enqueue_style('google-worksans-font', "https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500&display=swap", array());
}
add_action( 'wp_enqueue_scripts', 'allioc_register_styles' );


/**
 * Load Admin Scripts
 *
 * Enqueues the required admin scripts.
 *
 * @since 0.1
 * @return void
 */
function allioc_load_admin_scripts() {

	$js_dir = ALLIOC_PLUGIN_URL . 'assets/js/';
	$css_dir = ALLIOC_PLUGIN_URL . 'assets/css/';

	// Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix = ''; //( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_media();

	wp_register_script( 'allioc-admin-script', $js_dir . 'admin' . $suffix . '.js', array( 'jquery' ), ALLIOC_VERSION );
	wp_enqueue_script( 'allioc-admin-script' );

	wp_enqueue_style( 'allioc-admin-style', $css_dir . 'admin' . $suffix . '.css' );
	
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );

}
add_action( 'admin_enqueue_scripts', 'allioc_load_admin_scripts' );

add_action( 'wp_ajax_myprefix_get_image', 'myprefix_get_image'   );
function myprefix_get_image() {
    if(isset($_GET['id']) ){
        $image = wp_get_attachment_image( filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ), 'medium', false, array( 'id' => 'myprefix-preview-image' ) );
        $data = array(
            'image'    => $image,
        );
        wp_send_json_success( $data );
    } else {
        wp_send_json_error();
    }
}

/**
 *
 */
function allioc_get_language( $language ) {

	if ( $language == '' ) {

		$locale = get_locale();

		$language_map = array(
				'pt_BR' 	=> 'pt-BR',
				'zh_HK'		=> 'zh-HK',
				'zh_CN'		=> 'zh-CN',
				'zh_TW'		=> 'zh-TW',
				''			=> 'en',
				'en_AU'		=> 'en-AU',
				'en_CA'		=> 'en-CA',
				'en_GB'		=> 'en-GB',
				'en_IN'		=> 'en-IN',
				'en_US'		=> 'en-US',
				'nl_NL'		=> 'nl',
				'fr_FR'		=> 'fr',
				'fr_CA'		=> 'fr-CA',
				'de_DE'		=> 'gr',
				'it_IT'		=> 'it',
				'ja'		=> 'ja',
				'ko_KR'		=> 'ko',
				'pt_PT'		=> 'pt',
				'ru_RU'		=> 'ru',
				'es'		=> 'es',
				'es_ES'		=> 'es',
				'es_VE'		=> 'es-419',
				'es_MX'		=> 'es-419',
				'es_CR'		=> 'es-419',
				'es_GT'		=> 'es-419',
				'es_CL'		=> 'es-419',
				'es_PE'		=> 'es-419',
				'es_AR'		=> 'es-419',
				'es_CO'		=> 'es-419',
				'uk'		=> 'uk',
		);

		return $language_map[$locale];
	}

	return $language;
}
add_filter( 'allioc_language', 'allioc_get_language' );
