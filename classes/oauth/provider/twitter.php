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
class OAuth_Provider_Twitter extends OAuth_Provider {

	protected $signature = 'HMAC-SHA1';

	public function request_token(OAuth_Consumer $consumer)
	{
		$request = OAuth_Request::factory('token', 'https://api.twitter.com/oauth/request_token')
			->params(array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_callback'     => $consumer->callback,
			));

		// Sign the request using only the consumer, no token is available yet
		$request->sign($this->signature, $consumer);

		// Create a response from the request
		$response = $request->execute();

		// Store this token somewhere useful
		return OAuth_Token::factory('request', array(
			'token'  => $response->param('oauth_token'),
			'secret' => $response->param('oauth_token_secret'),
		));
	}

	public function authorize_url(OAuth_Token $token)
	{
		return 'https://api.twitter.com/oauth/authorize?oauth_token='.OAuth::urlencode($token->token);
	}

	public function access_token(OAuth_Consumer $consumer, OAuth_Token $token)
	{
		$request = OAuth_Request::factory('access', 'https://api.twitter.com/oauth/access_token')
			->params(array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token'        => $token->token,
				'oauth_verifier'     => $token->verifier,
			));

		// Sign the request using only the consumer, no token is available yet
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		// Store this token somewhere useful
		return OAuth_Token::factory('access', array(
			'token'  => $response->param('oauth_token'),
			'secret' => $response->param('oauth_token_secret'),
		));
	}

} // End OAuth_Provider_Twitter
