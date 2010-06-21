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
abstract class OAuth_Token {

	public static function factory($name, array $options = NULL)
	{
		$class = __CLASS__.'_'.$name;

		return new $class($options);
	}

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

	public function __set($key, $value)
	{
		$this->$key = $value;
	}

} // End OAuth_Token
