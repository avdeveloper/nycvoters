<?php
class Model
{
	protected $verbose = VERBOSE;
	public $db;
	
	// ===== singleton =============================================
	
	protected static $instance;

	public static function engine()
	{
        if (!isset(self::$instance)) 
		{
            $c = get_called_class();
            self::$instance = new $c;
        }
        return self::$instance;
	}
		
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

	// ===== data request =============================================

	static function getVoters($req)
	{
		$req = RequestMapper::encodeApiReq($req);
		return self::authReq(APIENTRY . '/list', $req, 'json');
	}

	static function getParties()
	{
		return self::authReq(APIENTRY . '/parties');
	}
	
	static function getFutureParties()
	{
		return self::authReq(APIENTRY . '/futureparties');
	}
	
	static function getVoter($id)
	{
		return self::authReq(APIENTRY . '/' . $id);
	}
	
	static function authReq($url, $req='', $method='post')
	{
		$hh = ["X-AUTH-KEY: " . APIKEY];
		$res = Curl::exec($url, [CURLOPT_HTTPHEADER => $hh], $req, $method);
		//var_dump($res);
		$jj = json_decode($res, true);
		return $jj;
	}
}