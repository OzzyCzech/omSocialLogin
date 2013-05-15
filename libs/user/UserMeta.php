<?php
namespace omSocialLogin;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class UserMeta {

	/**
	 * Add all avaliable response data to user
	 *
	 * @param $user_id
	 * @param Response $response
	 * @param bool $created
	 */
	public static function addResponseData($user_id, Response $response, $created = false) {
		// save info
		$key = UserMeta::key('auth', $response->getProvider(), null);

		// update connected social accounts
		$connected = UserMeta::getConnectedProviders($user_id);
		$connected[$response->getProvider()] = true;
		update_user_meta($user_id, 'auth_connected', array($response->getProvider() => true));

		// update others data
		update_user_meta($user_id, $key . 'uid', $response->getUserUid());
		update_user_meta($user_id, $key . 'image', $response->getUserImage());
		update_user_meta($user_id, $key . 'info', $response->getInfo());
		update_user_meta($user_id, $key . 'signature', $response->getSignature());

		// Twitter can be save in default Wodpress meta
		if ($response->getProvider() === 'Twitter') {
			update_user_meta($user_id, 'twitter', preg_replace('/@/', '', $response->getUserNickname()));
		}

		if ($created) {
			update_user_meta($user_id, 'auth_created_by', $response->getProvider());
		} else {
			// TODO update also user mail, description, name etc.
			// $isSocialAccount = get_user_meta($user_id, 'auth_social_created', true)
		}
	}


	/**
	 * Return user info
	 *
	 * @param string $user_id
	 * @param string $provider
	 * @return array
	 */
	public static function getSignature($user_id, $provider) {
		return get_user_meta($user_id, 'auth_' . $provider . '_signature', true);
	}


	/**
	 * Return user info
	 *
	 * @param string $user_id
	 * @param string $provider
	 * @return array
	 */
	public static function getInfo($user_id, $provider) {
		return get_user_meta($user_id, 'auth_' . $provider . '_info', true);
	}


	/**
	 * Return user image by Provider
	 *
	 * @param $user_id
	 * @param null $provider
	 * @return mixed
	 */
	public static function getImageUrl($user_id, $provider = null) {
		if ($provider === null) $provider = UserMeta::getCreatorProviderName($user_id);
		return get_user_meta($user_id, 'auth_' . $provider . '_image', true);
	}


	public static function getConnectedProviders($user_id) {
		if ($connected = get_user_meta($user_id, 'auth_connected', true)) {
			return (array)$connected;
		} else {
			return array();
		}
	}

	/**
	 * Return name of profider if user was created by social Network
	 *
	 * @param $user_id
	 * @return mixed
	 */
	public static function getCreatorProviderName($user_id) {
		return get_user_meta($user_id, 'auth_created_by', true);
	}

	/**
	 * Return user by metadata value
	 *
	 * @param $meta_key
	 * @param $meta_value
	 * @return \WP_User|false
	 */
	public static function getUser($meta_key, $meta_value) {
		return reset(
			get_users(
				array(
					'meta_key' => $meta_key,
					'meta_value' => $meta_value,
					'number' => 1,
					'count_total' => false
				)
			)
		);
	}

	/**
	 * Generate key from inputs
	 *
	 * @return string
	 */
	public static function key() {
		return implode('_', func_get_args());
	}

	public static function uidMetaKey($provider) {
		return self::key('auth', $provider, 'uid');
	}
}