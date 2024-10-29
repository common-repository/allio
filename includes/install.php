<?php
/**
 * Install Function
 *
 * @package     ALLIOC
 * @subpackage  Functions/Install
 * @copyright   Copyright (c) 2017, Daniel Powney
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1
 */

// Exit if accessed directly
if (! defined ( 'ABSPATH' ))
	exit ();

/**
 * Install
 */
function allioc_install() {
	
	// Add the transient to redirect
	set_transient( '_allioc_activation_redirect', true, 30 );
	
}
register_activation_hook( ALLIOC_PLUGIN_FILE, 'allioc_install' );