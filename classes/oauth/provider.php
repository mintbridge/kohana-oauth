<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Provider
 *
 * @package    Kohana/OAuth
 * @package    Base
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class OAuth_Provider {

	public static function factory($name, OAuth_Consumer $consumer)
	{
		$class = __CLASS__.'_'.$name;

		return new $class($consumer);
	}

	protected $version = '1.0';

	protected $consumer;

	public function __construct(OAuth_Consumer $consumer)
	{
		$this->consumer = $consumer;
	}

	public function __get($key)
	{
		return $this->$key;
	}

	public function timestamp()
	{
		return time();
	}

	public function nonce()
	{
		return Text::random('alnum', 20);
	}

} // End OAuth_Signature
