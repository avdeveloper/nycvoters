<?php
class Csv
{
	static protected $trimRows = true;
	
	static function encodeCSV($dd, $delim=",", $quot='"', $strDelim="\n", $saveHeaders=true, $decimalConv=true)
	{
		if (!$dd)
			return '';
		$tt = [];
		foreach ($dd as $k=>$v)
			$tt = array_merge($tt, $v);
		$ff = array_keys($tt);
		$rr = $saveHeaders ? [$quot . implode($quot . $delim . $quot, $ff) . $quot] : [];
		foreach ($dd as $d) {
			$tmp = [];
			foreach ($ff as $f)
			{
				$v = $d[$f];
				if (is_real($v))
					$v = $decimalConv ? str_replace(".", ",", (string)$d[$f]) : (string)$d[$f];
				$v = $quot ? str_replace($quot, $quot . $quot, $v) : $v;
				$tmp[$f] = $quot . $v . $quot;
			}
			$rr[] = implode($delim, $tmp);
		}
		$res = implode($strDelim, $rr);
		return $res;
	}

}	