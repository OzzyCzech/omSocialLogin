<?php
namespace omSocialLogin;
/**
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class UserMeta {

	const KEY_UID = 'auth_%s_uid';
	const KEY_IMAGE = 'auth_%s_image';
	const KEY_INFO = 'auth_%s_info';
	const KEY_SIGNATURE = 'auth_%s_signature';
	const KEY_CONNECTED = 'auth_connected'; // array of connected provider
	const KEY_CREATED_BY = 'auth_created_by';

	/**
	 * Add all avaliable response data to user
	 *
	 * @param $user_id
	 * @param Response $response
	 * @param bool $created
	 */
	public static function addResponseData($user_id, Response $response, $created = false) {

		// update connected social accounts
		$connected = UserMeta::getConnectedProviders($user_id);
		$connected[$response->getProvider()] = true;
		update_user_meta($user_id, self::KEY_CONNECTED, (array)$connected);

		// update others data

		$meta = array(
			sprintf(self::KEY_UID, $response->getProvider()) => $response->getUserUid(),
			sprintf(self::KEY_IMAGE, $response->getProvider()) => $response->getUserImage(),
			sprintf(self::KEY_INFO, $response->getProvider()) => $response->getInfo(),
			sprintf(self::KEY_SIGNATURE, $response->getProvider()) => $response->getSignature(),
		);

		foreach ($meta as $key => $value) {
			update_user_meta($user_id, $key, $value);
		}

		// Twitter can be save in default Wodpress meta
		if ($response->getProvider() === 'Twitter') {
			update_user_meta($user_id, 'twitter', ltrim($response->getUserNickname(), '@'));
		}

		if ($created) {
			update_user_meta($user_id, self::KEY_CREATED_BY, $response->getProvider());
		}
	}

	/**
	 * Remove provider from user
	 *
	 * @param string $user_id
	 * @param string $provider
	 * @return bool
	 */
	public static function removeProvider($user_id, $provider) {
		// user can't be disconnected if was created by social provider
		if (get_user_meta($user_id, self::KEY_CREATED_BY, true)) return false;

		// remove all data about social connected account
		delete_user_meta($user_id, sprintf(self::KEY_UID, $provider));
		delete_user_meta($user_id, sprintf(self::KEY_IMAGE, $provider));
		delete_user_meta($user_id, sprintf(self::KEY_INFO, $provider));
		delete_user_meta($user_id, sprintf(self::KEY_SIGNATURE, $provider));

		// update connected social networks array
		$connected = UserMeta::getConnectedProviders($user_id);
		unset($connected[$provider]); // remove provider
		update_user_meta($user_id, self::KEY_CONNECTED, (array)$connected);

		return true;
	}

	/**
	 * Merge user account when come from
	 *
	 * @param $user_id
	 * @param $provider
	 * @param $uid
	 */
	public static function mergeUsers($user_id, $provider, $uid) {

		// 1. get all existing users created by provider

		$users = get_users(
			array(
				'meta_query' => array(
					array(
						'key' => sprintf(self::KEY_UID, $provider),
						'value' => $uid,
						'compare' => '='
					),
					array(
						'key' => self::KEY_CREATED_BY,
						'value' => $provider,
						'compare' => '='
					),
				),
				'exclude' => array($user_id), // exclude
				'count_total' => false
			)
		);

		// 2. merge them with $user_id (delete users created by social network)

		foreach ($users as $user) {
			require_once(ABSPATH . 'wp-admin/includes/user.php');
			/** @var \WP_User $user */
			wp_delete_user($user->ID, $user_id);
		}
	}


	/**
	 * Return UID from social provider
	 *
	 * @param string $user_id
	 * @param string $provider
	 * @return mixed
	 */
	public static function getUid($user_id, $provider) {
		return get_user_meta($user_id, sprintf(self::KEY_UID, $provider), true);
	}


	/**
	 * Return user info
	 *
	 * @param string $user_id
	 * @param string $provider
	 * @return array
	 */
	public static function getSignature($user_id, $provider) {
		return get_user_meta($user_id, sprintf(self::KEY_SIGNATURE, $provider), true);
	}


	/**
	 * Return user info
	 *
	 * @param string $user_id
	 * @param string $provider
	 * @return array
	 */
	public static function getInfo($user_id, $provider) {
		return get_user_meta($user_id, sprintf(self::KEY_INFO, $provider), true);
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
		return get_user_meta($user_id, sprintf(self::KEY_IMAGE, $provider), true);
	}


	/**
	 * Return currently connected providers
	 *
	 * @param $user_id
	 * @return array
	 */
	public static function getConnectedProviders($user_id) {
		if ($connected = get_user_meta($user_id, self::KEY_CONNECTED, true)) {
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
		return get_user_meta($user_id, self::KEY_CREATED_BY, true);
	}

	/**
	 * Return all possible providers for user (origin + connected)
	 *
	 * @param $user_id
	 * @return mixed
	 */
	public static function getAllProviders($user_id) {
		$output = static::getConnectedProviders($user_id);
		if ($origin = static::getCreatorProviderName($user_id)) {
			$output[$origin] = true;
		}
		return $output;
	}

	/**
	 * Return Wordpress user by provider and UIDs
	 *
	 * @param string $provider
	 * @param string $uid
	 * @return false|\WP_User
	 */
	public static function getUserByUid($provider, $uid) {
		return reset(
			get_users(
				array(
					'meta_key' => sprintf(self::KEY_UID, $provider),
					'meta_value' => $uid,
					'number' => 1,
					'count_total' => false
				)
			)
		);
	}

}