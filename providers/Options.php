<?php
namespace omSocialLogin\providers;
/**
 * @property bool $enable
 * @property bool $strategy
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Options extends \om\Options {
	protected $options = array(
		'enable' => true,
		'strategy' => array()
	);

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
