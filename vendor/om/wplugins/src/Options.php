<?php
namespace om;
/**
 * Copyright (c) 2013 Roman Ožana (http://omdesign.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Options {

	/** @var array */
	protected $options = array();

	/** @var null */
	private $name = null;

	/**
	 * Extract settings
	 */
	public function __construct($name = null, array $default = array()) {
		if ($name === null && __CLASS__ !== get_class($this)) {
			throw new Exception('Invalid Options name');
		}

		$this->name = ($name) ? : get_class($this);
		if ($options = get_option($this->name, null)) {
			$this->options = array_merge($this->options, $options);
		}
	}

	/**
	 * Return all options
	 *
	 * @return array
	 */
	public function getOptions() {
		return $this->options;
	}


	/**
	 * Update options by post data
	 *
	 * @param array $data
	 * @param string $name
	 * @param bool $settype
	 */
	public function setByArray(array $data, $name = '%s', $settype = true) {
		foreach ($this->options as $key => $oldvalue) {
			$param = sprintf($name, $key);
			$value = array_key_exists($param, $data) ? $data[$param] : null;
			if ($settype && $oldvalue !== null) settype($value, gettype($oldvalue)); // use same type as before except null
			$this->options[$key] = $value;
		}
	}

	/**
	 * Update options
	 *
	 * @return false
	 */
	public function saveOptions() {
		return update_option($this->name, $this->options);
	}

	/**
	 * Return settings option
	 *
	 * @param string $name
	 * @return mixed|null
	 */
	public function __get($name) {
		return array_key_exists($name, $this->options) ? $this->options[$name] : null;
	}

	/**
	 * Set option value
	 *
	 * @param $name
	 * @param $value
	 * @throws \Exception
	 */
	public function __set($name, $value) {
		if (array_key_exists($name, $this->options)) {
			$this->options[$name] = $value;
		} else {
			throw new \Exception(sprintf('Uknown option key "%s"', $name));
		}
	}

}