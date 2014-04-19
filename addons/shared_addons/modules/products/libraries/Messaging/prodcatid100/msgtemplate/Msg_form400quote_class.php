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
						'datetimeObj'=>json_decode($post['datetimeObj']),																																																																																														
						);
	}	

	public function set_message_custom_data($frontitem, $frontitemparams, $cfg)
	{
		$datetime_result_Arr = $this->set_data_datetimeArr($frontitemparams['datetimeObj']);
		$result = array(
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
						//fields
						'pax'=>$frontitemparams['pax'],
						'layouts_ids'=>$frontitemparams['layouts_ids'],
						'features_ids'=>$frontitemparams['features_ids'],
						'activity_use'=>$frontitemparams['activity_use'],
						'comments_features'=>$frontitemparams['comments_features'],							
						'comments_general'=>$frontitemparams['comments_general'],	
						'datetime_subtdays'=>$datetime_result_Arr['subtdays'],
						'datetime_subthours'=>$datetime_result_Arr['subthours'],
						'datetimeArr'=>$datetime_result_Arr['datetimeArr'],													
					);
		return $result;
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


	private function set_data_datetimeArr($Arr)
	{
		$result = array();
		$result['subtdays'] = 0;
		$result['subthours'] = 0;
		$result['datetimeArr'] = array();
		foreach ($Arr as $obj) 
		{
			$subtdayshours = $this->math_datetime_subtotal_days_hours($obj);
			$result['subtdays']+= $subtdayshours['subtdays'];
			$result['subthours']+= $subtdayshours['subthours'];
			$datetime = array();
			$datetime['datetype'] = $obj->datetype;
			$datetime['datestart'] = $obj->datestart;	
			$datetime['dateend'] = $obj->dateend;	
			$datetime['dateslist'] = $obj->dateslist;
			$datetime['timestart'] = $obj->timestart;				
			$datetime['timeend'] = $obj->timeend;
			$datetime['timerangehours'] = $obj->timerangehours;	
			$datetime['incsaturday'] = $obj->incsaturday;
			$datetime['incsunday'] = $obj->incsunday;		
			$datetime['repeats'] = $obj->repeats;									
			$result['datetimeArr'][] = $datetime;
		}
		return $result;
	}

	private function math_datetime_subtotal_days_hours($obj)
	{
		$dt = array();
		$dt['subtdays'] = $obj->subtdays;
		$dt['subthours'] = $obj->subtdays * $obj->subthours * $obj->repeats;
		return $dt;
	}
	
}