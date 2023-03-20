<?php

namespace omSocialLogin;

/**
 * @author Roman Ozana <roman@ozana.cz>
 */
class UserColumns {

	public function __construct() {
		add_filter('manage_users_columns', [$this, 'manage_users_columns']);
		add_action('manage_users_custom_column', [$this, 'manage_users_custom_column'], 9, 3);
	}

	public function manage_users_columns($columns) {
		$columns['auth_connect'] = __('Connected', SL);
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
		if ($name === 'auth_connect') {
			ob_start();
			require omSocialLogin::template('user/UserColumn.phtml');
			$return = ob_get_contents();
			ob_clean();
			return $return;
		}
	}

}