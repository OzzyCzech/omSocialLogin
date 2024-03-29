<?php

namespace omSocialLogin;

use omSocialLogin\providers\Container;

/**
 * @author Roman Ozana <roman@ozana.cz>
 */
class Buttons {

	/**
	 * @param \omSocialLogin\providers\Container
	 */
	public static function renderLoginButtons(Container $providers) {
		require_once __DIR__ . '/../templates/loginButtons.phtml';
	}

}