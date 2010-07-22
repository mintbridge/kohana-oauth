<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Token
 *
 * @package    Kohana/OAuth
 * @category   Token
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 * @since      3.0.7
 */
abstract class Kohana_OAuth_Token {

	/**
	 * Create a new token object.
	 *
	 *     $token = OAuth_Token::factory($name);
	 *
	 * @param   string  token type
	 * @param   array   token options
	 * @return  OAuth_Token
	 */
	public static function factory($name, array $options = NULL)
	{
		$class = 'OAuth_Token_'.$name;

		return new $class($options);
	}

	/**
	 * @var  string  token type name: request, access
	 */
	protected $name;

	/**
	 * @var  string  token key
	 */
	protected $token;

	/**
	 * @var  string  token secret
	 */
	protected $secret;

	/**
	 * Sets the token and secret values.
	 *
	 * @param   array   token options
	 * @return  void
	 */
	public function __construct(array $options = NULL)
	{
		if ( ! isset($options['token']))
		{
			throw new Kohana_OAuth_Exception('Required option not passed: :option',
				array(':option' => 'token'));
		}

		if ( ! isset($options['secret']))
		{
			throw new Kohana_OAuth_Exception('Required option not passed: :option',
				array(':option' => 'secret'));
		}

		$this->token = $options['token'];

		$this->secret = $options['secret'];
	}

	/**
	 * Return the value of any protected class variable.
	 *
	 *     // Get the token secret
	 *     $secret = $token->secret;
	 *
	 * @param   string  variable name
	 * @return  mixed
	 */
	public function __get($key)
	{
		return $this->$key;
	}

} // End OAuth_Token
