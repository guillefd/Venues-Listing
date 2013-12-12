<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Locations types Class
 *
 * Locations types Library
 *
 * @package			CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Locations_type
{
  
    /**
        * Constructor - Sets loads and vars
        *
        */
    function __construct()
    {
        $this->_table = "products_locations_type";   
    }
            

    /**
     * get
     * Get list of features categories
     * return array
     */
    public function get()
    {
        ci()->db->order_by('id', 'asc');
        $q = ci()->db->get($this->_table);      
        if($q->num_rows()>0)
        {
            return $data = $q->result();
        }
        else
        {
            return false;
        }  
    }
    
    /**
     * genera array para dropdowns form
     * @param type $result
     * @return array 
     */
    public function gen_dd_array()
    {
        $vec = array();
        if($result = $this->get($this->_table) )
        {
            foreach($result as $reg)
            {              
                $vec[$reg->id] =  $reg->name;
            }
        }
        return $vec;
    }


    /**
     * genera array para dropdowns form
     * @param type $result
     * @return array 
     */
    public function gen_dd_array_local($result)
    {
        $vec = array();
        if($result) 
        {
            foreach($result as $reg)
            {              
                $vec[$reg->id] =  $reg->name;
            }
        }
        return $vec;
    }


    public function gen_dd_array_slug()
    {
        $vec = array();
        if($result = $this->get($this->_table) )
        {
            foreach($result as $reg)
            {              
                $vec[$reg->id] =  $reg->slug;
            }
        }
        return $vec;
    }    

    public function gen_dd_array_slug_local($result)
    {
        $vec = array();
        if($result)
        {
            foreach($result as $reg)
            {              
                $vec[$reg->id] =  $reg->slug;
            }
        }
        return $vec;
    }

    /**
     * get
     * return array
     */
    function get_by_id($id = 0)
    {
        return ci()->db->select('*')
                    ->where(array('id' => $id))
                    ->get($this->_table)
                    ->row();
    }


    function get_field_by_id($id=0, $field = '')
    {
        $reg = $this->get_by_id($id);         
        if(isset($reg->{$field}))
        {
            return $reg->{$field};            
        }
        else
            {
                return '';
            }
    }      
   
} 