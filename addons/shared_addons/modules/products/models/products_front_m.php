<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Categories model
 *
 * @package		Products Module
 * @subpackage	Front
 * @category	Modules
 * @author		Guillermo Dova
 */
class Products_front_m extends MY_Model
{
	// Products
	protected $_t_products = 'products';	
	protected $t_prod_front_draft_PREF = "products_front_draft__"; 
    protected $t_prod_front_PREF = "products_front__";  

	// Draft published, for validating
	protected $_t_pf_d__1_facilities = 'products_front_draft__1_facilities';
	protected $_t_pf_d__1_features = 'products_front_draft__1_features';
	protected $_t_pf_d__1_layouts = 'products_front_draft__1_layouts';			
	protected $_t_pf_d__1_usetypes = 'products_front_draft__1_usetypes';	
	// front published
	protected $_t_pf__1_facilities = 'products_front__1_facilities';
	protected $_t_pf__1_features = 'products_front__1_features';
	protected $_t_pf__1_layouts = 'products_front__1_layouts';
	protected $_t_pf__1_usetypes = 'products_front__1_usetypes';	


////////////////////////////////
// FUNCTIONS - PER PRODUCT DRAFT TYPES //
////////////////////////////////

	/**
	 * [insert_front_draft Insert product draft]
	 * @param  [type] $array [description]
	 * @return [type]        [description]
	 */
	function insert_product_front_draft($product, $typeid)
	{
		//init transaction
		$this->db->trans_start();
			//insert product info
			$data = array(
						'prod_id'=>$product->product_id,
						'prod_cat_id'=>$product->category_id,
						'prod_cat_slug'=>$product->category->slug,
						'account_id'=>$product->account_id,
						'seller_account_id'=>$product->seller_account_id,
						'name'=>$product->name,
						'name_slug'=>$product->slug,
						'intro'=>$product->intro,
						'body'=>$product->body,
						'images'=>$product->images,
						'loc_id'=>$product->location->id,
						'loc_name'=>$product->location->name,
						'loc_type_id'=>$product->location->location_type_id,
						'loc_type'=>$product->location->location_type,												
						'loc_type_slug'=>$product->location->location_type_slug,	
						'loc_cityid'=>$product->location->CityID,
						'loc_city'=>$product->located->city,
						'loc_city_lat'=>$product->located->city_lat,
						'loc_city_lng'=>$product->located->city_lng,
						'loc_city_slug'=>$product->located->city_slug,
						'loc_area'=>$product->location->area,
						'loc_area_slug'=>$product->located->area_slug,
						'loc_country'=>$product->located->country,
						'loc_country_id'=>$product->located->country_id,
						'loc_country_iso3'=>$product->located->country_iso3,
						'loc_intro'=>$product->location->intro,
						'loc_description'=>$product->location->description,
						'loc_address'=>$product->location->address_l1,
						'loc_geo_street_name'=>$product->location->geo_street_name,
						'loc_geo_street_number'=>$product->location->geo_street_number,
						'loc_slug'=>$product->location->location_slug,
						'loc_lat'=>$product->location->Latitude,
						'loc_lng'=>$product->location->Longitude,
						'loc_phone_area'=>$product->location->phone_area_code,
						'loc_phone'=>$product->location->phone,
						'loc_mobile'=>$product->location->mobile,
						'loc_email'=>$product->location->email,
						'loc_social_accounts'=>$product->location->chatSocial_accounts,
						'space_id'=>$product->space->space_id,
						'space_name'=>$product->space->name,
						'space_denomination'=>$product->space->denomination,
						'space_denomination_id'=>$product->space->denomination_id,
						'space_description'=>$product->space->description,
						'space_level'=>$product->space->level,
						'space_width'=>$product->space->width,
						'space_height'=>$product->space->height,
						'space_length'=>$product->space->length,
						'space_square_mt'=>$product->space->square_mt,
						'space_shape_id'=>$product->space->shape_id,
						'space_shape'=>$product->space->shape,
						'space_max_capacity'=>$product->space_max_capacity,
						'space_usetype_id'=>$product->space_usetype_id,
						'space_usetype'=>$product->space_usetype,												
						'space_usetype_slug'=>$product->space_usetype_slug,	
						'space_slug'=>$product->space->space_slug,
						'draft_version'=>now(),
						'created_on' =>now(),
						'updated_on' =>now(),
						'product_updated_on' => empty($product->updated_on) ? $product->created_on : $product->updated_on,
						'location_updated_on' => empty($product->location->updated_on) ? $product->location->created_on : $product->location->updated_on,
						'space_updated_on' => empty($product->space->updated_on) ? $product->space->created_on : $product->space->updated_on,						
						'author_id'=>$this->current_user->id,
						);					
			$this->db->insert($this->t_prod_front_draft_PREF.$typeid, $data);
			$id = $this->db->insert_id();
			//insert product_facilities
			unset($data);
			foreach($product->facilities as $facility)
			{
				$item->front_space_id = $id;
				$item->facility_id = intval($facility);
				$data[] = $item;
				unset($item);
			}
			$this->db->insert_batch($this->t_prod_front_draft_PREF.$typeid.'_facilities', $data);
			//insert product_usetypes
			unset($data);
			foreach($product->usetypes as $usetype)
			{
				$item->front_space_id = $id;
				$item->usetype_id = intval($usetype);
				$data[] = $item;
				unset($item);
			}
			$this->db->insert_batch($this->t_prod_front_draft_PREF.$typeid.'_usetypes', $data);
     		//insert product_features
			unset($data);
			foreach($product->layouts_array as $layout=>$capacity)
			{
				$item->front_space_id = $id;
				$item->layout_id = $layout;
				$item->capacity = $capacity;
				$data[] = $item;
				unset($item);
			}
			$this->db->insert_batch($this->t_prod_front_draft_PREF.$typeid.'_layouts', $data);
			//insert product_layouts
			unset($data);
			foreach($product->features as $feature)
			{
				$item->front_space_id = $id;
				$item->default_feature_id = $feature->default_f_id;
				$item->description = $feature->description;
				$item->value = $feature->value;
				$item->is_optional = $feature->isOptional;			
				$item->name = $feature->name;						
				$item->usageunit = $feature->usageunit;							
				$data[] = $item;
				unset($item);				
			}
			$this->db->insert_batch($this->t_prod_front_draft_PREF.$typeid.'_features', $data);			

		$this->db->trans_complete();
		// end
		return $this->db->trans_status();
	}	


	function soft_delete_front_draft($prod_id = 0, $typeid = 0)
	{
		$data = array(
						'deleted'=>1, 
						'updated_on'=>now()
					 );
		$this->db->where('prod_id', $prod_id);
		return $this->db->update($this->t_prod_front_draft_PREF.$typeid, $data); 		
	}


    function get_front_by_prodid($prod_id = 0, $typeid = 0)
	{
		switch($typeid)
		{
			case 1:		
					$this->db->where('prod_id', $prod_id);
					$q = $this->db->get($this->t_prod_front_PREF.$typeid); 
					if ($q->num_rows()>0)
					{
						return $q->row();
					}
					else
						{
							return null;
						}
					break;

			default: return null;
		}	
	}


    function get_front_by_id($id = 0, $typeid = 0)
	{
		switch($typeid)
		{
			case 1:		
					$this->db->where('id', $id);
					$q = $this->db->get($this->t_prod_front_PREF.$typeid); 
					if ($q->num_rows()>0)
					{
						return $q->row();
					}
					else
						{
							return null;
						}
					break;

			default: return null;
		}	
	}

    function delete_front($front_id = 0, $typeid = 0)
	{
		switch($typeid)
		{
			case 1:		
					$this->db->trans_start();			
						$this->db->where('id', $front_id);
						$this->db->delete($this->t_prod_front_PREF.$typeid); 
						$tables = array($this->_t_pf__1_facilities, $this->_t_pf__1_features, $this->_t_pf__1_layouts, $this->_t_pf__1_usetypes);
						$this->db->where('front_space_id', $front_id);
						$this->db->delete($tables);							
					$this->db->trans_complete();
					if ($this->db->trans_status() === FALSE)
					{
						return false;
					}
					else
						{
							return true;
						}
					break;

			default: return false;
		}	
	}


	/**
	 * [get_front_draft_space ]
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	function get_front_draft($draft_id = 0, $typeid = 0)
	{
		$item = null;
		$this->db->select('*');
		$q = $this->db->get_where($this->t_prod_front_draft_PREF.$typeid, array('id'=>$draft_id));
		if($q->num_rows()>0)
		{	
			$item = $q->row();
			switch($typeid)
			{
				case 1:
						// get layouts, features, facilites.	
						$this->db->trans_start();
							$item->facilities = $this->products_front_m->get_front_draft_space_facilities($draft_id);
							$item->usetypes = $this->products_front_m->get_front_draft_space_usetypes($draft_id);
							$item->layouts = $this->products_front_m->get_front_draft_space_layouts($draft_id);
							$item->features = $this->products_front_m->get_front_draft_space_features($draft_id);						
						$this->db->trans_complete();
						if ($this->db->trans_status() === FALSE)
						{
							return array();
						}
						break;

				default: 
						return null;			
			}
		}			
		return $item;
	}

	/**
	 * [get_all_front_draft Get all active products, that have front-draft. And counts versions.]
	 * @return [type] [description]
	 */
	function search_draft($mode, $data = array())
	{
        if (array_key_exists('prod_type_id', $data))
        {
            $typeid = $data['prod_type_id'];
            switch($typeid)
            {
            	case 1: 
            			$query = $this->query_search_draft($typeid, $data);
            			break;

            	default:
            			return false;		
            }
        } 		
        else
	        {
            	return false;	        	
	        }	        
        //fire query
        $q = $this->db->query($query);         
        if($mode =='counts')
        {                
            return $q->num_rows;
        }
        else
            {
                return $q->result();
            }	        		
	}


	/**
	 * [query_search_draft query generator]
	 * @param  integer $typeid [description]
	 * @param  [type]  $data   [description]
	 * @return [type]          [description]
	 */
	function query_search_draft($typeid = 0, $data)
	{	
	    switch($typeid)
	    {
	    	case 1: 		
				   $query = 'SELECT '.
							'COUNT(pfds.id) AS draft_count,
							pfds.id AS draft_id,
							pfds.draft_version AS current_version,
							pfds.prod_id,
							pfds.prod_cat_slug AS draft_prod_cat_slug, 
							pfds.prod_cat_id AS draft_prod_cat_id, 
							pfds.account_id AS draft_account_id, 
							pfds.seller_account_id AS draft_seller_account_id, 
							pfds.space_id AS draft_space_id, 
							pfds.space_name AS draft_space_name, 
							pfds.loc_name AS draft_loc_name, 
							pfds.space_denomination AS draft_space_denomination, 
							pfds.loc_id AS draft_loc_id, 
							pfds.intro AS draft_prod_intro, 
							pfds.name AS prod_name,
							front.id AS front_id, 
							front.front_version AS front_version '.							 
							// from table draft_spaces
							' FROM default_'.$this->t_prod_front_draft_PREF.$typeid.' AS pfds '.
							// Inner join most recent draft
							'INNER JOIN (
							SELECT
							MAX(draft_version) AS max_version,
							id AS draft_id,
							prod_id AS draft_prod_id
							FROM default_products_front_draft__1
							GROUP BY prod_id
							) pfdsm ON draft_prod_id = pfds.prod_id AND max_version = pfds.draft_version '.							
							 // join table products - only records that match both tables
				 			 ' LEFT OUTER JOIN default_'.$this->t_prod_front_PREF.$typeid.' as front ON draft_prod_id = front.prod_id  '.
				 			 // exclude deleted products 
				 			 ' WHERE pfds.deleted = 0';			 			 
				 			 //' AND MAX(pfds.draft_version)';
				            if (array_key_exists('prod_cat_id', $data))
				            {
				                $query.= ' AND `prod_cat_id` = '.$data['prod_cat_id'];
				            } 			 
				            if (array_key_exists('keywords', $data))
				            {
				                $query.= " AND (pfds.name LIKE '%".$data['keywords']."%')";
				            }
				            //STATEVIEW
				            if (array_key_exists('stateview', $data))
				            {
				                switch($data['stateview'])
				                {
				                	case 'offline': $query.= " AND front.id IS NULL";
				                					break;	
				                	
				                	case 'update': $query.= " AND pfds.draft_version <> front.front_version";
				                					break;	

				                	case 'requestaction': $query.= " AND (front.id IS NULL || pfds.draft_version <> front.front_version)";
				                					break;	
				                } 
				            }				            					            
				 			 // for COUNT draft versions / get last
				 			$query.= ' GROUP BY pfds.prod_id';
				            //Ordenar ultimo publicado
				            $query.= " ORDER BY pfds.id DESC";		                        
					        // Limit the results based pagination
					        if (isset($data['pagination']['offset']) && isset($data['pagination']['limit']))
					        {
					            $query.= " LIMIT ".$data['pagination']['offset'].", ".$data['pagination']['limit'];;
					        }        
					            elseif (isset($data['pagination']['limit']))
					            {    
					                $query.= ", ".$data['pagination']['limit'];
					            } 
				            break;

			default: 
					$query = null;	                
		}					
		return $query;	
	}


////////////////////////
// DRAFT SPACE _ AUX  //
////////////////////////

	function get_front_draft_space_layouts($draft_id = 0)
	{
		$this->db->select('*');
		$q = $this->db->get_where($this->_t_pf_d__1_layouts, array('front_space_id'=>$draft_id));
		return $q->result();
	}

	function get_front_draft_space_features($draft_id = 0)
	{
		$this->db->select('*');
		$q = $this->db->get_where($this->_t_pf_d__1_features, array('front_space_id'=>$draft_id));
		return $q->result();
	}

	function get_front_draft_space_facilities($draft_id = 0)
	{
		$this->db->select('*');
		$q = $this->db->get_where($this->_t_pf_d__1_facilities, array('front_space_id'=>$draft_id));
		return $q->result();
	}	

	function get_front_draft_space_usetypes($draft_id = 0)
	{
		$this->db->select('*');
		$q = $this->db->get_where($this->_t_pf_d__1_usetypes, array('front_space_id'=>$draft_id));
		return $q->result();
	}

////////////////////////////////
// FUNCTIONS - PER PRODUCT FRONT TYPES //
////////////////////////////////

	function insert_product_front($draft_id = 0, $type_id = 0, $gsc_array_bysize = array())
	{
        switch($type_id)
        {
        	case 1: 
        			$draft = $this->get_front_draft($draft_id, $type_id);  			
        			if(empty($draft) == false)
    				{
  						$front_table_fields = $this->db->list_fields($this->t_prod_front_PREF.$type_id);    					
    					$draft_array = $this->prepare_front_space($draft, $front_table_fields, $gsc_array_bysize);
    				}
    				else
	    				{
	    					return false;
	    				}
					//init transaction
					$this->db->trans_start();
						/* insert front */
							$this->db->insert($this->t_prod_front_PREF.$type_id, $draft_array);
							$front_id = $this->db->insert_id();	
						/* insert front facilities */	
							$data = $this->prepare_front_space_facilities($draft->facilities, $front_id);
							$this->db->insert_batch($this->t_prod_front_PREF.$type_id.'_facilities', $data);
							unset($data);
						/* insert front usetypes */	
							$data = $this->prepare_front_space_usetypes($draft->usetypes, $front_id);
							$this->db->insert_batch($this->t_prod_front_PREF.$type_id.'_usetypes', $data);
							unset($data);
						/* insert front features */	
							$data = $this->prepare_front_space_features($draft->features, $front_id);
							$this->db->insert_batch($this->t_prod_front_PREF.$type_id.'_features', $data);
							unset($data);
						/* insert front layouts */	
							$data = $this->prepare_front_space_layouts($draft->layouts, $front_id);
							$this->db->insert_batch($this->t_prod_front_PREF.$type_id.'_layouts', $data);							
							unset($data);
					$this->db->trans_complete();
					return $this->db->trans_status();						
 	       			break;

        	default:
        			return false;		
        }	
	}


	function update_product_front($draft_id = 0, $type_id = 0, $front_id = 0, $gsc_array_bysize = array())
	{
        switch($type_id)
        {
        	case 1: 
        			/* get draft data*/
        			$draft = $this->get_front_draft($draft_id, $type_id);  			
        			if(empty($draft) == false)
    				{
    					/* get front table field list */
  						$front_table_fields = $this->db->list_fields($this->t_prod_front_PREF.$type_id);   
  						/*  */ 					
    					$draft_array = $this->prepare_front_space($draft, $front_table_fields, $gsc_array_bysize);
    				}
    				else
	    				{
	    					return false;
	    				}
					//init transaction
					$this->db->trans_start();
						/* delete aux tables records */
							$tables = array($this->_t_pf__1_facilities, $this->_t_pf__1_features, $this->_t_pf__1_layouts, $this->_t_pf__1_usetypes);
						    $this->db->where('front_space_id', $front_id);
						    $this->db->delete($tables);
						/* update front */						
						    $this->db->where('id', $front_id);										    
							$this->db->update($this->t_prod_front_PREF.$type_id, $draft_array);				
						/* insert front facilities */	
							$data = $this->prepare_front_space_facilities($draft->facilities, $front_id);
						 	$this->db->insert_batch($this->t_prod_front_PREF.$type_id.'_facilities', $data);
							unset($data);
						/* insert front usetypes */	
							$data = $this->prepare_front_space_usetypes($draft->usetypes, $front_id);				
							$this->db->insert_batch($this->t_prod_front_PREF.$type_id.'_usetypes', $data);
							unset($data);							
						/* insert front features */	
							$data = $this->prepare_front_space_features($draft->features, $front_id);
							$this->db->insert_batch($this->t_prod_front_PREF.$type_id.'_features', $data);
							unset($data);
						/* insert front layouts */	
							$data = $this->prepare_front_space_layouts($draft->layouts, $front_id);
							$this->db->insert_batch($this->t_prod_front_PREF.$type_id.'_layouts', $data);							
							unset($data);
					$this->db->trans_complete();
					return $this->db->trans_status();						
 	       			break;

        	default:
        			return false;		
        }	
	}


	function prepare_front_space($draft, $front_table_fields, $gsc_array_bysize)
	{
		//images
		$draft->cloud_th_images = $gsc_array_bysize['th'];
		$draft->cloud_sm_images = $gsc_array_bysize['sm'];
		$draft->cloud_md_images = $gsc_array_bysize['md'];
		$draft->cloud_bg_images = $gsc_array_bysize['bg'];
		$draft->cloud_lg_images = $gsc_array_bysize['lg'];								
		//update and unset values for front
		$draft->front_version = $draft->draft_version;
		unset($draft->id);
		unset($draft->draft_version);		
		unset($draft->deleted);
		$draft->author_id = $this->current_user->id;	
		$draft->created_on = now();
		unset($draft->updated_on);
		unset($draft->product_updated_on);
		unset($draft->location_updated_on);
		unset($draft->space_updated_on);
		//unset id from field array
		array_shift($front_table_fields);
		//array to store front
		$front_array = array();
		foreach ($front_table_fields as $field) 
		{
			$front_array[$field] = $draft->{$field};
		}								
		return $front_array; 
	}

	function prepare_front_space_facilities($facilities = array(), $id = 0)
	{
		$data = array();
		foreach($facilities as $facility)
		{
			$item->front_space_id = $id;
			$item->facility_id = $facility->facility_id;
			$data[] = $item;
			unset($item);
		}
		return $data;
	}

	function prepare_front_space_usetypes($usetypes = array(), $id = 0)
	{
		$data = array();
		foreach($usetypes as $usetype)
		{
			$item->front_space_id = $id;
			$item->usetype_id = $usetype->usetype_id;
			$data[] = $item;
			unset($item);
		}
		return $data;
	}


	function prepare_front_space_features($features = array(), $id = 0)
	{
		$data = array();
		foreach($features as $feature)
		{
			$item->front_space_id = $id;
			$item->default_feature_id = $feature->default_feature_id;
			$item->description = $feature->description;
			$item->value = $feature->value;
			$item->is_optional = $feature->is_optional;			
			$item->name = $feature->name;						
			$item->usageunit = $feature->usageunit;							
			$data[] = $item;
			unset($item);	
		}
		return $data;
	}


	function prepare_front_space_layouts($layouts = array(), $id = 0)
	{
		$data = array();
		foreach($layouts as $layout)
		{
			$item->front_space_id = $id;
			$item->layout_id = $layout->layout_id;
			$item->capacity = $layout->capacity;
			$data[] = $item;
			unset($item);
		}
		return $data;
	}	


}	