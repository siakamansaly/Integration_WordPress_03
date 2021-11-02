<?php

/**
 * Plugin Name:       Estatik
 * Plugin URI:        http://estatik.net
 * Description:       A simple version of Estatik Real Estate plugin for Wordpress.
 * Version:           3.11.4
 * Author:            Estatik
 * Author URI:        http://estatik.net
 * Text Domain:       es-plugin
 * License:           GPL2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'ES_PLUGIN_URL',               plugin_dir_url( __FILE__ ) );
define( 'ES_PLUGIN_PATH',              dirname( __FILE__ ) );
define( 'ES_ADMIN_IMAGES_URL',         ES_PLUGIN_URL  . 'admin/assets/images/' );
define( 'ES_ADMIN_CUSTOM_STYLES_URL',  ES_PLUGIN_URL  . 'admin/assets/css/custom/' );
define( 'ES_ADMIN_VENDOR_STYLES_URL',  ES_PLUGIN_URL  . '/admin/assets/css/vendor/' );
define( 'ES_ADMIN_CUSTOM_SCRIPTS_URL', ES_PLUGIN_URL  . 'admin/assets/js/custom/'  );
define( 'ES_ADMIN_VENDOR_SCRIPTS_URL', ES_PLUGIN_URL  . '/admin/assets/js/vendor/'  );
define( 'ES_ADMIN_TEMPLATES',          ES_PLUGIN_PATH . '/admin/templates/'         );
define( 'ES_TEMPLATES',                ES_PLUGIN_PATH . '/templates/'               );
define( 'ES_DS',                       DIRECTORY_SEPARATOR                          );
define( 'ES_PLUGIN_BASENAME',          plugin_basename(__FILE__)               );

// Main plugin class.
require_once 'classes/class-estatik-init.php';

// Register function on activate plugin action.
register_activation_hook( __FILE__, array( 'Estatik', 'install' ) );

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function es_load_textdomain() {
	load_plugin_textdomain( 'es-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'es_load_textdomain' );

// Initialize plugin.
Estatik::run();

register_deactivation_hook( __FILE__, array( 'Estatik', 'deactivation' ) );
register_activation_hook( __FILE__, array( 'Estatik', 'activation' ) );
