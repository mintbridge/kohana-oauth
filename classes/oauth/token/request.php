<?php defined('SYSPATH') or die('No direct script access.');

class OAuth_Token_Request extends OAuth_Token {

	protected $name = 'request';

	protected $verifier;

	public function verifier($verifier)
	{
		$this->verifier = $verifier;
	}

} // End OAuth_Token_Request
