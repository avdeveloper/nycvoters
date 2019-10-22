<?php
class UsersModel
{
	public static $fixedUsers = [
		'dummy-user' => 'dummy-PASS-987(*?',
		'dummy-user1' => 'dummy-PASS-()90()90',
		'brooklynlp' => 'ks4;c3:^YWDC6/!',
		'bronxlp' => '+8*f*wv5]xxF+7_',
		'manhattanlp' => 'ZR5q5?3Gs]";&?5',
		'statenislandlp' => 'AeXw]&%K7~B,X/-',
		'queenslp' => 'tef#2T?6H+mGzAZ',
	];
	
	static function check($login, $pass)
	{
		return self::$fixedUsers[$login] == $pass;
	}	
}