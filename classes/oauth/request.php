<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Request
 *
 * @package    Kohana/OAuth
 * @package    Basex
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class OAuth_Request {

	protected $method;

	protected $url;

	protected $params;

	public function __construct($method, $url, array $params)
	{
		$this->method = strtoupper($method);

		$this->url = $url;

		$this->params = $params;
	}

	public function __get($key)
	{
		return $this->$key;
	}

	public function param($key, $value = NULL)
	{
		if (func_get_args() < 2)
		{
			return Arr::get($this->params, $key);
		}

		if ($value === NULL)
		{
			unset($this->params[$key]);
		}
		else
		{
			$this->params[$key] = $value;
		}

		return $this;
	}

	public function execute()
	{
		return Remote::get($this->url.'?'.OAuth::build_query($this->params));

		$header = array();

		foreach ($this->params as $key => $value)
		{
			$header[] = OAuth::urlencode($key).'="'.OAuth::urlencode($value).'"';
		}

		$options = array(
			CURLOPT_HEADER     => TRUE,
			// CURLOPT_POST       => TRUE,
			CURLOPT_HTTPHEADER => array('Authorization: OAuth '.implode(', ', $header)),
		);

		echo Kohana::debug(Remote::get($this->url, $options));exit;
	}

} // End OAuth_Request
