<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Response
 *
 * @package    Kohana/OAuth
 * @package    Base
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 * @since      3.0.7
 */
class Kohana_OAuth_Response {

	public static function factory($body)
	{
		return new OAuth_Response($body);
	}

	/**
	 * @var   array   response parameters
	 */
	protected $params = array();

	public function __construct($body = NULL)
	{
		if ($body)
		{
			$this->params = OAuth::parse_params($body);
		}
	}

	/**
	 * Return the value of any protected class variable.
	 *
	 *     // Get the response parameters
	 *     $params = $response->params;
	 *
	 * @param   string  variable name
	 * @return  mixed
	 */
	public function __get($key)
	{
		return $this->$key;
	}

	public function param($name, $default = NULL)
	{
		return Arr::get($this->params, $name, $default);
	}

} // End OAuth_Response
