<?php defined('SYSPATH') or die('No direct script access.');

class Controller_OAuth_Twitter extends Controller {

	public function action_index()
	{
		echo HTML::anchor($this->request->uri(array('action' => 'login')), 'Login with Twitter');

		if ($token = Cookie::get('oauth_token'))
		{
			$token = unserialize($token);

			if ($token->name === 'access')
			{
				echo Kohana::debug('access token granted!');
			}
			else
			{
				echo Kohana::debug('token exists, but does not allow access');
			}
		}
	}

	public function action_login()
	{
		$config = Kohana::config('oauth.twitter');

		$config['callback'] = 'http://local.kohanaframework.org/oauth/twitter/complete';

		$consumer = OAuth_Consumer::factory($config);

		$provider = OAuth_Provider::factory('twitter');

		// Get a request token for the consumer
		$token = $provider->request_token($consumer);

		// Store the token
		Cookie::set('oauth_token', serialize($token));

		// Redirect to the twitter login page
		$this->request->redirect($provider->authorize_url($token));
	}

	public function action_complete()
	{
		$config = Kohana::config('oauth.twitter');

		$consumer = OAuth_Consumer::factory($config);

		$provider = OAuth_Provider::factory('twitter');

		$token = unserialize(Cookie::get('oauth_token'));

		if ($token->token !== Arr::get($_REQUEST, 'oauth_token'))
		{
			Cookie::delete('oauth_token');

			$this->request->redirect($this->request->uri(array('action' => 'login')));
		}

		$token->verifier = Arr::get($_REQUEST, 'oauth_verifier');

		// Exchange the request token for an access token
		$token = $provider->access_token($consumer, $token);

		// Store the token
		Cookie::set('oauth_token', serialize($token));

		$this->request->redirect($this->request->uri(array('action' => FALSE)));
	}

} // End OAuth_Twitter
