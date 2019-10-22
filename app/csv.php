<?php
require_once __DIR__ . '/include/loader.php';

$r = IncomingRequest::engine();
$v = View::engine();
if (!$r->auth())
	$v->redirect('login.php');
if (!$_GET)
	Throw new Exception('Malformed request');
$m = Model::engine();
$req = array_merge($_GET, ['res_type' => 'full', 'page_size' => 100000, 'page_num' => 1]);
$data = $m->getVoters($req);
$csv = Csv::encodeCSV($data['results']);
$v->sendCSV($csv, RequestMapper::encodeFilename($_GET) . date("-Ymd-His") . ".csv");
