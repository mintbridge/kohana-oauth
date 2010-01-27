<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Client
 *
 * @package    OAuth
 * @author     Paul Dixon
 * @copyright  (c) 2010 Paul Dixon
 * @license    http://kohanaphp.com/license.html
 */
abstract class OAuth_Client 
{
	public $key;
  	public $secret;
	public $callback;

	function __construct($name = 'default', $options = NULL) 
	{
		$this->key = $key;
		$this->secret = $secret;
		$this->callback_url = $callback_url;
	}
	
	function __toString()
	{
		return "OAuth_Client[key=$this->key,secret=$this->secret]";
	}
	
} // End OAuth_Client