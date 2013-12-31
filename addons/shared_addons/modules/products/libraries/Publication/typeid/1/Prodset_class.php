<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Prodset Class
 *
 *
 * @package			CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Prodset
{
	public $dd_array;
	public $tot_rows;
	public $post_data;
	public $result;
	public $data_array;
	public $item;
	public $draft;
	public $validation_rules;

    function __construct()
    {
    	$this->init();       	   	   	
    }

    function init()
    {
		ci()->load->model(array('products_m'));
        ci()->load->helper(array('date', 'products'));                
		ci()->load->library(array('form_validation','features_categories', 'usageunit','categories', 'shapes','spaces_denominations', 'locations_type', 'spaces_usetype'));  
        ci()->template->append_css('module::products.css')
                       ->prepend_metadata('<script>var IMG_PATH = "'.BASE_URL.SHARED_ADDONPATH.'modules/'.ci()->module.'/img/"; </script>'); 
        $this->set_dd_array();
        define('TYPEID', ALQ_ESPACIOS_TYPEID);
    }

    function set_validation_rules()
    {
    	$this->set_validation_rules_values();
		ci()->form_validation->set_rules( $this->validation_rules );    	
    }

    /////////////////////////////////////////
    // INDEX --------------------------- / //
    /////////////////////////////////////////

    function set_postdata()
    {
        $this->post_data = array();  
        if(ci()->input->post('f_keywords'))
        {    
            $this->post_data['keywords'] = ci()->input->post('f_keywords');
        }                 
        if(ci()->input->post('f_account_id'))
        {
            $this->post_data['account_id'] = ci()->input->post('f_account_id');                  
        }                                
        if(ci()->input->post('f_category_id') && ci()->input->post('f_category_id')!='')
        {
            $this->post_data['category_id'] = ci()->input->post('f_category_id');                  
        }                                   
        if( (ci()->input->post('f_status') || ci()->input->post('f_status')==0) && ci()->input->post('f_status')!='')
        {
            $this->post_data['active'] = ci()->input->post('f_status');              
        }                
        if( (ci()->input->post('f_deleted') || ci()->input->post('f_deleted')==0) && ci()->input->post('f_deleted')!='')
        {
            $this->post_data['deleted'] = ci()->input->post('f_deleted');              
        }     	    	
    }

    function set_tot_rows()
    {
    	$this->tot_rows = ci()->products_m->join_search('counts', $this->post_data);
    }    

    function set_pagination()
    {
        $this->post_data['pagination'] = create_pagination('admin/products/index/'.TYPEID, $this->tot_rows, 20, 5);     	
    }

    function set_result()
    {
		$this->result = ci()->products_m->join_search('results', $this->post_data);
        $this->populate_product_ids();
        $this->set_draft_status_view(); 		    	
    }

    function set_index_view()
    {    	     
        if( ci()->input->is_ajax_request() )	
        {
        	ci()->template->set_layout(FALSE)
							 ->title($this->module_details['name'])
							 ->set('pagination', $this->post_data['pagination'])
							 ->set('products', $this->result)
							 ->set('total_rows', $this->tot_rows)                  
				             ->build('admin/products/tables/products__'.TYPEID); 
        }
        else
	        {
				ci()->template
					 ->title($this->module_details['name'])
					 ->append_js('module::products_filter.js')    			 
					 ->set('pagination', $this->post_data['pagination'])
					 ->set('products', $this->result)
					 ->set('total_rows', $this->tot_rows)			 
					 ->set('dd_array', $this->dd_array)
					 ->build('admin/products/index/index__'.TYPEID);   
	        }			  	
    }


    //////////////////////////////////////
    // CREATE ------------------------/ //
    //////////////////////////////////////

    function set_create_data_array()
    {
		$imgIDs = gen_imageIDs_array(ci()->input->post('dzfileslistid'));
		/* DATA ARRAY */
		$this->data_array = array(
							'location_id'       => convert_empty_value_to_zero(ci()->input->post('location_id')),
							'space_id'          => convert_empty_value_to_zero(ci()->input->post('space_id')),
							'space_usetype_id'  => ci()->input->post('space_usetype_id'),			
							'category_id'       => ci()->input->post('category_id'),
							'account_id'        => ci()->input->post('account_id'),
							'outsourced'        => convert_empty_value_to_zero(ci()->input->post('chk_seller_account')),
							'seller_account_id' => convert_empty_value_to_zero(ci()->input->post('seller_account_id')),
							'name'				=> ci()->input->post('name'),
							'slug'				=> slugify_string(ci()->input->post('name')),
							'intro'				=> ci()->input->post('intro'),
							'body'				=> ci()->input->post('body'),
							'images'			=> ci()->input->post('dzfileslistid'),
							'created_on'        => now(),								
							'author_id'			=> ci()->current_user->id,
							'updated_on'        => now(),								
							);    	
    }

    function run_save()
    {
		// BEGIN TRANSACTION :::::::::::::::::::::::::::::::::::::
		ci()->db->trans_start();
		$product_id = ci()->products_m->insert($this->data_array);		
		if($product_id)
		{
			$features_field_list = array('product_id','default_feature_id','description','value','is_optional');
			$features_array = array();
			$features_array = $this->generate_features_array_from_json( $features_field_list, ci()->input->post('features'), $product_id );
			if( ($prod_folder_id = check_folder($product_id)) && (count($imgIDs)>0) )
			{
				//imagenes
				move_tempfiles_to_prod_folder($prod_folder_id, $imgIDs);	
			}
		}
		if (ci()->products_m->insert_features($features_array))
		{
			ci()->session->set_flashdata(
				'success', sprintf(ci()->lang->line('products_post_add_success'),ci()->input->post('title'))
										 );
		}
		else
			{
				ci()->session->set_flashdata('error', ci()->lang->line('products_post_add_error'));
			}
		ci()->db->trans_complete();
		// END TRANSACTION :::::::::::::::::::::::::::::::::::::			
		$this->redirect_index();	
    }

    function run_create_view()
    {    	
		// Go through all the known fields and get the post values
		foreach ($this->validation_rules as $field)
		{
			$product->$field['field'] = ci()->input->post($field['field']);
		}
		//clean features_json
		$product->features_json = str_replace('&quot;', '"', $product->features);	         	
		ci()->template
			->title(ci()->module_details['name'], lang('products_create_title'))     	
			->append_css('module::jquery/jquery.tagsinput.css')
			->append_js('module::jquery/jquery.tagsinput.js')
            ->append_js('module::jquery/jquery.mask.min.js')                        
			->append_js('module::products_form.js')
            ->append_js('module::products_form_features.js')
        	->set('product_type_name', $this->dd_array->type_array[TYPEID])
			->set('product', $product)
			->set('dzForm', ci()->dropzone->dzFormMarkup('admin/products/filetempupload_ajax'))
			->set('dd_array', $this->dd_array)
			->set('dd_categories', get_categories_by_typeid(TYPEID));
       	//load path for Dropzones assets
	    ci()->dropzone->loadAssetPath();						
		ci()->template->build('admin/products/form/form__1');		
    }

	function generate_features_json_from_array($features)
	{
		return json_encode($features);
	}    


    ////////////////////////////////////
    // EDIT ---------------------- // //
    ////////////////////////////////////

    function set_edit_item($id = 0)
    {
		//first entry, populate human readable values	
		if(ci()->input->post() == null)
		{		
		    $this->item = ci()->products_m->get($id);
		    if($this->item)
		    {	    	
		    	$this->item->chk_seller_account = $this->item->outsourced;
				$this->item = $this->populate_product_ids_iteration($this->item);				
				$this->item->features = ci()->products_m->get_all_features_by_id($id);	
				$this->item->features = $this->populate_features_array($this->item->features);
		        $this->item->features_json = $this->generate_features_json_from_array($this->item->features);
		        $this->item->dzfileslistid = $this->item->images;					
			}
			else
				{
					ci()->session->set_flashdata(array('error' => sprintf(lang('products_edit_error_noexist'), $id)));
					$this->redirect_index();
				}	
		}
		else
			{
				//save feature json
				$this->item->features_json = ci()->input->post('features');
				// if post exist, save edited, else save database value.
				$this->item->dzfileslistid = ci()->input->post('dzfileslistid'); 				
			}    	
    }


    function set_edit_data_array()
    {
		$imgIDs = gen_imageIDs_array(ci()->input->post('dzfileslistid'));	
		/* DATA ARRAY */
		$this->data_array = array(
								'location_id'       => convert_empty_value_to_zero(ci()->input->post('location_id')),
								'space_id'          => convert_empty_value_to_zero(ci()->input->post('space_id')),
								'space_usetype_id'  => ci()->input->post('space_usetype_id'),				
								'category_id'       => ci()->input->post('category_id'),
								'account_id'        => ci()->input->post('account_id'),
								'outsourced'        => convert_empty_value_to_zero(ci()->input->post('chk_seller_account')),
								'seller_account_id' => convert_empty_value_to_zero(ci()->input->post('seller_account_id')),
								'name'				=> ci()->input->post('name'),
								'slug'				=> slugify_string(ci()->input->post('name')),
								'intro'				=> ci()->input->post('intro'),
								'body'				=> ci()->input->post('body'),
								'images'			=> ci()->input->post('dzfileslistid'),
								'updated_on'        => now(),									
							);	    	
    }

    function run_update($id)
    {
		// BEGIN TRANSACTION :::::::::::::::::::::::::::::::::::::
		ci()->db->trans_start();
		$result = ci()->products_m->update_product($id, $this->data_array);			
		if ($result && ci()->products_m->delete_products_features($id) )
		{
			//features
			$features_field_list = array('product_id','default_feature_id','description','value','is_optional');
			$features = array();
			$features = $this->generate_features_array_from_json( $features_field_list, ci()->input->post('features'), $id );
			//imagenes
			if( ($prod_folder_id = check_folder($id)) && (count($imgIDs)>0) )
			{
				//imagenes
				move_tempfiles_to_prod_folder($prod_folder_id, $imgIDs);
			}				
			if (ci()->products_m->insert_features($features))
			{
				ci()->session->set_flashdata(array('success' => sprintf(lang('products_edit_success'), ci()->input->post('name'))));
			}
			else
			{
				ci()->session->set_flashdata('error', $this->lang->line('products_edit_error'));
			}		
		}			
		else
		{
			ci()->session->set_flashdata('error', $this->lang->line('products_edit_error'));
		}
		ci()->db->trans_complete();
		// END TRANSACTION :::::::::::::::::::::::::::::::::::::			
		// Redirect back to the form or main page
		$this->redirect_index();    	
    }


    function run_edit_view()
    {
	    foreach ($this->validation_rules as $rule)
	    {
	        if (ci()->input->post($rule['field']) !== null)
	        {
	            $this->item->{$rule['field']} = ci()->input->post($rule['field']);
	        }
	    } 		
		// get category list for current typed
		$dd_categories = get_categories_by_typeid(TYPEID);
		ci()->template
			->title(ci()->module_details['name'], lang('products_create_title'))     	
			->append_css('module::jquery/jquery.tagsinput.css')
			->append_js('module::jquery/jquery.tagsinput.js')
            ->append_js('module::jquery/jquery.mask.min.js')                        
			->append_js('module::products_form.js')
            ->append_js('module::products_form_features.js')
			->set('product', $this->item)
			->set('dzForm', ci()->dropzone->dzFormMarkup('admin/products/filetempupload_ajax'))
			->set('dd_array', $this->dd_array)
			->set('dd_categories', $dd_categories);			
       	//load path for Dropzones assets
	    ci()->dropzone->loadAssetPath();		   				
		ci()->template->build('admin/products/form/form__1');	    	
    }

    //////////////////////////////////////////////////
    // VIEW ------------------------------------ // //
    //////////////////////////////////////////////////

    function set_view_item($id)
    {
	    if( $this->item = ci()->products_m->get($id) )
	    {
			$this->item = $this->populate_product_ids_iteration($this->item);
			$this->item->features = ci()->products_m->get_all_features_by_id($id);
			$this->item->features_array = $this->populate_features_array($this->item->features);				
		}
    }

    function run_item_view()
    {
		if($this->item)
		{
			ci()->template
				->title(ci()->module_details['name'], lang('products_create_title'))   
	            ->set_layout('modal','admin')			                                  
				->set('product', $this->item)
				->set('features', $this->item->features)
				->set('dd_yes_no', $this->gen_dd_yes_no())
				->build('admin/products/view');	    	
		}
		else
			{
				$this->item->name = '<span style="color:red">'.lang('products_error_noexist').'</span>';
				ci()->template
					->title(ci()->module_details['name'], lang('products_error_noexist'))   
		            ->set_layout('modal','admin')			                                  
					->set('product', $this->item )
					->set('features', array())
					->set('dd_yes_no', $this->gen_dd_yes_no())
					->build('admin/products/view');					
			}  			
    }


    /////////////////////////////////////////
    // PUBLISH DRAFT -------------------// //
    /////////////////////////////////////////

    function set_draft_item($id)
    {
	    if( $this->item = ci()->products_m->get($id) )
	    {
			$this->item = $this->populate_product_ids_iteration($this->item);
			$this->item->features = ci()->products_m->get_all_features_by_id($id);
			$this->item->features = $this->populate_features_array($this->item->features);
		}
		else
			{
				ci()->session->set_flashdata(array('error' => sprintf(lang('products_publish_draft_error_noexist'), $id)));
				$this->redirect_index();
			}    	
    }

    function set_draft_template($id)
    {
		$this->set_full_draft();	
		if($this->draft === null)
		{
			ci()->session->set_flashdata(array('error' => sprintf(lang('products_publish_draft_error_template_gen'), $id)));
			$this->redirect_index();									
		}		    	
    }

    function run_save_draft($id)
    {
		$this->load_front_model();
		if(ci()->products_front_m->insert_product_front_draft($this->draft, TYPEID))
		{
			ci()->session->set_flashdata(array('success'=>sprintf(lang('products_publish_draft_success'), $id)));
		}
		else
			{
				ci()->session->set_flashdata(array('error'=>sprintf(lang('products_publish_draft_error_template_gen'), $id)));						
			}   
		$this->redirect_index();			 	
    }


    function load_front_model()
    {
		ci()->load->model(array('products_front_m'));    	
    }

	/**
	 * [gen_front_draft__1_template Generate Front Draft Template of data, for Product Type = 1]
	 * @param  [type] $product [description]
	 * @return [type]          [description]
	 */
	function set_full_draft()
	{
		//get category detail
		$this->item->category = ci()->categories->get_by_id($this->item->category_id);
		//get space
		$this->item->space = ci()->products->get_space_by_id($this->item->space_id);
		//get space denomination
		$this->item->space->denomination = $this->get_space_denomination($this->item->space->denomination_id);
		//get space shape
		$this->item->space->shape = ci()->shapes->get_shape_by_id($this->item->space->shape_id);
		//get location
		$this->item->location = ci()->products->get_location($this->item->location_id);
		$location_type = ci()->locations_type->get_by_id($this->item->location->location_type_id);
		$this->item->location->location_type = $location_type->name;
		$this->item->location->location_type_slug = $location_type->slug;
		$this->item->located = $this->get_located_details($this->item->location);
		//generate layout/capacity
		$this->item->layouts_array = $this->get_layout_capacity_values($this->item->space->layouts);
		//get max capacity
		$this->item->space_max_capacity = $this->get_space_max_capacity($this->item->layouts_array);
		//generate facilites array
		$this->item->facilities = unserialize($this->item->space->facilities);
		//generate front-slug
		$this->item->space->space_slug = $this->gen_space_slug($this->item->space->denomination, $this->item->space->name);
		//get space usetype
	 	$usetype = ci()->spaces_usetype->get_by_id($this->item->space_usetype_id);
		$this->item->space_usetype_slug = $usetype->slug;
		$this->item->space_usetype = $usetype->name;
		//generate space_usetypes array
		$this->item->usetypes = unserialize($this->item->space->usetypes);	
		//check 
		if( $this->item->space 
			&& isset($this->item->space->denomination)
			&& isset($this->item->location)
			&& isset($this->item->layouts_array)
			&& isset($this->item->space_max_capacity)
			&& isset($this->item->facilities)
			&& isset($this->item->space->space_slug)
			&& isset($this->item->space_usetype_slug)
			&& isset($this->item->space_usetype)
			&& isset($this->item->usetypes)				
			)	
		{
			$this->draft = $this->item;
		}
		else
			{
				$this->item = null;
			}
	}


	////////////////////////////////////////
	// DELETE DRAFT -------------------/ //
	////////////////////////////////////////

	function delete_draft($prodid)
	{
		$this->load_front_model();	    	
        if(ci()->products_front_m->soft_delete_front_draft($prodid, TYPEID))
		{
			ci()->session->set_flashdata(array('success' => sprintf(lang('draft_delete_success'), $prodid)));			
		}	
		else
			{
				ci()->session->set_flashdata(array('error' => sprintf(lang('draft_delete_error'), $prodid)));				
			}
		$this->redirect_index();			
	}


    ////////////////////////////////////////
    // AUX ----------------------------// //
    ////////////////////////////////////////


	function redirect_index()
	{
		redirect('admin/products/index/'.TYPEID);			
	}

	/**
	 * [gen_dropdowns_array 
	 * generates products dropdowns]
	 * @return [type] [description]
	 */
    function set_dd_array()
	{
	    $dd_array = new stdClass();
	    $dd_array->type_array = ci()->product_type->gen_dd_array();
	    // categoria de espacio a publicar ----------------
	    $dd_array->spaces_usetype_array = ci()->spaces_usetype->gen_dd_array();  
	    /* ----------------------------------------------- */
	    $dd_array->type_with_cat_products_multiarray = ci()->categories->gen_dd_multiarray();
	    $dd_array->cat_products_array = ci()->categories->gen_dd_array();
	    $dd_array->cat_features_array = ci()->features_categories->gen_dd_array();
	    $dd_array->usageunit_array = ci()->usageunit->gen_dd_array();  
	    $dd_array->dd_yes_no  = $this->gen_dd_yes_no_filter();
	    $dd_array->dd_status = $this->gen_dd_status();     
		$dd_array->hours = array_combine($hours = range(0, 23), $hours);
		$dd_array->minutes = array_combine($minutes = range(0, 59), $minutes);
		$this->dd_array = $dd_array;   		 	
	}    	

	function gen_dd_yes_no()
	{
		return array(''=>'','0'=>lang('products_no_label'),'1'=>lang('products_yes_label'));
	}


	function gen_dd_yes_no_filter()
	{
	    return array('0'=>lang('products_no_label'),'1'=>lang('products_yes_label'));
	}


	function gen_dd_status()
	{
	    return array(''=>lang('products_all_label'),'1'=>lang('products_published'),'0'=>lang('products_not_published'));
	}


	function populate_product_ids()
	{
		if(is_array($this->result))
		{
			foreach ($this->result as $product) 
			{
				$result[] = $this->populate_product_ids_iteration($product);
			}
		}
		else
			{
				$result = $this->populate_product_ids_iteration($this->result);		
			}
		return $result;
	}


	function populate_product_ids_iteration($product)
	{
		$product->category = isset($this->dd_array->cat_products_array[$product->category_id]) ? $this->dd_array->cat_products_array[$product->category_id] : '';
		$product->account = ci()->products->get_account_field_by_id($product->account_id, 'name');
		$product->seller_account = ($product->outsourced == 1) && (isset($product->seller_account_id)) ? ci()->products->get_account_field_by_id($product->seller_account_id, 'name') : '';
		$product->outsourced_txt = $this->dd_array->dd_yes_no[$product->outsourced];
		$product->location = ci()->products->get_location_field_by_id($product->location_id, 'name');
		$product->space = ci()->products->get_space_field_by_id($product->space_id, 'name');
		$product->typeid = get_product_typeid($product->category_id);
		$product->space_usetype = isset($product->space_usetype_id) ? ci()->spaces_usetype->get_field_by_id($product->space_usetype_id, 'name') : '';
		return $product;
	}


	function set_validation_rules_values()
	{	
		$this->validation_rules =  array(       
										array(
											'field' => 'category_id',
											'label' => 'lang:products_category_label',
											'rules' => 'trim|numeric|required'
										),
										array(
											'field' => 'account',
											'label' => 'lang:products_account_label',
											'rules' => 'trim'
										),             
										array(
											'field' => 'account_id',
											'label' => 'lang:products_account_label',
											'rules' => 'trim|numeric|required'
										), 
										array(
											'field' => 'seller_account',
											'label' => 'lang:products_account_label',
											'rules' => 'trim'
										), 
										array(
											'field' => 'chk_seller_account',
											'label' => 'lang:products_chk_seller_account_label',
											'rules' => 'trim'
										), 										
										array(
											'field' => 'seller_account_id',
											'label' => 'lang:products_account_label',
											'rules' => 'trim|numeric|callback__check_seller_account_option'
										), 	
								        array(
											'field' => 'name',
											'label' => 'lang:products_title_label',
											'rules' => 'trim|htmlspecialchars|required|max_length[100]'
										),
										array(
											'field' => 'intro',
											'label' => 'lang:products_intro_label',
											'rules' => 'trim'
										),
										array(
											'field' => 'body',
											'label' => 'lang:products_content_label',
											'rules' => 'trim|required'
										),
										array(
											'field' => 'features',
											'label' => 'lang:products_features_label',
											'rules' => 'trim|required|callback__check_features'
										),
										array(
											'field' => 'dzfileslistid',
											'label' => 'lang:products_images_label',
											'rules' => 'trim'
										),					
										array(
											'field' => 'location_id',
											'label' => 'lang:products_location_label',
											'rules' => 'trim|numeric|required'
										),                        
										array(
											'field' => 'space_id',
											'label' => 'lang:products_space_label',
											'rules' => 'trim|numeric|required'
										),
										array(
											'field' => 'space_usetype_id',
											'label' => 'lang:products_space_usetype_label',
											'rules' => 'trim|numeric|required'
										),
									);		
	}


	function populate_features_array($features_array)
	{
	    if( !empty($features_array) )
		{	
			$i=0;	
			foreach ($features_array as $feature) 
			{
			    $obj = new stdClass;	
				$obj->default_f_id = $feature->default_feature_id;
				$obj->name = $feature->name;
				$obj->description = $feature->description;
				$obj->usageunit = $feature->usageunit;
				$obj->value = $feature->value;
				$obj->isOptional = $feature->is_optional;
				$obj->vecFid = '';
				$obj->n = $i;
				$i++;
				$data[] = $obj;			
			}
		}
		return $data;
	}


	function generate_features_array_from_json($fields, $dataJson, $product_id)
	{
		$array = array();
		if($dataArray = json_decode($dataJson) )
		{	
			foreach ($dataArray as $reg) 
			{
				if (!empty($reg)) 
				{
					$array[] = array( $fields[0]=>$product_id,
									  $fields[1]=>$reg->default_f_id,
									  $fields[2]=>$reg->description,
									  $fields[3]=>$reg->value,
									  $fields[4]=>$reg->isOptional
									);
				}
			}
		}
		return $array;
	}


	function get_locations_and_spaces_updates($products)
	{
		$updates->locations = array();
		$updates->spaces = array();
		foreach($products as $product)
		{
			$location_id_list[] = $product->location_id;
			$space_id_list[] = $product->space_id;
		} 
		$location_id_list = array_unique($location_id_list);
		$space_id_list = array_unique($space_id_list);
		if( empty($location_id_list) == false )
		{
			$updates->locations = ci()->products_m->get_locations_updated_on($location_id_list);
		}
		if( empty($space_id_list) == false )
		{
			$updates->spaces = ci()->products_m->get_spaces_updated_on($space_id_list);
		}
		return $updates;
	}	


	function get_space_denomination($denomination_id)
	{
		$denomination_reg = ci()->spaces_denominations->get_by_id($denomination_id);
		return $denomination_reg->name;
	}

	function get_located_details($location)
	{
		ci()->load->library('geoworldmap');
		$city = ci()->geoworldmap->getCityByID($location->CityID);
		$located->city_slug = slugify_string($city->City);
		$located->area_slug = slugify_string($location->area);
		$located->city = $city->City;
		$located->city_lat = $city->Latitude;
		$located->city_lng = $city->Longitude;
		$located->country_id = $city->CountryID;	
		$country = ci()->geoworldmap->getCountryById($city->CountryID);
		$located->country = $country->Country;
		$located->country_iso3 = $country->ISO3;
		return $located;
	}	


	/**
	 * [gen_layout_capacity_values Generate array from layout string]
	 * @param  string $layouts [ layout value, string]
	 * @return [array]         [ array('layout_id'=>'capacity')]
	 */
	function get_layout_capacity_values($layouts = '')
	{
		$vec = array();
		if(!empty($layouts))
		{
			$array = explode(';', $layouts);
			array_pop($array); // deletes last empty reg
			if($array)
			{
				foreach ($array as $value) 
				{
					$temp[] = explode(',', $value);		
				}
				foreach ($temp as $key=>$value) 
				{
					$vec[$value[0]] = $value[1];
				}
			}
		}
		return $vec; 
	}

	/**
	 * [get_space_max_capacity get layouts array and return max capacity]
	 * @param  array  $layouts [array with layout/capacity values]
	 * @return [int]           [max capacity value]
	 */
	function get_space_max_capacity($layouts = array())
	{
		$max = 0;
		foreach($layouts as $key=>$value)
		{
			$max = $value > $max ? $value : $max;
		}
		return $max;
	}	

	function gen_space_slug($denomination = "", $space_name = "")
	{
		$denomination_slug = slugify_string($denomination);
		$name_slug = slugify_string($space_name);
		return $denomination_slug.'-'.$name_slug;
	}



////////////
// DRAFT STATUS //
////////////


	/**
	 * [publish_status_view 
	 * generates index publication view status]
	 * @param  [type] $products [description]
	 * @return [type]           [description]
	 */
	function set_draft_status_view()
	{
		$updated_on = $this->get_locations_and_spaces_updates($this->result);			
		foreach($this->result as $product)
		{	
			$draft_exist = $this->draft_exist($product);
			$front_exist = $this->front_exist($product);
			$draft_space_updated = $this->check_space_update_status_vs_draft_data($product, $draft_exist, $updated_on);			
			$draft_location_updated = $this->check_location_update_status_vs_draft_data($product, $draft_exist, $updated_on);
			$draft_product_updated = $this->check_prod_update_status_vs_draft_data($product, $draft_exist);
			$product = $this->update_draft_btn_txt_status($product, $draft_location_updated, $draft_space_updated, $draft_product_updated);
			$product = $this->publish_draft_btn_txt_status($draft_exist, $front_exist, $product);
			$product = $this->validation_draft_btn_txt_status($draft_exist, $front_exist, $product);					
		}
	}


	////////////////////////////////////////////////////////////
	// ::: DRAFT STATUS - AUX ::::::::::::::::::::::::::::::: //
	////////////////////////////////////////////////////////////

	function draft_exist($product)
	{
		return ( is_null($product->draft_id) ) ? false : true;
	}

	function front_exist($product)
	{
		return is_null($product->front_id) ? false : true;	
	}


	function check_prod_update_status_vs_draft_data($product, $draft_exist)
	{	
		//if draft no exist, updated true
		if( $draft_exist == false)
		{
			return true;
		}	
		if( $product->prod_updated_on == $product->draft_prod_updated_on )
		{
			return true;
		}
		else
			{
				return false;
			}  
	}

	/**
	 * [check_space_update_status_vs_draft_data 
	 * check space updated_on value and compare with draft space updated_on value]
	 * @return [type] [description]
	 */
	function check_space_update_status_vs_draft_data($product, $draft_exist, $updated_on)
	{
		//if draft no exist, updated true
		if( $draft_exist == false)
		{
			return true;
		}
		if( $product->space_id != 0 && (isset($updated_on->spaces[$product->space_id])) )
		{
			return $updated_on->spaces[$product->space_id] == $product->draft_space_updated_on ? true : false;
		}
		else
			{
				return true;	
			}
	}

	/**
	 * [check_location_update_status_vs_draft_data 
	 * check location updated_on value and compare with draft location updated_on value]
	 * @return [type] [description]
	 */
	function check_location_update_status_vs_draft_data($product, $draft_exist, $updated_on)
	{
		//check location data updated in draft
		if( $draft_exist == false )
		{
			return true;
		}
		if ( $product->location_id != 0 && (isset($updated_on->locations[$product->location_id])) ) 
		{
			return $updated_on->locations[$product->location_id] == $product->draft_loc_updated_on ? true : false;
		}
		else
			{
				return true;	
			}
	}

	/**
	 * [update_draft_btn_txt_status
	 * assign boton and text values for Draft update status]
	 * @param  [type] $product                [description]
	 * @param  [type] $draft_location_updated [description]
	 * @param  [type] $draft_space_updated    [description]
	 * @return [type]                         [description]
	 */
	function update_draft_btn_txt_status($product, $draft_location_updated, $draft_space_updated, $draft_product_updated)
	{
		if( $draft_location_updated && $draft_space_updated && $draft_product_updated )
		{ 			
			$product->btn_update_draft = 'class="btn orange small disabled" onclick="return false;"';
			$product->txt_update_draft = 'muted';
			$product->icon_update_draft = '<i class="icon-exclamation-sign icon-white"></i>';			
		}
		else 
			{
				$product->btn_update_draft = 'class="btn orange small"';
			    $product->txt_update_draft = 'yellow_font';							
				$product->icon_update_draft = '<i class="icon-exclamation-sign"></i>';
			}
		return $product;	
	}


	function publish_draft_btn_txt_status($draft_exist, $front_exist, $product)
	{
		$product->btn_publish_draft = $draft_exist ? 'class="btn blue small disabled" onclick="return false;"' : 'class="btn blue small"';
		$product->btn_delete_draft =  $draft_exist ? 'class="btn red small"' : 'class="btn red small disabled" onclick="return false;"';	
		// PUBLISH STATE
		if($draft_exist && $front_exist)
		{
			$product->txt_publish_draft = '';
			$product->icon_publish_draft = '<i class="icon-ok-circle"></i>';		
		}
		else
			{
				$product->txt_publish_draft = 'muted';
				$product->icon_publish_draft = '<i class="icon-ok-circle icon-white"></i>';				
			}
		return $product;		
	}


	function validation_draft_btn_txt_status($draft_exist, $front_exist, $product)
	{
		//VALIDATION
		if($draft_exist && $front_exist == false 
			|| $draft_exist && $front_exist && $product->front_version =! $product->draft_version)
		{
			$product->txt_validation_draft = 'yellow_font';
			$product->icon_validation_draft = '<i class="icon-time"></i>';
		}
		else
			{
				$product->txt_validation_draft = 'muted';
				$product->icon_validation_draft = '<i class="icon-time icon-white"></i>';				
			}	
		return $product;	
	}	


}