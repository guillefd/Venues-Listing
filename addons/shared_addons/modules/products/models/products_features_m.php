<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Categories model
 *
 * @package		PyroCMS
 * @subpackage	Categories Module
 * @category	Modules
 * @author		Phil Sturgeon - PyroCMS Dev Team
 */
class Products_features_m extends MY_Model
{
    
	public function __construct()
	{		
		parent::__construct();
		
		$this->_table = 'products_features_defaults';
	}
              
    /**
     * Returns unique location 
     * @param type $id
     * @return boolean 
     */
    public function get_where($data = array())
    {
        $q = $this->db->get_where($this->_table, $data);      
        if($q->num_rows()>0)
        {
            return $data = $q->row();
        }
        else
        {
            return FALSE;
        }        
    }
    
    /**
     * INACTIVE - Poner espacio inactivo
     * @param type $id
     * @param type $data
     * @return type 
     */
    function inactive($id)
    {            
        $data = array(
           'active' => 0
        );
        $this->db->where('space_id', $id);
        return $this->db->update($this->_table, $data); 
    }
    
    /**
     * Update
     * @param type $id
     * @param type $data
     * @return type 
     */
    function update($id,$data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->_table, $data); 
    }        
        
        
    	/**
	 * Searches items based on supplied data array
	 * @param $mode var to select return type: counts or results
         * @param $data array
	 * @return array
	 */
	function search($mode, $data = array())
	{        
	    $conditions = array();
            $query = "SELECT * FROM (`default_".$this->_table."`)"; 
            if (array_key_exists('cat_product_id', $data) && $data['cat_product_id']!=0)
            {
                $conditions [] = ' `cat_product_id` = '.$data['cat_product_id'];
            }
            if (array_key_exists('cat_feature_id', $data) && $data['cat_feature_id']!=0)
            {
                $conditions [] = ' `cat_feature_id` = '.$data['cat_feature_id'];
            }            
            if (array_key_exists('keywords', $data))
            {
                $conditions [] = " `name` LIKE '%".$data['keywords']."%' ";
            }
            //checks conditions and assembles query
            if(!empty($conditions ))
            {
                $query.= ' WHERE '.array_shift($conditions); 
                foreach($conditions  as $cond)
                {
                    $query.= ' AND '.$cond;
                }
            }
            //Ordenar alfabeticamente
            $query.= " ORDER BY `name` ASC";            
            // Limit the results based on 1 number or 2 (2nd is offset)
            if (isset($data['pagination']) && $data['pagination']['offset'] > 0)
            {
                    $query.= " LIMIT ".$data['pagination']['offset'].", ".$data['pagination']['limit'];
            }        
            elseif (isset($data['pagination']['limit']))
            {    
                    $query.= " LIMIT ".$data['pagination']['limit'];
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
     * Insert a new feature category into the database via ajax
     *
     * @param array $input The data to insert
     *
     * @return int
     */
    public function insert_cat_feature_ajax($input = array())
    {
        $data = array(
           'name' => $input['name'],
           'description' => $input['description'],           
        );
        return $this->db->insert('products_features_categories', $data); 
    }


    
    
}