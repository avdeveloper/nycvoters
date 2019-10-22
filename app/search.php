<?php
require_once __DIR__ . '/include/loader.php';

$r = IncomingRequest::engine();
$v = View::engine();
if (!$r->auth())
	$v->redirect('login.php');
$v->searchPage($_GET);