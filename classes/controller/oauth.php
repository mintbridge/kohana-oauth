<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_OAuth extends Controller {

	protected $provider;

	protected $consumer;

	protected $token;

	public function before()
	{
		parent::before();

		// Load the configuration for this provider
		$config = Kohana::config("oauth.{$this->provider}");

		// Create an consumer from the config
		$this->consumer = OAuth_Consumer::factory($config);

		// Load the provider
		$this->provider = OAuth_Provider::factory('twitter');

		if ($token = Cookie::get('oauth_token'))
		{
			// Get the token from storage
			$this->token = unserialize($token);
		}
	}

	public function action_login()
	{
		// We will need a callback URL for the user to return to
		$callback = URL::site($this->request->uri(array('action' => 'complete')), Request::$protocol);

		// Add the callback URL to the consumer
		$this->consumer->callback($callback);

		// Get a request token for the consumer
		$token = $this->provider->request_token($this->consumer);

		// Store the token
		Cookie::set('oauth_token', serialize($token));

		// Redirect to the twitter login page
		$this->request->redirect($this->provider->authorize_url($token));
	}

	public function action_complete()
	{
		if ($this->token AND $this->token->token !== Arr::get($_REQUEST, 'oauth_token'))
		{
			// Delete the token, it is not valid
			Cookie::delete('oauth_token');

			// Send the user back to the beginning
			$this->request->redirect($this->request->uri(array('action' => 'index')));
		}

		// Get the verifier
		$verifier = Arr::get($_REQUEST, 'oauth_verifier');

		// Store the verifier in the token
		$this->token->verifier($verifier);

		// Exchange the request token for an access token
		$token = $this->provider->access_token($this->consumer, $this->token);

		// Store the token
		Cookie::set('oauth_token', serialize($token));

		$this->request->redirect($this->request->uri(array('action' => FALSE)));
	}

} // End OAuth
