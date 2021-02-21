<?php
/**
 * Plugin Name: Bootstrap Conditional
 * Plugin URI: https://wordpress.org/plugins/bootstrap-conditional/
 * Description: Bootstrap Conditional - load full Bootstrap version 3 or 4
 * Author: <a href="https://wpbeaches.com">Neil Gowran</a>,
 * Version: 1.2.0
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: bootstrap-conditional
 * Domain Path: /languages
 *
 * @package Bootstrap Conditional
 */
define( 'BOOTSTRAP_CONDITIONAL', '1.2.0' );
if ( ! class_exists( 'Bootstrap_Conditional', false ) ) {
	require_once dirname( __FILE__ ) . '/inc/class-bootstrap-conditional.php';
}
// Get it started.
bootstrap_conditional();
