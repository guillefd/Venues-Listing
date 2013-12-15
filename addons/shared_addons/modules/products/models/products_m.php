<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Products_m extends MY_Model {

//////////////////////////////////////////////////////////
// FEATURES ::::::::::::::::::::::::::::::::::::::::::: //
//////////////////////////////////////////////////////////

	public function __construct()
	{		
		parent::__construct();
		$this->t_products = 'products';
		$this->t_products_f = 'products_features';
		$this->t_products_f_defaults = 'products_features_defaults';
	}

	
	function insert_features($array)
	{
		return $this->db->insert_batch($this->t_products_f, $array); 
	}


	function get_all_features_by_id($product_id)
	{
		$this->db->select('products_features.*, pfd.name, pfd.usageunit_id, ui.name as usageunit')
		 		 ->join('products_features_defaults as pfd','products_features.default_feature_id = pfd.id')
		 		 ->join('products_usageunit as ui','pfd.usageunit_id = ui.id');
		$this->db->where('product_id', $product_id);
		$this->db->order_by('is_optional', 'ASC');
		return $this->db->get($this->t_products_f)->result();
	}	


	function delete_products_features($product_id)
	{
		return $this->db->delete('products_features', array('product_id' => $product_id)); 
	}	


/////////////////////////////////////////////////////////
// PRODUCT ::::::::::::::::::::::::::::::::::::::::::: //
/////////////////////////////////////////////////////////

	function get($id, $deleted = 0)
	{
		return $this->db->select('products.*, profiles.display_name')
					->join('profiles', 'profiles.user_id = products.author_id', 'left')
					->where(array('products.product_id' => $id))
					->where('deleted', $deleted)
					->get('products')
					->row();
	}


	function update_product($id, $data)
	{
		$this->db->where('product_id', $id);
		return $this->db->update('products', $data);		
	}	


	function soft_delete_product($id)
	{
		$data = array(
						'deleted'=>1, 
						'updated_on'=>now()
					 );
		$this->db->where('product_id', $id);
		return $this->db->update($this->t_products, $data); 		
	}


	function get_many_by($params = array())
	{
		// Limit the results based on 1 number or 2 (2nd is offset)
		if (isset($params['limit']) && is_array($params['limit']))
		{
			$this->db->limit($params['limit'][0], $params['limit'][1]);
		}
		elseif (isset($params['limit']))
			{	
				$this->db->limit($params['limit']);
			}
		//only not deleted
		$this->db->where('deleted', 0);
		return $this->get_all();
	}
	


	function count_by($params = array())
	{
		$this->db->join('products_categories', 'products.category_id = products_categories.id', 'left');
		//only not deleted
		$this->db->where('deleted', 0);
		return $this->db->count_all_results('products');
	}


		/**
	 * Searches accounts posts based on supplied data array
	 * @param $data array
	 * @return array
	 */
	function search($mode,$data = array())
	{
	    $query = "SELECT * FROM (`default_".$this->t_products."`)";
            //deleted
            if (array_key_exists('deleted', $data)) 
            {
                $query.= ' WHERE `deleted` = '.$data['deleted'];                
            }
            else
            	{
            		$query.= ' WHERE `deleted` = 0';   
            	}	    
            if (array_key_exists('keywords', $data))
            {
                $query.= " AND (`name` LIKE '%".$data['keywords']."%')";
            }	    
            if (array_key_exists('account_id', $data) && $data['account_id']!=0)
            {
                $query.= ' AND `account_id` = '.$data['account_id'];
            }         
            if (array_key_exists('category_id', $data) && $data['category_id']!=0)
            {
                $query.= ' AND `category_id` = '.$data['category_id'];
            } 
            //Ordenar alfabeticamente
            $query.= " ORDER BY `name` ASC";            
	        // Limit the results based pagination
	        if (isset($data['pagination']['offset']) && isset($data['pagination']['limit']))
	        {
	            $query.= " LIMIT ".$data['pagination']['offset'].", ".$data['pagination']['limit'];;
	        }        
	            elseif (isset($data['pagination']['limit']))
	            {    
	                $query.= ", ".$data['pagination']['limit'];
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
	 * Searches accounts posts based on supplied data array
	 * @param $data array
	 * @return array
	 */
	function join_search($mode = '', $data = array())
	{
	    $query = "SELECT ".
	        "prod.product_id AS product_id,". 
	        "prod.category_id AS category_id,".
	        "prod.account_id AS account_id,". 
	        "prod.seller_account_id AS seller_account_id,". 
	        "prod.outsourced AS outsourced,". 
	        "prod.location_id AS location_id,". 
	        "prod.space_id AS space_id,". 
	        "prod.name as name, prod.slug AS slug,". 
	        "prod.created_on AS prod_created_on,". 
	        "prod.updated_on AS prod_updated_on,".
			"draft_id,".
			"draft_prod_updated_on,".
			"draft_loc_updated_on,".
			"draft_space_updated_on,".
			"draft_deleted,".
			"draft_version,".
			"front.id AS front_id,". 
			"front.front_version AS front_version ".
	    	"FROM `default_".$this->t_products."` AS prod ".
	    	"LEFT OUTER JOIN ".
	    	"( SELECT 
				product_updated_on AS draft_prod_updated_on,
				location_updated_on AS draft_loc_updated_on,
				space_updated_on AS draft_space_updated_on,
				deleted AS draft_deleted,
				MAX(id) AS draft_id,
				MAX(draft_version) AS draft_version,
				prod_id AS prod_id
				FROM `default_products_front_draft__1`
				WHERE deleted = 0 
				GROUP BY id ORDER BY id DESC) AS draft ". 
	    	" ON prod.product_id = draft.prod_id ".
			"LEFT OUTER JOIN `default_products_front__1` AS front ON prod.product_id = front.prod_id ";
            //prod deleted
            if (array_key_exists('deleted', $data)) 
            {
                $query.= ' WHERE prod.deleted = '.$data['deleted'];                
            }
            else
            	{
            		$query.= ' WHERE prod.deleted = 0';   
            	}	    
            if (array_key_exists('keywords', $data))
            {
                $query.= " AND (prod.name LIKE '%".$data['keywords']."%')";
            }	    
            if (array_key_exists('account_id', $data) && $data['account_id']!=0)
            {
                $query.= ' AND prod.account_id = '.$data['account_id'];
            }         
            if (array_key_exists('category_id', $data) && $data['category_id']!=0)
            {
                $query.= ' AND prod.category_id = '.$data['category_id'];
            } 
            //Ordenar alfabeticamente
            $query.= " GROUP BY prod.product_id";   
            //Ordenar alfabeticamente
            $query.= " ORDER BY prod.product_id DESC";      
	        // Limit the results based pagination
	        if (isset($data['pagination']['offset']) && isset($data['pagination']['limit']))
	        {
	            $query.= " LIMIT ".$data['pagination']['offset'].", ".$data['pagination']['limit'];;
	        }        
	            elseif (isset($data['pagination']['limit']))
	            {    
	                $query.= ", ".$data['pagination']['limit'];
	            }                                                       
            //fire query
            $q = $this->db->query($query); 
            if($mode =='')
            {
	            $result = new stdClass();
	            $result->num_rows = $q->num_rows;  
	            $result->results = $q->result();
	            return $result;
	        }
            if($mode =='counts')
            {                
                return $q->num_rows;
            }
            if($mode =='results')
            {   
                return $q->result();
            }
	} 


////////////////////////////////////////////////////////////////////////////////////////
// AUX :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * [get_spaces_updated_on values
	 * Get value of product_spaces last update]
	 * @param  array  $space_id_list [list of unique spaces IDs]
	 * @return [type]                [array of key=id, value=updated_on]
	 */
	function get_spaces_updated_on($space_id_list = array())
	{
		if(empty($space_id_list))
		{
			return false;
		}
		// go on
		$query = "SELECT space.space_id AS space_id, space.updated_on AS space_updated_on ".
				 "FROM `default_products_spaces` AS space";		
		if(count($space_id_list)==1)
		{
			$query.= " WHERE space_id = ".array_shift($space_id_list);
		}
		else
			{
				$query.= " WHERE space_id = ".array_shift($space_id_list);								
				foreach($space_id_list as $space_id)
				{
					$query.= " OR space_id = ".$space_id;
				}			
			}	
        $q = $this->db->query($query);  
        if($q->num_rows()>0)
        {
        	foreach($q->result() as $row)
        	{
        		$result[$row->space_id] = $row->space_updated_on;
        	}
        	return $result;        	
        }  
        else
	        {
	        	return array();
	        }	
	}

	/**
	 * [get_locations_updated_on values
	 * Get value of product_locations last update]
	 * @param  array  $location_id_list [list of unique spaces IDs]
	 * @return [type]                [array of key=id, value=updated_on]
	 */
	function get_locations_updated_on($loc_id_list = array())
	{
		if(empty($loc_id_list))
		{
			return false;
		}
		// go on
		$query = "SELECT loc.id AS loc_id, loc.updated_on AS loc_updated_on ".
				 "FROM `default_products_locations` AS loc";		
		if(count($loc_id_list)==1)
		{
			$query.= " WHERE loc.id = ".array_shift($loc_id_list);
		}
		else
			{
				$query.= " WHERE loc.id = ".array_shift($loc_id_list);								
				foreach($loc_id_list as $loc_id)
				{
					$query.= " OR loc.id = ".$loc_id;
				}			
			}	
        $q = $this->db->query($query);
        if($q->num_rows()>0)
        {
        	foreach($q->result() as $row)
        	{
        		$result[$row->loc_id] = $row->loc_updated_on;
        	}
        	return $result;        	
        }  
        else
	        {
	        	return array();
	        }	
	}


	function check_product_exists($field, $value = '', $id = 0)
	{
		$this->db->where(array('deleted'=>0, $field => $value, 'product_id <>' =>$id));
		$q = $this->db->get('products');	
		if($q->num_rows()>0)
		{
			return false;
		}
		else
			{
				return true;
			}
	}


////////////////////////////////////////////////////////////////////////////////////////
// FILES :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
////////////////////////////////////////////////////////////////////////////////////////

	function move_product_file($fileid, $newfolderid)
	{
		$data = array('folder_id'=>$newfolderid);
		$this->db->where('id', $fileid);
		return $this->db->update('files', $data);
	}	



}