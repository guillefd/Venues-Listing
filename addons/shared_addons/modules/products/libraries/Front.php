<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Frontend Class
 *
 * Frontend process
 *
 * @package			CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Front
{
	public $page;
	public $CFG;
    /**
        * Constructor - Sets loads and vars
        *
        */
    function __construct()
    {
    	require_once 'Front/Front_classes.php';
    }

    function init_page($catid)
    {
		$this->page = new page(intval($catid), ci()->uri->segment_array());
		$this->_load_category_params();
		$this->_load_category_aux_params(); 
		$this->_set_request_type();
    }

    function init_cfg()
    {
    	$settings = $this->get_config_settings();
		$this->CFG = new configData(intval($this->page->catid), $settings);   	
    }

    function _load_category_params()
    {
    	//category params
    	$category = ci()->categories->get_by_id($this->page->catid);
    	$this->page->set_categoryparams($category);   
	}

	function _load_category_aux_params()    	
    {	
    	$params = array();
		switch($this->page->catid)
		{
			case ALQUILERDESALAS_CATID:
										$params['categories_dd'] = ci()->categories->gen_dd_array();  
										$params['usetypes'] = ci()->spaces_usetype->get();
										$params['usetypessync'] = ci()->spaces_usetype->get_syncindex();
										$params['locations_type'] = ci()->locations_type->get();  
										$params['facilities'] = ci()->facilities->get_syncindex();  
										$params['layouts'] = ci()->layouts->get_syncindex();
										$params['features_defaults'] = ci()->features_categories->get_features_defaults_by_prodcat_syncindex($this->page->catid);										
										$params['features_defaults_list'] = ci()->features_categories->get_defaults_syncindex($this->page->catid);
										//dd_arrays
										$params['usetypes_slug_dd'] = ci()->spaces_usetype->gen_dd_array_slug_local($params['usetypes']);
										$params['locations_type_dd'] = ci()->locations_type->gen_dd_array_local($params['locations_type']);   
										$params['locations_type_slug_dd'] = ci()->locations_type->gen_dd_array_slug_local($params['locations_type']);   
										$params['facilities_dd'] = ci()->facilities->gen_dd_array_local($params['facilities']); 										
										break;
		}			
		//set to page
		$this->page->set_categoryauxiliars($params);
    }

    function _set_request_type()
    {
    	$this->page->isajaxrequest = ci()->input->is_ajax_request();
    }


    /////////////////////////////////////////////////////////////////
    // CONFIG ------------------------------------------------- // //
    /////////////////////////////////////////////////////////////////

    function get_config_settings()
    {
		ci()->config->load('product_'.ENVIRONMENT, TRUE);		
		return ci()->config->item('product_'.ENVIRONMENT);  
    }


	//////////////////////////////////////////////////////////////
	// URI PROCESS ----------------------------------------  // //
	//////////////////////////////////////////////////////////////


	function validate_urisegments()
	{

		// if total segments more than limit, return FALSE
		if( count($this->page->urisegments) > $this->CFG->urisegments->max )
		{
			return false;
		}
		//go with validation	
		$validated = array();
		if(count($this->page->urisegments)>0)
		{		
			switch($this->page->catid)
			{
				case ALQUILERDESALAS_CATID:
										$validated = $this->cat1_validate_allowed_segments(); 	
										break;					  	
			}
		}
		return $validated;
	}


	function validate_urisegments_values($urisegments)
	{
		switch($this->page->catid)
		{
			case ALQUILERDESALAS_CATID:
									return $this->cat1_validate_segments_values($urisegments);	
									break;						
		}				
	}	


	/**
	 * [cat1_validate_allowed_segments For product category: alquiler-de-salas
	 * clean and save values of each segment]
	 * @param  [type]  $segments [description]
	 * @param  integer $limit    [description]
	 * @return [type]            [description]
	 */
	function cat1_validate_allowed_segments()
	{
		$segments_valid = array();
		$segments = $this->page->urisegments;
		$limit = $this->CFG->urisegments->max;
		// validate segments
		for($i=1;$i<=$limit;$i++)
		{
			switch($i)
			{
				/* category-slug+usetype-slug */			
				case 1: 
						$segment = $this->explode_and_clean_segment($segments, $i);
						if( $this->segment_string_valid($segment, 0) ) 
						{
							$segments_valid[$i] = new stdClass();
							$segments_valid[$i]->prod_cat_slug = $segment[0];
							if( $this->segment_string_valid($segment, 1) )
							{
								$segments_valid[$i]->space_usetype_slug = $segment[1];							
							}
						}
						else
							{
								$segments_valid[$i] = null;
							}					
						unset($segment);
						break;
				/* city-slug+area-slug+locationtype-slug */			
				case 2: 
						$segment = $this->explode_and_clean_segment($segments, $i);
						if( $this->segment_string_valid($segment, 0) ) 
						{
							$segments_valid[$i] = new stdClass();							
							$segments_valid[$i]->loc_city_slug = $segment[0];
							if( $this->segment_string_valid($segment, 1) )
							{
								$segments_valid[$i]->loc_area_slug = $segment[1];							
								if( $this->segment_string_valid($segment, 2) )
								{
									$segments_valid[$i]->loc_type = $segment[2];							
								}
							}
						}
						else
							{
								$segments_valid[$i] = null;
							}					
						unset($segment);
						break;
				/* location-slug */
				case 3: 
						$segment = $this->explode_and_clean_segment($segments, $i);
						if( $this->segment_string_valid($segment, 0) ) 
						{
							$segments_valid[$i] = new stdClass();							
							$segments_valid[$i]->loc_slug = $segment[0];
						}
						else
							{
								$segments_valid[$i] = null;
							}
						unset($segment);
						break;
				case 4: /* space-slug+front-version */
						$segment = $this->explode_and_clean_segment($segments, $i);
						if( $this->segment_string_valid($segment, 0) ) 
						{
							$segments_valid[$i] = new stdClass();							
							$segments_valid[$i]->space_slug = $segment[0];
							if( $this->segment_string_valid($segment, 1) )
							{
								$segments_valid[$i]->front_version = $segment[1];							
							}
						}
						else
							{
								$segments_valid[$i] = null;
							}					
						unset($segment);
						break;															
			}
		}
		return $segments_valid;
	}	

	function cat1_validate_segments_values($urisegments)
	{
		//init
		$validated = array('space_usetype_slug'=>true);
		$result = true;

		// check usetype (product sub category)
		if(isset($urisegments[1]->space_usetype_slug))
		{
			$usetypes_dd = $this->page->get_categoryauxiliars('usetypes_slug_dd');
			if(!array_key_exists($urisegments[1]->space_usetype_slug, $usetypes_dd)) 
			{
				$validated['space_usetype_slug'] = false;
			} 
		}

		//check result
		foreach ($validated as $value) 
		{
			$result = $value;
		}
		return $result;	
	}
	

///////////////////////////////////////////////////////////////////
// PROCESS URI FILTERS ----------------------------------------- //
///////////////////////////////////////////////////////////////////


	/**
	 * [validate_uri_filters get cat filters values ]
	 * @param  integer $productcategoryid [description]
	 * @param  array   $segments          [description]
	 * @return [type]                     [description]
	 */
	function validate_urifilters()
	{
		$urifilters = ci()->input->get();
		$validUrifilters = $this->CFG->urifilters->valid;
		if(empty($urifilters))
		{	
			return array();
		}	
		//go with validation	
		switch($this->page->catid)
		{
			case ALQUILERDESALAS_CATID:
									return $this->cat1_validate_urifilters($urifilters, $validUrifilters); 	
									break;					  	
		}	
	}


	function cat1_validate_urifilters($urifilters, $validUrifilters)
	{
		$validatedFilters = array();
		foreach ($validUrifilters as $index) 
		{
			if(array_key_exists($index, $urifilters))
			{
				$explodeuri = $this->explode_in_array($urifilters[$index]);
				if($explodeuri !== false)
				{
					$validatedFilters[$index] = $explodeuri;
				}
				else
				{
					$validatedFilters[$index] = $urifilters[$index];				
				}
			}
		}
		return $validatedFilters;
	}	

/////////////////////////////////////////////////
// SEARCH PARAMS ---------------------------// //
/////////////////////////////////////////////////

	function set_search_params()
	{
		$params = array();
		$params['prodcatname'] = $this->page->get_category_param('title');
		$city_slug = $this->page->get_validsegment('loc_city_slug');
		$params['cityname'] = $this->CFG->get_city_param($city_slug, 'name');

		$params['usetype'] = $this->page->get_usetypeSearch();
		$this->page->set_searchparam($params);
	}

///////////////////////////////////////////////////
// ROUTE VIEW ----------------------------- //	 //
///////////////////////////////////////////////////

	function route_request_view()
	{
		switch($this->page->catid)
		{
			case ALQUILERDESALAS_CATID:
							return $this->cat1_route_request_view(); 	
							break;
			default:
							return null;												  	
		}	
	}


	/* CATEGORY 1: alquiler-de-salas */

	function cat1_route_request_view()
	{
		$validUriSegments = $this->page->validurisegments;
		$catViewsArr = $this->CFG->views;
		$view = null;
		//get publication or space [ 1 + 2 + 3 + 4 ]
		if($validUriSegments[1] != null 
			&& $validUriSegments[2] != null 
			&& $validUriSegments[3] != null 
			&& $validUriSegments[4] != null)
		{
			// get product [ 1.1 1.2 + 2 + 3 + 4.1 4.2 ]
			if( isset($validUriSegments[4]->space_slug) 
				&& !empty($validUriSegments[4]->space_slug) 
				&& isset($validUriSegments[4]->front_version) 
				&& !empty($validUriSegments[4]->front_version) )
			{
				return $catViewsArr[3]; //product
			}
			// get space [ 1.1 + 2 + 3 + 4.1 ]
			if( isset($validUriSegments[4]->space_slug) 
				&& !empty($validUriSegments[4]->space_slug) 
				&& !isset($validUriSegments[4]->front_version) 
				&& empty($validUriSegments[4]->front_version) )
			{
				return $catViewsArr[2]; //space
			}		
		}
		//get location [ 1 + 2 + 3 ]
		if($validUriSegments[1] != null 
			&& $validUriSegments[2] != null 
			&& $validUriSegments[3] != null )
		{
			return $catViewsArr[4]; //location
		}	
		//get home list [ 1 + 2 ]
		if($validUriSegments[1] != null 
			&& $validUriSegments[2] != null )
		{
			if( isset($validUriSegments[1]->prod_cat_slug) 
				&& !empty($validUriSegments[1]->prod_cat_slug) 
				&& isset($validUriSegments[1]->space_usetype_slug) 
				&& !empty($validUriSegments[1]->space_usetype_slug) )
			{
				return $catViewsArr[1]; //homelist product
			}
			else
				{ 			
					return $catViewsArr[0]; //homelist space
				}
		}				
		return $view;
	}

	function frontlink_generator($item = array())
	{
		switch($this->page->catid)
		{
			case ALQUILERDESALAS_CATID:
							return $this->cat1_frontlink_generator($item); 	
							break;
			default:
							return null;												  	
		}	
	}


	function cat1_frontlink_generator($item = array())
	{	
		switch($this->page->view['id'])
		{
			//space list
			case 100: 
							return $this->cat1_gen_frontlink_space($item);
							break;
			//product list						
			case 200:
							return $this->cat1_gen_frontlink_product($item);		
							break;
			//space
			case 300:
							return '';
							break;

			//product
			case 400:
							return '';
							break;		
			
			//location
			case 500:

			default:			
							return null;
		}
	}

	function cat1_gen_frontlink_space($item)
	{
		return base_url().$item->prod_cat_slug.'/'.$item->loc_city_slug.'/'.$item->loc_slug.'/'.$item->space_slug;	
	}

	function cat1_gen_frontlink_product($item)
	{
		return base_url().$item->prod_cat_slug.'+'.$item->space_usetype_slug.'/'.$item->loc_city_slug.'/'.$item->loc_slug.'/'.$item->space_slug.'+'.$item->front_version;	
	}

	//go back links

	function cat1_front_goback_link_generator()
	{	
		switch($this->page->view['id'])
		{

			//space
			case 300:
							return $this->cat1_gen_front_goback_link_space();
							break;

			//product
			case 400:
							return $this->cat1_gen_front_goback_link_product();
							break;		
			
			//location
			case 500:

			default:			
							return $this->cat1_gen_front_goback_link_space();
		}
	}	


	function cat1_gen_front_goback_link_space()
	{
		return base_url().$this->page->validurisegments[1]->prod_cat_slug.'/'.$this->page->validurisegments[2]->loc_city_slug.$this->link_restore_lastfilteruri();	
	}

	function cat1_gen_front_goback_link_product()
	{
		return base_url().$this->page->validurisegments[1]->prod_cat_slug.'+'.$this->page->validurisegments[1]->space_usetype_slug.'/'.$this->page->validurisegments[2]->loc_city_slug.$this->link_restore_lastfilteruri();	
	}

	function link_restore_lastfilteruri()
	{
		$filteruri = ''; 
		if($lastfilters = ci()->session->userdata('lastfilters'))
		{	
			$i = 0;
			foreach ($lastfilters as $key => $value) 
			{
				if($i==0)
				{
					$filteruri.= '/?'.$key.'='.$value;
				}
				else
					{
						$filteruri.='&'.$key.'='.$value;
					}
				$i++;
			}
		}
		return $filteruri;
	}	


	//////////////////////////////////////////
	// USER CURRENT URL   ---------------- //
	//////////////////////////////////////////

	function set_usernavigation_current_url()
	{
		$sessionusernav = ci()->session->all_userdata();
		if(!array_key_exists('lastfilters', $sessionusernav))
		{
			$lastfilters = array();
		}		
		if($this->page->view['id']<300 ) 
		{
			$lastfilters = $this->page->validurifilters;
		}
		else
			{
				$lastfilters = $sessionusernav['lastfilters'];
			}
		$currentusernav = array(
							'currenturl'=> current_url(), 
					 	 	'lasturl'=> $this->set_usernavigation_default_last_url(),
					 	 	'lastfilters'=> $lastfilters,
						);				
		ci()->session->set_userdata($currentusernav);	
		$this->page->sessiondata = ci()->session->all_userdata();
	}

	function set_usernavigation_default_last_url()
	{
		return $this->cat1_front_goback_link_generator();
	}

	//////////////////////////////////////////////
	// SEARCH / PAGINATION   --------------- // //
	//////////////////////////////////////////////

	function create_pagination()
	{	
		if( (intval($this->page->get_list_result()->totrows) > 0) )
		{
			$maxrecordsshown = intval($this->CFG->page->maxrecords) * $this->page->get_pagination('currentpage');
			if($maxrecordsshown < $this->page->get_list_result()->totrows)
			{
				$pagenum = $this->page->get_pagination('currentpage') + 1;
				$filtersURI = $this->_gen_pagination_urifilters($pagenum);
				$link = current_url().$filtersURI;
				$this->page->set_pagination('link', $link);
				$this->page->set_pagination('filtersuri', $filtersURI);
			}
		} 
	}

	function _gen_pagination_urifilters($pagenum)
	{
		$uri = '/?';
		$validurifiltersArr = $this->page->validurifilters;
		unset($validurifiltersArr['page']); 	
		if(count($validurifiltersArr)>0)
		{
			foreach ($validurifiltersArr as $key => $value) 
			{
				$uri.= $key.'='.$value;
			}
			$uri.= '&';			
		}
		$uri.= 'page='.$pagenum;		
		return $uri;
	}


	function cat1_search()
	{	
		switch($this->page->view['id'])
		{
			//space list
			case 100: 
							return ci()->products_frontend_1_m->get_list_spaces($this->page, $this->CFG);
							break;
			//product list						
			case 200:
							return ci()->products_frontend_1_m->get_list_products($this->page, $this->CFG);
							break;
			//space
			case 300:
							return ci()->products_frontend_1_m->get_item_space($this->page, $this->CFG);
							break;

			//product
			case 400:
							return ci()->products_frontend_1_m->get_item_product($this->page, $this->CFG);
							break;		
			
			//location
			//case 4:

			default:			
							return null;
		}
	}


///////////////////////////////////////////////
// GOOGLE MAP --------------------------- // //
///////////////////////////////////////////////

	function load_listMap()
	{
		//load settings
		$this->_load_map_settings();		
		//load page map
		$city_slug = $this->page->get_validsegment('loc_city_slug');
		$center = $this->CFG->get_city_map_data($city_slug);
		$this->page->set_map_data(
							array(
									'imgUrl'=>BASE_URL.SHARED_ADDONPATH.'themes/amr/img/',
									'markerImgUrl'=>BASE_URL.SHARED_ADDONPATH.'themes/amr/img/'.$this->CFG->map->markerimg,
									'markerImgUrl_hover'=>BASE_URL.SHARED_ADDONPATH.'themes/amr/img/'.$this->CFG->map->markerimgHover, 
									'cloudimgUrl'=>$this->CFG->cloudstorage->cdn->uri,
									'center'=>$center
									)
						);
		//load results
		$this->_load_pagemap_result();
		//load gmap API to template
		$this->_prepend_google_map_js_api();
	}	

	function load_itemMap()
	{
		//load settings
		$this->_load_map_settings();	
		$this->page->set_map_data(
							array(
									'imgUrl'=>BASE_URL.SHARED_ADDONPATH.'themes/amr/img/',
									'markerImgUrl'=>BASE_URL.SHARED_ADDONPATH.'themes/amr/img/'.$this->CFG->map->markerimg,
									'markerImgUrl_hover'=>BASE_URL.SHARED_ADDONPATH.'themes/amr/img/'.$this->CFG->map->markerimgHover, 
									'cloudimgUrl'=>$this->CFG->cloudstorage->cdn->uri,
									'center'=>$this->page->get_item_map_center(),
									)
						);		
		//load results
		$this->_load_pagemap_item();								
		//load gmap API to template
		$this->_prepend_google_map_js_api();
	}	

	function _load_map_settings()
	{
		$this->CFG->set_map_settings();		
	}

	function _load_pagemap_result()
	{
		$mapresult = array();
		$dbfields = $this->CFG->map->itemdbfields;
		$imgfield = $this->CFG->get_cloudstorage_img_field('th');
		$result = $this->page->get_result();	
		foreach($result->list->items as $item)
		{
			array_push($mapresult, $this->_get_map_item($item, $dbfields));
		}
		$this->page->set_map_data(array('result'=> $mapresult));
	}

	function _load_pagemap_item()
	{
		$mapresult = array();
		$dbfields = $this->CFG->map->itemdbfields;
		$imgfield = $this->CFG->get_cloudstorage_img_field('th');
		$item = $this->page->get_item_result();	
		array_push($mapresult, $this->_get_map_item($item, $dbfields));
		$this->page->set_map_data(array('result'=> $mapresult));
	}		


	function _get_map_item($item, $dbfields)
	{	
		$newitem = new stdClass();
		foreach($dbfields as $field)
		{
			$newitem->{$field} = isset($item->{$field}) ? $item->{$field} : '';
		}
		return $newitem;
	}	



	function _prepend_google_map_js_api()
	{
		/* GMAP*/
		ci()->template
			->append_metadata('<script src="https://maps.googleapis.com/maps/api/js?key='.$this->CFG->map->gmapApiKey.'&libraries=geometry&sensor=false"></script>');
	}


	/////////////////////////////////////////
	// MEDIA RESOURCES ----------------// //
	/////////////////////////////////////////

	function load_media_resources()
	{
		$media = new stdClass;
		$media->cdnUri = $this->CFG->cloudstorage->cdn->uri;
		$this->page->set_media($media);
	}


 ////////////////////////////////////////////
 // POPULATE OR FORMAT RESULT ITEMS ----// //
 ////////////////////////////////////////////

	function format_and_populate_result_for_view()
	{
		$formatedResult = array();
		$result = $this->page->get_result();	
		$imgSizes = $this->CFG->get_cloudstorage_imgSizes();
		if(isset($result->list))
		{
			foreach ($result->list->items as $item) 
			{	
				$item = $this->explode_items_string($item);
    			$item = $this->gen_image_array_per_item_result($item, $imgSizes);	
				$item->itemUri = $this->frontlink_generator($item);
				$formatedResult[] = $item;

			}
			$result->list->items = $formatedResult;
			$this->page->set_result($result->list);
		}
		if(isset($result->item))
		{	
			$result->item = $this->explode_items_string($result->item);			
			$formatedResult = $this->gen_image_array_per_item_result($result->item, $imgSizes);
			$result = $formatedResult;
			$this->page->set_result($result);					
		}			
	}


	function gen_image_array_per_item_result($item, $imgSizes)
	{			
		foreach ($imgSizes as $size=>$data) 
		{					
			$imgdbfield = $data[4];		
			$item->{$imgdbfield} = $this->gen_imageIDs_array($item->{$imgdbfield});
		}
		return $item;		
	}

	function explode_items_string($item)
	{
		$item->space_usetypes_all = isset($item->space_usetypes_all) ? explode(",", $item->space_usetypes_all) : array();
		$item->space_usetypes_published = isset($item->space_usetypes_published) ? explode(",", $item->space_usetypes_published) : array();
		$item->front_version_published = isset($item->front_version_published) ? explode(",", $item->front_version_published) : array();
		$item->space_usetypes_published_uri = isset($item->space_usetypes_published) ? $this->generate_usetypes_published_uris($item) : array();		
		$item->space_facilities_list = isset($item->space_facilities_list) ? explode(",", $item->space_facilities_list) : array();
		$item->space_features_list = isset($item->space_features_list) ? explode(",", $item->space_features_list) : array();			
		return $item;		
	}

	function generate_usetypes_published_uris($item)
	{
		$uris = array();
		for($i=0; $i<=count($item->space_usetypes_published)-1; $i++)
		{
			$sup = new stdClass();
			$sup->prod_cat_slug = $item->prod_cat_slug;
			$sup->space_usetype_slug = $item->space_usetype_slug;
			$sup->loc_city_slug = $item->loc_city_slug;
			$sup->loc_slug = $item->loc_slug;
			$sup->space_slug = $item->space_slug;
			$sup->front_version = $item->front_version_published[$i];
			$uris[$item->space_usetypes_published[$i]] = $this->cat1_gen_frontlink_product($sup);
		}
		return $uris;
	}


////////////////////////////////////////////
// HTML FILTER ----------------------  // //
////////////////////////////////////////////


	/**
	 * [gen_dropdowns_array 
	 * generates products dropdowns]
	 * @return [type] [description]
	 */
	function set_htmlfilterdata() 
	{
	    $data = new stdClass();
	    $data->facilities= $this->page->get_categoryauxiliars('facilities_dd');
	    /* use types arrays*/	
	    $data->usetypes_txt = $this->page->get_categoryauxiliars('usetypes');
	    $data->usetypes_slug = $this->page->get_categoryauxiliars('usetypes_slug_dd');
	    $data->usetypes_btn = array_slice($data->usetypes_slug, 0, 7, true);
	    $data->usetypes_select = array_slice($data->usetypes_slug, 7, count($data->usetypes_slug), true);
	    /* location types */
	    $data->location_types_txt = $this->page->get_categoryauxiliars('locations_type');
	    $data->location_types = $this->page->get_categoryauxiliars('locations_type_dd'); 
	    $data->location_types_slug = $this->page->get_categoryauxiliars('locations_type_dd');
	    $this->page->set_htmlfilterdata($data);
	}


	function populate_filter_values()
	{
		$values = array();
		//segments
		$values['category'] = isset($this->page->validurisegments[1]->space_usetype_slug) && $this->page->validurisegments[1]->space_usetype_slug!='' ? $this->page->validurisegments[1]->space_usetype_slug : '';
		//filters
		$values['capacity'] = isset($this->page->validurifilters['capacity']) && $this->page->validurifilters['capacity']!='' ? $this->page->validurifilters['capacity'] : '';
		$values['loctypes'] = isset($this->page->validurifilters['loctypes']) && $this->page->validurifilters['loctypes']!='' ? $this->page->validurifilters['loctypes'] : '';
		$values['layouts'] = isset($this->page->validurifilters['layouts']) && $this->page->validurifilters['layouts']!='' ? $this->page->validurifilters['layouts'] : '';
		$values['facilities'] = isset($this->page->validurifilters['facilities']) && $this->page->validurifilters['facilities']!='' ? $this->page->validurifilters['facilities'] : '';
		$values['features'] = isset($this->page->validurifilters['features']) && $this->page->validurifilters['features']!='' ? $this->page->validurifilters['features'] : '';
		$values['page'] = $this->page->pagination->currentpage;
		return $values;
	}


	////////////////////////////////////////////
	//PAGE MESSAGES ----------------------// //
	////////////////////////////////////////////


	function load_listview_messages()
	{
		//check empty result
		if($this->page->get_result()->list->totrows == 0)
		{
			$this->page->set_message('result', $this->CFG->get_print_message('result') );
		}
	}


	/////////////////////////////////////////////
	// REDIRECTS ------------------------- // //
	/////////////////////////////////////////////

	function redirect_to_last_list_view()
	{
		//get view dbfields in config
		$cfg_listview_urifields = $this->CFG->get_view_urifields('homelist_'.$this->page->view['name']);
		// if config ok, create uri
		if(is_array($cfg_listview_urifields) && count($cfg_listview_urifields)>0)
		{
			$uri = '/';
			//loop base urisegements
			foreach($this->CFG->urisegments->dbfields as $index=>$segment)
			{
				//loop segment
				foreach($segment as $field)
				{
					//is segment field, in 
					if(in_array($field, $cfg_listview_urifields))
					{			
						if( isset($this->page->validurisegments[$index]->$field) )
						{	
							$uri.= $this->page->validurisegments[$index]->$field.'+';					
						}
					}
				}
				if($uri[strlen($uri)-1] == '+')
				{
					$uri = substr($uri, 0, -1);
				}
				if($uri[strlen($uri)-1] != '/')
				{
					$uri.= '/';
				}				
			}
			ci()->session->set_flashdata('homelist_message', $this->CFG->get_print_message('spacenotfound'));			
			redirect($uri);
		}
		else
			{
				//redirect because last listview is not valid
				redirect('/404');		
			}
		die;	
	}



 ////////////////////////////////////////////
 // AUX ------------------------------- // //
 ////////////////////////////////////////////


	function explode_and_clean_segment($array = array(), $index = 0)
	{
		$segment = null;
		if(isset($array[$index]))
		{
			/* explode */
			$segment = explode("+", $array[$index]);
			foreach ($segment as $seg) 
			{
				/* clean */
				$seg = empty($seg) == false ? $this->slugify_string($seg) : '';
			}
		}
		return $segment;
	}


	/**
	 * [slugify_string description]
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	function slugify_string($text) 
	{
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


	function segment_string_valid($segment, $index)
	{
		return  isset($segment[$index]) && empty($segment[$index]) == false;
	}


	function explode_in_array($string)
	{
		$array = explode(',', $string);
		if(count($array)>1)
		{
			return $array;
		}
		else
		{
			return false;	
		}
	}		

	/**
	 * [gen_imageIDs_array generates array with images IDs]
	 * @param  [string] $field [input]
	 * @return [array]        [array with id values]
	 */
	function gen_imageIDs_array($field = '')
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

}    