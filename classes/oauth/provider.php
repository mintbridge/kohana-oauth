<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Provider
 *
 * @package    Kohana/OAuth
 * @category   Provider
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class OAuth_Provider {

	public static function factory($name, array $options = NULL)
	{
		$class = __CLASS__.'_'.$name;

		return new $class($options);
	}

	public function __construct(array $options = NULL)
	{
		if (isset($options['signature']))
		{
			$this->signature = $options['signature'];
		}

		if ( ! is_object($this->signature))
		{
			$this->signature = OAuth_Signature::factory($this->signature);
		}
	}

	public function __get($key)
	{
		return $this->$key;
	}

	abstract public function request_token(OAuth_Consumer $consumer);

	abstract public function access_token(OAuth_Consumer $consumer, OAuth_Token $token);

} // End OAuth_Signature
