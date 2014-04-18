<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Message Template Class - form300query
 *
 * Defines message methods
 *
 * @package			CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Msgtpl
{

	public function run_custom_validation()
	{
		return $this->custom_check_integer(ci()->input->post('layoutsids'), 'Armado') 
			   && $this->custom_check_integer(ci()->input->post('featureids'), 'Equipamiento y servicios') 	
		       && $this->custom_check_datetime(ci()->input->post('datetimeObj'));
	}

	public function set_frontitem_params($post)
	{	
		return array(
						'prod_cat_slug'=>$post['dataFprod_cat_slug'],
						'loc_city_slug'=>$post['dataFloc_city_slug'],
						'loc_slug'=>$post['dataFloc_slug'],
						'space_slug'=>$post['dataFspace_slug'],
						'reference'=>$post['reference'],
						'email'=>$post['email'],
						'name'=>$post['name'],	
						'phone'=>$post['phone'],
						'pax'=>$post['pax'],
						'activity_use'=>$post['activity'],
						'comments_general'=>$post['comments_gral'],	
						'comments_features'=>$post['comments_ftr'],	
						'layouts_ids'=>$post['layoutsids'],
						'features_ids'=>$post['featureids'],
						'datetimeObj'=>json_decode($post['datetimeObj'])																																																																																														
						);
	}	

	public function set_message_custom_data($frontitem, $frontitemparams, $cfg)
	{
		return array(
						'prod_id'=>$frontitem->prod_id,							
						'prod_cat_id'=>$frontitem->prod_cat_id,
						'prod_account_id'=>$frontitem->account_id,
						'front_version'=>$frontitem->front_version,
						'space_slug'=>$frontitem->space_slug,
						'loc_slug'=>$frontitem->loc_slug,
						'loc_name'=>$frontitem->loc_name,
						'space_full_name'=>$frontitem->space_denomination.' '.$frontitem->space_name,
						'sender_email'=>$frontitemparams['email'],
						'sender_name'=>$frontitemparams['name'],
						'sender_phone'=>$frontitemparams['phone'],											
						'sender_name+email'=>$frontitemparams['name'].' <'.$frontitemparams['email'].'>',	
						'subject'=>$cfg['template']['msgreference'].' '.$frontitemparams['reference'],
						'amrfromaddress'=>$cfg['systemparams']['amrfromaddress'],
						'amremail'=>$cfg['systemparams']['amremail'],										
						'amrnoticeaddress'=>$cfg['systemparams']['amrnoticeaddress'],
						'amrnoticeemail'=>$cfg['systemparams']['amrnoticeemail'],
						'amrname'=>$cfg['systemparams']['amrname'],					
					);
	}
	

	////////////
	//  AUX   //
	////////////

	public function custom_check_integer($string = '', $field)
	{	
		$vec = array();
		if($string=='')
		{
			return true;
		}
		else
			{
				$vec = explode(',', $string);
				foreach ($vec as $value) 
				{
					if(ctype_digit($value)==false)
					{
						$this->validationcustommessages['chk_numeric_'.$field] = '"'.$field.'" contiene valores inválidos.';						
						return false;
					}
				}
				return true;
			}
	}


	public function custom_check_datetime($srlzObj)
	{
		$srlzObj = json_decode($srlzObj);
		if(is_array($srlzObj) && count($srlzObj)>0)
		{
			return true;
		}
		else
			{
				$this->validationcustommessages['chk_datetime'] = 'El formato de fechas/horarios es inválido.';	
				return false;	
			}	
	}
	
}