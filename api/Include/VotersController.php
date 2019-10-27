<?php
use Jacwright\RestServer\RestException;
use ScrapingClub\Crawler\Packages\ExternalAPIs\APIMapper;

class VotersController extends AbstractController
{
	public $acceptableAuth = ['key'];

   /**
     * Gets voters list
     *
     * @url POST /list
     */
    public function getVoters()
    {
		$modelX = Model::engine();
		//return $this->request->data;
		return $modelX->getVoters($this->request->data);
    }
		
   /**
     * Gets parties list
     *
     * @url GET /parties
     */
    public function getParties()
    {
		$modelX = Model::engine();
		return $modelX->getParties();
    }
	
   /**
     * Gets future parties list
     *
     * @url GET /futureparties
     */
    public function getFutureParties()
    {
		$modelX = Model::engine();
		return $modelX->getFutureParties();
    }
	
	
   /**
     * Gets voter details
     *
     * @url GET /$id
     */
    public function getVoterDetails($id)
    {
		$modelX = Model::engine();
		return $modelX->getVoterDetails($id);
    }
}
