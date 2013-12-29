<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Publication Class
 *
 *
 * @package			CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Publication
{
	public $prodset;
	public $method;

    function __construct($params)
    {	
		switch($params['typeid'])
		{
			case ALQ_ESPACIOS_TYPEID: 
										require_once 'publication/typeid/'.ALQ_ESPACIOS_TYPEID.'/prodset_class.php';
										break;

			default:					return $this->prodset;					    
		}				
		$this->prodset = new prodset($params);	
		$this->method = $params['method'];
	}

	
	public function set_index()
	{
		$this->prodset->set_tot_rows();
		$this->prodset->set_postdata();
		$this->prodset->set_result();  
		$this->prodset->set_template();        
	}




}