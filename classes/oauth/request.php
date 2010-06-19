<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * OAuth Request
 *
 * @package    Kohana/OAuth
 * @package    Basex
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class OAuth_Request {

	protected static $oauth_version = '1.0';

	protected $oauth_consumer_key = NULL;

	protected $oauth_signature_method = NULL;

	protected $oauth_signature = NULL;

	protected $oauth_callback = NULL;


} // End OAuth_Request
