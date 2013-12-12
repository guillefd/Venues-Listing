<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Facilities Class
 *
 * Facilities Library
 *
 * @package			CodeIgniter
 * @subpackage                  Libraries
 * @category                    Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Facilities
{
  
    /**
        * Constructor - Sets loads and vars
        *
        */
    function __construct()
    {
        $this->_table = "products_spaces_facilities";   
        
    }
            

    /**
     * get
     * Get list of features categories
     * return array
     */
    public function get()
    {
        $q = ci()->db->get($this->_table);      
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
     * Get list of features categories
     * return array
     */
    public function get_syncindex()
    {
        $q = ci()->db->get($this->_table);      
        if($q->num_rows()>0)
        {
            $vec = array();
            foreach ($q->result() as $key => $obj) 
            {
                $vec[$obj->id] = $obj;
            }
            return $vec;
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
    public function gen_dd_array()
    {
        $vec = array();
        if($result = $this->get() )
        {
            foreach($result as $reg)
            {                              
                $category = $reg->category;
                $vec_name = $reg->name;
                //multidimensional array for select dropdown grouping
                $vec[$category][$reg->id] =  $vec_name;
            }
        }
        return $vec;
    }

    /**
     * genera array para dropdowns form
     * @param type $result
     * @return array multidimensional
     */
    public function gen_dd_array_local($result)
    {
        $vec = array();
        if($result)
        {
            foreach($result as $reg)
            {                              
                $category = $reg->category;
                $vec_name = $reg->name;
                //multidimensional array for select dropdown grouping
                $vec[$category][$reg->id] =  $vec_name;
            }
        }
        return $vec;
    }


} 