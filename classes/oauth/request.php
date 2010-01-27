<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Request
 *
 * @package    OAuth
 * @author     Paul Dixon
 * @copyright  (c) 2010 Paul Dixon
 * @license    http://kohanaphp.com/license.html
 */
abstract class OAuth_Request 
{
	protected static $oauth_version = '1.0';
	
	protected $oauth_consumer_key = NULL;
	
	protected $oauth_signature_method = NULL;
	
	protected $oauth_signature = NULL;
	
	protected $oauth_callback = NULL;
	
	
} // End OAuth_Request