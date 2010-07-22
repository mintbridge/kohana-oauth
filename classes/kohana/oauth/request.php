<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Request
 *
 * @package    Kohana/OAuth
 * @category   Request
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class OAuth_Request {

	/**
	 * Create a new request object.
	 *
	 *     $request = OAuth_Request::factory('token', 'http://example.com/oauth/request_token');
	 *
	 * @param   string  request type
	 * @param   string  request URL
	 * @param   string  request method
	 * @param   array   request parameters
	 * @return  OAuth_Request
	 */
	public static function factory($type, $method, $url = NULL, array $params = NULL)
	{
		$class = __CLASS__.'_'.$type;

		return new $class($method, $url, $params);
	}

	/**
	 * @var  integer  connection timeout
	 */
	public $timeout = 10;

	/**
	 * @var  boolean  send Authorization header?
	 */
	public $send_header = TRUE;

	/**
	 * @var  string  request type name: token, authorize, access, resource
	 */
	protected $name;

	/**
	 * @var  string  request method: GET, POST, etc
	 */
	protected $method = 'GET';

	/**
	 * @var  string  request URL
	 */
	protected $url;

	/**
	 * @var   array   request parameters
	 */
	protected $params = array();

	/**
	 * @var  array  required parameters
	 */
	protected $required = array();

	/**
	 * @var  array  POST body
	 */
	protected $post = array();

	/**
	 * Set the request URL, method, and parameters.
	 *
	 * @param  string  request method
	 * @param  string  request URL
	 * @param  array   request parameters
	 * @uses   OAuth::parse_url
	 */
	public function __construct($method, $url, array $params = NULL)
	{
		if ($method)
		{
			// Set the request method
			$this->method = strtoupper($method);
		}

		// Separate the URL and query string, which will be used as additional
		// default parameters
		list ($url, $default) = OAuth::parse_url($url);

		// Set the request URL
		$this->url = $url;

		if ($default)
		{
			// Set the default parameters
			$this->params($default);
		}

		if ($params)
		{
			// Set the request parameters
			$this->params($params);
		}

		if ($this->required('oauth_version') AND ! isset($this->params['oauth_version']))
		{
			// Set the version of this request
			$this->params['oauth_version'] = OAuth::$version;
		}

		if ($this->required('oauth_timestamp') AND ! isset($this->params['oauth_timestamp']))
		{
			// Set the timestamp of this request
			$this->params['oauth_timestamp'] = $this->timestamp();
		}

		if ($this->required('oauth_nonce') AND ! isset($this->params['oauth_nonce']))
		{
			// Set the unique nonce of this request
			$this->params['oauth_nonce'] = $this->nonce();
		}
	}

	/**
	 * Return the value of any protected class variable.
	 *
	 *     // Get the request parameters
	 *     $params = $request->params;
	 *
	 *     // Get the request URL
	 *     $url = $request->url;
	 *
	 * @param   string  variable name
	 * @return  mixed
	 */
	public function __get($key)
	{
		return $this->$key;
	}

	/**
	 * Generates the UNIX timestamp for a request.
	 *
	 *     $time = $request->timestamp();
	 *
	 * [!!] This method implements [OAuth 1.0 Spec 8](http://oauth.net/core/1.0/#rfc.section.8).
	 *
	 * @return  integer
	 */
	public function timestamp()
	{
		return time();
	}

	/**
	 * Generates the nonce for a request.
	 *
	 *     $nonce = $request->nonce();
	 *
	 * [!!] This method implements [OAuth 1.0 Spec 8](http://oauth.net/core/1.0/#rfc.section.8).
	 *
	 * @return  string
	 * @uses    Text::random
	 */
	public function nonce()
	{
		return Text::random('alnum', 40);
	}

	/**
	 * Get the base signature string for a request.
	 *
	 *     $base = $request->base_string();
	 *
	 * [!!] This method implements [OAuth 1.0 Spec A5.1](http://oauth.net/core/1.0/#rfc.section.A.5.1).
	 *
	 * @param   OAuth_Request   request to sign
	 * @return  string
	 * @uses    OAuth::urlencode
	 * @uses    OAuth::normalize_post
	 * @uses    OAuth::normalize_params
	 */
	public function base_string()
	{
		$url = $this->url;

		// Get the request parameters
		$params = $this->params;

		if ($this->post)
		{
			// Add the POST fields to the parameters
			$params += $this->post;
		}

		// "oauth_signature" is never included in the base string!
		unset($params['oauth_signature']);

		// method & url & sorted-parameters
		return implode('&', array(
			$this->method,
			OAuth::urlencode($url),
			OAuth::urlencode(OAuth::normalize_params($params)),
		));
	}

	/**
	 * Parameter getter and setter. Setting the value to `NULL` will remove it.
	 *
	 *     // Set the "oauth_consumer_key" to a new value
	 *     $request->param('oauth_consumer_key', $key);
	 *
	 *     // Get the "oauth_consumer_key" value
	 *     $key = $request->param('oauth_consumer_key');
	 *
	 *     // Remove "oauth_consumer_key"
	 *     $request->param('oauth_consumer_key', NULL);
	 *
	 * @param   string   parameter name
	 * @param   mixed    parameter value
	 * @param   boolean  allow duplicates?
	 * @return  mixed    when getting
	 * @return  $this    when setting
	 * @uses    Arr::get
	 */
	public function param($name, $value = NULL, $duplicate = FALSE)
	{
		if (func_num_args() < 2)
		{
			// Get the parameter
			return Arr::get($this->params, $name);
		}

		if ($value === NULL)
		{
			// Remove the parameter
			unset($this->params[$name]);
		}
		else
		{
			if (isset($this->params[$name]) AND $duplicate)
			{
				if ( ! is_array($this->params[$name]))
				{
					// Convert the parameter into an array
					$this->params[$name] = array($this->params[$name]);
				}

				// Add the duplicate value
				$this->params[$name][] = $value;
			}
			else
			{
				// Set the parameter value
				$this->params[$name] = $value;
			}
		}

		return $this;
	}

	/**
	 * Set multiple parameters.
	 *
	 *     $request->params($params);
	 *
	 * @param   array    parameters
	 * @param   boolean  allow duplicates?
	 * @return  $this
	 * @uses    OAuth_Request::param
	 */
	public function params(array $params, $duplicate = FALSE)
	{
		foreach ($params as $name => $value)
		{
			$this->param($name, $value, $duplicate);
		}

		return $this;
	}

	/**
	 * Get and set POST fields. Adding a value will cause the request to be
	 * sent with the HTTP POST method.
	 *
	 *     $request->post($field, $value);
	 *
	 * @param   string  field name
	 * @param   string  field value
	 * @return  string  when getting
	 * @return  $this   when setting
	 * @uses    Arr::get
	 */
	public function post($field, $value = NULL)
	{
		if (func_num_args() < 2)
		{
			return Arr::get($this->post, $field);
		}

		$this->post[$field] = $value;

		return $this;
	}

	/**
	 * Get and set required parameters.
	 *
	 *     $request->required($field, $value);
	 *
	 * @param   string   parameter name
	 * @param   boolean  field value
	 * @return  boolean  when getting
	 * @return  $this    when setting
	 */
	public function required($param, $value = NULL)
	{
		if ($value === NULL)
		{
			return ! empty($this->required[$param]);
		}

		// Change the requirement value
		$this->required[$param] = (boolean) $value;

		return $this;
	}

	/**
	 * Convert the request parameters into an `Authorization` header.
	 *
	 *     $header = $request->as_header();
	 *
	 * [!!] This method implements [OAuth 1.0 Spec 5.4.1](http://oauth.net/core/1.0/#rfc.section.5.4.1).
	 *
	 * @return  string
	 */
	public function as_header()
	{
		$header = array();

		foreach ($this->params as $name => $value)
		{
			// OAuth Spec 5.4.1
			// "Parameter names and values are encoded per Parameter Encoding [RFC 3986]."
			$header[] = OAuth::urlencode($name).'="'.OAuth::urlencode($value).'"';
		}

		return 'OAuth '.implode(', ', $header);
	}

	/**
	 * Convert the request parameters into a query string, suitable for GET and
	 * POST requests.
	 *
	 *     $query = $request->as_query();
	 *
	 * [!!] This method implements [OAuth 1.0 Spec 5.2 (2,3)](http://oauth.net/core/1.0/#rfc.section.5.2).
	 *
	 * @return  string
	 */
	public function as_query()
	{
		return OAuth::normalize_params($this->params);
	}

	/**
	 * Return the entire request URL with the parameters as a GET string.
	 *
	 *     $url = $request->as_url();
	 *
	 * @return  string
	 * @uses    OAuth_Request::as_query
	 */
	public function as_url()
	{
		return $this->url.'?'.$this->as_query();
	}

	/**
	 * Sign the request, setting the `oauth_signature_method` and `oauth_signature`.
	 *
	 * @param   OAuth_Signature  signature
	 * @param   OAuth_Consumer   consumer
	 * @param   OAuth_Token      token
	 * @return  $this
	 * @uses    OAuth_Signature::sign
	 */
	public function sign(OAuth_Signature $signature, OAuth_Consumer $consumer, OAuth_Token $token = NULL)
	{
		// Create a new signature class from the method
		$this->param('oauth_signature_method', $signature->name);

		// Sign the request using the consumer and token
		$this->param('oauth_signature', $signature->sign($this, $consumer, $token));

		return $this;
	}

	/**
	 * Checks that all required request parameters have been set. Throws an
	 * exception if any parameters are missing.
	 *
	 *     try
	 *     {
	 *         $request->check();
	 *     }
	 *     catch (OAuth_Exception $e)
	 *     {
	 *         // Request has missing parameters
	 *     }
	 *
	 * @return  TRUE
	 * @throws  OAuth_Exception
	 */
	public function check()
	{
		foreach ($this->required as $param => $required)
		{
			if ($required AND ! isset($this->params[$param]))
			{
				throw new OAuth_Exception('Request to :url requires missing parameter ":param"', array(
					':url'   => $this->url,
					':param' => $param,
				));
			}
		}

		return TRUE;
	}

	/**
	 * Execute the request and return a response.
	 *
	 * @param   string   request type: GET, POST, etc (NULL for header)
	 * @param   array    additional cURL options
	 * @return  string   request response body
	 * @uses    OAuth_Request::check
	 * @uses    Arr::get
	 * @uses    Remote::get
	 */
	public function execute(array $options = NULL)
	{
		// Check that all required fields are set
		$this->check();

		// Get the URL of the request
		$url = $this->url;

		if ( ! isset($options[CURLOPT_CONNECTTIMEOUT]))
		{
			// Use the request default timeout
			$options[CURLOPT_CONNECTTIMEOUT] = $this->timeout;
		}

		if ($this->send_header)
		{
			// Get the the current headers
			$headers = Arr::get($options, CURLOPT_HTTPHEADER, array());

			// Add the Authorization header
			$headers[] = 'Authorization: '.$this->as_header();

			// Store the new headers
			$options[CURLOPT_HTTPHEADER] = $headers;
		}
		elseif ($query = $this->as_query())
		{
			// Append the parameters to the query string
			$url = "{$url}?{$query}";
		}

		if ($this->method === 'POST')
		{
			// Send the request as a POST
			$options[CURLOPT_POST] = TRUE;

			if ($this->post)
			{
				// Attach the post fields to the request
				$options[CURLOPT_POSTFIELDS] = OAuth::normalize_post($this->post);
			}
		}

		return Remote::get($url, $options);
	}

} // End OAuth_Request
