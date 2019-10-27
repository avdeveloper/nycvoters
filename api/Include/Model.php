<?php
Use ScrapingClub\Crawler\Packages\Db\Db;

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

	public function getVoters(array $req)
	{
		if ($this->verbose)
			file_put_contents(ROOTDIR . '/srv/lastreq.dat', print_r($req, true));
		if (!$req['request'])
			throw new Exception('Empty request');
		$enhReq = $partyReq = [];
		foreach ($req['request'] as $k=>$v)
		{
			$k = addslashes($k);
			$v = !is_array($v) ? addslashes($v) : $v;
			switch ($k)
			{
				case 'Political_Party':
				case 'Future_Party':
					$vv = implode("','", (array)$v);
					$partyReq[] = "{$k} IN ('{$vv}')";
					break;
				case 'Year_Last_Voted_min':
				case 'Times_Voted_min':
					$f = preg_replace('~_min~si', '', $k);
					$v = (int)$v;
					$enhReq[] = "{$f} >= {$v}";
					break;
				case 'Year_Last_Voted_max':
				case 'Times_Voted_max':
					$f = preg_replace('~_max~si', '', $k);
					$v = (int)$v;
					$enhReq[] = "{$f} <= {$v}";
					break;
				default:
					$enhReq[] = "{$k} LIKE '{$v}'";
			}
		}
		if ($partyReq)
			$enhReq[] = '(' . implode(' OR ', $partyReq) . ')';
		$whereStr = implode(' AND ', $enhReq);
		
/*		$enhOrd = [];
		foreach ((array)$req['order'] as $k=>$order)
		{
			$k = addslashes($k);
			$order = preg_match('~^desc$~si', $order) ? ' DESC' : '';
			$enhOrd[] = "{$k}{$order}";
		}
		$ordStr = $enhOrd ? "\nORDER BY " . implode(',', $enhOrd) : '';
*/		$ordStr = $req['order'] ? "\nORDER BY {$req['order']['field']} {$req['order']['direction']}" : "\nORDER BY First_Name, Last_Name";
		
		$pageSize = (int)($req['paging']['page_size'] ?? 50);
		$pageNum = (int)($req['paging']['page_num'] ?? 1);
		$limitStr = ($pageSize && $pageNum) 
			? sprintf("\nLIMIT %u,%u", $pageSize * ($pageNum - 1), $pageSize)
			: '';
		
		$ts = microtime(true);
		$cnt = $this->db->compressedSelect("SELECT count(*) FROM voter_data WHERE {$whereStr}");

		$fieldset = 
		
		$reqStr = $req['paging']['res_type'] == 'full'
			? "SELECT * "
			: <<<EOD
SELECT
	County_EMSID,
	First_Name,
	Last_Name,
	Political_Party,
	Future_Party,
	House_Number,
	Street_Name,
	Apartment_Number,
	City,
	Zip_Code,
	Telephone,
	Times_Voted
EOD;
		$reqStr .= <<<EOD

FROM voter_data
WHERE {$whereStr} {$ordStr} {$limitStr}
EOD;
		//$this->log($reqStr);
		$data = $this->db->q($reqStr);
		if ($this->verbose)
			file_put_contents(ROOTDIR . '/srv/lastreq.dat', "\n\n{$reqStr}", FILE_APPEND);
		$res = ['results' => $data, 'total' => $cnt, 'page_num' => $pageNum, 'page_size' => $pageSize, 'exec_time' => round(microtime(true) - $ts, 3)];
		if ($this->verbose)
			file_put_contents(ROOTDIR . '/srv/lastresp.dat', print_r($res, true));
		return $res;
	}

	public function getVoterDetails($id)
	{
		$ts = microtime(true);
		$voter = $this->db->compressedSelect("SELECT * FROM voter_data WHERE County_EMSID LIKE '{$id}'");
		$history = $this->db->q("SELECT * FROM voter_history WHERE County_EMSID LIKE '{$id}' ORDER BY Election_Date desc");
		return ['voter' => $voter, 'history' => $history, 'exec_time' => round(microtime(true) - $ts, 3)];
	}
	

	
	// ===== tokens =============================================

	public function checkToken($token)
	{
		$token = addslashes($token);
		$token = md5($token);
		
		return ($this->db->compressedSelect("SELECT count(*) from tokens WHERE token LIKE '{$token}'") > 0);
	}
	
	public function saveToken($token)
	{
		$token = addslashes($token);
		$md = md5($token);
		
		$this->db->compressedSelect("INSERT IGNORE INTO tokens (token) VALUES ('{$md}')");
		return true;
	}


	// ===== parties =============================================

	public function getParties()
	{
		return $this->db->compressedSelect('SELECT code FROM parties');
	}

	public function getFutureParties()
	{
		return $this->db->compressedSelect('SELECT code FROM future_parties');
	}


	// ===== srv ================================================

	protected function __construct()
	{
		$this->db = new Db(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'), getenv('DB_NAME'));
	}
	
	
	protected function log($msg)
	{
		if (!$this->verbose)
			return;
		echo "{$msg}\n";
		flush();
	}
}
