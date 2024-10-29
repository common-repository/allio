<?php
/**
 * Register Settings
 *
 * @package     ALLIOC
 * @subpackage  Admin/Settings
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Get Settings
 *
 * Retrieves all plugin settings
 *
 * @since 0.1
 * @return array ALLIOC settings
 */
function allioc_get_settings() {

	$settings = get_option( 'allioc_settings' );

	if( empty( $settings ) ) {

		// Update old settings with new single option

		$general_settings = is_array( get_option( 'allioc_general_settings' ) )    ? get_option( 'allioc_general_settings' )    : array();
		$overlay_settings = is_array( get_option( 'allioc_overlay_settings' ) )    ? get_option( 'allioc_overlay_settings' )    : array();

		$settings = array_merge( $general_settings, $overlay_settings );

		update_option( 'allioc_settings', $settings );
	}

	return apply_filters( 'allioc_get_settings', $settings );
}

/**
 * Reister settings
 */
function allioc_register_settings() {

	register_setting( 'allioc_general_settings', 'allioc_general_settings', 'allioc_sanitize_general_settings' );
	register_setting( 'allioc_overlay_settings', 'allioc_overlay_settings', 'allioc_sanitize_overlay_settings' );

	add_settings_section( 'allioc_section_general', null, 'allioc_section_general_desc', 'Allio&tab=allioc_general_settings' );
	add_settings_section( 'allioc_section_overlay', null, 'allioc_section_overlay_desc', 'Allio&tab=allioc_overlay_settings' );

	$setting_fields = array(		
			'active_key' => array(
					'title' 	=> __( 'Active Key', 'Allio' ),
					'callback' 	=> 'allioc_field_input',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'active_key',
							'label' 		=> __( 'Enter Active Key', 'Allio' ),
							'placeholder'	=> __( 'Enter Active Key...', 'Allio' ),
							'required'		=> true
					)
			),
			'allioc_access_token' => array(
					'title' 	=> __( 'Access Token', 'Allio' ),
					'callback' 	=> 'allioc_field_text',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'allioc_access_token',
							'label' 		=> __( 'Enter Dialogflow agent client access token.', 'Allio' ),
							'placeholder'	=> __( 'Enter access token...', 'Allio' ),
							'required'		=> true,
							'class' => 'allioc_acces_token',
					)
			),

			'allioc_welcome_message' => array(
				'title' 	=> __( 'Welcome Message', 'Allio' ),
				'callback' 	=> 'allioc_field_text',
				'page' 		=> 'Allio&tab=allioc_general_settings',
				'section' 	=> 'allioc_section_general',
				'args' => array(
						'option_name' 	=> 'allioc_general_settings',
						'setting_id' 	=> 'allioc_welcome_message',
						'label' 		=> __( '', 'Allio' ),
						'placeholder'	=> __( 'Enter Welcome Message', 'Allio' ),
						'required'		=> true
				)
		),
			'Stripe_PK' => array(
					'title' 	=> __( 'Stripe Pulbic Key', 'Allio' ),
					'callback' 	=> 'allioc_field_input',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'Stripe_PK',
							'label' 		=> __( '', 'Allio' ),
							'placeholder'	=> __( '', 'Allio' ),
							'required'		=> true
					)
			),
			'Stripe_SK' => array(
				'title' 	=> __( 'Stripe Secret Key', 'Allio' ),
				'callback' 	=> 'allioc_field_input',
				'page' 		=> 'Allio&tab=allioc_general_settings',
				'section' 	=> 'allioc_section_general',
				'args' => array(
						'option_name' 	=> 'allioc_general_settings',
						'setting_id' 	=> 'Stripe_SK',
						'label' 		=> __( '', 'Allio' ),
						'placeholder'	=> __( '', 'Allio' ),
						'required'		=> true
				)
			),
			'input_text' => array(
				'title' 	=> __( 'Input Text', 'Allio' ),
				'callback' 	=> 'allioc_field_input',
				'page' 		=> 'Allio&tab=allioc_general_settings',
				'section' 	=> 'allioc_section_general',
				'args' => array(
						'option_name' 	=> 'allioc_general_settings',
						'setting_id' 	=> 'input_text',
						'label' 		=> __( 'Enter input text.', 'Allio' ),
						'placeholder'	=> __( 'Enter input text...', 'Allio' ),
						'required'		=> true
				)
			),
			/*'enable_welcome_event' => array(
					'title' 	=> __( 'Fist Visit', 'Allio' ),
					'callback' 	=> 'allioc_field_checkbox',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'enable_welcome_event',
							'label' 		=> __( 'First visit time expanded the Chat', 'Allio' )
					)
			),*/
			'language' => array(
					'title' 	=> __( 'Language', 'Allio' ),
					'callback' 	=> 'allioc_field_select',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'language',
							'label' 		=> __( 'Leave blank for current locale.', 'Allio' ),
							'select_options' => array(
									'' 				=> '',
									'pt-BR' 		=> __( 'Brazilian Portuguese', 'Allio' ),
									'zh-HK' 		=> __( 'Chinese (Cantonese)', 'Allio' ),
									'zh-CN' 		=> __( 'Chinese (Simplified)', 'Allio' ),
									'zh-TW' 		=> __( 'Chinese (Traditional)', 'Allio' ),
									'en' 			=> __( 'English', 'Allio' ),
									'en-AU' 		=> __( 'English - Autralian locale', 'Allio' ),
									'en-CA' 		=> __( 'English - Canadian locale', 'Allio' ),
									'en-GB' 		=> __( 'English - Great Britain locale', 'Allio' ),
									'en-IN' 		=> __( 'English - Indian locale', 'Allio' ),
									'en-US' 		=> __( 'English - US locale', 'Allio' ),
									'nl' 			=> __( 'Dutch', 'Allio' ),
									'fr' 			=> __( 'French', 'Allio' ),
									'fr-CA' 		=> __( 'French - Canadian locale', 'Allio' ),
									'gr' 			=> __( 'German', 'Allio' ),
									'it' 			=> __( 'Italian', 'Allio' ),
									'ja' 			=> __( 'Japanese', 'Allio' ),
									'ko' 			=> __( 'Korean', 'Allio' ),
									'pt' 			=> __( 'Portuguese', 'Allio' ),
									'ru' 			=> __( 'Russian', 'Allio' ),
									'es' 			=> __( 'Spanish', 'Allio' ),
									'es-419' 		=> __( 'Spanish - Latin America locale', 'Allio' ),
									'es-ES' 		=> __( 'Spanish - Spain locale', 'Allio' ),
									'uk' 			=> __( 'Ukranian', 'Allio' )
								)
					)
			),
			'messaging_platform' => array(
					'title' 	=> __( 'Messaging Platform', 'Allio' ),
					'callback' 	=> 'allioc_field_radio_buttons',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'messaging_platform',
							'radio_buttons'	=> array(
									array(
											'value' => 'default',
											'label' => __( 'Default', 'Allio' ),
									),
									array(
											'value' => 'google',
											'label' => __( 'Actions on Google', 'Allio' ),
									),
									array(
											'value' => 'facebook',
											'label' => __( 'Facebook Messenger', 'Allio' ),
									),
									array(
											'value' => 'slack',
											'label' => __( 'Slack', 'Allio' ),
									),
									array(
											'value' => 'telegram',
											'label' => __( 'Telegram', 'Allio' ),
									),
									array(
											'value' => 'kik',
											'label' => __( 'Kik', 'Allio' ),
									),
									array(
											'value' => 'viber',
											'label' => __( 'Viber', 'Allio' ),
									),
									array(
											'value' => 'skype',
											'label' => __( 'Skype', 'Allio' ),
									)

							),
							'label'			=> __( 'Assume appearance of a Dialogflow supported messaging platform. Note default responses do not support rich message content.', 'Allio' )
					)
			),
			'show_time' => array(
					'title' 	=> __( 'Show Time', 'Allio' ),
					'callback' 	=> 'allioc_field_checkbox',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'show_time',
							'label' 		=> __( 'Check this box if you want to show the time underneath the conversation bubbles.', 'Allio' )
					)
			),
			'show_loading' => array(
					'title' 	=> __( 'Show Loading', 'Allio' ),
					'callback' 	=> 'allioc_field_checkbox',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'show_loading',
							'label' 		=> __( 'Check this box if you want to display loading dots until a response is returned.', 'Allio' )
					)
			),
			'loading_dots_color' => array(
					'title' 	=> __( 'Loading Dots Color', 'Allio' ),
					'callback' 	=> 'allioc_field_color_picker',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'loading_dots_color',
							'label'			=> __( 'Choose a color for the loading dots.', 'Allio' )
					)
			),
			'response_delay' => array(
					'title' 	=> __( 'Response Delay', 'Allio' ),
					'callback' 	=> 'allioc_field_input',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'response_delay',
							'label' 		=> __( 'milliseconds. Add a delay between messages.', 'Allio' ),
							'min'			=> 0,
							'max'			=> 5000,
							'required'		=> true,
							'type' 			=> 'number',
							'class'			=> 'small-text'
					)
			),
			'request_background_color' => array(
					'title' 	=> __( 'Request Background Color', 'Allio' ),
					'callback' 	=> 'allioc_field_color_picker',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'request_background_color',
							'label'			=> __( 'Choose a background color for the conversation request bubble.', 'Allio' )
					)
			),
			'request_font_color' => array(
					'title' 	=> __( 'Request Font Color', 'Allio' ),
					'callback' 	=> 'allioc_field_color_picker',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'request_font_color',
							'label'			=> __( 'Choose a font color for the conversation request text.', 'Allio' )
					)
			),
			'response_background_color' => array(
					'title' 	=> __( 'Response Background Color', 'Allio' ),
					'callback' 	=> 'allioc_field_color_picker',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'response_background_color',
							'label'			=> __( 'Choose a background color for the conversation response bubble.', 'Allio' )
					)
			),
			'response_font_color' => array(
					'title' 	=> __( 'Response Font Color', 'Allio' ),
					'callback' 	=> 'allioc_field_color_picker',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'response_font_color',
							'label'			=> __( 'Choose a font color for the conversation response text.', 'Allio' )
					)
			),
			'non_current_opacity' => array(
					'title' 	=> __( 'Non Current Opacity', 'Allio' ),
					'callback' 	=> 'allioc_field_input',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'non_current_opacity',
							'label' 		=> __( 'Set the background color opacity for non current converation bubbles.', 'Allio' ),
							'type'			=> 'number',
							'class'			=> 'small-text',
							'min'			=> 0,
							'max'			=> 1,
							'step'			=> 0.05,
							'required'		=> true
					)
			),
			'enable_overlay' => array(
					'title' 	=> __( 'Enable Overlay', 'Allio' ),
					'callback' 	=> 'allioc_field_checkbox',
					'page' 		=> 'Allio&tab=allioc_overlay_settings',
					'section' 	=> 'allioc_section_overlay',
					'args' => array(
							'option_name' 	=> 'allioc_overlay_settings',
							'setting_id' 	=> 'enable_overlay',
							'label' 		=> __( 'Check this box if you want to enable an overlay of the chatbot on every page. Note you can override this setting from the Edit post screen for specific posts.', 'Allio' )
					)
			),
			'chat_logo' => array(
				'title' 	=> __( 'chat logo', 'Allio' ),
				'callback' 	=> 'allioc_field_media_selector',
				'page' 		=> 'Allio&tab=allioc_overlay_settings',
				'section' 	=> 'allioc_section_overlay',
				'args' => array(
						'option_name' 	=> 'allioc_overlay_settings',
						'setting_id' 	=> 'chat_logo',
						'label' 		=> __( 'Open the chat logo', 'Allio' )
				)
			),
			'user_icon' => array(
				'title' 	=> __( 'User Icon', 'Allio' ),
				'callback' 	=> 'allioc_field_media_selector',
				'page' 		=> 'Allio&tab=allioc_overlay_settings',
				'section' 	=> 'allioc_section_overlay',
				'args' => array(
						'option_name' 	=> 'allioc_overlay_settings',
						'setting_id' 	=> 'user_icon',
						'label' 		=> __( 'Open the chat logo', 'Allio' )
				)
			),
			'background_img' => array(
				'title' 	=> __( 'Background Image', 'Allio' ),
				'callback' 	=> 'allioc_field_media_selector',
				'page' 		=> 'Allio&tab=allioc_overlay_settings',
				'section' 	=> 'allioc_section_overlay',
				'args' => array(
						'option_name' 	=> 'allioc_overlay_settings',
						'setting_id' 	=> 'background_img',
						'label' 		=> __( 'Open the chat logo', 'Allio' )
				)
			),
			'position_right' => array(
				'title' 	=> __( 'Position Right', 'Allio' ),
				'callback' 	=> 'allioc_field_input',
				'page' 		=> 'Allio&tab=allioc_overlay_settings',
				'section' 	=> 'allioc_section_overlay',
				'args' => array(
						'option_name' 	=> 'allioc_overlay_settings',
						'setting_id' 	=> 'position_right',
						'label' 		=> __( 'px', 'Allio' ),
						'placeholder'	=> __( 'right position', 'Allio' )
				)
			),
			'position_bottom' => array(
				'title' 	=> __( 'Position Bottom', 'Allio' ),
				'callback' 	=> 'allioc_field_input',
				'page' 		=> 'Allio&tab=allioc_overlay_settings',
				'section' 	=> 'allioc_section_overlay',
				'args' => array(
						'option_name' 	=> 'allioc_overlay_settings',
						'setting_id' 	=> 'position_bottom',
						'label' 		=> __( 'px', 'Allio' ),
						'placeholder'	=> __( 'bottom position', 'Allio' )
				)
			),
			'overlay_header_background_color' => array(
					'title' 	=> __( 'Header Background Color', 'Allio' ),
					'callback' 	=> 'allioc_field_color_picker',
					'page' 		=> 'Allio&tab=allioc_overlay_settings',
					'section' 	=> 'allioc_section_overlay',
					'args' => array(
							'option_name' 	=> 'allioc_overlay_settings',
							'setting_id' 	=> 'overlay_header_background_color',
							'label'			=> __( 'Choose a background color for the overlay header.', 'Allio' )
					)
			),
			'overlay_header_font_color' => array(
					'title' 	=> __( 'Header Font Color', 'Allio' ),
					'callback' 	=> 'allioc_field_color_picker',
					'page' 		=> 'Allio&tab=allioc_overlay_settings',
					'section' 	=> 'allioc_section_overlay',
					'args' => array(
							'option_name' 	=> 'allioc_overlay_settings',
							'setting_id' 	=> 'overlay_header_font_color',
							'label'			=> __( 'Choose a font color for the overlay header text.', 'Allio' )
					)
			),
			'overlay_default_open' => array(
					'title' 	=> __( 'Default Open', 'Allio' ),
					'callback' 	=> 'allioc_field_checkbox',
					'page' 		=> 'Allio&tab=allioc_overlay_settings',
					'section' 	=> 'allioc_section_overlay',
					'args' => array(
							'option_name' 	=> 'allioc_overlay_settings',
							'setting_id' 	=> 'overlay_default_open',
							'label' 		=> __( 'Check this box if you want to default the overlay to open on page load.', 'Allio' )
					)
			),
			'overlay_header_text' => array(
					'title' 	=> __( 'Header Text', 'Allio' ),
					'callback' 	=> 'allioc_field_input',
					'page' 		=> 'Allio&tab=allioc_overlay_settings',
					'section' 	=> 'allioc_section_overlay',
					'args' => array(
							'option_name' 	=> 'allioc_overlay_settings',
							'setting_id' 	=> 'overlay_header_text',
							'label' 		=> __( 'Enter overlay header text.', 'Allio' ),
							'placeholder'	=> __( 'Enter overlay header text...', 'Allio' )
					)
			),
			'overlay_powered_by_text' => array(
					'title' 	=> __( 'Powered By Text', 'Allio' ),
					'callback' 	=> 'allioc_field_input',
					'page' 		=> 'Allio&tab=allioc_overlay_settings',
					'section' 	=> 'allioc_section_overlay',
					'args' => array(
							'option_name' 	=> 'allioc_overlay_settings',
							'setting_id' 	=> 'overlay_powered_by_text',
							'label' 		=> __( 'Enter overlay powered by text. If empty, the powered by bar will not be displayed.', 'Allio' ),
							'placeholder'	=> __( 'Enter powered by text...', 'Allio' )
					)
			),
			'disable_css_styles' => array(
					'title' 	=> __( 'Disable CSS Styles', 'Allio' ),
					'callback' 	=> 'allioc_field_checkbox',
					'page' 		=> 'Allio&tab=allioc_general_settings',
					'section' 	=> 'allioc_section_general',
					'args' => array(
							'option_name' 	=> 'allioc_general_settings',
							'setting_id' 	=> 'disable_css_styles',
							'label' 		=> __( 'Check this box if you want to disable loading the plugin default CSS styles.', 'Allio' )
					)
			),
			'allioc_custom_css' => array(
				'title' 	=> __( 'Custom Style', 'Allio' ),
				'callback' 	=> 'allioc_field_text',
				'page' 		=> 'Allio&tab=allioc_general_settings',
				'section' 	=> 'allioc_section_general',
				
				'args' => array(
						'option_name' 	=> 'allioc_general_settings',
						'setting_id' 	=> 'allioc_custom_css',
						'label' 		=> __( '', 'Allio' ),
						'placeholder'	=> __( 'Enter Custom Style..', 'Allio' ),
						'required'		=> true,
						'class' => 'allioc_custom_css',
				)
		),
	);

	foreach ( $setting_fields as $setting_id => $setting_data ) {
		// $id, $title, $callback, $page, $section, $args
		add_settings_field( $setting_id, $setting_data['title'], $setting_data['callback'], $setting_data['page'], $setting_data['section'], $setting_data['args'] );
	}
}

function allioc_include_all() {
	$general_settings = (array) get_option( 'allioc_general_settings' );
	$key = $general_settings["active_key"];
	$url = site_url();
	$domain = str_replace("http://", "", $url);
	$domain = str_replace("https://", "", $domain);
	if (strpos($domain, "localhost") !== false) {
		return true;
	}
	
	if (md5($domain . "7211") == $key){
		return true;
	} else {
		return false;
	}
}
/**
 * Set default settings if not set
 */
function allioc_default_settings() {

	$general_settings = (array) get_option( 'allioc_general_settings' );

	$general_settings = array_merge( array(
			'active_key'		=> '',
			'allioc_access_token' 					=> '',
			'Stripe_PK' => '',
			'Stripe_SK' => '',
			'input_text'						=> __( 'Ask something...', 'Allio' ),
			'enable_welcome_event'				=> false,
			'language'							=> 'en',
			'messaging_platform'				=> 'default',
			'show_time'							=> true,
			'show_loading'						=> true,
			'loading_dots_color'				=> '#1f4c73',
			'response_delay'					=> 1500,

			// conversation bubbles
			'request_background_color'			=> '#1f4c73',
			'request_font_color'				=> '#fff',
			'response_background_color'			=> '#e8e8e8',
			'response_font_color'				=> '#323232',
			'non_current_opacity'				=> 0.8,

			'disable_css_styles'				=> false,
			'allioc_custom_css'					=> '',
			'allioc_welcome_message'	=> '',
	), $general_settings );

	update_option( 'allioc_general_settings', $general_settings );

	$overlay_settings = (array) get_option( 'allioc_overlay_settings' );

	$overlay_settings = array_merge( array(
			'enable_overlay'					=> true,
			'chat_logo' => '',
			"user_icon" => "",
			"background_img" => '',
			'position_bottom' => '10',
			'position_right' => '10',
			'overlay_default_open'				=> false,
			'overlay_powered_by_text'			=> __( 'Powered by <a href="#">Replace Me</a>', 'Allio' ),
			'overlay_header_text'				=> __( 'Allio', 'Allio' ),
			'overlay_header_background_color'	=> '#1f4c73',
			'overlay_header_font_color'			=> '#fff',
	), $overlay_settings );

	update_option( 'allioc_overlay_settings', $overlay_settings );

}

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	add_action( 'admin_init', 'allioc_default_settings', 10, 0 );
	add_action( 'admin_init', 'allioc_register_settings' );
}

/**
 * Sanitize general settings
 * @param 	$input
 */
function allioc_sanitize_general_settings( $input ) {

	if ( isset( $input['enable_welcome_event'] ) && $input['enable_welcome_event'] == 'true' ) {
		$input['enable_welcome_event'] = true;
	} else {
		$input['enable_welcome_event'] = false;
	}

	if ( isset( $input['show_time'] ) && $input['show_time'] == 'true' ) {
		$input['show_time'] = true;
	} else {
		$input['show_time'] = false;
	}

	if ( isset( $input['show_loading'] ) && $input['show_loading'] == 'true' ) {
		$input['show_loading'] = true;
	} else {
		$input['show_loading'] = false;
	}

	if ( ! is_numeric( $input['response_delay'] ) ) {
		add_settings_error( 'allioc_general_settings', 'non_numeric_response_delay', __( 'Response delay must be numeric.' , 'Allio' ), 'error' );
	} else if ( intval( $input['response_delay'] ) < 0 || intval( $input['response_delay'] ) > 5000 ) {
		add_settings_error( 'allioc_general_settings', 'range_error_response_delay', __( 'Response delay cannot be less than 0 or greater than 5000.', 'Allio' ), 'error' );
	}

	if ( isset( $input['disable_css_styles'] ) && $input['disable_css_styles'] == 'true' ) {
		$input['disable_css_styles'] = true;
	} else {
		$input['disable_css_styles'] = false;
	}

	if ( ! is_numeric( $input['non_current_opacity'] ) ) {
		add_settings_error( 'allioc_general_settings', 'non_numeric_non_current_opacity', __( 'Non current opacity must be numeric.' , 'Allio' ), 'error' );
	} else if ( floatval( $input['non_current_opacity'] ) < 0 || floatval( $input['non_current_opacity'] ) > 1 ) {
		add_settings_error( 'allioc_general_settings', 'range_error_non_current_opacity', __( 'Non current opacity cannot be less than 0 or greater than 1.', 'Allio' ), 'error' );
	}

	return $input;
}

function allioc_sanitize_overlay_settings( $input ) {

	if ( isset( $input['overlay_default_open'] ) && $input['overlay_default_open'] == 'true' ) {
		$input['overlay_default_open'] = true;
	} else {
		$input['overlay_default_open'] = false;
	}

	if ( isset( $input['enable_overlay'] ) && $input['enable_overlay'] == 'true' ) {
		$input['enable_overlay'] = true;
	} else {
		$input['enable_overlay'] = false;
	}

	return $input;
}
