<?php defined('BASEPATH') OR exit('No direct script access allowed');

//:::::::::: VALIDATION ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

function validation_rules($typeID = 0)
{	
	$default =  array(       
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
				);
	switch($typeID)
	{
		case ALQ_ESPACIOS_TYPEID:
					$rules = 	array(
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
									)
								);	
								break;

		default:	
					$rules = array();	
	}		
	return array_merge($default, $rules);		
}


function populate_product_ids($products, $dd_array)
{
	if(is_array($products))
	{
		foreach ($products as $product) 
		{
			$result[] = populate_product_ids_iteration($product, $dd_array);
		}
	}
	else
		{
			$result = populate_product_ids_iteration($products, $dd_array);		
		}
	return $result;
}

function populate_product_ids_iteration($product, $dd_array)
{
	$product->category = isset($dd_array->cat_products_array[$product->category_id]) ? $dd_array->cat_products_array[$product->category_id] : '';
	$product->account = ci()->products->get_account_field_by_id($product->account_id, 'name');
	$product->seller_account = ($product->outsourced == 1) && (isset($product->seller_account_id)) ? ci()->products->get_account_field_by_id($product->seller_account_id, 'name') : '';
	$product->outsourced_txt = $dd_array->dd_yes_no[$product->outsourced];
	$product->location = ci()->products->get_location_field_by_id($product->location_id, 'name');
	$product->space = ci()->products->get_space_field_by_id($product->space_id, 'name');
	$product->typeid = get_product_typeid($product->category_id);
	return $product;
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


function get_product_typeid($category_id = 0) 
{
	if($category = ci()->categories->get_by_id($category_id))
	{
		return $category->type_id;  
	}	
	else
		{
			return null;
		}
}

function get_categories_by_typeid($typeid = 0)
{
	if($typeid==0) return array();
	return ci()->categories->gen_dd_array($typeid);
}

/**
 * [gen_dropdowns_array 
 * generates products dropdowns]
 * @return [type] [description]
 */
function gen_dropdowns_array() 
{
    $data = new stdClass();
    $data->type_array = ci()->product_type->gen_dd_array();
    $data->spaces_usetype_array = ci()->spaces_usetype->gen_dd_array();
    $data->type_with_cat_products_multiarray = ci()->categories->gen_dd_multiarray();
    $data->cat_products_array = ci()->categories->gen_dd_array();
    $data->cat_features_array = ci()->features_categories->gen_dd_array();
    $data->usageunit_array = ci()->usageunit->gen_dd_array();  
    $data->dd_yes_no  = gen_dd_yes_no_filter();
    $data->dd_status = gen_dd_status();     
	$data->hours = array_combine($hours = range(0, 23), $hours);
	$data->minutes = array_combine($minutes = range(0, 59), $minutes);    	
    return $data;
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


function convert_empty_value_to_zero($var)
{
	return (empty($var) || is_null($var)) ? 0 : $var;
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


function generate_features_json_from_array($features)
{
	return json_encode($features);
}


function check_product_slug($slug)
{
	return slugify_string($slug);
}

/**
 * [slugify_string description]
 * @param  [type] $text [description]
 * @return [type]       [description]
 */
function slugify_string($text) {
	// replace non letter or digits by -
	$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
	// trim
	$text = trim($text, '-');	
	// transliterate
	$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	// lowercase
	$text = strtolower($text);	
	// remove unwanted characters
	$text = preg_replace('~[^-\w]+~', '', $text);	
	if (empty($text))
	{
		return 'n-a';
	}
    return trim($text, '-');
}


////////////////////////////////////////////////////////////////////////////////////////////////////
// :::::::::::::: FILES PROCESS ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * [gen_imageIDs_array generates array with images IDs]
	 * @param  [string] $field [input]
	 * @return [array]        [array with id values]
	 */
	function gen_imageIDs_array($field)
	{
		if($field)
		{
			$imgIDs = explode(";", trim($field));
			for($i=0;$i<=count($imgIDs);$i++)
			{
				if($imgIDs[$i]=="")
				{
					unset($imgIDs[$i]);
				}
			}
			return $imgIDs;
		}
		return array();
	}

	function gen_imageIDS_string($array)
	{
		$string = '';
		if(is_array($array))
		{
			foreach($array as $value)
			{
				$string.= $value.';';
			}
		}
		return $string;
	}


	function get_images_metadata_by_ids($array = array())
	{
		ci()->load->library('files/files');
		$imgs = array();
		foreach ($array as $fileid) {
			$file = Files::get_file($fileid);
			$image = array();
			$image['id'] = $file['data']->id;			
			$image['width'] = $file['data']->width;
			$image['height'] = $file['data']->height;
			$image['mimetype'] = $file['data']->mimetype;
			$image['description'] = $file['data']->description;			
			$image['extension'] = $file['data']->extension;
			$imgs[$fileid] = $image;
		}
		return $imgs; 
	}   


	function check_folder($product_id)
	{
		$prodfoldername = 'Prod'.$product_id;
		$tree = Files::folder_tree();
		$notfound = true;
		$i = 0;
		while($notfound && $i <= count($tree) )
		{
			if( $tree[$i-1]['name'] == $prodfoldername )
			{
				$notfound = false;
				$prodfolderid = $tree[$i-1]['id'];
			}	
			$i++;
		}
		if($notfound)
		{
			$result = Files::create_folder(0, $prodfoldername );
			if($result['status']==true)
			{
				$prodfolderid = $result['data']['id'];
			}
			else
			{
				$prodfolderid = false;
			}		
		}
		return $prodfolderid;	
	}


	function move_tempfiles_to_prod_folder($prodfolderid, $imgarray)
	{
		foreach($imgarray as $imgid)
		{
			ci()->products_m->move_product_file($imgid, $prodfolderid);
		}
	}


//////////////////////
// PRODUCT   DRAFT  //
//////////////////////


/**
 * [load_product_draft_template
 * Checks if template is defined for product type
 * generates ]
 * @return [type] [description]
 */
function load_product_draft_template($prod_type_id, $product)
{
	// switch by product_type
	switch($prod_type_id)
	{
		// type: 'Alquiler de Espacio'
		case ALQ_ESPACIOS_TYPEID: 
				$template = gen_front_draft__1_template($product);			
				break;

		// // type: 'Servicios'
		// case SERVICIOS_TYPEID: 
		// 		$template = gen_front_draft__2_template($product);			
		// 		break;

		default: 
				return null;								
	}
	return $template;
}


/**
 * [gen_front_draft__1_template Generate Front Draft Template of data, for Product Type = 1]
 * @param  [type] $product [description]
 * @return [type]          [description]
 */
function gen_front_draft__1_template($product)
{
	//get category detail
	$product->category = ci()->categories->get_by_id($product->category_id);
	//get space
	$product->space = ci()->products->get_space_by_id($product->space_id);
	//get space denomination
	$product->space->denomination = get_space_denomination($product->space->denomination_id);
	//get space shape
	$product->space->shape = ci()->shapes->get_shape_by_id($product->space->shape_id);
	//get location
	$product->location = ci()->products->get_location($product->location_id);
	$location_type = ci()->locations_type->get_by_id($product->location->location_type_id);
	$product->location->location_type = $location_type->name;
	$product->location->location_type_slug = $location_type->slug;
	$product->located = get_located_details($product->location);
	//generate layout/capacity
	$product->layouts_array = gen_layout_capacity_values($product->space->layouts);
	//get max capacity
	$product->space_max_capacity = get_space_max_capacity($product->layouts_array);
	//generate facilites array
	$product->facilities = unserialize($product->space->facilities);
	//generate front-slug
	$product->space->space_slug = gen_space_slug($product->space->denomination, $product->space->name);
	//get space usetype
 	$usetype = ci()->spaces_usetype->get_by_id($product->space_usetype_id);
	$product->space_usetype_slug = $usetype->slug;
	$product->space_usetype = $usetype->name;
	//generate space_usetypes array
	$product->usetypes = unserialize($product->space->usetypes);	
	//check 
	if( $product->space 
		&& isset($product->space->denomination)
		&& isset($product->location)
		&& isset($product->layouts_array)
		&& isset($product->space_max_capacity)
		&& isset($product->facilities)
		&& isset($product->space->space_slug)
		&& isset($product->space_usetype_slug)
		&& isset($product->space_usetype)
		&& isset($product->usetypes)				
		)	
	{
		return $product;
	}
	else
		{
			return null;
		}
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

function get_layouts_list()
{
	return ci()->layouts->gen_dd_array();
}

/**
 * [gen_layout_capacity_values Generate array from layout string]
 * @param  string $layouts [ layout value, string]
 * @return [array]         [ array('layout_id'=>'capacity')]
 */
function gen_layout_capacity_values($layouts = '')
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
function draft_status_view($products)
{
	foreach($products as $product)
	{
		/* get product_type */
		$prod_typeid = get_product_typeid($product->category_id);
		switch($prod_typeid)
		{
			/* Alquiler de sala */
			case ALQ_ESPACIOS_TYPEID:			
					$updated_on = get_locations_and_spaces_updates($products);	
					$draft_exist = draft_exist($product);
					$front_exist = front_exist($product);
					$draft_space_updated = check_space_update_status_vs_draft_data($product, $draft_exist, $updated_on);			
					$draft_location_updated = check_location_update_status_vs_draft_data($product, $draft_exist, $updated_on);
					$draft_product_updated = check_prod_update_status_vs_draft_data($product, $draft_exist);
					$product = update_draft_btn_txt_status($product, $draft_location_updated, $draft_space_updated, $draft_product_updated);
					$product = publish_draft_btn_txt_status($draft_exist, $front_exist, $product);
					$product = validation_draft_btn_txt_status($draft_exist, $front_exist, $product);					
					break;

			default:
					$product->btn_update_draft = 'class="btn orange small disabled" onclick="return false;"';
					$product->txt_update_draft = 'muted';
					$product->icon_update_draft = '<i class="icon-exclamation-sign icon-white"></i>';	
					$product->btn_publish_draft = 'class="btn blue small disabled" onclick="return false;"';
					$product->btn_delete_draft =  'class="btn red small disabled" onclick="return false;"';
					$product->txt_publish_draft = 'muted';
					$product->icon_publish_draft = '<i class="icon-ok-circle icon-white"></i>';	
					$product->txt_validation_draft = 'muted';
					$product->icon_validation_draft = '<i class="icon-time icon-white"></i>';																			
					break;							
		}
	}
	return $products;
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