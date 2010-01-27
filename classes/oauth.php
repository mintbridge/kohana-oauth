<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Library
 *
 * @package    OAuth
 * @author     Paul Dixon
 * @copyright  (c) 2010 Paul Dixon
 * @license    http://kohanaphp.com/license.html
 */
abstract class Auth {

	public static function urlencode_rfc3986($input) 
	{
		if (is_array($input)) 
		{
			return array_map(array('OAuth', 'urlencode_rfc3986'), $input);
		} 
		elseif (is_scalar($input)) 
		{
			return str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($input)));
		} 
		return '';
	}
} // End OAuth