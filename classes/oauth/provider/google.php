<?php defined('SYSPATH') or die('No direct script access.');
/**
 * OAuth Google Provider
 *
 * Documents for implementing Google OAuth can be found at
 * <http://code.google.com/apis/accounts/docs/OAuth.html>.
 * Individual Google APIs have separate documentation. A complete list is
 * available at <http://code.google.com/more/>.
 *
 * [!!] This class does not implement any Google API. It is only an
 * implementation of standard OAuth with Google as the service provider.
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

	public function user_profile(OAuth_Consumer $consumer, OAuth_Token_Access $token, $format = 'json')
	{
		$request = OAuth_Request::factory('resource', 'GET', "http://www-opensocial.googleusercontent.com/api/people/@me/@self", array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token'        => $token->token,
		));

		// Sign the request using only the consumer, no token is available yet
		$request->sign($this->signature, $consumer, $token);

		// Return the response
		return $request->execute();
	}

} // End OAuth_Provider_Google
