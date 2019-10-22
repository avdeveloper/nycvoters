<?php
require_once __DIR__ . '/include/loader.php';

$r = IncomingRequest::engine();
$r->logout();
$v = View::engine();
$v->redirect('login.php');