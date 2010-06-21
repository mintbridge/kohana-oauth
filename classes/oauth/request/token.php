<?php defined('SYSPATH') or die('No direct script access.');
/**
 * OAuth Token Request
 *
 * @package    Kohana/OAuth
 * @category   Request
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class OAuth_Request_Token extends OAuth_Request {

	protected $name = 'request';

	protected $required = array(
		'oauth_callback',
		'oauth_consumer_key',
		'oauth_signature_method',
		'oauth_signature',
		'oauth_timestamp',
		'oauth_nonce',
	);

} // End OAuth_Request_Token
