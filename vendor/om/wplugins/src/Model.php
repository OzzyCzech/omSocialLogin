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
abstract class Model {

  /** @var array */
  protected $data = array();

  /** @var  array */
  protected $format;

  /** @var string */
  public static $prefix;

  /**
   * The database table primary key
   *
   * @var array
   */
  public static $primary = 'id';

  /**
   * @param null|int $id
   * @param null|string $primary
   */
  public function __construct($id = null, $primary = null) {

    // save primary column name
    if ($primary !== null) {
      static::$primary = $primary;
    }

    // get data by ID
    if ($id !== null) {
      $result = static::wpdb()->get_row(
        sprintf('SELECT * FROM `%s` WHERE `%s` = %d LIMIT 1', static::table(), static::$primary, $id),
        ARRAY_A
      );

      if ($result) $this->data = $result;
    }
  }

  /**
   * @param array $data
   */
  public function setData(array $data) {
    $this->data = $data;
  }

  /**
   * @return array|mixed
   */
  public function getData() {
    return $this->data;
  }


  /**
   * Set Wordpress format array (optional param)
   *
   * @param array $format
   */
  public function setFormat(array $format) {
    $this->format = $format;
  }

  /**
   * Magic method for getting a item from the data array
   *
   * @param string
   * @return mixed
   */
  public function __get($key) {
    if (array_key_exists($key, $this->data)) {
      return $this->data[$key];
    }
  }

  /**
   * Magic method for setting a item in the data array
   *
   * @param string
   * @param mixed
   */
  public function __set($key, $value) {
    $this->data[$key] = $value;
  }

  /**
   * @param array $data
   * @param array $format
   * @return \false|int
   */
  public function insert(array $data = null, array $format = null) {
    return static::wpdb()->insert(static::table(), $data ? : $this->data, $format ? : $this->format);
  }

  /**
   * @param $data
   * @param array $where
   * @param array|null $format
   * @param null $where_format
   * @return \false|int
   */
  public function update($data = null, array $where = null, array $format = null, $where_format = null) {
    $data = $data ? : $this->data;
    $where = $where ? : array(static::$primary => $this->getId());
    $format = $format ? : $this->format;

    return static::wpdb()->update(static::table(), $data, $where, $format, $where_format);
  }

  /**
   * @param $where
   * @param null $where_format
   * @return \false|int
   */
  public function delete(array $where = null, $where_format = null) {
    $where = $where ? : array(static::$primary => $this->getId());

    return static::wpdb()->delete(static::table(), $where, $where_format);
  }

  /**
   * Return ID value
   *
   * @return mixed
   */
  public function getId() {
    if (array_key_exists(static::$primary, $this->data)) {
      return $this->data[static::$primary];
    }
  }

  /**
   * Return table name
   *
   * @param null $name
   * @return string
   */
  public static function table($name = null) {
    if (!is_null($name)) return static::$prefix . $name;
    return static::$prefix . static::$table;
  }

  /**
   * Return WPDB data
   *
   * @return \wpdb
   */
  public static function wpdb() {
    global $wpdb;
    return $wpdb;
  }

  /**
   * @param string $method
   * @param string $arguments
   * @return mixed
   */
  public static function __callStatic($method, $arguments) {
    $obj = new get_called_class();

    if (method_exists($obj, $method)) {
      return call_user_func_array(array($obj, $method), $arguments);
    }
  }

}