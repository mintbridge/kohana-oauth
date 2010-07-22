<?php defined('SYSPATH') or die('No direct script access.');
/**
 * OAuth Request Token
 *
 * @package    Kohana/OAuth
 * @category   Token
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_OAuth_Token_Request extends OAuth_Token {

	protected $name = 'request';

	/**
	 * @var  string  request token verifier
	 */
	protected $verifier;

	/**
	 * Change the token verifier.
	 *
	 *     $token->verifier($key);
	 *
	 * @param   string   new verifier
	 * @return  $this
	 */
	public function verifier($verifier)
	{
		$this->verifier = $verifier;

		return $this;
	}

} // End OAuth_Token_Request
