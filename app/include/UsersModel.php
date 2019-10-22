<?php
class UsersModel
{
	public static $fixedUsers = [
		'test-user' => 'testpass0000',
	];
	
	static function check($login, $pass)
	{
		return self::$fixedUsers[$login] == $pass;
	}	
}