<?php defined('SYSPATH') or die('No direct script access.');

class OAuth_Signature_HMAC_SHA1 extends OAuth_Signature {

	protected $name = 'HMAC-SHA1';

	public function sign($data, OAuth_Consumer $consumer, OAuth_Token $token = NULL)
	{
		$key = $this->secrets($consumer, $token);

		$hash = hash_hmac('sha1', $data, $key, TRUE);

		return base64_encode($hash);
	}

} // End OAuth_Signature_HMAC_SHA1
