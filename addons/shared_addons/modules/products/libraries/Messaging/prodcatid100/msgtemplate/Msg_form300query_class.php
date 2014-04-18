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
		return true;
	}

	public function set_frontitem_params($post)
	{
		return array(
						'prod_cat_slug'=>$post['dataFprod_cat_slug'],
						'loc_city_slug'=>$post['dataFloc_city_slug'],
						'loc_slug'=>$post['dataFloc_slug'],
						'space_slug'=>$post['dataFspace_slug'],
						'reference'=>$post['reference'],
						'message'=>$post['message'],
						'email'=>$post['email'],
						'name'=>$post['name'],								
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
						'message'=>$frontitemparams['message'],						
						'sender_name+email'=>$frontitemparams['name'].' <'.$frontitemparams['email'].'>',	
						'subject'=>$cfg['template']['msgreference'].' '.$frontitemparams['reference'],
						'amrfromaddress'=>$cfg['systemparams']['amrfromaddress'],
						'amremail'=>$cfg['systemparams']['amremail'],										
						'amrnoticeaddress'=>$cfg['systemparams']['amrnoticeaddress'],
						'amrnoticeemail'=>$cfg['systemparams']['amrnoticeemail'],
						'amrname'=>$cfg['systemparams']['amrname'],							
					);
	}

	
}