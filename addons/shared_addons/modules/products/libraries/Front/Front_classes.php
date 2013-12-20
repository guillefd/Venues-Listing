<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Front Classes
 *
 * For Front process
 *
 * @package			CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Page
{
	public $catid;
	public $validurisegments;
	public $validurifilters;
	public $categoryparams;
	public $searchparams;
	public $view;
	public $result;
	public $map;
	public $media;
	public $message;
	public $htmlfilterdata;
	public $htmlfilter;
	public $pagination;
	public $isajaxrequest;
	// vars to be unsetted after use
	public $urisegments;
	public $urifilters;


	function __construct($id, $segments)
	{
		$this->catid = $id;
		$this->set_urisegments($segments);
		$this->init_attributes();
		// empty object (should be improved)
		$this->result = new stdClass();
		$this->map = new stdClass();		
	}

	private function set_urisegments($segments)
	{
		$this->urisegments = $segments;
	}

	private function init_attributes()
	{
		$this->categoryparams = new stdClass();
		$this->categoryparams->auxiliars = array();		
		$this->categoryparams->tablerow = array(
											'id'=> 0,
											'type_id'=>'',
											'slug'=>'',
											'title'=>'',
											'description'=>'',
											);
		$this->searchparams = array(
									'prodcatname'=>'',
									'cityname'=>'',
									'usetype'=>''
									);

		$this->message = array(
								'result'=>'',
								);	
		$this->pagination = new stdClass();	
		$this->pagination->currentpage = '';
		$this->pagination->link = '';									
		$this->pagination->filtersuri = '';
	}

	/////////////////////////////////////////////
	//PUBLIC SETS -------------------------// //
	/////////////////////////////////////////////

	public function set_urifilters($urifilters)
	{
		$this->urisegments = $urifilters;
	}	

	public function set_validurisegments($segments)
	{
		$this->validurisegments = $segments;
		unset($this->urisegments);
	}

	public function set_validurifilters($filters)
	{
		$this->validurifilters = $filters;
		unset($this->urifilters);
	}	

	public function set_categoryparams($params)
	{
		foreach ($params as $key => $value) 
		{
			$this->categoryparams->tablerow[$key] = $value;
		}
	}

	public function set_categoryauxiliars($params)
	{
		foreach ($params as $key => $value) 
		{
			$this->categoryparams->auxiliars[$key] = $value;
		}
	}	

	public function set_searchparam($params)
	{
		foreach ($params as $key => $value) 
		{
			if(array_key_exists($key, $this->searchparams))
			{
				$this->searchparams[$key] = $value;
			}	
		}
	}

	public function set_view($view)
	{
		$this->view = $view;
	}

	public function set_uri_page_number_requested()
	{
		$page = 1;
		if( isset($this->validurifilters['page']) && is_numeric($this->validurifilters['page']) )
		{	
			$this->validurifilters['page'] = intval($this->validurifilters['page']);
			if(!is_int($this->validurifilters['page']))
			{
				$this->validurifilters['page'] = $page;
			}
		}
		else
			{
				$this->validurifilters['page'] = $page;
			}	
		$this->set_pagination('currentpage', $this->validurifilters['page']);	
	}

	public function set_result($result)
	{
		if(isset($result->items))
		{
			$this->result->list = $result;
		}
		else
			{
				$this->result->item = $result;
			}
	}

	public function set_map_data($arrayvalues)
	{
		foreach($arrayvalues as $key=>$value)
		{
			$this->map->{$key} = $value;
		}
	}

	public function set_htmlfilterdata($data)
	{
		$this->htmlfilterdata = $data;
	}	

	public function set_media($media)
	{
		$this->media = $media;
	}	

	public function set_message($index, $message)
	{
		$this->message[$index] = $message;
	}

	public function set_pagination($field = '', $value = '')
	{
		if( isset($this->pagination->{$field}) )
		{
			$this->pagination->{$field} = $value;
		}
		else
		{
			return false;
		}
	}

	//////////////////////////////////////////////////////
	//PUBLIC GETS ----------------------------------// //
	//////////////////////////////////////////////////////

	public function get_category_param($param)
	{
		return array_key_exists($param, $this->categoryparams->tablerow) ? $this->categoryparams->tablerow[$param] : '';
	}

	public function get_categoryauxiliars($param)
	{
		return array_key_exists($param, $this->categoryparams->auxiliars) ? $this->categoryparams->auxiliars[$param] : '';
	}

	public function get_result()
	{
		return $this->result;
	}

	public function get_list_result()
	{
		return $this->result->list;
	}

	public function get_item_result()
	{
		return $this->result->item;
	}

	public function	get_item_map_center()
	{
		$center['lat'] = $this->get_item_result()->loc_lat;
		$center['lng'] = $this->get_item_result()->loc_lng;		
 		return $center;
	}	

	public function get_validsegment($name)
	{
		foreach($this->validurisegments as $segment)
		{
			if(isset($segment->$name))
			{
				return $segment->$name;
			}
		}
		return null;
	}	

	public function get_usetypeSearch()
	{
		// $result = $this->get_result();
		// return isset($result->list->usetype_search) ? $result->list->usetype_search : '';
		if($segmentvalue = $this->get_validsegment('space_usetype_slug'))
		{
			$usetypes_dd = $this->get_categoryauxiliars('usetypes_slug_dd');
			return $usetypes_dd[$segmentvalue];
		}
		return '';
	}

	public function get_message($index)
	{
		return array_key_exists($index, $this->message) ? $this->message[$index] : '';
	}

	public function get_pagination($field)
	{
		return isset($this->pagination->{$field}) ? $this->pagination->{$field} : ''; 
	}	

}


/////////////////////////////////////////////////////////////////////////////////////////////////////
// CONFIG CLASS --------------------------------------------------------------------------------// //
/////////////////////////////////////////////////////////////////////////////////////////////////////

class Configdata
{
	private $catid; //product category ID
	public $page;
	public $urisegments;
	public $urifilters;
	public $views;
	public $cloudstorage;
	public $map;	
	private $settings; //config settings	

	function __construct($id, $settings)
	{
		$this->catid = $id;
		$this->settings = $settings;
		$this->load_settings();
	}

	private function load_settings()
	{
		//page
		$this->page = new stdClass();
		$this->page->maxrecords = $this->get_value('front_records_per_page');
		//$this->page->emptyresultmessage = $this->get_value('front_print_messages');
		$this->page->print_messages = $this->get_array_value('front_print_messages');
		$this->page->layoutsimages = $this->get_value('front_layouts_images');
		//urisegments
		$this->urisegments = new stdClass();
		$this->urisegments->areawildcard = $this->get_value('front_wildcard_all');
		$this->urisegments->max = $this->get_array_value('front_cat_total_uri_segments');
		$this->urisegments->dbfields = $this->get_array_value('front_segments_db_values_array');
		//views
		$this->views = $this->get_array_value('front_cat_views_index');
		//urifilters
		$this->urifilters = new stdClass();
		$this->urifilters->valid = $this->get_array_value('front_cat_filters_index');
		//cloudstorage
		$this->cloudstorage = new stdClass();
		$this->cloudstorage->cdn = new stdClass();
		$this->cloudstorage->images = new stdClass();		
		$this->cloudstorage->cdn->bucketname = $this->get_array_value('gcs_buckets_list');
		$this->cloudstorage->cdn->uri = $this->get_array_value('gcs_cdn_url_list');
		$this->cloudstorage->images = new stdClass();
		$this->cloudstorage->images->sizes = $this->get_value('gcs_images_size');
	}

	/* private -------------------------------- */

	private function get_value($name)
	{
		if(array_key_exists($name, $this->settings))
		{
			return $this->settings[$name];
		}
		else
			{
				return null;
			}
	}

	private function get_array_value($name)
	{
		if(array_key_exists($name, $this->settings))
		{
			return $this->settings[$name][$this->catid];
		}
		else
			{
				return array();
			}
	}	 

	/* public ----------------------------------- */

	public function set_map_settings()
	{
		$this->map = new stdClass();		
		$this->map->itemdbfields = $this->get_value('front_gmap_result_items_fields');
		$this->map->markerimg = $this->get_value('front_gmap_marker_image');
		$this->map->markerimgHover = $this->get_value('front_gmap_marker_image_hover');
		$this->map->gmapApiKey = $this->get_value('google_maps_v3_api_key');			
	}

	public function get_city_map_data($city_slug)
	{
		if(array_key_exists($city_slug, $this->settings['front_cities_search_index']))
		{
			return $this->settings['front_cities_search_index'][$city_slug];		
		}
		else
			{
				return $this->settings['front_cities_search_index']['default'];	
			}
	}

	public function get_city_param($city_slug, $param)
	{
		if(array_key_exists($city_slug, $this->settings['front_cities_search_index']))
		{
			return $this->settings['front_cities_search_index'][$city_slug][$param];		
		}
		else
			{
				return $this->settings['front_cities_search_index']['default'][$param];	
			}
	}

	public function get_cloudstorage_img_field($size)
	{
		return $this->cloudstorage->images->sizes[$size][4];
	}

	public function get_cloudstorage_imgSizes()
	{
		return $this->cloudstorage->images->sizes;
	}

	public function get_layouts_images()
	{
		return $this->page->layoutsimages;
	}	

	public function get_view_urifields($viewname)
	{
		$urifields = array();
		foreach($this->views as $view)
		{
			if($view['name'] == $viewname)
			{
				$urifields = $view['urifields'];
				break;
			}
		}
		return $urifields;
	}

	public function get_print_message($index = '')
	{
		if(isset($this->page->print_messages[$index]))
		{
			return $this->page->print_messages[$index];
		}
		else
		{
			return '';
		}
	}

}