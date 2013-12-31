<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @package  	PyroCMS
 * @subpackage  Products
 * @category  	Module
 */
class Admin extends Admin_Controller
{
	/**
	 * The current active section
	 * @access protected
	 * @var string
	 */
	protected $section = 'products';

	/**
	 * The constructor
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->config->load('product_'.ENVIRONMENT);
		$this->load->library(array('product_type', 'dropzone', 'files/files', 'products'));
		$this->lang->load(array('products', 'categories', 'locations','features','spaces'));		
		define('ALQ_ESPACIOS_TYPEID', $this->config->item('alq_espacios_typeid'));
		define('SERVICIOS_TYPEID', $this->config->item('servicios_typeid'));                                
	}

	private function load_publication($typeid, $method)
	{	
		$params = array('typeid'=>$typeid, 'method'=>$method);
		$this->load->library('publication', $params);  
	}

	public function validate_typeid_and_id($typeid, $id)
	{
		if($typeid == 0 && $id == 0 || $typeid == 0)
		{
			$this->session->set_flashdata(array('error' => sprintf(lang('products_create_select_type_not_defined'), $id)));
			redirect('admin/products/index/1');
		}
		else if($id == 0)
			{
				$this->session->set_flashdata(array('error' => sprintf(lang('products_edit_not_defined'), $id)));
				redirect('admin/products/index/1');			
			}
		//check if typeid is 
		switch($typeid)
		{	
			//alquiler de espacios
			case ALQ_ESPACIOS_TYPEID:		
										break;
			//servicios
			// case SERVICIOS_TYPEID:
			// 							break;
			default:
					$this->session->set_flashdata(array('error' => sprintf(lang('products_create_select_type_not_defined'), $id)));
					redirect('admin/products/index/1');						
		}
	}
        
    /**
	 * Show all created products posts
	 * @access public
	 * @return void
	 */
	public function index($typeid = 0)
	{
		if($typeid==0)
		{
			redirect('admin/products/index/1');
		}
		$this->load_publication($typeid, __METHOD__);
		$this->publication->index();		
	}


	public function create_index()
	{  
		$type_array = $this->product_type->get();
		$this->template
			 ->title($this->module_details['name'])
			 ->set('type_array', $type_array)
			 ->build('admin/products/create_index');
	}


	/**
	 * [create description]
	 * @param  integer $typeID [product type ID]
	 * @return [type]          [description]
	 */
	public function create($typeid = 0)
	{
		//check if typeid is 
		switch($typeid)
		{
			case 0:
					$this->session->set_flashdata(array('error' => sprintf(lang('products_create_select_type'), $typeid)));
					redirect('admin/products/create_index');			
			//alquiler de espacios
			case ALQ_ESPACIOS_TYPEID:		
										break;
			//servicios
			// case SERVICIOS_TYPEID:
			// 							break;
			default:
					$this->session->set_flashdata(array('error' => sprintf(lang('products_create_select_type_not_defined'), $typeid)));
					redirect('admin/products/create_index');					
		}
		$this->load_publication($typeid, __METHOD__);
		$this->publication->create();		
	}

	/**
	 * Edit products
	 *
	 * @access public
	 * @param int $id the ID of the products post to edit
	 * @return void
	 */
	public function edit($typeid = 0, $id = 0)
	{
		$this->validate_typeid_and_id($typeid, $id);		
		$this->load_publication($typeid, __METHOD__);
		$this->publication->edit($id);				
	}


	public function view($typeid = 0, $id = 0)
	{	    
		$this->validate_typeid_and_id($typeid, $id);	
		$this->load_publication($typeid, __METHOD__);
		$this->publication->view($id);						
	}


	/**
	* Delete product - no se borra, se deja inactivo
	* @param type $id 
	*/
	public function delete($typeid = 0, $id = 0)
	{
		$this->validate_typeid_and_id($typeid, $id);		    
	    $consistency = $this->products->consistency_delete_product($id, $typeid);
	    if( $consistency === null )
	    {
			$this->load_publication($typeid, __METHOD__);
			$this->publication->delete($id);		    	
	    }
	    else
	        {
	            $this->session->set_flashdata('error', sprintf(lang('products:delete_consistency_error'), " '".$consistency->table."', total registros: ".$consistency->num_rows ));
	            redirect('admin/products/index/'.$typeid);           
	        }
	}  


	/**
	 * [publish product to Front Draft for publishing]
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function publishdraft($typeid = 0, $id = 0)
	{
		$this->validate_typeid_and_id($typeid, $id);
		$this->load_publication($typeid, __METHOD__);	
		$this->publication->publishdraft($id);		
	}


	/**
	* Delete - no se borra, se deja inactivo
	* @param type $id 
	*/
	public function deletedraft($typeid = 0, $id = 0)
	{
		$this->validate_typeid_and_id($typeid, $id);	
	    $consistency = $this->products->consistency_delete_front_draft($id, $typeid);    
	    if( $consistency === null)
	    {
			$this->load_publication($typeid, __METHOD__);	
			$this->publication->deletedraft($id);
	    }
	    else
	        {
	            $this->session->set_flashdata('error', sprintf(lang('draft:delete_consistency_error'), $prod_id ));
	            redirect('admin/products');           
	        }
	}


	/**
	 * Helper method to determine what to do with selected items from form post
	 * @access public
	 * @return void
	 */
	public function action()
	{
		switch ($this->input->post('btnAction'))
		{
			case 'publish':
				role_or_die('products', 'put_live');
				$this->publish();
				break;
			
			case 'delete':
				role_or_die('products', 'delete_live');
				$this->delete();
				break;
			
			default:
				redirect('admin/products');
				break;
		}
	}


// AUX _ VALIDATIONS :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

	public function _check_seller_account_option()
	{		
		if( $this->input->post('chk_seller_account')==1 )
		{
			if( $this->input->post('seller_account_id') == null )
			{
				$this->form_validation->set_message('_check_seller_account_option', sprintf(lang('products_seller_account_not_selected'), lang('products_seller_account_label')));
				return false;
			}
		}
		return true;
	}

	public function _check_features($features)
	{		
		$vecF = array();
		$vecF_result = array();
		$vecF = json_decode($features);
		if(is_array($vecF))
		{
			foreach($vecF as $vec)
			{
				if(!empty($vec))
				{
					array_push($vecF_result,$vec);
				}
			}
		}
		if(empty($vecF_result))
		{
			$this->form_validation->set_message('_check_features', sprintf(lang('products_features_submit_error'), lang('products_features_label')));
			return false;
		}
		else
			{
				return true;
			}	
	}

// :::::::: AJAX ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

     /**
	 * method to fetch filtered results for products list
	 * @access public
	 * @return void
	 */
	public function ajax_filter($typeid)
	{
		if($typeid==0)
		{
			echo json_encode('');
		}
		$this->load_publication($typeid, __METHOD__);
		$this->publication->index();
	}	

	public function filetemp_upload_ajax()
	{
		$tempfolderid = $this->dropzone->check_temp_folder();
		echo json_encode( Files::upload($tempfolderid) );
	}


	public function getFile_ajax($fileid)
	{
		if( $fileid!="")
		{
			echo json_encode( Files::get_file($fileid));
		}
		else
		{
			echo json_encode( array('status'=>'false', 'message'=>'No valid ID provided.') );
		}
	}


}
