<?php defined('SYSPATH') or die('No direct script access.');

class Controller_OAuth_Twitter extends Controller_OAuth {

	protected $provider = 'twitter';

	public function action_index()
	{
		if ($this->token AND $this->token->name === 'access')
		{
			$this->request->response = Request::factory($this->request->uri(array('action' => 'tweet')))->execute();
		}
		else
		{
			$this->request->response = HTML::anchor($this->request->uri(array('action' => 'login')), 'Login with Twitter');
		}
	}

	public function action_tweet()
	{
		$this->request->response = View::factory('oauth/twitter/tweet')
			->bind('response', $response)
			->bind('tweet', $tweet);

		if ($tweet = Arr::get($_POST, 'tweet'))
		{
			$response = $this->provider->status_update($this->consumer, $this->token, $tweet);

			$response = Kohana::debug($response);
		}
	}

} // End OAuth_Twitter
