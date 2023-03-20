<?php

namespace omSocialLogin;

use omSocialLogin\providers\Container;

/**
 * @author Roman Ozana <roman@ozana.cz>
 */
class Frontend {

	/** @var Container */
	public $providers;

	public function __construct(Container $providers) {
		$this->providers = $providers;
		add_action('login_form', [$this, 'addLoginButtonsLoginForm']);
		add_action('comment_form_top', [$this, 'addLoginButtonsCommentsForm']);
		add_action('pre_comment_approved', [$this, 'pre_comment_approved'], 100, 2);
	}

	public function addLoginButtonsLoginForm() {
		global $action;
		if ($action === 'login') Buttons::renderLoginButtons($this->providers);
	}

	public function addLoginButtonsCommentsForm() {
		if (comments_open() && !is_user_logged_in()) {
			Buttons::renderLoginButtons($this->providers);
		}
	}

	/**
	 * Pre comment approve
	 *
	 * @param $approved
	 * @param $commentdata
	 * @return bool
	 */
	public function pre_comment_approved($approved, $commentdata) {
		if (!$approved && is_user_logged_in()) return true;
		return $approved;
	}
}