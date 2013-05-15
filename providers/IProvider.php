<?php
namespace omSocialLogin\providers;
/**
 * @see https://github.com/uzyn/opauth/wiki/Auth-response
 * @author Roman Ozana <ozana@omdesign.cz>
 */
interface IProvider {
	/**
	 * @return mixed
	 */
	public function isEnable();

	/**
	 * Return strategy name
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Return strategy configuration array
	 *
	 * @return array
	 */
	public function getStrategy();

	/**
	 * Return options form HTML
	 *
	 * @return mixed
	 */
	public function getOptionsForm();

	/**
	 * Setup options data
	 *
	 * @return mixed
	 */
	public function setOptionsData();

	/**
	 * Return hexa value of brand color
	 *
	 * @see http://brandcolors.net/
	 * @return string
	 */
	public function getColor();

}