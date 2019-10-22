<?php
class DataMapper
{

// ---- voters ------------------------

	static function votersHeaders($data)
	{
		$hh = array_keys(current($data));
		$hh = array_diff($hh, ['County_EMSID']);
		$rr = [];
		foreach ($hh as $h)
			$rr[$h] = RequestMapper::getLabel($h);
		return $rr;
	}
	
	static function votersData($data)
	{
		$rr = [];
		foreach ($data as $row)
		{
			$rr[] = array_merge(
				array_diff_key($row, ['County_EMSID' => 1]),
				[	
					'First_Name' => "<a href='voter.php?id={$row['County_EMSID']}'>{$row['First_Name']}</a>",
					'Last_Name' => "<a href='voter.php?id={$row['County_EMSID']}'>{$row['Last_Name']}</a>"
				]
			);
		}
		return $rr;
	}	

// ---- history ------------------------
	
	static function historyHeaders($data)
	{
		$hh = array_keys(current($data));
		$hh = array_diff($hh, ['id']);
		$rr = [];
		foreach ($hh as $h)
			$rr[$h] = RequestMapper::getLabel($h);
		return $rr;
	}	

	static function historyData($data)
	{
		$rr = [];
		foreach ($data as $row)
			$rr[] = array_diff_key($row, ['id' => 1]);
		return $rr;
	}	

}