<?php
use	ScrapingClub\Crawler\Packages\Content\Log;
 
set_time_limit(0);
error_reporting(E_ALL ^ E_NOTICE);

define('ROOTDIR', dirname(__DIR__));
define('DATADIR', ROOTDIR . '/data');
require_once ROOTDIR . '/Composed/vendor/autoload.php';
if (file_exists(ROOTDIR . '/ini.php'))
	require_once ROOTDIR . '/ini.php';
if (file_exists(ROOTDIR . '/_env.php'))
	require_once ROOTDIR . '/_env.php';

//===== settings hidden ========================================================
define('VERBOSE', false);

//==============================================================================
foreach (scandir(__DIR__) as $f)
	if (!preg_match('~\.$|_|loader~si', $f) && !is_dir(__DIR__ . "/{$f}"))
		require_once(__DIR__ . "/{$f}");
