<?php
namespace omSocialLogin;

/**
 * @see https://github.com/uzyn/opauth/wiki/Auth-response
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Response {

	/** @var null|array */
	public $response = null;

	/**
	 * Set response and check format of input array
	 *
	 * @param array $response
	 * @throws LoginException
	 * @return mixed|void
	 */
	public function setResponse(array $response = null) {

		// 1. check for errors

		if (array_key_exists('error', $response)) {
			$message = ($response['error']['message']) ? : $response['error']['raw'];
			throw new LoginException(
				$response['error']['provider'] . ': ' . (is_array($message) ? implode(' ', $message) : $message),
				$response['error']['code']
			);
		}

		// 2. check for mandatory elements

		if (
			$response === null ||
			empty($response['auth']['provider']) ||
			empty($response['auth']['uid']) ||
			empty($response['timestamp']) ||
			empty($response['signature'])
		) {
			throw new LoginException(__('Empty or corrupted response from selected provider.', SL));
		}

		// 3. set response

		$this->response = $response;
	}

	/**
	 * Return response
	 *
	 * @return array|null
	 */
	public function getResponse() {
		return $this->response;
	}


	/**
	 * Factory
	 *
	 * @param array $array
	 * @return Response
	 */
	public static function fromArray(array $array = null) {
		$response = new self;
		$response->setResponse($array);
		return $response;
	}

	/**
	 * @return string
	 */
	public function getCredentialsToken() {
		return isset($this->response['auth']['credentials']['token']) ? $this->response['auth']['credentials']['token'] : null;
	}

	/**
	 * @return string
	 */
	public function getCredentialsSecret() {
		return isset($this->response['auth']['credentials']['secret']) ? $this->response['auth']['credentials']['secret'] : null;
	}

	/**
	 * Return user id
	 *
	 * @return string
	 */
	public function getUserUid() {
		return isset($this->response['auth']['uid']) ? $this->response['auth']['uid'] : null;
	}

	/**
	 * Provide email
	 *
	 * @return string|null
	 */
	public function getUserEmail() {
		return isset($this->response['auth']['info']['email']) ? $this->response['auth']['info']['email'] : null;
	}

	/**
	 * Return name of user
	 *
	 * @return string|null
	 */
	public function getUserName() {
		return isset($this->response['auth']['info']['name']) ? $this->response['auth']['info']['name'] : null;
	}

	/**
	 * Return response signature
	 *
	 * @return string
	 */
	public function getSignature() {
		return isset($this->response['signature']) ? $this->response['signature'] : null;
	}

	/**
	 * Return response timestamp
	 *
	 * @return string
	 */
	public function getTimestamp() {
		return isset($this->response['timestamp']) ? $this->response['timestamp'] : null;
	}


	/**
	 * Alias for getName() function
	 *
	 * @return string
	 */
	public function getProvider() {
		return isset($this->response['auth']['provider']) ? $this->response['auth']['provider'] : null;
	}

	/**
	 * Return array info
	 *
	 * @return array
	 */
	public function getInfo() {
		return isset($this->response['auth']['info']) ? (array)$this->response['auth']['info'] : array();
	}

	/**
	 * The username of an authenticating user
	 * (such as your @-name from Twitter or
	 * GitHub account name)
	 *
	 * @return string|null
	 */
	public function getUserNickname() {
		return isset($this->response['auth']['info']['nickname']) ? $this->response['auth']['info']['nickname'] : null;

	}

	/**
	 * Return user first_name
	 *
	 * @return string|null
	 */
	public function getUserFirstName() {
		return isset($this->response['auth']['info']['first_name']) ? $this->response['auth']['info']['first_name'] : null;
	}

	/**
	 * Return user last_name
	 *
	 * @return string|null
	 */
	public function getUserLastName() {
		return isset($this->response['auth']['info']['last_name']) ? $this->response['auth']['info']['last_name'] : null;
	}

	/**
	 * The general location of the user, usually a city and state
	 *
	 * @return mixed
	 */
	public function getUserLocation() {
		return isset($this->response['auth']['info']['location']) ? $this->response['auth']['info']['location'] : null;
	}

	/**
	 * A short description of the authenticating user
	 *
	 * @return mixed
	 */
	public function getUserDescription() {
		return isset($this->response['auth']['info']['description']) ? $this->response['auth']['info']['description'] : null;
	}

	/**
	 * The telephone number of the authenticating user (no formatting is enforced).
	 *
	 * @return string
	 */
	public function getUserPhone() {
		return isset($this->response['auth']['info']['phone']) ? $this->response['auth']['info']['phone'] : null;
	}

	/**
	 * An array containing key value pairs of an identifier
	 * for the website and its URL. For instance, an entry could be array()
	 *
	 * @return string
	 */
	public function getUserUrls() {
		return isset($this->response['auth']['info']['urls']) ? (array)$this->response['auth']['info']['urls'] : array();
	}

	/**
	 * A URL representing a profile image of the authenticating user.
	 * Where possible, should be specified to a square, roughly 50x50 pixel image.
	 *
	 * @return string|null
	 */
	public function getUserImage() {
		return isset($this->response['auth']['info']['image']) ? $this->response['auth']['info']['image'] : null;
	}

	/**
	 * Return array of credentials
	 *
	 * @return mixed
	 */
	public function getCredentials() {
		return isset($this->response['auth']['credentials']) ? (array)$this->response['auth']['credentials'] : array();
	}

	/**
	 * Return auth array data
	 *
	 * @return array
	 */
	public function getAuth() {
		return isset($this->response['auth']) ? (array)$this->response['auth'] : array();
	}

	/**
	 * An array of all information gather about a user.
	 * It should be converted to array before
	 * returning to the user, eg. json_decode if it's in JSON.
	 * May contain repeat information from the above.
	 *
	 * @return array
	 */
	public function getAuthRaw() {
		return isset($this->response['auth']['raw']) ? (array)$this->response['auth']['raw'] : array();
	}

}