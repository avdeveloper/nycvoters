<?php
require_once __DIR__ . '/include/loader.php';

$r = IncomingRequest::engine();
$v = View::engine();
if ($r->auth())
	$v->redirect('search.php');
$alert = $_POST['login'] || $_POST['pass'] ? ['Login or password is not correct'] : [];
$v->loginPage($_POST['login'], $alert);