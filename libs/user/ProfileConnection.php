<?php
namespace omSocialLogin;

use omSocialLogin\providers\Container;

/**
 * @author Roman Ožana <ozana@omdesign.cz>
 */
class ProfileConnection {

	/** @var Container */
	private $providers;

	/**
	 * @param Container $providers
	 */
	public function __construct(Container $providers) {
		$this->providers = $providers;

		add_action('show_user_profile', array($this, 'user_profile'));
		add_action('edit_user_profile', array($this, 'user_profile'));
	}

	/**
	 * @param \WP_User $user
	 */
	public function user_profile($user) {
		$connected = UserMeta::getConnectedProviders($user->ID);
		require omSocialLogin::template('user/ProfileConnection.phtml');
	}

}