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
	public $msgtpl;
	public $cfg;
	public $prodcatid;
	public $viewid; 
	public $validationrules;
	public $validationcustommessages = array();
	public $frontitemparams;
	public $frontitem;
	public $data;
	public $dbdata;
	public $dbdatacustom = array();
	public $dbinsertedID;
	public $template;
	public $queuelist;
	private $dbcatmodel;

	function __construct($params)
	{
		// new message template
		$this->msgtpl = new msgtpl();
		$this->prodcatid = $params['prodcatid'];
		$this->viewid = $params['viewid'];
		$this->set_config_settings($params['cfgsettings'], $params['cfgsettings_msg']);		
		$this->init();
		$this->dbcatmodel = ci()->load->model('products_frontend_1_m');	
	   	$this->load_emailtemplate();	
	}

    private function set_config_settings($settings, $settings_msg)
    {  	
		$this->cfg = array();
		/*gral settings*/
		$this->cfg['systemparams'] = $settings['msg_system_params'];		
		//table name - general form fields
		$this->cfg['msgtbl'] = $settings['msg_tbl_name'][$this->prodcatid];
		$this->cfg['msgtblfields'] = $settings['msg_tbl_fields'][$this->prodcatid];  
		//table name - custom form fields
		$this->cfg['msgtblcustom'] = $settings_msg['msg_tbl_name_customform'];
		$this->cfg['msgtblcustomfields'] = $settings_msg['msg_tbl_name_customform_fields'];  				
		//form settings
		$this->cfg['validation_rules'] = $settings_msg['msgform_validation_rules'];
		$this->cfg['template'] = $settings_msg['msgform_template'];		
		//API service settings
		$this->cfg['mailgun_api'] = $settings['msg_mailgun_api'];
		$this->cfg['mailgun_domain'] = $settings['msg_mailgun_domain'];
		$this->cfg['msgqueuedb'] = $settings['msg_db_api_queue'];
		$this->cfg['msgqueuedb_fields'] = $settings['msg_db_api_queue_fields'];				 
    }

///////////////////////////////////////////////////////////////////
// INIT -------------------------------------------------------- //
///////////////////////////////////////////////////////////////////

	private function init()
	{
		$this->validationrules = $this->set_validation_rules();
		$this->data = array();
		$this->dbdata = array();
		$this->queuelist = array();	
	}

	private function init_custom_fields_obj()
	{
		$custom = new stdClass();
		$custom->index = '';
		$custom->tablename = '';
		$custom->tablefields = array();
		return $custom;
	}

	private function load_emailtemplate()
	{
    	$params = array('templatename'=>$this->cfg['template']['templatename']);
    	ci()->load->library('emailtemplates', $params);   	
    	$this->template = ci()->emailtemplates->tpl;		
	}

	private function set_validation_rules()
	{
		return $this->cfg['validation_rules'];
	}


///////////////////////////////////////////////////////////////////
// public methods ---------------------------------------------- //
///////////////////////////////////////////////////////////////////


	public function run_custom_validation()
	{
		return $this->msgtpl->run_custom_validation();
	}

	public function set_frontitem()
	{		
		$this->frontitemparams = $this->msgtpl->set_frontitem_params(ci()->input->post());
		$this->frontitem = $this->dbcatmodel->MSG_get_item_space($this->frontitemparams);
	}

	public function set_message_data()
	{
		$customdata = $this->msgtpl->set_message_custom_data($this->frontitem, $this->frontitemparams, $this->cfg);
		$this->data = array(
						'block_bodymsg'=>'form fields info',
						'form_view_id'=>$this->viewid,
						'account_agent_email'=>$this->get_product_agent_email($this->frontitem),																				
					);	
		$this->data = array_merge($customdata, $this->data);	
	}	

	public function set_message_queuelist()
	{
		foreach($this->cfg['template']['queue'] as $queuedata)
		{
			$this->set_queue_data($queuedata);
		}   		 	
	}

	public function save_message_to_db()
	{
		$this->set_dbdata();
		$this->set_dbdatacustom();
print_r($this->data);
print_r($this->dbdata);
print_r($this->dbdatacustom);
print_r($this->frontitemparams);
die;					
		if(!empty($this->dbdata))
		{
			$this->save_dbdata_and_get_insertid();	
			if($this->dbinsertedID > 0)
			{
				$this->save_dbdatacustom();
				return true;
			}
		}
		else
			{
				return false;
			}
	}


////////////////////////////////////////////////////////////////
// private methods ------------------------------------------ //
////////////////////////////////////////////////////////////////

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

	private function set_dbdata()
	{
		foreach($this->cfg['msgtblfields'] as $field)
		{
			$this->dbdata[$field] = isset($this->data[$field]) ? $this->data[$field] : null;
		}
	}	

	private function set_dbdatacustom()
	{
		foreach($this->cfg['msgtblcustom'] as $tableindex=>$tablename)
		{
			$custom = $this->init_custom_fields_obj();
			$custom->index = $tableindex;
			$custom->tablename = $tablename;
			//load data to dbdata
			$msgtblcustomfields = $this->cfg['msgtblcustomfields'][$tableindex];
			if($msgtblcustomfields['is_array'] === 0) //unique record
			{
				foreach($msgtblcustomfields['fields'] as $field)
				{
					$custom->tablefields[$field] = isset($this->data[$field]) ? $this->data[$field] : null;
				}
				$this->dbdatacustom[] = $custom;
			}
			else
				{
					//more than one record
					

				}
		}
	}	


//////////////////////////////////////////////////////////////
// Helpers ------------------------------------------------ //
//////////////////////////////////////////////////////////////

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


	private function save_dbdata_and_get_insertid()
	{
		ci()->db->insert($this->cfg['msgtbl'], $this->dbdata);
		$this->dbinsertedID = ci()->db->insert_id();			
	}


	private function save_dbdatacustom()
	{	
		foreach($this->dbdatacustom as $dbobj)
		{
			if(array_key_exists('fm_id', $dbobj->tablefields))
			{
				$dbobj->tablefields['fm_id'] = $this->dbinsertedID;
			}
			ci()->db->insert($dbobj->tablename, $dbobj->tablefields);
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


}	