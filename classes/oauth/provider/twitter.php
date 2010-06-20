<?php defined('SYSPATH') or die('No direct script access.');

class OAuth_Provider_Twitter extends OAuth_Provider {

	protected $signature;

	public function __construct(OAuth_Consumer $consumer)
	{
		parent::__construct($consumer);

		$this->signature = new OAuth_Signature_HMAC_SHA1;
	}

	public function __toString()
	{
		return get_class($this).'['.implode(',', OAuth::urlencode($this->params())).']';
	}

	public function request_token()
	{
		$params = array(
			'oauth_version'          => $this->version,
			'oauth_consumer_key'     => $this->consumer->key,
			'oauth_signature_method' => $this->signature->name,
			'oauth_timestamp'        => $this->timestamp(),
			'oauth_nonce'            => $this->nonce(),
		);

		if ($this->consumer->callback)
		{
			$params['oauth_callback'] = $this->consumer->callback;
		}

		$request = new OAuth_Request('GET', 'https://api.twitter.com/oauth/request_token', $params);

		$base = $this->signature->base($request, $this->consumer);

		$request->param('oauth_signature', $this->signature->sign($base, $this->consumer));

		$token = $request->execute($params);

		return new OAuth_Token($token);
	}

	public function access_token()
	{
	}

} // End OAuth_Provider_Twitter
