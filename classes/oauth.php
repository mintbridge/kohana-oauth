<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Library
 *
 * @package    Kohana/OAuth
 * @package    Base
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class OAuth {

	/**
	 * @var  string  OAuth complaince version
	 */
	public static $version = '1.0';

	/**
	 * RFC3986 compatible version of urlencode. Passing an array will encode
	 * all of the values in the array. Array keys will not be encoded.
	 *
	 *     $input = OAuth::urlencode($input);
	 *
	 * Multi-dimensional arrays are not allowed!
	 *
	 * [!!] This method implements [OAuth 1.0 Spec 5.1](http://oauth.net/core/1.0/#rfc.section.5.1).
	 *
	 * @param   mixed   input string or array
	 * @return  mixed
	 */
	public static function urlencode($input)
	{
		if (is_array($input))
		{
			// Encode the values of the array
			return array_map(array('OAuth', 'urlencode'), $input);
		}

		// Encode the input
		$input = rawurlencode($input);

		if (version_compare(PHP_VERSION, '<', '5.3'))
		{
			// rawurlencode() is RFC3986 compliant in PHP 5.3
			// the only difference is the encoding of tilde
			$input = str_replace('%7E', '~', $input);
		}

		return $input;
	}

	/**
	 * RFC3986 complaint version of urldecode. Passing an array will decode
	 * all of the values in the array. Array keys will not be encoded.
	 *
	 *     $input = OAuth::urldecode($input);
	 *
	 * Multi-dimensional arrays are not allowed!
	 *
	 * [!!] This method implements [OAuth 1.0 Spec 5.1](http://oauth.net/core/1.0/#rfc.section.5.1).
	 *
	 * @param   mixed  input string or array
	 * @return  mixed
	 */
	public static function urldecode($input)
	{
		if (is_array($input))
		{
			// Decode the values of the array
			return array_map(array('OAuth', 'urldecode'), $input);
		}

		// Decode the input
		return rawurldecode($input);
	}

	/**
	 * Normalize all request parameters into a string.
	 *
	 *     $query = OAuth::normalize_params($params);
	 *
	 * [!!] This method implements [OAuth 1.0 Spec 9.1.1](http://oauth.net/core/1.0/#rfc.section.9.1.1).
	 *
	 * @param   array   request parameters
	 * @return  string
	 */
	public static function normalize_params(array $params)
	{
		// Encode the parameter keys and values
		$keys   = OAuth::urlencode(array_keys($params));
		$values = OAuth::urlencode(array_values($params));

		// Recombine the parameters
		$params = array_combine($keys, $values);

		// OAuth Spec 9.1.1 (1)
		// "Parameters are sorted by name, using lexicographical byte value ordering."
		uksort($params, 'strcmp');

		// Create a new query string
		$query = array();

		foreach ($params as $name => $value)
		{
			if (is_array($value))
			{
				// OAuth Spec 9.1.1 (1)
				// "If two or more parameters share the same name, they are sorted by their value."
				$value = natsort($value);

				foreach ($value as $duplicate)
				{
					$query[] = $name.'='.$duplicate;
				}
			}
			else
			{
				$query[] = $name.'='.$value;
			}
		}

		return implode('&', $query);
	}

	/**
	 * Parse the parameters in a string and return an array. Duplicates are
	 * converted into indexed arrays.
	 *
	 *     // Parsed: array('a' => '1', 'b' => '2', 'c' => '3')
	 *     $params = OAuth::parse_params('a=1,b=2,c=3');
	 *
	 *     // Parsed: array('a' => array('1', '2'), 'c' => '3')
	 *     $params = OAuth::parse_params('a=1,a=2,c=3');
	 *
	 * @param   string  parameter string
	 * @return  array
	 */
	public static function parse_params($params)
	{
		// Split the parameters by &
		$params = explode('&', trim($params));

		// Create an array of parsed parameters
		$parsed = array();

		foreach ($params as $param)
		{
			// Split the parameter into name and value
			list($name, $value) = explode('=', $param, 2);

			// Decode the name and value
			$name  = OAuth::urldecode($name);
			$value = OAuth::urldecode($value);

			if (isset($parsed[$name]))
			{
				if ( ! is_array($parsed[$name]))
				{
					// Convert the parameter to an array
					$parsed[$name] = array($parsed[$name]);
				}

				// Add a new duplicate parameter
				$parsed[$name][] = $value;
			}
			else
			{
				// Add a new parameter
				$parsed[$name] = $value;
			}
		}

		return $parsed;
	}

} // End OAuth
