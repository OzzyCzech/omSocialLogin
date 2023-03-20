<?php

namespace omSocialLogin;

use omSocialLogin\providers\Container;

/**
 * @author Roman Ožana <roman@ozana.cz>
 */
class ProfileConnection {

	/** @var Container */
	private $providers;

	/**
	 * @param Container $providers
	 */
	public function __construct(Container $providers) {
		$this->providers = $providers;
		add_action('show_user_profile', [$this, 'user_profile']);
		add_action('edit_user_profile', [$this, 'user_profile']);

		add_action('personal_options_update', [$this, 'user_update']);
		add_action('edit_user_profile_update', [$this, 'user_update']);
	}

	/**
	 * @param string $user_id
	 */
	public function user_update($user_id) {
		$connected = UserMeta::getConnectedProviders($user_id);

		if (isset($_POST['disconnect']) && isset($connected[$_POST['disconnect']])) {
			UserMeta::removeProvider($user_id, $_POST['disconnect']);
		}
	}

	/**
	 * @param \WP_User $user
	 * @return bool
	 */
	public function user_profile($user) {
		$connected = UserMeta::getConnectedProviders($user->ID);
		$creatorProviderName = UserMeta::getCreatorProviderName($user->ID);
		require omSocialLogin::template('user/ProfileConnection.phtml');
	}

}