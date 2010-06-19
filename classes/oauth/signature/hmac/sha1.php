<?php defined('SYSPATH') or die('No direct script access.');

class OAuth_Signature_HMAC_SHA1 extends OAuth_Signature {

	protected $name = 'HMAC-SHA1';

	public function sign($data, OAuth_Consumer $consumer)
	{
		return hash_hmac('sha1', $data, parent::sign($data, $consumer));
	}

} // End OAuth_Signature_HMAC_SHA1
