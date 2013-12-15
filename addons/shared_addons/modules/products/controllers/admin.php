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
	public $dd_array = array();

	/**
	 * The constructor
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->config->load('product_'.ENVIRONMENT);
		define('ALQ_ESPACIOS_TYPEID', $this->config->item('alq_espacios_typeid'));
		define('SERVICIOS_TYPEID', $this->config->item('servicios_typeid'));
		//
		$this->load->model(array('products_m'));
        $this->load->helper(array('date', 'products'));                
		$this->lang->load(array('products', 'categories', 'locations','features','spaces'));
		$this->load->library(array('form_validation','features_categories', 'usageunit', 'product_type','categories', 'shapes', 'files/files','dropzone','products','spaces_denominations', 'locations_type', 'spaces_usetype'));            
        $this->template->append_css('module::products.css')
                       ->prepend_metadata('<script>var IMG_PATH = "'.BASE_URL.SHARED_ADDONPATH.'modules/'.$this->module.'/img/"; </script>');                                     
		$this->dd_array = gen_dropdowns_array(); 
// DEBUG ::::::::::::::::::::::::::::::::::::::::::::::
        //$this->output->enable_profiler(TRUE);
// DEBUG ::::::::::::::::::::::::::::::::::::::::::::::        
	}
        
    /**
	 * Show all created products posts
	 * @access public
	 * @return void
	 */
	public function index()
	{
		// Create pagination links
		$tot_rows = $this->products_m->join_search('counts');
        //params (URL -for links-, Total records, records per page, segmnet number )	
        $post_data['pagination'] = create_pagination('admin/products/index', $tot_rows, 5, 4);  
        //query with limits              
		$products = $this->products_m->join_search('results', $post_data);               
        $products = populate_product_ids($products, $this->dd_array);
        $products = draft_status_view($products);   
		$this->template
			 ->title($this->module_details['name'])
			 ->append_js('module::products_filter.js')    			 
			 ->set('pagination', $post_data['pagination'])
			 ->set('products', $products)
			 ->set('total_rows', $tot_rows)			 
			 ->set('dd_array', $this->dd_array)
			 ->build('admin/products/index');
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
	public function create($typeID = 0)
	{
		//check if typeid is 
		switch($typeID)
		{
			case 0:
					$this->session->set_flashdata(array('error' => sprintf(lang('products_create_select_type'), $id)));
					redirect('admin/products/create_index');			
			//alquiler de espacios
			case ALQ_ESPACIOS_TYPEID:		
										break;
			//servicios
			// case SERVICIOS_TYPEID:
			// 							break;
			default:
					$this->session->set_flashdata(array('error' => sprintf(lang('products_create_select_type_not_defined'), $id)));
					redirect('admin/products/create_index');					
		}
		// go on ..
		$this->form_validation->set_rules( validation_rules($typeID) );
		if ($this->form_validation->run())
		{			
			$imgIDs = gen_imageIDs_array($this->input->post('dzfileslistid'));
			switch ($typeID) 
			{
				case ALQ_ESPACIOS_TYPEID:	
											$auxdata = array(
														'location_id'       => convert_empty_value_to_zero($this->input->post('location_id')),
														'space_id'          => convert_empty_value_to_zero($this->input->post('space_id')),
														'space_usetype_id'  => $this->input->post('space_usetype_id')
														);
											break;
				
				default:					
											$auxdata = array();
											break;
			}
			/* DATA ARRAY */
			$default_data = array(
									'category_id'       => $this->input->post('category_id'),
									'account_id'        => $this->input->post('account_id'),
									'outsourced'        => convert_empty_value_to_zero($this->input->post('chk_seller_account')),
									'seller_account_id' => convert_empty_value_to_zero($this->input->post('seller_account_id')),
									'name'				=> $this->input->post('name'),
									'slug'				=> slugify_string($this->input->post('name')),
									'intro'				=> $this->input->post('intro'),
									'body'				=> $this->input->post('body'),
									'images'			=> $this->input->post('dzfileslistid'),
									'created_on'        => now(),
									'updated_on'        => now(),									
									'author_id'			=> $this->current_user->id
								);
			$data_array = array_merge($default_data, $auxdata);
			// BEGIN TRANSACTION :::::::::::::::::::::::::::::::::::::
			$this->db->trans_start();
			$product_id = $this->products_m->insert($data_array);		
			if($product_id)
			{
				$features_field_list = array('product_id','default_feature_id','description','value','is_optional');
				$features_array = array();
				$features_array = generate_features_array_from_json( $features_field_list, $this->input->post('features'), $product_id );
				if( ($prod_folder_id = check_folder($product_id)) && (count($imgIDs)>0) )
				{
					//imagenes
					move_tempfiles_to_prod_folder($prod_folder_id, $imgIDs);	
				}
			}
			if ($this->products_m->insert_features($features_array))
			{
				$this->session->set_flashdata('success', sprintf($this->lang->line('products_post_add_success'), $this->input->post('title')));
			}
			else
			{
				$this->session->set_flashdata('error', $this->lang->line('products_post_add_error'));
			}
			$this->db->trans_complete();
			// END TRANSACTION :::::::::::::::::::::::::::::::::::::			
			// Redirect back to the form or main page
			$this->input->post('btnAction') == 'save_exit' ? redirect('admin/products') : redirect('admin/products/edit/' . $id);
		}
		else
			{		
				// Go through all the known fields and get the post values
				foreach (validation_rules($typeID) as $field)
				{
					$product->$field['field'] = $this->input->post($field['field']);
				}
			}      
		//clean features_json
		$product->features_json = str_replace('&quot;', '"', $product->features);	         
		// get category list for current typed
		$dd_categories = get_categories_by_typeid($typeID);
		$this->template
			->title($this->module_details['name'], lang('products_create_title'))     	
			->append_css('module::jquery/jquery.tagsinput.css')
			->append_js('module::jquery/jquery.tagsinput.js')
            ->append_js('module::jquery/jquery.mask.min.js')                        
			->append_js('module::products_form.js')
            ->append_js('module::products_form_features.js')
        	->set('product_type_name', $this->dd_array->type_array[$typeID])
			->set('product', $product)
			->set('dzForm', $this->dropzone->dzFormMarkup('admin/products/filetempupload_ajax'))
			->set('dd_array', $this->dd_array)
			->set('dd_categories', $dd_categories);
       	//load path for Dropzones assets
	    $this->dropzone->loadAssetPath();						
		$this->template->build('admin/products/form__'.$typeID);
	}

	/**
	 * Edit products
	 *
	 * @access public
	 * @param int $id the ID of the products post to edit
	 * @return void
	 */
	public function edit($typeID = 0, $id = 0)
	{
		$id OR redirect('admin/products');
		//check if typeid is 
		switch($typeID)
		{
			case 0:
					redirect('admin/products');			
			//alquiler de espacios
			case ALQ_ESPACIOS_TYPEID:		
										break;
			//servicios
			// case SERVICIOS_TYPEID:
			// 							break;
			default:
					$this->session->set_flashdata(array('error' => sprintf(lang('products_create_select_type_not_defined'), $id)));
					redirect('admin/products/create_index');					
		}		
		//first entry, populate human readable values	
		if($this->input->post() == null)
		{		
		    $product = $this->products_m->get($id);
		    if($product)
		    {	    	
		    	$product->chk_seller_account = $product->outsourced;
				$product = populate_product_ids($product, $this->dd_array);				
				$product->features = $this->products_m->get_all_features_by_id($id);	
				$product->features = populate_features_array($product->features);
		        $product->features_json = generate_features_json_from_array($product->features);
		        $product->dzfileslistid = $product->images;					
			}
			else
				{
					$this->session->set_flashdata(array('error' => sprintf(lang('products_edit_error_noexist'), $id)));
					redirect('admin/products');
				}	
		}
		else
			{
				//save feature json
				$product->features_json = $this->input->post('features');
				// if post exist, save edited, else save database value.
				$product->dzfileslistid = $this->input->post('dzfileslistid'); 				
			}
		$this->form_validation->set_rules(validation_rules($typeID));	
		if ($this->form_validation->run())
		{
			$imgIDs = gen_imageIDs_array($this->input->post('dzfileslistid'));	
			switch ($typeID) 
			{
				case ALQ_ESPACIOS_TYPEID:	
											$auxdata = array(
														'location_id'       => convert_empty_value_to_zero($this->input->post('location_id')),
														'space_id'          => convert_empty_value_to_zero($this->input->post('space_id')),
														'space_usetype_id'  => $this->input->post('space_usetype_id')
														);
											break;
				
				default:					
											$auxdata = array();
											break;
			}
			/* DATA ARRAY */
			$default_data = array(
									'category_id'       => $this->input->post('category_id'),
									'account_id'        => $this->input->post('account_id'),
									'outsourced'        => convert_empty_value_to_zero($this->input->post('chk_seller_account')),
									'seller_account_id' => convert_empty_value_to_zero($this->input->post('seller_account_id')),
									'name'				=> $this->input->post('name'),
									'slug'				=> slugify_string($this->input->post('name')),
									'intro'				=> $this->input->post('intro'),
									'body'				=> $this->input->post('body'),
									'images'			=> $this->input->post('dzfileslistid'),
									'updated_on'        => now(),									
								);
			$data_array = array_merge($default_data, $auxdata);					
			// BEGIN TRANSACTION :::::::::::::::::::::::::::::::::::::
			$this->db->trans_start();
			$result = $this->products_m->update_product($id, $data_array);			
			if ($result && $this->products_m->delete_products_features($id) )
			{
				//features
				$features_field_list = array('product_id','default_feature_id','description','value','is_optional');
				$features = array();
				$features = generate_features_array_from_json( $features_field_list, $this->input->post('features'), $id );
				//imagenes
				if( ($prod_folder_id = check_folder($id)) && (count($imgIDs)>0) )
				{
					//imagenes
					move_tempfiles_to_prod_folder($prod_folder_id, $imgIDs);
				}				
				if ($this->products_m->insert_features($features))
				{
					$this->session->set_flashdata(array('success' => sprintf(lang('products_edit_success'), $this->input->post('name'))));
				}
				else
				{
					$this->session->set_flashdata('error', $this->lang->line('products_edit_error'));
				}		
			}			
			else
			{
				$this->session->set_flashdata('error', $this->lang->line('products_edit_error'));
			}
			$this->db->trans_complete();
			// END TRANSACTION :::::::::::::::::::::::::::::::::::::			
			// Redirect back to the form or main page
			redirect('admin/products');
		}		
	    // Loop through each rule
	    foreach (validation_rules($typeID) as $rule)
	    {
	        if ($this->input->post($rule['field']) !== null)
	        {
	            $product->{$rule['field']} = $this->input->post($rule['field']);
	        }
	    } 		
		// get category list for current typed
		$dd_categories = get_categories_by_typeid($typeID);
		$this->template
			->title($this->module_details['name'], lang('products_create_title'))     	
			->append_css('module::jquery/jquery.tagsinput.css')
			->append_js('module::jquery/jquery.tagsinput.js')
            ->append_js('module::jquery/jquery.mask.min.js')                        
			->append_js('module::products_form.js')
            ->append_js('module::products_form_features.js')
			->set('product', $product)
			->set('dzForm', $this->dropzone->dzFormMarkup('admin/products/filetempupload_ajax'))
			->set('dd_array', $this->dd_array)
			->set('dd_categories', $dd_categories);			
       	//load path for Dropzones assets
	    $this->dropzone->loadAssetPath();		   				
		$this->template->build('admin/products/form__'.$typeID);				
	}


	public function view($id = 0)
	{	    
	    if( $product = $this->products_m->get($id) )
	    {
			$product = populate_product_ids($product, $this->dd_array);
			$product->features_array = $this->products_m->get_all_features_by_id($id);
			$product->features_array = populate_features_array($product->features_array);		
		}
		else
			{
				$this->session->set_flashdata(array('error' => sprintf(lang('products_error_noexist'), $id)));
				redirect('admin/products');
			}
		$this->template
			->title($this->module_details['name'], lang('products_create_title'))   
            ->set_layout('modal','admin')			                                  
			->set('product', $product)
			->set('features', $features_array)
			->set('dd_yes_no', gen_dd_yes_no())
			->build('admin/products/view');				
	}


	/**
	* Delete product - no se borra, se deja inactivo
	* @param type $id 
	*/
	public function delete($id = 0)
	{
		/* get product_type */
		$category_id = $this->products->get_product_field_by_id($id, 'category_id');
		$typeID = get_product_typeid($category_id);	
		if($typeID === null)
		{
			$this->session->set_flashdata(array('error' => sprintf(lang('products_delete_error_prod-typeid-error'), $id)));
			redirect('admin/products');			
		}	    
	    $consistency = $this->products->consistency_delete_product($id, $typeID);
	    if( $consistency === null)
	    {
	        if($this->products_m->soft_delete_product($id, $typeID))
			{
				$this->session->set_flashdata(array('success' => sprintf(lang('products_delete_success'), $id)));			
			}	
			else
				{
					$this->session->set_flashdata(array('error' => sprintf(lang('products_delete_error'), $id)));				
				}
			redirect('admin/products');	
	    }
	    else
	        {
	            $this->session->set_flashdata('error', sprintf(lang('products:delete_consistency_error'), " '".$consistency->table."', total registros: ".$consistency->num_rows ));
	            redirect('admin/products');           
	        }
	}  


	/**
	 * [publish product to Front Draft for publishing]
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function publishdraft($prod_id = 0)
	{
	    if( $product = $this->products_m->get($prod_id) )
	    {
			$product = populate_product_ids($product);
			$product->features = $this->products_m->get_all_features_by_id($prod_id);
			$product->features = populate_features_array($product->features);
		}
		else
			{
				$this->session->set_flashdata(array('error' => sprintf(lang('products_publish_draft_error_noexist'), $prod_id)));
				redirect('admin/products');
			}
		/* get product_type */
		$typeID = get_product_typeid($product->category_id);
		if($typeID === null)
		{
			$this->session->set_flashdata(array('error' => sprintf(lang('products_publish_draft_error_cat_noexist'), $prod_id)));
			redirect('admin/products');			
		}	
		/* Generate product draft template*/
		$product = load_product_draft_template($typeID, $product);
		if($product === null)
		{
			$this->session->set_flashdata(array('error' => sprintf(lang('products_publish_draft_error_template_gen'), $prod_id)));
			redirect('admin/products');									
		}
		/* Insert draft in db*/
		$this->load->model(array('products_front_m'));		
		if($this->products_front_m->insert_product_front_draft($product, $typeID))
		{
			$this->session->set_flashdata(array('success' => sprintf(lang('products_publish_draft_success'), $prod_id)));
			redirect('admin/products');		
		}
		else
			{
				$this->session->set_flashdata(array('error' => sprintf(lang('products_publish_draft_error_template_gen'), $prod_id)));
				redirect('admin/products');							
			}	
		}



	/**
	* Delete - no se borra, se deja inactivo
	* @param type $id 
	*/
	public function deletedraft($prod_id = 0)
	{
		/* get product_type */
		$category_id = $this->products->get_product_field_by_id($prod_id, 'category_id');
		$typeID = get_product_typeid($category_id);	
		if($typeID === null)
		{
			$this->session->set_flashdata(array('error' => sprintf(lang('products_delete_error_prod-typeid-error'), $prod_id)));
			redirect('admin/products');			
		}		
	    $consistency = $this->products->consistency_delete_front_draft($prod_id, $typeID);    
	    if( $consistency === null)
	    {
			$this->load->model(array('products_front_m'));	    	
	        if($this->products_front_m->soft_delete_front_draft($prod_id, $typeID))
			{
				$this->session->set_flashdata(array('success' => sprintf(lang('draft_delete_success'), $prod_id)));			
			}	
			else
				{
					$this->session->set_flashdata(array('error' => sprintf(lang('draft_delete_error'), $prod_id)));				
				}
			redirect('admin/products');	
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
	public function ajax_filter()
	{
                //captura post
                $post_data = array();  
                if($this->input->post('f_keywords'))
                {    
                    $post_data['keywords'] = $this->input->post('f_keywords');
                }                 
                if($this->input->post('f_account_id'))
                {
                    $post_data['account_id'] = $this->input->post('f_account_id');                  
                }                                
                if($this->input->post('f_category_id') && $this->input->post('f_category_id')!='')
                {
                    $post_data['category_id'] = $this->input->post('f_category_id');                  
                }                                   
                if( ($this->input->post('f_status') || $this->input->post('f_status')==0) && $this->input->post('f_status')!='')
                {
                    $post_data['active'] = $this->input->post('f_status');              
                }                
                if( ($this->input->post('f_deleted') || $this->input->post('f_deleted')==0) && $this->input->post('f_deleted')!='')
                {
                    $post_data['deleted'] = $this->input->post('f_deleted');              
                }                

				$tot_rows = $this->products_m->join_search('counts', $post_data);
		        //params (URL -for links-, Total records, records per page, segmnet number )	
		        $post_data['pagination'] = create_pagination('admin/products/index', $tot_rows, 20, 4);  
		        //query with limits              
				$products = $this->products_m->join_search('results', $post_data);               
		        $products = populate_product_ids($products, $this->dd_array);
		        $products = draft_status_view($products); 
				//set the layout to false and load the view
                $this->input->is_ajax_request() ? $this->template->set_layout(FALSE) : '';                 
				$this->template
								->title($this->module_details['name'])
								->set('products', $products)
								->set('pagination', $post_data['pagination']) 
							 	->set('total_rows', $tot_rows)	                   
		                        ->append_js('module::locations_index.js')
		                        ->append_css('module::jquery/jquery.autocomplete.css')
		                        ->build('admin/products/tables/products', $this->data);
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
