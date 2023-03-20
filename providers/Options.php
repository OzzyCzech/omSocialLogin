<?php

namespace omSocialLogin\providers;

/**
 * @property bool $enable
 * @property bool $strategy
 * @author Roman Ozana <roman@ozana.cz>
 */
class Options extends \om\Options {

	protected $options = [
		'enable' => true,
		'strategy' => [],
	];

	/**
	 * Return strategy value
	 *
	 * @param $key
	 * @return null
	 */
	public function getStrategy($key) {
		return array_key_exists($key, $this->strategy) ? $this->strategy[$key] : null;
	}
}
