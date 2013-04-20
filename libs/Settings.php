<?php
namespace omSocialLogin;

use omSocialLogin\providers\Container;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Settings {

	/** @var Container */
	public $providers;

	/**
	 * @param Container $providers
	 */
	public function __construct(Container $providers) {
		$this->providers = $providers;
		add_action('admin_menu', array($this, 'admin_menu'));
	}

	/**
	 * Add settings options to Wordpres smenu
	 */
	public function admin_menu() {
		add_options_page(
			'Social Login', 'Social Login', 'manage_options', omSocialLogin::file(), array($this, 'settings_page')
		);
	}

	/**
	 * Return setting page HTML
	 */
	public function settings_page() {
		if (isset($_POST['submit'])) {
			$this->providers->setOptionsData(); // update providers options
			echo '<div class="updated"><p><strong>' . __('Social login options save', SL) . '</strong></p></div>';
		}

		$action = 'options-general.php?page=' . omSocialLogin::name();
		require __DIR__ . '/../templates/settings.phtml'; // render settings html
	}


}