<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Message Class for product category ID 100
 *
 * Defines message parameters and content
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
	public $queuelist;
	private $dbcatmodel;

	function __construct($params)
	{
		$this->prodcatid = $params['prodcatid'];
		$this->viewid = $params['viewid'];
		$this->set_config_settings($params['cfgsettings']);		
		$this->init();
		$this->dbcatmodel = ci()->load->model('products_frontend_1_m');	
	   	$this->load_emailtemplate();	
	}

    private function set_config_settings($settings)
    {  	
		$this->cfg = array();
		$this->cfg['msgdbtable'] = $settings['msg_db_table_name'][$this->prodcatid];
		$this->cfg['dbfields'] = $settings['msg_db_fields'][$this->prodcatid];  
		$this->cfg['template'] = $settings['msg_template'][$this->prodcatid][$this->viewid];
		$this->cfg['systemparams'] = $settings['msg_system_params']; 
    }

	private function init()
	{
		$this->validationrules = $this->set_validation_rules();
		$this->data = array();
		$this->dbdata = array();
		$this->queuelist = array();	
	}

	private function load_emailtemplate()
	{
    	$params = array('templatename'=>$this->cfg['template']['templatename']);
    	ci()->load->library('emailtemplates', $params);   	
    	$this->template = ci()->emailtemplates->tpl;		
	}


	private function set_validation_rules()
	{
		switch ($this->viewid)
		{
			case '300query': 	
							return array(
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

	public function set_frontitem()
	{		
		$this->set_frontitem_params(ci()->input->post());
		$this->frontitem = $this->dbcatmodel->MSG_get_item_space($this->frontitemparams);
	}

	public function set_frontitem_params($post)
	{			
		$this->frontitemparams = array();	
		switch($this->viewid)
		{
			case '300query':		
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


	public function process_message()
	{
		switch($this->viewid)
		{
			/* consulta en vista espacio */
			case '300query':			
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
										'space_slug'=>$this->frontitem->space_slug,
										'loc_slug'=>$this->frontitem->loc_slug,
										'view_id'=>$this->viewid,
										'data'=>serialize($dataTOserialize),
										'comments'=>$this->frontitemparams['message'],
										'account_agent_email'=>$this->get_product_agent_email($this->frontitem),
										'sender_email'=>$this->frontitemparams['email'],
										'sender_name'=>$this->frontitemparams['name'],
										'sender_name+email'=>$this->frontitemparams['name'].' <'.$this->frontitemparams['email'].'>',
										'subject'=>$this->cfg['template']['msgreference'].' '.$this->frontitemparams['reference'],
										'amrfromaddress'=>$this->cfg['systemparams']['amrfromaddress'],
										'amremail'=>$this->cfg['systemparams']['amremail'],
										'amrname'=>$this->cfg['systemparams']['amrname'],																				
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

	private function set_dbdata()
	{
		foreach($this->cfg['dbfields'] as $field)
		{
			$this->dbdata[$field] = isset($this->data[$field]) ? $this->data[$field] : null;
		}
	}


	public function set_message_queuelist()
	{
		foreach($this->cfg['template']['queue'] as $queuedata)
		{
			$this->set_queue_data($queuedata);
		}   		 	
	}

	private function set_queue_data($queuedata)
	{
		$from = $queuedata['from'];
		$to = $queuedata['to'];
		$subject = $this->replace_subject_string_data_vars($queuedata['subject']);
		$html = $queuedata['html'];
		$queue = array(
						'name'=>$queuedata['queuename'],
						'from'=>$this->data[$from],
						'to'=>$this->data[$to],
						'subject'=>$subject,
						'html'=>$html,							
						);
		array_push($this->queuelist, $queue);
	}


	public function save_message_to_db()
	{
		$this->set_dbdata();		
		if(!empty($this->dbdata))
		{
			ci()->db->insert($this->cfg['msgdbtable'], $this->dbdata);
		}
	}


	////////////////////////////////////////
	// AUX ---------------------------// //
	////////////////////////////////////////

	private function replace_subject_string_data_vars($subjectArr)
	{
		foreach($subjectArr['vars'] as $var)
		{
			$subjectArr['string'] = str_replace('{'.$var.'}', $this->data[$var], $subjectArr['string']);
		}
		return $subjectArr['string'];
	}
	






}	