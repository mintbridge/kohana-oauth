<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Token
 *
 * @package    Kohana/OAuth
 * @package    Token
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class OAuth_Token {

	protected $token;

	protected $secret;

	public function __construct(array $options = NULL)
	{
		if ( ! isset($options['token']))
		{
			throw new OAuth_Exception('Required option not passed: :option',
				array(':option' => 'token'));
		}

		if ( ! isset($options['secret']))
		{
			throw new OAuth_Exception('Required option not passed: :option',
				array(':option' => 'secret'));
		}

		$this->token = $options['token'];

		$this->secret = $options['secret'];
	}

	public function __get($key)
	{
		return $this->$key;
	}

} // End OAuth_Token
