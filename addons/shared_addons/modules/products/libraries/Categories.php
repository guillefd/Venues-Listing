<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Product Categories Class
 *
 * Categpries Library
 *
 * @package			CodeIgniter
 * @subpackage                  Libraries
 * @category                    Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Categories
{
  
    /**
        * Constructor - Sets loads and vars
        *
        */
    function __construct()
    {
        $this->_cat_table = "products_categories";
        $this->_type_table = "products_type";        
    }
            

    /**
     * get
     * Get category
     * return array
     */
    public function get_by_id($id)
    {
        return ci()->db->select('*')
                    ->where(array('id' => $id))
                    ->get($this->_cat_table)
                    ->row();
    }

    public function get_id_by_slug($slug = "")
    {
        ci()->db->where('slug', $slug);
        return ci()->db->get($this->_cat_table)->row();        
    }

    /**
     * get
     * Get list of categories
     * return array
     */
    public function get_categories()
    {
        
        $q = ci()->db->get($this->_cat_table);      
        if($q->num_rows()>0)
        {
            return $data = $q->result();
        }
        else
        {
            return FALSE;
        } 

    }
    
    /**
     * get
     * Get list of types
     * return array
     */
    public function get_types()
    {
        $q = ci()->db->get($this->_type_table);      
        if($q->num_rows()>0)
        {
            return $data = $q->result();
        }
        else
        {
            return FALSE;
        }  
    }    
    
    /**
     * genera array para dropdowns form
     * @param type $result
     * @return array multidimensional
     */
    public function gen_dd_multiarray()
    {
        $vec_categories = array();
        if($categories = $this->get_categories() )
        {
            $types = $this->get_types();
            $vec_types = array();
            foreach($types as $regt)
            {
                $vec_types[$regt->id] = $regt->title;
            }
            foreach($categories as $regc)
            {                              
                $typeTitle = $vec_types[$regc->type_id];                
                $vec_categories[$typeTitle][$regc->id] =  $regc->title;
            }
        }
        return $vec_categories;
    }
    
    /**
     * genera array para dropdowns form
     * @param type $result
     * @return array 
     */
    public function gen_dd_array($typeid = 0)
    {
        $vec = array();
        if($result = $this->get_categories() )
        {
            if($typeid == 0)
            {    
                foreach($result as $reg)
                {              
                    $vec[$reg->id] = $reg->title;
                }
            }
            else
                {
                    foreach($result as $reg)
                    {              
                        if($reg->type_id == $typeid)
                        {    
                            $vec[$reg->id] = $reg->title;
                        }
                    }
                }
        }
        return $vec;
    }   
       
    
} 