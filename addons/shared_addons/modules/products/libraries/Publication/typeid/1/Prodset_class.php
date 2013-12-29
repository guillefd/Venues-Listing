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

    function __construct()
    {
    	$this->init();       	   	   	
    }

    function init()
    {
		ci()->load->model(array('products_m'));
        ci()->load->helper(array('date', 'products'));                
		ci()->load->library(array('form_validation','features_categories', 'usageunit','categories', 'shapes', 'files/files','dropzone','products','spaces_denominations', 'locations_type', 'spaces_usetype'));  
        ci()->template->append_css('module::products.css')
                       ->prepend_metadata('<script>var IMG_PATH = "'.BASE_URL.SHARED_ADDONPATH.'modules/'.$this->module.'/img/"; </script>'); 
        $this->set_dd_array();                                  	
    }


    function set_tot_rows()
    {
    	$this->tot_rows = ci()->products_m->join_search('counts');
    }

    function set_postdata()
    {
        $this->post_data['pagination'] = create_pagination('admin/products/index/1', $this->tot_rows, 20, 5);     	
    }

    function set_result()
    {
		$this->result = ci()->products_m->join_search('results', $this->post_data);
        $this->populate_product_ids();
        $this->set_draft_status_view(); 		    	
    }

    function set_template()
    {
		ci()->template
			 ->title($this->module_details['name'])
			 ->append_js('module::products_filter.js')    			 
			 ->set('pagination', $this->post_data['pagination'])
			 ->set('products', $this->result)
			 ->set('total_rows', $this->tot_rows)			 
			 ->set('dd_array', $this->dd_array)
			 ->build('admin/products/index');    	
    }


    ////////////////////////////////////////
    // AUX ----------------------------// //
    ////////////////////////////////////////

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
		foreach($this->result as $product)
		{	
			$updated_on = get_locations_and_spaces_updates($products);	
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