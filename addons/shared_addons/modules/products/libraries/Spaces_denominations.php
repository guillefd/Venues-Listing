<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Features Categories Class
 *
 * Features categories Library
 *
 * @package			CodeIgniter
 * @subpackage                  Libraries
 * @category                    Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Spaces_denominations
{
  
    /**
        * Constructor - Sets loads and vars
        *
        */
    function __construct()
    {
        $this->_table = "products_spaces_denominations";   
        
    }
            

    /**
     * get
     * Get item
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
     * Get item
     * return array
     */
    public function get_by_id($id = 0)
    {      
        $q = ci()->db->get_where($this->_table, array('id'=>$id));             
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