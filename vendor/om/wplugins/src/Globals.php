<?php
namespace om;
/**
 * Return all Wordpress Globals variable
 *
 * @see http://codex.wordpress.org/Global_Variables
 * @author Roman Ozana <ozana@omdesign.cz>
 *
 * @method static \WP_Post post()
 * @method static authordata()
 * @method static currentday()
 * @method static page()
 * @method static pages()
 * @method static multipage()
 * @method static more()
 * @method static numpages()
 * @method static bool is_iphone()
 * @method static bool is_chrome()
 * @method static bool is_safari()
 * @method static bool is_NS4()
 * @method static bool is_opera()
 * @method static bool is_macIE()
 * @method static bool is_winIE()
 * @method static bool is_gecko()
 * @method static bool is_lynx()
 * @method static bool is_IE()
 * @method static is_apache()
 * @method static is_IIS()
 * @method static is_iis7()
 * @method static array wp_filter($name = null)
 * @method static string wp_version()
 * @method static string wp_db_version()
 * @method static array wp_taxonomies($name = null)
 * @method static string tinymce_version()
 * @method static string manifest_version()
 * @method static string required_php_version()
 * @method static string required_mysql_version()
 * @method static string pagenow()
 * @method static array allowedposttags()
 * @method static array allowedtags()
 * @method static wpdb wpdb()
 * @method static WP_Query wp_query()
 * @method static WP_Query wp_the_query()
 * @method static WP_Rewrite wp_rewrite()
 * @method static WP wp()
 * @method static WP_Locale wp_locale()
 * @method static shortcode_tags()
 * @method static WP_Embed wp_embed()
 * @method static string blog_id()
 * @method static WP_User current_user()
 * @method static WP_User userdata()
 * @method static WP_Roles wp_roles()
 * @method static WP_Object_Cache wp_object_cache()
 * @method static WP_Widget_Factory wp_widget_factory()
 * @method static WP_Styles wp_styles()
 */
class Globals {

	/**
	 * Return global variable
	 *
	 * @param $name
	 * @param $args
	 * @throws Exception
	 * @return mixed
	 */
	public static function __callStatic($name, $args) {
		return is_array($var = self::get($name)) && !empty($args) ? $var[reset($args)] : $var;
	}

	/**
	 * @param string $name
	 * @return mixed
	 * @throws Exception
	 */
	private static function &get($name) {
		if (array_key_exists($name, $GLOBALS)) {
			return $GLOBALS[$name];
		} else {
			throw new Exception('Variable ' . $name . ' not exists');
		}
	}
}