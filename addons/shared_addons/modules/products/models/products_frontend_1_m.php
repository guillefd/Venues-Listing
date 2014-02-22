<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Categories model
 *
 * @package		Products Module
 * @subpackage	Front
 * @category	Modules
 * @author		Guillermo Dova
 */
class Products_frontend_1_m extends MY_Model
{
	// Products
    protected $t_front = "products_front__1";  

	// front published
	protected $t_facilities = 'products_front__1_facilities';
	protected $t_features = 'products_front__1_features';
	protected $t_layouts = 'products_front__1_layouts';
	protected $t_usetypes = 'products_front__1_usetypes';		


//////////////////////////////////////////////////////////////////////
// HOMELIST ------------------------------------------------- // // //
//////////////////////////////////////////////////////////////////////

	/* space list by params (unique spaces) */
	function get_list_spaces($page, $CFG)
	{		
		$query = '';
		/* aux */
		$all_wildcard = $CFG->urisegments->areawildcard;
		// for homelist_view
		$query.= 'SELECT SQL_CALC_FOUND_ROWS null as rows, dpf.*, 
				 GROUP_CONCAT( DISTINCT ut.usetype_id) space_usetypes_all, 
				 GROUP_CONCAT( DISTINCT dpf.space_usetype_id ) space_usetypes_published,
				 GROUP_CONCAT( DISTINCT dpf.front_version ) front_version_published '
				.'FROM `default_'.$this->t_front.'` dpf '
				.'JOIN `default_products_front__1_usetypes` as ut ON ut.front_space_id=dpf.id  ';
		/* SEGMENTS --------------------------------------------------------------------*/					 
		$query.= 'WHERE `prod_cat_slug` = "'.$page->validurisegments[1]->prod_cat_slug.'" '
				.'AND `loc_city_slug` = "'.$page->validurisegments[2]->loc_city_slug.'" ';
		/* area-slug */		
		if(array_key_exists('loc_area_slug', $page->validurisegments[2]) 
			&& $page->validurisegments[2]->loc_area_slug != $all_wildcard)
		{
			$query.='AND `loc_area_slug` = "'.$page->validurisegments[2]->loc_area_slug.'" ';
		}				
		/* FILTERS --------------------------------------------------------------------*/
		/* capacity */
		if(array_key_exists('capacity', $page->validurifilters))
		{
			$query.=$this->_aux_get_list_capacity_MANUAL($page->validurifilters['capacity']);				
		}
		/* loctypes */
		if(array_key_exists('loctypes', $page->validurifilters))
		{
			$query.=$this->_aux_get_loctypes_condition_MANUAL($page->validurifilters['loctypes']);				
		}
		$query.=' GROUP BY dpf.space_id';
		$query.=' ORDER BY name ASC';		
		//limit
		if($page->isajaxrequest)
		{
			$limit = $CFG->page->maxrecords;			
			$offset = ($limit * $page->validurifilters['page']) - $limit;
		}	
		else
			{
				$limit = $CFG->page->maxrecords * $page->validurifilters['page'];				
				$offset = 0;	
			}
		$query.=' LIMIT '.$offset.', '.$limit;
		/* --------- RUN QUERY ----------- */
		//print_r($query); die;
 		$q = $this->db->query($query);		
		$result = new stdClass();
		if ($q->num_rows()>0)
		{
			$result->offset = $offset;					
			$result->numrows = $q->num_rows();
			$result->items = $q->result();
			$result->totrows = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;		
		}
		else
			{
				$result->offset = 0;						
				$result->numrows = 0;
				$result->items = array();
				$result->totrows = 0;			
			}					
		return $result;		
	}


	/* products list by params */
	function get_list_products($page, $CFG)
	{
		$query = '';		
		/* aux */
		$all_wildcard = $CFG->urisegments->areawildcard;
		// for homelist_view	
		$query.= 'SELECT SQL_CALC_FOUND_ROWS null as rows, dpf.*, 
				  GROUP_CONCAT( DISTINCT fac.facility_id ) space_facilities_list, 
				  GROUP_CONCAT( DISTINCT ftr.default_feature_id ) space_features_list '
				.'FROM `default_'.$this->t_front.'` dpf '
				.'LEFT JOIN `default_'.$this->t_facilities.'` as fac ON fac.front_space_id = dpf.id ' 
				.'LEFT JOIN `default_'.$this->t_features.'` as ftr ON ftr.front_space_id = dpf.id ';
		/* SEGMENTS --------------------------------------------------------------------*/	
		/* cat-slug */
		$query.= 'WHERE '
				 .'prod_cat_slug = "'.$page->validurisegments[1]->prod_cat_slug.'" '; 
		/* space-usetype */
		if(array_key_exists('space_usetype_slug', $page->validurisegments[1]))
		{
			$query.= 'AND space_usetype_slug = "'.$page->validurisegments[1]->space_usetype_slug.'" ';					
		}
		/* city-slug */
		$query.= 'AND loc_city_slug = "'.$page->validurisegments[2]->loc_city_slug.'" ';
		/* area-slug */		
		if(array_key_exists('loc_area_slug', $page->validurisegments[2]) 
			&& $page->validurisegments[2]->loc_area_slug != $all_wildcard)
		{
			$query.='AND loc_area_slug = "'.$page->validurisegments[2]->loc_area_slug.'" ';
		}				 	
		/* FILTERS --------------------------------------------------------------------*/
		/* capacity */
		if(array_key_exists('capacity', $page->validurifilters))
		{
			$query.=$this->_aux_get_list_capacity_MANUAL($page->validurifilters['capacity']);				
		}
		/* loctypes */
		if(array_key_exists('loctypes', $page->validurifilters))
		{
			$query.=$this->_aux_get_loctypes_condition_MANUAL($page->validurifilters['loctypes']);				
		}			
		$query.=' GROUP BY dpf.id, dpf.id ';
		$query.=' ORDER BY name ASC';		
		//limit
		if($page->isajaxrequest)
		{
			$limit = $CFG->page->maxrecords;			
			$offset = ($limit * $page->validurifilters['page']) - $limit;
		}	
		else
			{
				$limit = $CFG->page->maxrecords * $page->validurifilters['page'];				
				$offset = 0;	
			}
		$query.=' LIMIT '.$offset.', '.$limit;
		/* --------- RUN QUERY ----------- */
		//print_r($query); die;
 		$q = $this->db->query($query);		
		$result = new stdClass();		
		if ($q->num_rows()>0)
		{
			$result->offset = $offset;			
			$result->numrows = $q->num_rows();
			$result->items = $q->result();
			$result->totrows = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
			$result->usetype_search = $q->row()->space_usetype;	
		}
		else
			{
				$result->offset = 0;
				$result->numrows = 0;
				$result->items = array();
				$result->totrows = 0;			
				$result->usetype_search ='';					
			}	
		return $result;
	}


//////////////////////////
// SPACE  ------------/ //
//////////////////////////


	function get_item_space($page, $CFG)
	{
		$space = null;
		$query = 'SELECT dpf.*, 
				 GROUP_CONCAT( DISTINCT ut.usetype_id) space_usetypes_all, 
				 GROUP_CONCAT( DISTINCT dpf.space_usetype_id ) space_usetypes_published,
				 GROUP_CONCAT( DISTINCT dpf.front_version ) front_version_published, 
				 GROUP_CONCAT( DISTINCT fac.facility_id ) space_facilities_list '
				.'FROM `default_'.$this->t_front.'` dpf '
				.'JOIN `default_products_front__1_usetypes` as ut ON ut.front_space_id=dpf.id  '
				.'JOIN `default_products_front__1_facilities` as fac ON fac.front_space_id=dpf.id  ';
		/* SEGMENTS --------------------------------------------------------------------*/					 
		$query.= 'WHERE `prod_cat_slug` = "'.$page->validurisegments[1]->prod_cat_slug.'" '
				.'AND `loc_city_slug` = "'.$page->validurisegments[2]->loc_city_slug.'" '
				.'AND `loc_slug` = "'.$page->validurisegments[3]->loc_slug.'" '
				.'AND `space_slug` = "'.$page->validurisegments[4]->space_slug.'" ';
		$query.=' GROUP BY dpf.space_id';								
 		$q = $this->db->query($query);	
		if($q->num_rows() > 0)
		{			
			$space = $this->process_item_space_result($q->result());
			//start transaction
			$this->db->trans_start();
				$space->data_layouts = $this->get_front_layouts_syncindex($space->id);					
			$this->db->trans_complete();	
			if($this->db->trans_status() === true)
			{
				return $space;
			}
			else
				{
					return null;
				}
		}
		else
			{
				return false;
			}
	}

	function process_item_space_result($result)
	{
		$space = $result[0];		
		//if more than one, save other posts data
		if(count($result)>1)
		{
			foreach($result as $row)
			{
				$space->other_posts[] = array(
												'space_usetype_slug'=>$row->space_usetype_slug,
												'space_usetype'=>$row->space_usetype,
												'front_version'=>$row->front_version,
												'cloud_th_images'=>$row->cloud_th_images
											); 
			}
		}
		return $space;
	}	


	//////////////////////////////
	//PRODUCT ------------- // //
	//////////////////////////////

	function get_item_product($page, $CFG)
	{
		$product = null;
		$query = 'SELECT dpf.*, 
				 GROUP_CONCAT( DISTINCT ut.usetype_id) space_usetypes_all, 
				 GROUP_CONCAT( DISTINCT dpf.space_usetype_id ) space_usetypes_published,
				 GROUP_CONCAT( DISTINCT dpf.front_version ) front_version_published, 
				 GROUP_CONCAT( DISTINCT fac.facility_id ) space_facilities_list '
				.'FROM `default_'.$this->t_front.'` dpf '
				.'JOIN `default_products_front__1_usetypes` as ut ON ut.front_space_id=dpf.id  '
				.'JOIN `default_products_front__1_facilities` as fac ON fac.front_space_id=dpf.id  ';
		/* SEGMENTS --------------------------------------------------------------------*/					 
		$query.= 'WHERE `prod_cat_slug` = "'.$page->validurisegments[1]->prod_cat_slug.'" '
				.'AND `space_usetype_slug` = "'.$page->validurisegments[1]->space_usetype_slug.'" '		
				.'AND `loc_city_slug` = "'.$page->validurisegments[2]->loc_city_slug.'" '
				.'AND `loc_slug` = "'.$page->validurisegments[3]->loc_slug.'" '
				.'AND `space_slug` = "'.$page->validurisegments[4]->space_slug.'" '
				.'AND `front_version` = "'.$page->validurisegments[4]->front_version.'" ';
		$query.=' GROUP BY dpf.space_id';								
 		$q = $this->db->query($query);	
		if($q->num_rows() > 0)
		{			
			$product = $q->row();
			//start transaction
			$this->db->trans_start();
				$product->data_layouts = $this->get_front_layouts_syncindex($product->id);
				$product->data_facilities = $this->get_front_facilities($product->id, true);
				$product->data_usetypes = $this->get_front_usetypes_syncindex($product->id);									
				$product->data_features = $this->get_front_features($product->id);
			$this->db->trans_complete();	
			if($this->db->trans_status() === true)
			{
				return $product;
			}
			else
				{
					return null;
				}
		}
		else
			{
				return false;
			}
	}



////////////////////////
// FRONT _ AUX        //
////////////////////////

	function get_front_layouts($front_id = 0)
	{
		$this->db->select('*');
		$q = $this->db->get_where($this->t_layouts, array('front_space_id'=>$front_id));
		return $q->result();
	}

	function get_front_layouts_syncindex($front_id = 0)
	{
		$this->db->select('*');
		$q = $this->db->get_where($this->t_layouts, array('front_space_id'=>$front_id));
        $vec = array();
        foreach ($q->result() as $key => $obj) 
        {
            $vec[$obj->layout_id] = $obj;
        }
        return $vec;		
	}	

	function get_front_features($front_id = 0)
	{
		$this->db->select('*');
		$q = $this->db->get_where($this->t_features, array('front_space_id'=>$front_id));
		return $q->result();
	}

	function get_front_facilities($front_id = 0, $listformat = false)
	{
		$this->db->select('*');
		$q = $this->db->get_where($this->t_facilities, array('front_space_id'=>$front_id));
		$result = $q->result();
		if($listformat)
		{
			return $this->get_elements_simple_list_array($result, 'facility_id');
		}
		else
		{
			return $result;
		}
	}			

	function get_front_usetypes($front_id = 0)
	{
		$this->db->select('*');
		$q = $this->db->get_where($this->t_usetypes, array('front_space_id'=>$front_id));
		return $q->result();
	}

	function get_front_usetypes_syncindex($front_id = 0)
	{
		$this->db->select('*');
		$q = $this->db->get_where($this->t_usetypes, array('front_space_id'=>$front_id));
        $vec = array();
        foreach ($q->result() as $key => $obj) 
        {
            $vec[$obj->usetype_id] = $obj;
        }
        return $vec;
	}


//////////////////////////////////////////////////
// AUX --------------------------------------// //
//////////////////////////////////////////////////

	function get_elements_simple_list_array($result = null, $field = null)
	{
		if($result === null || $field === null)
		{
			return array();
		}
		$array = array();
		foreach ($result as $item) 
		{
			array_push($array, $item->$field);	
		}
		return $array;
	}


	function _aux_get_list_capacity($urifilter_capacity)
	{
		switch($urifilter_capacity)
		{
			case '1-5':
							$this->db->where('space_max_capacity <', 16);
							break;
			case '5-10':
							$this->db->where('space_max_capacity >', 4);
							$this->db->where('space_max_capacity <', 25);
							break;				
			case '10-20':
							$this->db->where('space_max_capacity >', 9);
							$this->db->where('space_max_capacity <', 40);
							break;
			case '20-30':
							$this->db->where('space_max_capacity >', 19);
							$this->db->where('space_max_capacity <', 60);
							break;
			case '30-50':
							$this->db->where('space_max_capacity >', 29);
							$this->db->where('space_max_capacity <', 90);
							break;				
			case '50plus':
							$this->db->where('space_max_capacity >', 49);
							break;				
		}
	}


	function _aux_get_list_capacity_MANUAL($urifilter_capacity)
	{
		$query = '';
		switch($urifilter_capacity)
		{
			case '1-5':
							$query.='AND space_max_capacity < 16 ';							
							break;
			case '5-10':
							$query.='AND space_max_capacity > 4 ';
							$query.='AND space_max_capacity < 25 ';														
							break;				
			case '10-20':
							$query.='AND space_max_capacity > 9 ';
							$query.='AND space_max_capacity < 40 ';	
							break;
			case '20-30':
							$query.='AND space_max_capacity > 19 ';
							$query.='AND space_max_capacity < 60 ';								
							break;
			case '30-50':
							$query.='AND space_max_capacity > 29 ';
							$query.='AND space_max_capacity < 90 ';								
							break;				
			case '50plus':
							$query.='AND space_max_capacity > 49 ';
							break;				
		}
		return $query;
	}	


	function _aux_get_loctypes_condition($loctypes)
	{
		if(is_array($loctypes))
		{
			$string = '';
			$string.= ' (loc_type_id = '.$loctypes[0];		
			for($i=1; $i<=count($loctypes)-1; $i++)
			{
				$string.= ' OR loc_type_id = '.$loctypes[$i];
			}	
			$string.= ')';
			$this->db->where($string);	
		}
		else
			{
				if($loctypes!='')
				{
					$this->db->where('loc_type_id', $loctypes);					
				}
			}
	}


	function _aux_get_loctypes_condition_MANUAL($loctypes)
	{
		$query = '';
		if(is_array($loctypes))
		{
			$string = '';
			$string.= 'AND (loc_type_id = '.$loctypes[0];		
			for($i=1; $i<=count($loctypes)-1; $i++)
			{
				$string.= ' OR loc_type_id = '.$loctypes[$i];
			}	
			$string.= ') ';
			$query.= $string;
		}
		else
			{
				if($loctypes!='')
				{
					$query.='AND loc_type_id = '.$loctypes;										
				}
			}
		return $query;	
	}

/////////////////////////////////////////////////////
// DISTINCT QUERYS ---------------------------- // //
/////////////////////////////////////////////////////



	function get_front_distinct($field = null)
	{
		if($field !== null)
		{
			$this->db->select($field);
			$this->db->distinct();
			$q = $this->db->get($this->t_front);
			if($q->num_rows() > 0 )
			{
				return $q->result();
			}
			else
				{
					return array();
				}
		}
		else
			{
				return null;
			}
	}



//////////////////////////////////////////
// MESSAGE QUERYS ------------------/// //
//////////////////////////////////////////

	function MSG_get_item_space($data = array())
	{
		if(empty($data))
		{
			return false;
		}
		$data = array(
						'prod_cat_slug' => $data['prod_cat_slug'],
						'loc_city_slug' => $data['loc_city_slug'],
						'loc_slug' => $data['loc_slug'],
						'space_slug' => $data['space_slug'],	
					 );
		$q = $this->db->get_where($this->t_front, $data);
		if($q->num_rows() > 0)
		{			
			return $q->row();
		}
		else
			{
				return false;
			}
	}

}