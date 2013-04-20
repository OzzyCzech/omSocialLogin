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
class View extends \stdClass {

	/** @var $file */
	protected $template;

	/** @var array */
	protected $vars = array();

	/** @var string */
	public static $dir;

	/**
	 * Create new View instance
	 *
	 * @param $file
	 * @param string|null $dir
	 * @return View
	 */
	public static function from($file, $dir = null) {
		return new View($file, $dir);
	}

	/**
	 * @param string $template
	 * @param string $dir
	 * @throws Exception
	 */
	public function __construct($template = null, $dir = null) {
		if ($template) $this->setTemplate($template);
		if ($dir) $this->setTemplateDir($dir);
	}

	/**
	 * Setup template file
	 *
	 * @param $template
	 * @throws Exception
	 */
	public function setTemplate($template) {
		$dir = $this->getTemplateDir();
		if (
			is_file($set = $template) ||
			is_file($set = $template . '.phtml') ||
			is_file($set = $template . '.php') ||
			is_file($set = $dir . $template) ||
			is_file($set = $dir . $template . '.phtml') ||
			is_file($set = $dir . $template . '.php')
		) {
			$this->template = $set;
		} else {
			throw new Exception('Template file "' . $template . '" not found.');
		}
	}

	/**
	 * Render View
	 *
	 * @throws Exception
	 * @internal param $name
	 */
	public function render($template = null) {
		if ($template) $this->setTemplate($template);
		if (file_exists($this->template)) {
			extract($this->vars);
			include($this->template);
		}
	}


	/**
	 * Return variable value if exists
	 *
	 * @param string $name
	 * @throws Exception
	 * @return mixed
	 */
	public function __get($name) {
		if (array_key_exists($name, $this->vars)) {
			return $this->vars[$name];
		} else {
			throw new Exception('Variable ' . $name . ' not found');
		}
	}

	/**
	 * @param mixed $name
	 * @param mixed $value
	 */
	public function __set($name, $value) {
		$this->vars[$name] = $value;
	}

	/**
	 * Check if some variable isset
	 *
	 * @param string $name
	 * @return bool
	 */
	public function __isset($name) {
		return isset($this->vars[$name]);
	}

	/**
	 * Set template dir
	 *
	 * @param $dir
	 * @return void
	 */
	public function setTemplateDir($dir) {
		View::$dir = $dir;
	}

	/**
	 * @return mixed
	 */
	public function getTemplate() {
		return $this->template;
	}

	/**
	 * Return current template dir
	 *
	 * @return string
	 */
	public static function getTemplateDir() {
		return View::$dir;
	}

	/**
	 * Return view as string
	 *
	 * @return string
	 */
	public function __toString() {
		ob_start();
		$this->render();
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
}