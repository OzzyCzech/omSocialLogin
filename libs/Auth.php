<?php

namespace omSocialLogin;

use omSocialLogin\providers\Container;

/**
 * @author Roman Ozana <roman@ozana.cz>
 */
class Auth {

	/** @var array */
	public $config = [
		'path' => '/auth/',
		'debug' => false,
		'callback_url' => '{path}/process/',
		'callback_transport' => 'session', // session, post or get
		'security_salt' => AUTH_SALT,
		'security_iteration' => 300,
		'security_timeout' => '5 minutes',
		'Strategy' => [],
	];

	/** @var Container */
	public $providers;

	/** @var \Opauth */
	public $opauth;

	/**
	 * @param Container $providers
	 */
	public function __construct(Container $providers) {
		$this->providers = $providers;
		add_action('parse_request', [$this, 'parseRequest']);
	}

	/**
	 * Parse incomming request
	 *
	 * @param $wp
	 */
	public function parseRequest(&$wp) {
		if (!array_key_exists('auth', $wp->query_vars)) return;
		if (!$auth = $wp->query_vars['auth']) return;

		$this->config['Strategy'] = $this->providers->getStrategy();
		$this->opauth = new \Opauth($this->config, $auth !== 'process');

		if ($auth === 'process') {
			try {
				$this->processRequest();
			} catch (LoginException $error) {
				wp_die($error->getMessage(), __('Social provider login error', SL));
			}
		}
	}

	/**
	 * Process response from oAuth provider
	 *
	 * @see https://github.com/uzyn/opauth/wiki/Auth-response
	 * @throws LoginException
	 */
	private function processRequest() {
		if (!isset($_SESSION['opauth'])) wp_die(__('Ups! Missing data about response.', SL));

		// 1. getting response
		$response = $_SESSION['opauth'];

		do_action('oslProcessOauthResponse', $response); // start process response

		// 2. validate oauth response
		$response = Response::fromArray($response);

		if (!$this->opauth->validate(
			sha1(print_r($response->getAuth(), true)),
			$response->getTimestamp(),
			$response->getSignature(),
			$reason
		)
		) {
			throw new LoginException(__('Invalid auth response ', SL) . $reason);
		}

		// create or login user...

		$user = new User($response);
		if ($user->login()) {
			$referer = wp_get_referer();
			if ($referer && !strpos($referer, 'wp-login')) {
				wp_safe_redirect(wp_get_referer() . '#respond'); // comments login
			} else {
				wp_safe_redirect(get_home_url() . '#');
			}
			exit();
		}
	}
}