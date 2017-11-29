<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['debug'] = '1';
$config['enable_devlog_alerts'] = 'n';
$config['cache_driver'] = 'file';
$config['is_system_on'] = 'y';
$config['multiple_sites_enabled'] = 'n';
// ExpressionEngine Config Items
// Find more configs and overrides at
// https://docs.expressionengine.com/latest/general/system_configuration_overrides.html

$config['app_version'] = '3.5.11';
$config['encryption_key'] = '119f16e501e06b55973c92de10fae16fad612c2b';
$config['session_crypt_key'] = '5aaaaa4015c62c0dfaf0f824b9bd7e293d7df976';
$config['database'] = array(
	'expressionengine' => array(
		'hostname' => 'localhost',
		'database' => 'c9',
		'username' => 'root',
		'password' => '',
		'dbprefix' => 'exp_',
		'port'     => ''
	),
);

// EOF