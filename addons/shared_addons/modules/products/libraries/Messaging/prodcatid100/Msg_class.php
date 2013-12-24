<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Message Class for product category ID 100
 *
 * Messages process
 *
 * @package			CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Msg
{
	public $cfg;
	public $prodcatid;
	public $viewid; 
	public $validationrules;
	public $frontitemparams;
	public $frontitem;
	public $data;
	public $dbdata;
	public $template;
	public $body;

	function __construct($params)
	{
		$this->prodcatid = $params['prodcatid'];
		$this->set_config_settings($params['cfgsettings']);
		$this->viewid = $params['viewid'];
		$this->validationrules = $this->set_validation_rules();
		$this->data = array();
		$this->dbdata = array();	
		$this->body = '';	
	}


    function set_config_settings($settings)
    {
		$this->cfg = array();
		$this->cfg['dbfields'] = $settings['msg_db_fields'][$this->prodcatid];  
		$this->cfg['emailparams'] = $settings['msg_email_params'];
		$this->cfg['emailtemplate'] = $settings['msg_template_name'][$this->prodcatid]; 
    }

	private function set_validation_rules()
	{
		switch ($this->viewid)
		{
			case '300': 	return array(
				                            array(
				                                'field' => 'name',
				                                'label' => 'lang:front:form-name',
				                                'rules' => 'trim|required'
				                            ), 
				                            array(
				                                'field' => 'email',
				                                'label' => 'lang:front:form-email',
				                                'rules' => 'trim|valid_email|required'
				                            ), 
				                            array(
				                                'field' => 'message',
				                                'label' => 'lang:front:form-message',
				                                'rules' => 'trim|required'
				                            ),                                                                 
				                    	);	
							break;

			default: 
							return array();
		}	
	}

	public function set_frontitem_params($post)
	{			
		$this->frontitemparams = array();	
		switch($this->viewid)
		{
			case '300':		
						$this->frontitemparams = array(
														'prod_cat_slug'=>$post['dataFprod_cat_slug'],
														'loc_city_slug'=>$post['dataFloc_city_slug'],
														'loc_slug'=>$post['dataFloc_slug'],
														'space_slug'=>$post['dataFspace_slug'],
														'reference'=>$post['reference'],
														'message'=>$post['message'],
														'email'=>$post['email'],
														'name'=>$post['name'],																																	
														);
						break;
		}
	}	

	public function set_frontitem($item)
	{
		if($item)
		{
			$this->frontitem = $item;
		}
	}


	public function process_message()
	{
		switch($this->viewid)
		{
			/* consulta en vista espacio */
			case '300':			
						$dataTOserialize = array(
												'prod_cat_slug'=>$this->frontitem->prod_cat_slug,
												'loc_city_slug'=>$this->frontitem->loc_city_slug,
												'loc_slug'=>$this->frontitem->loc_slug,
												'space_slug'=>$this->frontitem->space_slug,
												'loc_name'=>$this->frontitem->loc_name,
												'loc_id'=>$this->frontitem->loc_id,
												'space_id'=>$this->frontitem->space_id,
												'space_name'=>$this->frontitem->space_name,	
												'reference'=>$this->frontitemparams['reference'],						
											);		
						$this->data = array(
										'prod_cat_id'=>$this->frontitem->prod_cat_id,
										'prod_account_id'=>$this->frontitem->account_id,
										'prod_id'=>$this->frontitem->prod_id,
										'front_version'=>$this->frontitem->front_version,
										'view_id'=>$this->viewid,
										'data'=>serialize($dataTOserialize),
										'comments'=>$this->frontitemparams['message'],
										'account_agent_email'=>$this->get_product_agent_email($this->frontitem),
										'sender_email'=>$this->frontitemparams['email'],
										'sender_name'=>$this->frontitemparams['name'],
										'subject'=>'Nueva consulta para '.$this->frontitem->space_denomination.'-'.$this->frontitem->space_name.'@'.$this->frontitem->loc_slug.' de #'.$this->frontitemparams['name'],
									);	
						break;															
		}		
	}	

	private function get_product_agent_email($item)
	{
		if($item->seller_account_id > 0)
		{
			ci()->load->library('accounts');	
			$account = ci()->accounts->get_account($this->frontitem->seller_account_id);	
			return $account->email;				
		}
		else
			{
				return $item->loc_email;
			}
	}

	public function set_dbdata()
	{
		foreach($this->cfg['dbfields'] as $field)
		{
			$this->dbdata[$field] = isset($this->data[$field]) ? $this->data[$field] : null;
		}
	}


    public function set_msg_body($target)
    {
    	$params = array('templatename'=>$this->cfg['emailtemplate'][$this->viewid]);
    	ci()->load->library('emailtemplates', $params);   	
    	$this->template = ci()->emailtemplates->tpl;
        switch($target)
        {
            case 'location':
                            $html = '';
                            break;
            case 'sender':    
                            $html = '';
                            break;
        }      
    }	



}	