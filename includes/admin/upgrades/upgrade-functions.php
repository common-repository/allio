<?php

/**
 * Check for updates
 */
function allioc_upgrade_check() {

	// Check if we need to do an upgrade from a previous version
	$previous_plugin_version = get_option( 'allioc_plugin_version' );

	if ( ! isset( $previous_plugin_version ) ) {
		//return;
	}

	if ( ! allioc_is_func_disabled( 'set_time_limit' ) && ! ini_get( 'safe_mode' ) ) {
		@set_time_limit( 0 );
	}

	if ( $previous_plugin_version != ALLIOC_VERSION && $previous_plugin_version < 0.4 ) {
		allioc_upgrade_to_0_4();
	}

	update_option( 'allioc_plugin_version', ALLIOC_VERSION ); // latest version upgrade complete

}
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	allioc_upgrade_check();
}


/**
 * Upgrade to v0.4. Copy some settings.
 */
function allioc_upgrade_to_0_4() {

	$general_settings = (array) get_option( 'allioc_general_settings' );
	$overlay_settings = (array) get_option( 'allioc_overlay_settings' );

	/*
	 * Copy the following settings from the general settings to overlay settings:
	 * enable_overlay, overlay_default_open, overlay_powered_by_text, overlay_header_text,
	 * overlay_header_background_color and overlay_header_font_color
	 */
	if ( isset( $general_settings['enable_overlay'] ) ) {
		$overlay_settings['enable_overlay'] = $general_settings['enable_overlay'];
		unset( $general_settings['enable_overlay'] );
	}
	if ( isset( $general_settings['chat_logo'] ) ) {
		$overlay_settings['chat_logo'] = $general_settings['chat_logo'];
		unset( $general_settings['chat_logo'] );
	}

	if ( isset( $general_settings['user_icon'] ) ) {
		$overlay_settings['user_icon'] = $general_settings['user_icon'];
		unset( $general_settings['user_icon'] );
	}

	if ( isset( $general_settings['background_img'] ) ) {
		$overlay_settings['background_img'] = $general_settings['background_img'];
		unset( $general_settings['background_img'] );
	}

	if ( isset( $general_settings['overlay_default_open'] ) ) {
		$overlay_settings['overlay_default_open'] = $general_settings['overlay_default_open'];
		unset( $general_settings['overlay_default_open'] );
	}
	if ( isset( $general_settings['overlay_powered_by_text'] ) ) {
		$overlay_settings['overlay_powered_by_text'] = $general_settings['overlay_powered_by_text'];
		unset( $general_settings['overlay_powered_by_text'] );
	}
	if ( isset( $general_settings['overlay_header_text'] ) ) {
		$overlay_settings['overlay_header_text'] = $general_settings['overlay_header_text'];
		unset( $general_settings['overlay_header_text'] );
	}
	if ( isset( $general_settings['overlay_header_background_color'] ) ) {
		$overlay_settings['overlay_header_background_color'] = $general_settings['overlay_header_background_color'];
		unset( $general_settings['overlay_header_background_color'] );
	}
	if ( isset( $general_settings['overlay_header_font_color'] ) ) {
		$overlay_settings['overlay_header_font_color'] = $general_settings['overlay_header_font_color'];
		unset( $general_settings['overlay_header_font_color'] );
	}

	update_option( 'allioc_general_settings',  $general_settings );
	update_option( 'allioc_overlay_settings',  $overlay_settings );

}
