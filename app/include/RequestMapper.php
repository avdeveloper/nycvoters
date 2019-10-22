<?php
class RequestMapper
{
	public static $labels = [
		'Apartment_Number' => 'Apartment',
		'House_Number' => 'House',
		'' => '',
	];
	
	static function getEnc($req, $addField=[], $navClean=false)
	{
		$req = array_merge($req, $addField);
		$req = array_diff($req, ['', null]);
		if ($navClean)
			$req = array_diff_key($req, array_fill_keys(['page_num', 'page_size', 'field', 'direction'], 0));
		return http_build_query($req);
	}
	
	static function titleEnc($req)
	{
		$req = array_diff($req, ['', null]);
		$rr = self::encodeApiReq($req);
		$aa = [];
		foreach ((array)$rr['request'] as $f=>$v)
			if (is_array($v))
				$aa[] = self::getLabel($f) . ": <b>" . implode(',', $v) . "</b>";
			else
				$aa[] = self::getLabel($f) . ": <b>{$v}</b>";
		return implode('; ', $aa);
	}
	
	static function encodeFilename($req)
	{
		$req = array_diff($req, ['', null]);
		$rr = self::encodeApiReq($req);
		$aa = [];
		foreach ((array)$rr['request'] as $f=>$v)
			if (is_array($v))
				$aa[] = "{$f}=" . implode(',', $v);
			else
				$aa[] = "{$f}={$v}";
		return implode(';', $aa);
	}
	
	static function getLabel($id)
	{
		if (self::$labels[$id])
			return self::$labels[$id];
		if ($id == 'Future_Party_Effective_Date')
			return 'Future Party Eff Date';
		return preg_replace('~_~', ' ', $id);
	}
	
// ----- api -----------------------------------	
	
	static function encodeApiReq(array $req)
	{
		$rr = [];
		$mm = [
			'First_Name' => 'request',
			'Last_Name' => 'request',
			'Apartment_Number' => 'request',
			'House_Number' => 'request',
			'Street_Name' => 'request',
			'City' => 'request',
			'Zip_Code' => 'request',
			'Political_Party' => 'request',
			'Future_Party' => 'request',
			'Election_District' => 'request',
			'Assembly_District' => 'request',
			'Congress_District' => 'request',
			'Council_District' => 'request',
			'Senate_District' => 'request',
			'Civil_Court_District' => 'request',
			'Judicial_District' => 'request',
			'Year_Last_Voted_min' => 'request',
			'Year_Last_Voted_max' => 'request',
			'Times_Voted_min' => 'request',
			'Times_Voted_max' => 'request',
			
			'field' => 'order',
			'direction' => 'order',
			
			'page_size' => 'paging',
			'page_num' => 'paging',
			'res_type' => 'paging',
		];
		foreach ($req as $k=>$v)
			if ($mm[$k] && $v <> '')
				$rr[$mm[$k]][$k] = $v;
		return $rr;	
	}
	
}