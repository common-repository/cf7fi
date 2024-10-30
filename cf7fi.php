<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that also follow
 * WordPress coding standards and PHP best practices.
 *
 * @package   Contact Form 7 Formisimo Integration
 * @author    Marcus Franke <m.franke@internet-marketing-dresden.de>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Franke Online Marketing
 *
 * @wordpress-plugin
 * Plugin Name: Contact Form 7 Formisimo Integration
 * Plugin URI:  http://www.internet-marketing-dresen.de
 * Description: Integrate the Formisimo Tracking for Contact Form 7
 * Version:     1.0.1
 * Author:      Marcus Franke
 * Author URI:  http://www.internet-marketing-dresen.de
 * Text Domain: cf7fi-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-cf7fi.php' );

CF7FI::get_instance();