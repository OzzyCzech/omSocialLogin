<?php
namespace omSocialLogin;

use omSocialLogin\providers\Container;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class UserColumns {

	public function __construct() {
		add_filter('manage_users_columns', array($this, 'manage_users_columns'));
		add_action('manage_users_custom_column', array($this, 'manage_users_custom_column'), 10, 3);
	}

	public function manage_users_columns($columns) {
		$columns['auth_login'] = __('Social login', SL);
		return $columns;
	}

	/**
	 *
	 * @param $value
	 * @param $name
	 * @param $user_id
	 * @return null|string
	 */
	public function manage_users_custom_column($value, $name, $user_id) {
		if ($name === 'auth_login') {
			ob_start();
			require omSocialLogin::template('user/UserColumn.phtml');
			$return = ob_get_contents();
			ob_clean();
			return $return;
		}

	}

}