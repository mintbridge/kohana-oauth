<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Request Credentials
 *
 * @package    OAuth
 * @author     Paul Dixon
 * @copyright  (c) 2010 Paul Dixon
 * @license    http://kohanaphp.com/license.html
 */
class OAuth_Request_Credentials extends OAuth_Request{

	protected var $oauth_callback = NULL;
	
	public function __construct()
	{
		
	}
} // End OAuth_Request_Credentials