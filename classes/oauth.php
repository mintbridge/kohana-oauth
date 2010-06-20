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

	public static function signature($name)
	{
		$class = 'OAuth_Signature_'.str_replace('-', '_', $name);

		return new $class;
	}

	/**
	 * RFC3986 compatible version of urlencode.
	 */
	public static function urlencode($input)
	{
		if (is_array($input))
		{
			return array_map(array('OAuth', 'urlencode'), $input);
		}
		elseif (is_scalar($input))
		{
			return str_replace(array('+', '%7E'), array('%20', '~'), rawurlencode($input));
		}

		return '';
	}

	public static function urldecode($input)
	{
		return rawurldecode($input);
	}

	public static function build_query($params)
	{
		$keys   = OAuth::urlencode(array_keys($params));
		$values = OAuth::urlencode(array_values($params));
		$params = array_combine($keys, $values);

		uksort($params, 'strcmp');

		$query = array();

		foreach ($params as $param => $value)
		{
			if (is_array($value))
			{
				$value = natsort($value);

				foreach ($value as $duplicate)
				{
					$query[] = $param.'='.$duplicate;
				}
			}
			else
			{
				$query[] = $param.'='.$value;
			}
		}

		return implode('&', $query);
	}

	public static function parse_str($str)
	{
		
	}

} // End OAuth
