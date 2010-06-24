<?php defined('SYSPATH') or die('No direct script access.');

class Controller_OAuth_Google extends Controller_OAuth {

	protected $provider = 'google';

	public function action_index()
	{
		if ($this->token AND $this->token->name === 'access')
		{
			$this->request->response = Request::factory($this->request->uri(array('action' => 'profile')))->execute();
		}
		else
		{
			$this->request->response = HTML::anchor($this->request->uri(array('action' => 'login')), 'Login with Google');
		}
	}

	public function action_profile()
	{
		$response = $this->provider->user_profile($this->consumer, $this->token);

		echo Kohana::debug(json_decode((string) $response));
	}

} // End OAuth_Twitter
