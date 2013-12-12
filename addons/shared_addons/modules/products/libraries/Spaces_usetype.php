<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Spaces Use Type Class
 *
 * Library
 *
 * @package			CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Spaces_usetype
{
  
    /**
        * Constructor - Sets loads and vars
        *
        */
    function __construct()
    {
        $this->_table = "products_spaces_usetype";   
        
    }
            

    /**
     * get
     * Get list of features categories
     * return array
     */
    public function get()
    {
        ci()->db->order_by('id', 'ASC');
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
        ci()->db->order_by('id', 'ASC');
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
     * get
     * Get list of features categories
     * return array
     */
    public function get_by_id($id = 0)
    {
        ci()->db->order_by('name', 'ASC');
        $q = ci()->db->get_where($this->_table, array('id'=>$id));       
        if($q->num_rows()>0)
        {
            return $q->row();
        }
        else
        {
            return FALSE;
        }  
    }    


    function get_field_by_id($id=0, $field = '')
    {
        $reg = '';
        if($id!=0 && $field!='')
        {
            $reg = $this->get_by_id($id);       
        }
        if(isset($reg->{$field}))
        {
            return $reg->{$field};            
        }
        else
            {
                return $reg;
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
     
    public function gen_dd_array_slug()
    {
        $vec = array();
        if($result = $this->get() )
        {
            foreach($result as $reg)
            {              
                $vec[$reg->slug] =  $reg->name;
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
                $vec[$reg->slug] =  $reg->name;
            }
        }
        return $vec;
    }

} 