<?php
namespace omSocialLogin;

use omSocialLogin\providers\Container;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Frontend {

	/** @var Container */
	public $providers;

	public function __construct(Container $providers) {
		$this->providers = $providers;
		add_action('login_form', array($this, 'addLoginButtons'));
	}

	public function addLoginButtons() {
		global $action;
		if ($action === 'login') Buttons::renderLoginButtons($this->providers);
	}

}