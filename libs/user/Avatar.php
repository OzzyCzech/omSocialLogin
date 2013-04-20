<?php
namespace omSocialLogin;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Avatar {


	public function __construct() {
		add_filter('get_avatar', array($this, 'getSocialAvatar'), 999, 5);
	}


	public function getSocialAvatar($avatar = '', $id_or_email, $size = 96, $default = '', $alt = false) {
		if (is_admin() && get_current_screen()->base === 'options-discussion') return $avatar;

		$id = 0;

		if (is_numeric($id_or_email)) {
			$id = $id_or_email;
		} elseif (is_string($id_or_email)) {
			$u = get_user_by('email', $id_or_email);
			$id = $u->id;
		} elseif (is_object($id_or_email)) {
			$id = $id_or_email->user_id;
		}

		if ($id == 0) return $avatar;

		$img = UserMeta::getImageUrl($id); // getting default Avatar

		if (!$img || $img == '') return $avatar;

		return '<img src="' . $img . '" class="avatar avatar-wordpress-social-login avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />';
	}

}