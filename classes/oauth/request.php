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

	public function as_header()
	{
		$header = array();

		foreach ($this->params as $key => $value)
		{
			$header[] = OAuth::urlencode($key).'="'.OAuth::urlencode($value).'"';
		}

		return 'Authorization: OAuth '.implode(', ', $header);
	}

	public function as_query()
	{
		return '?'.OAuth::build_query($this->params);
	}

	public function as_post()
	{
		return OAuth::build_query($this->params);
	}

	public function execute($type = NULL, array $options = NULL)
	{
		$url = $this->url;

		switch ($type)
		{
			case 'query':
				$url .= $this->as_query();
			break;
			case 'post':
				$options[CURLOPT_POST]       = TRUE;
				$options[CURLOPT_POSTFIELDS] = $this->as_post();
			break;
			default:
				$options[CURLOPT_HTTPHEADER][] = $this->as_header();
			break;
		}

		return Remote::get($url, $options);
	}

} // End OAuth_Request
