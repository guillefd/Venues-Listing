<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Layouts Class
 *
 * Layouts Library
 *
 * @package			CodeIgniter
 * @subpackage                  Libraries
 * @category                    Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Layouts
{
  
    /**
        * Constructor - Sets loads and vars
        *
        */
    function __construct()
    {
        $this->_table = "products_spaces_layouts";   
        
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
     * Get list
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
     * @return array 
     */
    public function gen_dd_array()
    {
        $vec = array();
        if($result = $this->get() )
        {
            foreach($result as $reg)
            {              
                $vec[$reg->id] =  $reg->name;
            }
        }
        return $vec;
    }
   
} 