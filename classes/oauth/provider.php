<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Provider
 *
 * @package    Kohana/OAuth
 * @category   Provider
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class OAuth_Provider {

	/**
	 * Create a new provider.
	 *
	 *     // Load the Twitter provider
	 *     $provider = OAuth_Provider::factory('twitter');
	 *
	 * @param   string   provider name
	 * @param   array    provider options
	 * @return  OAuth_Provider
	 */
	public static function factory($name, array $options = NULL)
	{
		$class = __CLASS__.'_'.$name;

		return new $class($options);
	}

	/**
	 * Overloads default class properties from the options.
	 *
	 * @param   array   provider options
	 */
	public function __construct(array $options = NULL)
	{
		if (isset($options['signature']))
		{
			// Set the signature method name or object
			$this->signature = $options['signature'];
		}

		if ( ! is_object($this->signature))
		{
			// Convert the signature name into an object
			$this->signature = OAuth_Signature::factory($this->signature);
		}
	}

	/**
	 * Return the value of any protected class variable.
	 *
	 *     // Get the provider signature
	 *     $signature = $provider->signature;
	 *
	 * @param   string  variable name
	 * @return  mixed
	 */
	public function __get($key)
	{
		return $this->$key;
	}

	/**
	 * Ask for a request token from the OAuth provider.
	 *
	 *     $token = $provider->request_token($consumer);
	 *
	 * @param   OAuth_Consumer  consumer
	 * @return  OAuth_Token_Request
	 * @uses    OAuth_Request_Token
	 */
	public function request_token(OAuth_Consumer $consumer)
	{
		$request = OAuth_Request::factory('token', 'GET', $this->urls['token'], array(
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

	/**
	 * Get the authorization URL for the request token.
	 *
	 *     $this->request->redirect($provider->authorize_url($token));
	 *
	 * @param   OAuth_Token_Request  token
	 * @return  string
	 */
	public function authorize_url(OAuth_Token_Request $token)
	{
		$query = OAuth::normalize_params(array(
			'oauth_token' => $token->token,
		));

		return $this->urls['authorize'].'?'.$query;
	}

	/**
	 * Exchange the request token for an access token.
	 *
	 *     $token = $provider->access_token($consumer, $token);
	 *
	 * @param   OAuth_Consumer       consumer
	 * @param   OAuth_Token_Request  token
	 * @return  OAuth_Token_Access
	 */
	public function access_token(OAuth_Consumer $consumer, OAuth_Token_Request $token)
	{
		$request = OAuth_Request::factory('access', 'GET', $this->urls['access'], array(
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

} // End OAuth_Signature
