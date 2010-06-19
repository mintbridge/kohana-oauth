<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Consumer
 *
 * @package    Kohana/OAuth
 * @package    Base
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class OAuth_Consumer {

	protected $key;

	protected $secret;

	public function __construct(array $options = NULL)
	{
		if ( ! isset($options['consumer_key']))
		{
			throw new OAuth_Exception('Required option not passed: :option',
				array(':option' => 'consumer_key'));
		}

		if ( ! isset($options['consumer_secret']))
		{
			throw new OAuth_Exception('Required option not passed: :option',
				array(':option' => 'consumer_secret'));
		}

		$this->key = $options['consumer_key'];

		$this->secret = $options['consumer_secret'];
	}

	public function __get($key)
	{
		return $this->$key;
	}

} // End OAuth_Consumer
