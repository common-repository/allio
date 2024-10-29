<?php
/**
 * Admin Pages
 *
 * @package     ALLIOC
 * @subpackage  Admin/Pages
 * @copyright   Copyright (c) 2017, Daniel Powney
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Creates an options page for plugin settings and links it to a global variable
 *
 * @since 0.1
 * @return void
 */
function allioc_add_options_link() {
	global $allioc_settings_page;

	$allioc_settings_page      = 	add_options_page( __( 'Allio', 'Allio' ), __( 'Allio', 'Allio' ), 'manage_options', 'Allio', 'allioc_options_page');
	
}
add_action( 'admin_menu', 'allioc_add_options_link', 10 );