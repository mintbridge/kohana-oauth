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

	protected $urls = array(
		'token'     => 'https://api.twitter.com/oauth/request_token',
		'authorize' => 'https://api.twitter.com/oauth/authorize',
		'access'    => 'https://api.twitter.com/oauth/access_token',
	);

	public function status_update(OAuth_Consumer $consumer, OAuth_Token_Access $token, $status, $format = 'json')
	{
		$request = OAuth_Request::factory('resource', 'POST', "https://api.twitter.com/1/statuses/update.{$format}", array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token'        => $token->token,
		));

		// Add "status" to the POST body
		$request->post('status', $status);

		// Sign the request using only the consumer, no token is available yet
		$request->sign($this->signature, $consumer, $token);

		// Return the response
		return $request->execute();
	}

} // End OAuth_Provider_Twitter
