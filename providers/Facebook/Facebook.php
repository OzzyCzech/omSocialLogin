<?php
namespace omSocialLogin\providers;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Facebook extends BaseProvider {

	/**
	 * Return setting HTML
	 *
	 * @return mixed
	 */
	public function getOptionsForm() {
		include __DIR__ . '/settings.phtml';
	}

	/**
	 * Setup options data
	 *
	 * @return mixed
	 */
	public function setOptionsData() {
		$this->options->setByArray($_POST, 'facebook_%s');
		$this->options->saveOptions();
	}

	/**
	 * Return hexa value of brand color
	 *
	 * @return mixed
	 */
	public function getColor() {
		return '#3b5998';
	}
}