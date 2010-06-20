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

	public static function factory($name, array $options = NULL)
	{
		$class = __CLASS__.'_'.str_replace('-', '_', $name);

		return new $class($options);
	}

	protected $name;

	public function __get($key)
	{
		return $this->$key;
	}

	public function base(OAuth_Request $request, OAuth_Consumer $consumer, OAuth_Token $token = NULL)
	{
		$params = $request->params;

		// oauth_signature is never included in the base string!
		unset($params['oauth_signature']);

		// method & url & sorted-parameters
		return implode('&', array(
			$request->method,
			OAuth::urlencode($request->url),
			OAuth::urlencode(OAuth::build_query($params)),
		));
	}

	public function secrets(OAuth_Consumer $consumer, OAuth_Token $token = NULL)
	{
		$base = OAuth::urlencode($consumer->secret).'&';

		if ($token)
		{
			$base .= OAuth::urlencode($token->secret);
		}

		return $base;
	}

	abstract public function sign($data, OAuth_Consumer $consumer, OAuth_Token $token);

	public function verify($signature, $data, OAuth_Consumer $consumer, OAuth_Token $token)
	{
		return $signature === $this->sign($data, $consumer, $token);
	}



} // End OAuth_Signature
