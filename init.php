<?php defined('SYSPATH') or die('No direct script access.');

Route::set('oauth', 'oauth(/<controller>(/<action>))')
	->defaults(array(
		'directory'  => 'oauth',
		'controller' => 'demo',
	));
