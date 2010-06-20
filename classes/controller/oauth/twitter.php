<?php defined('SYSPATH') or die('No direct script access.');

class Controller_OAuth_Twitter extends Controller {

	public function action_index()
	{
		// $token = new OAuth_Token(array('key' => rand().microtime(), 'secret' => sha1(rand().microtime())));
		// $consumer = new OAuth_Consumer(array('key' => rand().microtime(), 'secret' => sha1(rand().microtime())));
		// $signature = new OAuth_Signature_HMAC_SHA1;
		// 
		// 
		// 
		// echo Kohana::debug($one = $signature->sign($data, $consumer, $token), $signature->verify($one, $data, $consumer, $token));

		echo HTML::anchor($this->request->uri(array('action' => 'login')), 'Login with Twitter');
	}

	public function action_login()
	{
		$config = Kohana::config('oauth.twitter');

		$config['callback'] = 'http://demo.kohanaframework.org/oauth/twitter/complete';

		$consumer = OAuth_Consumer::factory($config);

		$provider = OAuth_Provider::factory('twitter', $consumer);

		echo Kohana::debug($provider->request_token());
	}

} // End OAuth_Twitter
