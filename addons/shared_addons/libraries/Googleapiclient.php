<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Google API Class
 * Service Accounts provide certificate-based authentication for server-to-server interactions
 * 
 *
 * @package			CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Googleapiclient
{
	public $googleapiclientpath;
	public $client;
	public $storageService;

	function __construct()
	{
		$this->googleapiclientpath = "addons/shared_addons/libraries/Googleapiclient/src/";
		set_include_path($this->googleapiclientpath . PATH_SEPARATOR . get_include_path());		
		require_once $this->googleapiclientpath.'Google/Client.php';
		//token
		define("SERVICE_PROVIDER", "Google Service Account");
		define("AUTH_DB", "cloud_authentications");

		/* OAUTH params */
		//params
		$client_id = "205701658724.apps.googleusercontent.com";
		$service_account_name = "205701658724-thms3ed2df8c2e80vvakr19i82cotfu8@developer.gserviceaccount.com";
		$key_file_location = $this->googleapiclientpath."Google/key/efa19ee39242680d8a6c5a56933e11f6a16cbc70-privatekey.p12";	
		$apiKey = "AIzaSyAWtSh9QrdOQR27NGczP96x7UnyOsWnfDQ"; //serverKey
		/* Constants for request parameters */
		define('API_VERSION', 'v1beta2');
		define('AMR_PROJECT', 'a-mrooms');

		//set client
		$this->setClient($client_id, $service_account_name, $key_file_location, $apiKey);	
	}



//////////////////////////////////////////////////////////////////
// METHODS ---------------------------------------------------/ //
//////////////////////////////////////////////////////////////////

	/* OBJECTS */

	/**
	 * Get_object from cloud storage bucket
	 * @param  [type] $_bucket [description]
	 * @param  [type] $_object [description]
	 * @return [type]          [description]
	 */
	public function get_object($_bucket = null, $_object = null)
	{
		if($_bucket != null && $_object != null)
		{
			$this->newStorageService();			
			try {
				$apiResponse = $this->storageService->objects->get($_bucket, $_object);
			} catch (Exception $e) {
				log_message('error', $e->getMessage());				
			   	$apiResponse->error = true;
			   	$apiResponse->message = $e->getMessage();
				$apiResponse->code = $e->getCode();			   	
			}		
			return $apiResponse;
		}
		else
			{
				return false;	
			}	
	}


	/**
	 * [insert_object description]
	 * @param  [type] $_bucket       [description]
	 * @param  [type] $_fileUrl      [description]
	 * @param  [type] $_name         [description]
	 * @param  [type] $_content_type [description]
	 * @param  [type] $_bytes        [description]
	 * @return [type]                [description]
	 */
	public function insert_object($_bucket = null, $_fileUrl = null, $_name = null, $_content_type = null, $_bytes = null)
	{
		if($_bucket != null && $_fileUrl != null && $_name != null)
		{
			$this->newStorageService();			
			$params = array(
							'mimeType' => $_content_type,
							'data' => file_get_contents($_fileUrl),
							'uploadType' => 'media',
							'name' => $_name
							);
			$gso = new Google_Service_Storage_StorageObject();
			if($_bytes != null)	$gso->setSize($_bytes);
			if($_name != null)	$gso->setName($_name);					
			//execute
			try {
				set_time_limit(300); 				
				$apiResponse = $this->storageService->objects->insert($_bucket, $gso ,$params);
			} catch (Exception $e) {
				log_message('error', $e->getMessage());				
			   	$apiResponse->error = true;
			   	$apiResponse->message = $e->getMessage();
				$apiResponse->code = $e->getCode();			   	
			}		
			return $apiResponse;
		}
		else
			{
				return false;	
			}	
	}

	/**
	 * [insert_object_batch description]
	 * @param  [type] $params_array [array(bucket,fileUrl,name,contentType,bytes,request_name)]
	 * @return [type]               [description]
	 */
	public function insert_object_batch($params_array)
	{
		$this->newStorageService();		
		$this->load_batch();			
		$this->client->setUseBatch(true);
		$batch = new Google_Http_Batch($this->client);		
		foreach($params_array as $prm)
		{
			if($prm['bucket'] != null && $prm['fileUri'] != null && $prm['contentType'] != null)	
			{					
				$params = array(
								'mimeType' => $prm['contentType'],
								'data' => file_get_contents($prm['fileUri'])		
								);
				$gso = new Google_Service_Storage_StorageObject();
				if($prm['bytes'] != null)	$gso->setSize($prm['bytes']);
				if($prm['name'] != null)	$gso->setName($prm['name']);		
				$req = $this->storageService->objects->insert($prm['bucket'], $gso ,$params);
				$batch->add($req, $prm['requestName'] );
				unset($req);
			}			
		}		
		//execute
		try { 			
			$apiResponse = $batch->execute();
		} catch (Exception $e) {		
			log_message('error', $e->getMessage());			
		   	$apiResponse->error = true;
		   	$apiResponse->message = $e->getMessage();
			$apiResponse->code = $e->getCode();			   	
		}		
		return $apiResponse;
	}


	public function delete_object($_bucket = null, $_object = null)
	{
		if($_bucket != null && $_object != null)
		{
			$this->newStorageService();		
			//execute
			try {
				$apiResponse = $this->storageService->objects->delete($_bucket, $_object);
			} catch (Exception $e) {
				log_message('error', $e->getMessage());				
			   	$apiResponse->error = true;
			   	$apiResponse->message = $e->getMessage();
				$apiResponse->code = $e->getCode();			   	
			}		
			return $apiResponse;
		}
		else
			{
				return false;	
			}	
	}


	/* BUCKETS */

	public function insert_defaultObjectAccessControl($_bucket, $_entity, $_role)
	{
		if($_bucket != null && $_entity != null && $_role != null)
		{		
			$this->newStorageService();				
			$gssOAC = new Google_Service_Storage_ObjectAccessControl();
			$gssOAC->setEntity($_entity);	
			$gssOAC->setRole($_role);				
			try {
				$apiResponse = $this->storageService->defaultObjectAccessControls->insert(
					$_bucket, $gssOAC );
			} catch (Exception $e) {
				log_message('error', $e->getMessage());				
			   	$apiResponse->error = true;
			   	$apiResponse->message = $e->getMessage();
				$apiResponse->code = $e->getCode();			   	
			}		
			return $apiResponse;
		}
		else
			{
				return false;	
			}
	}


	public function get_bucket($_bucket = null)
	{
		if($_bucket != null)
		{		
			$this->newStorageService();
			try {
				$apiResponse = $this->storageService->buckets->get($_bucket);
			} catch (Exception $e) {
				log_message('error', $e->getMessage());
			   	$apiResponse->error = true;
			   	$apiResponse->message = $e->getMessage();
				$apiResponse->code = $e->getCode();
			}		
			return $apiResponse;
		}
		else
			{
				return false;	
			}
	}	


//////////////////////////////////////////////////////////
// ADD SERVICES -------------------------------------// //
//////////////////////////////////////////////////////////

	public function newStorageService()
	{
		require_once $this->googleapiclientpath.'Google/Service/Storage.php';		
		//new service
		$this->storageService = new Google_Service_Storage($this->client);				
	}


//////////////////////////////////////////////////////
// ADD INCLUDES ---------------------------------// //
//////////////////////////////////////////////////////


	public function load_batch()
	{
		require_once 'Google/Http/Batch.php';			
	}

/////////////////////////////////////////////////////////
// AUX ----------------------------------------------/ //
/////////////////////////////////////////////////////////


	public function setClient($clientid, $service_account_name, $key_file_location, $apiKey)
	{
		$this->client = new Google_Client();
		$this->client->setApplicationName("AMR storage");

		// Set your cached access token.
		if ($jsontkn = $this->get_db_token()) 
		{
			$this->client->setAccessToken($jsontkn);
		}
		$key = file_get_contents($key_file_location);
		$cred  = new Google_Auth_AssertionCredentials(
				$service_account_name, 
				array('https://www.googleapis.com/auth/devstorage.full_control'), 
				$key);
		$this->client->setAssertionCredentials($cred);
		$this->client->setClientId($clientid);
  		$this->client->setDeveloperKey($apiKey);		
		//update token
		$this->save_token();	
	}

	public function getClient()
	{
		return $this->client;
	}


	public function save_token()
	{
		// update the cached access token.
		if ($this->client->getAccessToken()) 
		{
			$this->save_db_token($this->client->getAccessToken());
		}	
	}

/////////////////////////////////////////////////////////
// DB ---------------------------------------------- / //
/////////////////////////////////////////////////////////

	/**
	 * Save TOKEN in DB
	 * @param  [type] $tokenjson [description]
	 * @return [type]            [description]
	 */
	public function save_db_token($tokenjson = null)
	{
		if($tokenjson != null)
		{
			$token = json_decode($tokenjson);
			if(is_object($token)
				&& isset($token->access_token)
				&& isset($token->created)
				&& isset($token->expires_in)
				)
			{
				ci()->db->delete(AUTH_DB, array('provider'=>SERVICE_PROVIDER));
				$data = array(
								'provider'=>SERVICE_PROVIDER,
								'json_token'=>$tokenjson,
								'access_token'=>$token->access_token,
								'created_at'=>$token->created,
								'expires'=>$token->expires_in
								);
				ci()->db->insert(AUTH_DB, $data);
			}	
		}
	}

	/**
	 * get TOKEN from DB, if exists
	 * @return [type] [description]
	 */
	public function get_db_token()
	{
		$q = ci()->db->get_where(AUTH_DB, array('provider'=>SERVICE_PROVIDER));
		if($q->num_rows()>0)
		{
			$tkn = $q->row();
			return $tkn->json_token;
		}
		else
			{
				return null;
			}
	}


}