<?php defined('SYSPATH') or die('No direct script access.');

class OAuth_Signature_PLAINTEXT extends OAuth_Signature {

	protected $name = 'PLAINTEXT';

	public function sign($data, OAuth_Consumer $consumer, OAuth_Token $token)
	{
		return OAuth::urlencode($this->secrets($consumer, $token));
	}

} // End OAuth_Signature_HMAC_SHA1
