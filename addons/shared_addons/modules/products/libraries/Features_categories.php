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

class Features_categories
{
  
    /**
        * Constructor - Sets loads and vars
        *
        */
    function __construct()
    {
        $this->_table = "products_features_categories";   
        $this->_table_defaults = "products_features_defaults";           
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


    public function get_features_defaults_by_prodcat_syncindex($prodcatid = 0)
    {
        $query = 'SELECT '
                 .'ftrdef.id AS ftrID,
                 ftrdef.name AS ftrName,
                 ftrcat.id AS ftrcatID,
                 ftrdef.description AS ftrDesc,
                 ftrcat.name AS catName,
                 ftrcat.description AS catDesc'
                 .' FROM default_products_features_defaults AS ftrdef'
                 .' JOIN default_products_features_categories AS ftrcat ON ftrdef.cat_feature_id = ftrcat.id'
                 .' WHERE ftrdef.cat_product_id = '.$prodcatid;   
        $result = ci()->db->query($query);
        if($result->num_rows > 0)
        {
            $vec = array();
            foreach ($result->result() as $key => $obj) 
            {
                $vec[$obj->ftrcatID][$obj->ftrID] = $obj;
            }
            return $vec;
        }
        else
            {
                return array();
            }
    }

    
    
    
      
} 
