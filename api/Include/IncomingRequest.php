<?php

class IncomingRequest
{
	protected $ip, $userAgent, $key; // $cookies, $request;
	
	function __construct()
	{
		$this->ip = $_SERVER['REMOTE_ADDR'] ?? false;
		$this->userAgent = $_SERVER['HTTP_USER_AGENT'] ?? false;
		$this->key = $_SERVER['HTTP_X_AUTH_KEY'] ?? false;				//X-AUTH-KEY: 
		$this->data = (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json') 
				? json_decode(file_get_contents('php://input'), true)
				: '';
	}
	
	
	function auth()
	{
		$modelX = Model::engine();
		if ($this->key && $modelX->checkToken($this->key))
			return 'key';
		else
			return false;
	}

	
	function output()
	{
		foreach (['ip', 'userAgent', 'key', 'data', 'cookies'] as $n)
			if (is_array($this->$n))
			{	
				echo "{$n}=>\n";
				print_r($this->$n);
			}	
			else
				echo "{$n}=>{$this->$n}\n";

		if ($authType = $this->auth())
			echo "auth positive ({$authType})";
		else 
			echo 'auth negative';
	}

}