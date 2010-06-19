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
			return str_replace(array('+', '%7E'), array(' ', '~'), rawurlencode($input)));
		}

		return '';
	}

} // End OAuth
