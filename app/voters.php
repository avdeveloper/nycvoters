<?php
require_once __DIR__ . '/include/loader.php';

$r = IncomingRequest::engine();
$v = View::engine();
if (!$r->auth())
	$v->redirect('login.php');
$m = Model::engine();
$data = $m->getVoters($_GET);
$v->votersPage($_GET, $data);
//var_dump($data);