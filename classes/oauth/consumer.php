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

	public static function factory(array $options = NULL)
	{
		return new OAuth_Consumer($options);
	}

	protected $key;

	protected $secret;

	protected $callback;

	public function __construct(array $options = NULL)
	{
		if ( ! isset($options['key']))
		{
			throw new OAuth_Exception('Required option not passed: :option',
				array(':option' => 'key'));
		}

		if ( ! isset($options['secret']))
		{
			throw new OAuth_Exception('Required option not passed: :option',
				array(':option' => 'secret'));
		}

		$this->key = $options['key'];

		$this->secret = $options['secret'];

		if (isset($options['callback']))
		{
			$this->callback = $options['callback'];
		}
	}

	public function __get($key)
	{
		return $this->$key;
	}

} // End OAuth_Consumer
