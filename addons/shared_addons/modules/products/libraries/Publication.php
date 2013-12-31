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

			default:					
						ci()->session->set_flashdata(array('error' => sprintf(lang('product_type_not_defined'), $params['typeid'])));
						redirect('admin/products/index/1');								    
		}				
		$this->prodset = new prodset($params);	
		$this->method = $params['method'];
	}

	
	public function index()
	{
		$this->prodset->set_postdata();		
		$this->prodset->set_tot_rows();
		$this->prodset->set_pagination();
		$this->prodset->set_result();  
		$this->prodset->set_index_view();        
	}

	public function create()
	{			
		$this->prodset->set_validation_rules();	
		if (ci()->form_validation->run())
		{
			$this->prodset->set_create_data_array();
			$this->prodset->run_save();
		}
		else
			{				
				$this->prodset->run_create_view();
			}
	}

	public function edit($id)
	{
		$this->prodset->set_edit_item($id);
		$this->prodset->set_validation_rules();
		if (ci()->form_validation->run())
		{
			$this->prodset->set_edit_data_array();
			$this->prodset->run_update($id);
		}
		else
			{
				$this->prodset->run_edit_view();
			}		
	}

	public function view($id)
	{
		$this->prodset->set_view_item($id);
		$this->prodset->run_item_view();		
	}

	public function delete($id)
	{
        if(ci()->products_m->soft_delete_product($id, ALQ_ESPACIOS_TYPEID))
		{
			ci()->session->set_flashdata(array('success' => sprintf(lang('products_delete_success'), $id)));			
		}	
		else
			{
				ci()->session->set_flashdata(array('error' => sprintf(lang('products_delete_error'), $id)));				
			}
		redirect('admin/products/index/'.ALQ_ESPACIOS_TYPEID);	
	}


	public function publishdraft($id)
	{
		$this->prodset->set_draft_item($id);
		$this->prodset->set_draft_template($id);
		$this->prodset->run_save_draft($id);
	}

	public function deletedraft($id)
	{
		$this->prodset->delete_draft($id);		
	}	



}