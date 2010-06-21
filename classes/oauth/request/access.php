<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Access Request
 *
 * @package    Kohana/OAuth
 * @category   Request
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class OAuth_Request_Access extends OAuth_Request {

	protected $name = 'access';

	protected $required = array(
		'oauth_consumer_key',
		'oauth_token',
		'oauth_signature_method',
		'oauth_signature',
		'oauth_timestamp',
		'oauth_nonce',
		'oauth_verifier',
	);

} // End OAuth_Request_Access
