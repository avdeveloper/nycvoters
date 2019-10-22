<?php
require_once __DIR__ . '/include/loader.php';

$r = IncomingRequest::engine();
$v = View::engine();
if (!$r->auth())
	$v->redirect('login.php');
$id = $_GET['id'];
if (!$id)
	$v->back();
$m = Model::engine();
$data = $m->getVoter($id);
$v->voterPage($id, $data);