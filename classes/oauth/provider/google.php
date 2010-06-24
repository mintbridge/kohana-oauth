<?php defined('SYSPATH') or die('No direct script access.');
/**
 * OAuth Twitter Provider
 *
 * @package    Kohana/OAuth
 * @category   Provider
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class OAuth_Provider_Google extends OAuth_Provider {

	protected $signature = 'HMAC-SHA1';

	protected $urls = array(
		'request_token' => 'https://www.google.com/accounts/OAuthGetRequestToken',
		'authorize_url' => 'https://www.google.com/accounts/OAuthAuthorizeToken',
		'access_token'  => 'https://www.google.com/accounts/OAuthGetAccessToken',
	);

	protected $params = array(
		'request_token' => array(
			'scope' => 'http://www-opensocial.googleusercontent.com/api/people/',
		),
	);

} // End OAuth_Provider_Google
