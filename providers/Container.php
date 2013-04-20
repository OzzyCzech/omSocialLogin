<?php
namespace omSocialLogin\providers;

use omSocialLogin\Exception;

/**
 * @method array getStrategy()
 * @method void getOptionsForm()
 * @method void setOptionsData()
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Container implements \ArrayAccess, \Iterator {

	/** @var array */
	protected $providers = array();
	/** @var string */
	protected $selected;

	public function __construct() {
		foreach (func_get_args() as $class) {
			$class = '\\omSocialLogin\\providers\\' . $class;
			$this->add(new $class);
		}
	}

	/**
	 * @param IProvider $provider
	 */
	public function add(IProvider $provider) {
		$this->providers[$provider->getName()] = $provider;
	}

	/**
	 * @param $method
	 * @param $arguments
	 * @return array
	 * @throws \Exception
	 */
	public function __call($method, $arguments) {
		if (!method_exists('omSocialLogin\providers\IProvider', $method)) {
			throw new \Exception('Uknown method "' . $method . '" called.');
		}

		$response = array();
		foreach ($this->providers as $name => $provider) {
			/** @var IProvider $provider */
			$response[$name] = call_user_func_array(array($provider, $method), $arguments);
		}

		return $response;
	}


	// implementation of ArrayAccess -------------------------------------------------------------------------------------

	/**
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset) {
		return array_key_exists($offset, $this->providers);
	}

	/**
	 * @param mixed $offset
	 * @return mixed
	 * @throws \Exception
	 */
	public function offsetGet($offset) {
		if (isset($this[$offset])) {
			return $this->providers[$offset];
		} else {
			throw new \Exception('Provider "' . $offset . '" not found.');
		}
	}

	/**
	 * @param string $offset
	 * @param IProvider $value
	 * @throws \Exception
	 */
	public function offsetSet($offset, $value) {
		if ($value instanceof IProvider) {
			$this->providers[$offset] = $value;
		} else {
			throw new \Exception('Invalid input type. Container require IProvider only.');
		}
	}

	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset) {
		unset($this->providers[$offset]);
	}

	// implementation of Iterator ----------------------------------------------------------------------------------------

	public function current() {
		return current($this->providers);
	}

	public function next() {
		return next($this->providers);
	}

	public function key() {
		return key($this->providers);
	}

	public function valid() {
		return key($this->providers) !== null;
	}

	public function rewind() {
		return reset($this->providers);
	}
}