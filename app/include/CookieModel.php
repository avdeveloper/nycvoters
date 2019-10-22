<?php
class CookieModel
{
	public static $lifetime = 3600 * 24 * 3;
	public static $salt = '2l4k23jh4jh';
	static function generate()
	{
		$ts = time() + self::$lifetime;
		$name = bin2hex(random_bytes(15));
		$value = (string)crc32($name . $salt) * 100001 + $ts;
		return compact(['name', 'value', 'ts']);
	}
	
	static function check($name, $value)
	{
		$ts = $value - (string)crc32($name . $salt) * 100001;
		$ts = $ts - time();
		//echo $ts . "\n";
		if ($ts > 0 && $ts <= 3 * 365 * 24 * 3600)
		{
			if ($ts <= self::$lifetime * 0.6)
				self::replace($name);
			return true;
		}
		return false;
	}

	static function replace($name)
	{
		self::unset($name);
		self::sendNew();
	}
	
	static function sendNew()
	{
		extract(self::generate());
		return setcookie($name, $value, $ts);
	}
	
	static function unset($name)
	{
		return setcookie($name, '', time() - 3600);
	}
	
}