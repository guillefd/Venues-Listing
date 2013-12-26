<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Email templates Class
 *
 * Defines email template: design and parameters
 * 
 *
 * @package			CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Emailtemplates
{
	private $tplbase = 'emailtpl.php';
	public $templatepath;
	public $tpl;

    function __construct($params)
    { 	
   		$this->templatepath = "addons/shared_addons/libraries/Emailtemplates/templates/".$params['templatename'].'/';	
		if(file_exists($this->templatepath.$this->tplbase))
		{	
			require_once $this->templatepath.$this->tplbase;  
			$this->set_template();  
		}
		else
			{									
				$this->tpl = null;
			}
    }	

    private function set_template()
    {  	
    	$params = array('path'=>$this->templatepath);
    	$this->tpl = new emailtpl($params);
    }




}