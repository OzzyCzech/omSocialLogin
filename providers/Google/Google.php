<?php
namespace omSocialLogin\providers;

/**
 * @author Roman Ožana <ozana@omdesign.cz>
 */
class Google extends BaseProvider {

	/**
	 * Return options form HTML
	 *
	 * @return mixed
	 */
	public function getOptionsForm() {
		require_once __DIR__ . '/settings.phtml';
	}

	/**
	 * Setup options data
	 *
	 * @return mixed
	 */
	public function setOptionsData() {
		$this->options->setByArray($_POST, 'google_%s');
		$this->options->saveOptions();
	}
}