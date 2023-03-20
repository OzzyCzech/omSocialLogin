<?php

namespace omSocialLogin;

/**
 * @author Roman Ozana <roman@ozana.cz>
 */
class Query {

	public function __construct() {
		add_action('init', [$this, 'addRewriteRules']);
		add_action('query_vars', [$this, 'addCustomQueryVars']);
	}

	public function addRewriteRules() {
		add_rewrite_rule(
			'auth/process/',
			'wp-login.php?auth=process',
			'top'
		);

		add_rewrite_rule(
			'auth/([^/]*)/?',
			'index.php?auth=$matches[1]',
			'top'
		);
	}

	/**
	 * Add custom query vars
	 *
	 * @param $vars
	 * @return array
	 */
	public function addCustomQueryVars($vars) {
		$vars[] = 'auth';
		return $vars;
	}
}