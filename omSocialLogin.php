<?php
namespace omSocialLogin;

use omSocialLogin\providers\Container;

/**
 * Plugin Name: omSocialLogin
 * Plugin URI: http://www.omdesign.cz
 * Description: Social login
 * Version: 1.0
 * Author: Roman Ožana
 * Author URI: http://www.omdesign.cz/kontakt
 */

if (!class_exists('WP')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

define('SL', 'sl'); // textdomain name

/**
 * Main plugin class
 *
 * @author Roman Ožana <ozana@omdesign.cz>
 */
class omSocialLogin {


	public function __construct() {
		add_action('init', array($this, 'init'));
	}

	public function init() {
		// TODO load_plugin_textdomain(SL, false, basename(__DIR__) . '/languages/');
	}

	/**
	 * Plugin activate
	 */
	public function activate() {
		flush_rewrite_rules();
	}

	/**
	 * Plugin deactivate
	 */
	public function deactivate() {
		flush_rewrite_rules();
	}

	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * Return plugin URL
	 *
	 * @param string $path
	 * @return string
	 */
	public static function uri($path = '') {
		return sprintf(plugins_url($path, __FILE__));
	}

	/**
	 * Return file name
	 *
	 * @return string
	 */
	public static function file() {
		return __FILE__;
	}

	/**
	 * @return string
	 */
	public static function dir() {
		return __DIR__;
	}

	/**
	 * Return current plugin name
	 *
	 * @return string
	 */
	public static function name() {
		return plugin_basename(__FILE__);
	}

	public static function template($file) {
		return __DIR__ . '/templates/' . $file;
	}

}

// ---------------------------------------------------------------------------------------------------------------------

$omSocialLogin = new omSocialLogin();

register_activation_hook(__FILE__, array($omSocialLogin, 'activate'));
register_deactivation_hook(__FILE__, array($omSocialLogin, 'deactivate'));

// ---------------------------------------------------------------------------------------------------------------------

$providers = new Container('Facebook', 'Twitter', 'Google');

new Settings($providers); // plugin settings
new Frontend($providers); // frontend functions
new Auth($providers); //
new UserColumns($providers);
new Query(); //
new Avatar();

// ---------------------------------------------------------------------------------------------------------------------
