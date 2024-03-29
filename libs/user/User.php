<?php

namespace omSocialLogin;

use Mockery\Exception;

/**
 * @author Roman Ozana <roman@ozana.cz>
 */
class User {

	/** @var Response */
	private $response;

	/**
	 * Create user from provider
	 *
	 * @see https://github.com/uzyn/opauth/wiki/Auth-response
	 * @param Response $response
	 */
	public function __construct(Response $response) {
		$this->response = $response;
	}

	/**
	 * Login user or create new one if not exists
	 *
	 * @param bool $create
	 * @return bool
	 * @throws LoginException
	 */
	public function login($create = true) {

		// 1. connect account if user is loged in

		if (is_user_logged_in()) {

			// 1.1 merge duplicite users if exists and was created by social provider
			UserMeta::mergeUsers(get_current_user_id(), $this->response->getProvider(), $this->response->getUserUid());

			// 1.2 add response data to user current user
			return UserMeta::addResponseData(get_current_user_id(), $this->response); // save new response
		}

		// 2. login user by Provider and UID

		if ($user = UserMeta::getUserByUid($this->response->getProvider(), $this->response->getUserUid())) {
			UserMeta::addResponseData($user->ID, $this->response); // update reponse data
			return $this->loginUser($user);
		}

		// 3. login user user by email

		if ($user = $this->getUserByEmail($this->response->getUserEmail())) {
			UserMeta::addResponseData($user->ID, $this->response); // save new response
			return $this->loginUser($user);
		}

		// 4. create new user and login

		if ($create && $user = $this->createUser()) {
			UserMeta::addResponseData($user->ID, $this->response); // save new response
			return $this->loginUser($user);
		}

		// 5. nobody found or create

		throw new LoginException(
			sprintf(
				__('Cannot log you in. There is no account on this site connected to that %s user identity.', SL),
				$this->response->getProvider()
			)
		);
	}

	/**
	 * Auto-login Wordpress user
	 *
	 * @param \WP_User $user
	 * @return bool
	 */
	private
	function loginUser(
		\WP_User $user
	) {
		wp_set_current_user($user->ID, $user->user_login);
		wp_set_auth_cookie($user->ID);
		do_action('wp_login', $user->user_login);
		return true;
	}

	/**
	 * Get WP_User by email
	 *
	 * @param $email
	 * @return mixed
	 */
	public static function getUserByEmail($email) {
		if (is_email($email)) {
			return get_user_by('email', $email);
		}

		return false;
	}

	/**
	 * Create new from response
	 *
	 * @return bool|false|\WP_User
	 * @throws LoginException
	 */
	private function createUser() {

		// 1. check if email already exists (just for safe)

		if ($this->response->getUserEmail() && email_exists($this->response->getUserEmail())) {
			throw new LoginException(
				sprintf(
					__('Account with email <strong>%s</strong> already exists, please try login again.', SL),
					$this->response->getUserEmail()
				)
			);
		}

		// 2. prepare new user data

		$user_data = [
			'ID' => null,
			'user_pass' => wp_generate_password(12, false),
			'user_login' => $this->getBestFreeUserLogin(),
			'user_nicename' => $this->response->getUserName(),
			'user_url' => reset($this->response->getUserUrls()),
			'user_email' => strval($this->response->getUserEmail()),
			'display_name' => ($this->response->getUserNickname() ? $this->response->getUserNickname() : $this->response->getUserName()),
			'nickname' => strval($this->response->getUserNickname()),
			'first_name' => strval($this->response->getUserFirstName()),
			'last_name' => strval($this->response->getUserLastName()),
			'description' => $this->response->getUserDescription(),
			'rich_editing' => null, // true :)
			'role' => get_option('default_role'), // default role
		];

		// 3. inser user to database

		$user_id = wp_insert_user($user_data);

		if (!is_wp_error($user_id)) {
			UserMeta::addResponseData($user_id, $this->response, true);
			return get_user_by('id', $user_id);
		} else {
			/** @var \WP_Error $user_id */
			throw new LoginException($user_id->get_error_message());
		}

		return false;
	}

	/**
	 * Return free not existing username
	 *
	 * @return string
	 */
	private function getBestFreeUserLogin() {

		// 1. prepare possible usernames

		$usernames = [
			$this->response->getUserNickname(),
			$name = preg_replace('/@/', '', $this->response->getUserName()),
			sanitize_title($name),
			sanitize_title($this->response->getUserFirstName() . '-' . $this->response->getUserLastName()),
			sanitize_title(
				$this->response->getUserName() . '-' . $this->response->getProvider() . '-' . $this->response->getUserUid()
			),
		];

		$usernames = array_filter($usernames); // remove NULL or empry values

		// 2. check all possible usernames

		foreach ($usernames as $username) {
			if (strlen($username > 60)) continue; // skip ultra long names
			$username = sanitize_user($username);
			if (!username_exists($username)) return $username;
		}

		// 3. create username with number

		$name = sanitize_user(sanitize_title($name) . '-%d');

		$i = 1;
		do {
			$username = sprintf($name, $i++);
		} while (username_exists($username));

		return $username;
	}

}