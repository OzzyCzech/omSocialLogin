<?php

namespace omSocialLogin;

/**
 * @author Roman Ozana <roman@ozana.cz>
 */
class Avatar {

	public function __construct() {
		add_filter('get_avatar', [$this, 'getSocialAvatar'], 999, 5);
	}

	public function getSocialAvatar($avatar = '', $id_or_email, $size = 96, $default = '', $alt = false) {
		if (is_admin() && get_current_screen()->base === 'options-discussion') return $avatar;

		$id = 0;

		if (is_numeric($id_or_email)) {
			$id = $id_or_email;
		} elseif (is_string($id_or_email)) {
			$u = get_user_by('email', $id_or_email);
			$id = $u->ID;
		} elseif (is_object($id_or_email)) {
			$id = $id_or_email->user_id;
		}

		if ($id == 0) return $avatar;

		$img = UserMeta::getImageUrl($id); // getting default Avatar

		if (!$img || $img == '') {
			return $avatar;
		} else {
			$img = esc_attr(preg_replace('#^https?:#', '', $img));
			return '<img src="' . $img . '" class="avatar avatar-wordpress-social-login avatar-' . esc_attr(
					$size
				) . ' photo" height="' . esc_attr($size) . '" width="' . esc_attr($size) . '" />';
		}
	}

}