<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Signature
 *
 * @package    Kohana/OAuth
 * @package    Base
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class OAuth_Signature {

	protected $name;

	final public function name()
	{
		return $this->name;
	}

	public function sign($data, OAuth_Consumer $consumer)
	{
		return OAuth::urlencode($consumer->key).'&'.OAuth::urlencode($consumer->secret);
	}

	public function verify($data, OAuth_Consumer $consumer, $signature);
	{
		return $signature === $this->sign($data, $consumer);
	}

} // End OAuth_Signature
