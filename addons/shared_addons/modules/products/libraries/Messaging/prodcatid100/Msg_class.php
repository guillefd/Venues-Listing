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
	public $dbinsertedID;
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
		$this->cfg['msgformdb'] = $settings['msg_db_form_messages'][$this->prodcatid];
		$this->cfg['dbfields'] = $settings['msg_db_fields'][$this->prodcatid];  
		$this->cfg['template'] = $settings['msg_template'][$this->prodcatid][$this->viewid];
		$this->cfg['systemparams'] = $settings['msg_system_params'];
		$this->cfg['mailgun_api'] = $settings['msg_mailgun_api'];
		$this->cfg['mailgun_domain'] = $settings['msg_mailgun_domain'];
		$this->cfg['msgqueuedb'] = $settings['msg_db_api_queue'];
		$this->cfg['msgqueuedb_fields'] = $settings['msg_db_api_queue_fields'];				 
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
			case 'form300query': 	
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

			case 'form400quote': 	
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
			                                'field' => 'telefono',
			                                'label' => 'lang:front:phone',
			                                'rules' => 'trim'
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
			case 'form300query':		
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

			case 'form400quote':		
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
			case 'form300query':			
			case 'form400quote':
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
										'loc_name'=>$this->frontitem->loc_name,
										'space_full_name'=>$this->frontitem->space_denomination.' '.$this->frontitem->space_name,
										'block_bodymsg'=>'form fields info',
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
										'amrnoticeaddress'=>$this->cfg['systemparams']['amrnoticeaddress'],
										'amrnoticeemail'=>$this->cfg['systemparams']['amrnoticeemail'],
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
		$from = $this->replace_string_data_vars($queuedata['from']);
		$to = $this->replace_string_data_vars($queuedata['to']);
		$subject = $this->replace_string_data_vars($queuedata['subject']);
		$html = $this->set_email_html($queuedata['html']);
		$queue = array(
						'type'=>$queuedata['type'],	
						'name'=>$queuedata['queuename'],
						'from'=>$from,
						'to'=>$to,
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
			ci()->db->insert($this->cfg['msgformdb'], $this->dbdata);
			$this->dbinsertedID = ci()->db->insert_id();
		}
	}


	private function set_email_html($queuehtmlArr)
	{
		$html = '';
		foreach($queuehtmlArr as $section=>$reg)
		{
			$section = $this->template->html[$section];
			foreach($reg as $htmlvarslug=>$string)
			{		
				//replace strings varslug with data values
				$string = $this->replace_string_data_vars($string);
				//replace html varslugs with string
				$section = str_replace('{'.$htmlvarslug.'}', $string, $section);				
			}			
			$html.=$section;
		}	
		return $html;
	}


	////////////////////////////////////////
	// AUX ---------------------------// //
	////////////////////////////////////////


	private function replace_string_data_vars($string)
	{
		$varsArr = $this->get_varslug_array($string);
		foreach($varsArr as $var)
		{
			$string = str_replace('{'.$var.'}', $this->data[$var], $string);
		}
		return $string;
	}
	
	/**
	 * [get_varslug_array find this format -> {var_slug}]
	 * @param  string $string [description]
	 * @return [type]         [description]
	 */
	private function get_varslug_array($string = '')
	{	
		$strArr = str_split($string);
		$varsArr = array();
		$found = false;
		$varslug = '';
		for($i=0; $i<count($strArr); $i++)
		{
			if(!$found)
			{
				if($strArr[$i]=='{')
				{
					$found = true;
				}
			}
			else
				{
					if($strArr[$i]=='}')
					{
						array_push($varsArr, $varslug);
						$varslug = '';
						$found = false;
					}
					else
						{
							$varslug.= $strArr[$i];							
						}
				}
		}
		return $varsArr;
	}






}	