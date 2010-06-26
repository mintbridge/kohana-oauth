<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Authorization Request
 *
 * @package    Kohana/OAuth
 * @category   Request
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class OAuth_Request_Authorize extends OAuth_Request{

	protected $name = 'request';

	protected $required = array(
		'oauth_token',
	);

	public function execute(array $options = NULL)
	{
		return Request::instance()->redirect($this->as_url());
	}

} // End OAuth_Request_Authorize
