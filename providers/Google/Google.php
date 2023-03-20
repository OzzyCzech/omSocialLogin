<?php

namespace omSocialLogin\providers;

/**
 * @author Roman Ožana <roman@ozana.cz>
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

	/**
	 * Return hexa value of brand color
	 *
	 * @return string
	 */
	public function getColor() {
		return '#db4a39';
	}
}