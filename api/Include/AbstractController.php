<?php
use Jacwright\RestServer\RestException;
use ScrapingClub\Crawler\Packages\ExternalAPIs\APIMapper;

class AbstractController
{
	public $request;
	public $acceptableAuth = [];
	
	function __construct()
	{
		$this->request = new IncomingRequest();
	}
	
	function authorize()
	{
		return $this->request->auth() <> false;
	}

    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @url GET /
     */
    public function placeholder()
    {
        throw new RestException(404, 'Not found');
    }

// == parameters checking ===============================================
/*	
	protected function verifyQuery($query, $obligatoryFields)
	{
		$allowed = [
			'from' => '~^\d{4}-\d{2}-\d{2}$~si',
			'to' => '~^\d{4}-\d{2}-\d{2}$~si',
		];
		$placeholders = [
			'from' => date('Y-m-d'),
			'to' => date('Y-m-d', strtotime('+10 days')),
		];
		foreach ($query as $k=>$v)
			if (!isset($allowed[$k]) || !preg_match($allowed[$k], $v))
				throw new RestException(400, 'Malformed');
		foreach ($obligatoryFields as $f)
			if (!isset($query[$f]))
				$query[$f] = $placeholders[$f];
		return $query;
	}
*/	
}