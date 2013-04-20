<?php
namespace omSocialLogin\providers;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
abstract class BaseProvider implements IProvider {

	/** @var Options */
	public $options;

	public function __construct() {
		$this->options = new Options('omSocialLogin-' . $this->getName());
	}

	/**
	 * Getting strategy from options
	 *
	 * @return array
	 */
	public function getStrategy() {
		return (array)$this->options->strategy;
	}

	/**
	 * @return mixed
	 */
	public function isEnable() {
		return (bool)$this->options->enable;
	}


	/**
	 * Return name of provider
	 *
	 * @return string
	 */
	public function getName() {
		$class = explode('\\', get_class($this));
		return end($class);
	}

}