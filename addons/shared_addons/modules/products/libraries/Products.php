<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Location Class
 *
 * Info of location
 *
 * @package			CodeIgniter
 * @subpackage                  Libraries
 * @category                    Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Products
{
    /**
        * Constructor - Sets loads and vars
        *
        */
    function __construct()
    {
        //global object    
        $this->t_locations = 'products_locations';
        $this->t_accounts = "accounts";
        $this->t_spaces = "products_spaces"; 
        $this->t_products = "products";                   
        $this->t_prod_features = "products_features"; 
        $this->t_prod_front_draft_PREF = "products_front_draft__"; 
        $this->t_prod_front_PREF = "products_front__";                    
    }        
    
//////////////////////////////////////////////////////////////////////////////////////////////////////////
// LOCATIONS :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
//////////////////////////////////////////////////////////////////////////////////////////////////////////
 
    /**
        * Returns unique location by ID
        * @param type $id
        * @return boolean 
        */
    function get_location($id)
    {
        $q = ci()->db->get_where($this->t_locations, array('deleted'=>0,'id' => $id));      
        if($q->num_rows()>0)
        {
            return $data = $q->row();
        }
        else
        {
            return FALSE;
        }        
    }  


    function get_location_field_by_id($id=0, $field = '', $active = 1)
    {
        $reg = '';
        if($id!=0 && $field!='')
        {
            $reg = $this->get_location($id, $active);       
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


//////////////////////////////////////////////////////////////////////////////////////////////////////////
// SPACES ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
//////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * get
     * return array
     */
    function get_space_by_id($id)
    {
        return ci()->db->select('*')
                    ->where(array('deleted'=>0,'space_id' => $id))
                    ->get($this->t_spaces)
                    ->row();
    }

    function get_space_field_by_id($id=0, $field = '', $active = 1)
    {
        $reg = '';
        if($id!=0 && $field!='')
        {
            $reg = $this->get_space_by_id($id, $active);       
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
     * get
     * return array
     */
    function get_spaces_by_location_id($id = 0)
    {
        return ci()->db->select('*')
                    ->where(array('deleted'=>0,'location_id' => $id))
                    ->get($this->t_spaces)
                    ->row();
    }    



///////////////////////////////////////////////////////////////////////////////////////////////////////
// ACCOUNTS :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
///////////////////////////////////////////////////////////////////////////////////////////////////////


    /**
        * Returns unique account by ID
        * @param type $id
        * @return boolean 
        */
    function get_account($id, $active = 1)
    {
        return ci()->db->select('*')
                    ->where(array('account_id' => $id, 'active'=>$active))
                    ->get($this->t_accounts)
                    ->row();       
    } 

    function get_account_field_by_id($id=0, $field = '', $active = 1)
    {
        $reg = '';
        if($id!=0 && $field!='')
        {
            $reg = $this->get_account($id, $active);
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


    function get_account_by_spaceid($space_id)
    {
        return ci()->db->select($this->t_accounts.'.*')
                    ->join($this->t_locations, $this->t_accounts.'.account_id = '.$this->t_locations.'.account_id')
                    ->join($this->t_spaces, $this->t_spaces.'.location_id = '.$this->t_locations.'.id')
                    ->where(array($this->t_spaces.'.space_id' => $space_id))
                    ->get($this->t_accounts)
                    ->row();                            
    }


////////////////////////////////////
// PRODUCTS ::::::::::::::::::::: //
////////////////////////////////////


    /**
    * Returns unique account by ID
    * @param type $id
    * @return boolean 
    */
    function get_product($id, $deleted = 0)
    {
        return ci()->db->select('*')
                    ->where(array('product_id' => $id, 'deleted'=>$deleted))
                    ->get($this->t_products)
                    ->row();       
    } 

    function get_product_field_by_id($id = 0, $field = '', $deleted = 0)
    {
        $reg = '';
        if($id!=0 && $field!='')
        {
            $reg = $this->get_product($id, $deleted);       
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
  
//////////////////////
// PRODUCT FEATURES //
//////////////////////

    function get_features_by_product_id($prod_id = 0)
    {
        return ci()->db->select('*')
                       ->where(array('product_id'=>$prod_id))
                       ->get($this->t_prod_features); 
    }    


///////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CONSISTENCY DATABASE RELATIONS ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: // //
// checks before deletion                                                                                    //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    /**
     * [consistency_delete_space checks if space is used elsewehere before deletion ]
     * @param  integer $id [description]
     * @return [boolean]      [description]
     */
    function consistency_delete_space($id = 0)
    {
        $q = ci()->db->where(array('space_id'=>$id, 'deleted'=>0))->get($this->t_products);
        if( $q->num_rows > 0 )
        {
            $result->table = 'Productos';
            $result->num_rows = $q->num_rows();            
            $result->rows = $q->result();
            return $result;            
        }
        else
            {
                return null;
            }
    }

    /**
     * [consistency_delete_location 
     * checks if location is used elsewhere before deletion
     * check products and spaces]
     * @param  integer $id [description]
     * @return [type]      [description]
     */
    function consistency_delete_location($id = 0)
    {
        // check if used in products
        $q = ci()->db->where(array('location_id'=>$id, 'deleted'=>0))->get($this->t_products);        
        if( $q->num_rows > 0 )
        {
            $result->table = 'Productos';
            $result->num_rows = $q->num_rows();
            $result->rows = $q->result();            
            return $result;
        }
        // check if used in spaces
        $q = ci()->db->where(array('location_id'=>$id, 'deleted'=>0))->get($this->t_spaces);        
        if( $q->num_rows > 0 )
        {
            $result->table = 'Espacios';
            $result->num_rows = $q->num_rows();            
            $result->rows = $q->result();
            return $result;
        }        
        else
            {
                return null;
            }
    }

    /**
     * [consistency_delete_product checks if product is used elsewhere before deletion
     * check draft and front]
     * @param  integer $id [description]
     * @return [type]      [description]
     */
    function consistency_delete_product($id = 0, $typeid = 0)
    {
        /* check table exist*/
        if (ci()->db->table_exists($this->t_prod_front_PREF.$typeid) == false)
        {   
            return null;
        }  
        // check if product is live
        $q = ci()->db->where(array('prod_id'=>$id))->get($this->t_prod_front_PREF.$typeid);        
        if( $q->num_rows > 0 )
        {
            $result->table = 'Front';
            $result->num_rows = $q->num_rows();
            $result->rows = $q->result();            
            return $result;
        }        

        // check if product has draft
        $query = 'SELECT id, name, MAX(draft_version) '.
                 'FROM default_'.$this->t_prod_front_draft_PREF.$typeid.
                 ' WHERE deleted = 0 AND prod_id = '.$id.
                 ' GROUP BY prod_id ORDER BY id DESC';
        $q = ci()->db->query($query);         
        if( $q->num_rows > 0 )
        {
            $result->table = 'Publicacion';
            $result->num_rows = $q->num_rows();            
            $result->rows = $q->result();
            return $result;
        }        
        else
            {
                return null;
            }           
    }


    /**
     * [consistency_delete_front_draft checks if front_draft is used elsewhere before deletion]
     * @param  integer $id [description]
     * @return [type]      [description]
     */
    function consistency_delete_front_draft($prod_id = 0, $typeid = 0)
    {
        /* check table exist*/
        if (ci()->db->table_exists($this->t_prod_front_PREF.$typeid) == false)
        {   
            return null;
        } 
        // check if product is live
        $q = ci()->db->where(array('prod_id'=>$prod_id))->get($this->t_prod_front_PREF.$typeid);        
        if( $q->num_rows > 0 )
        {
            $result->table = 'Front';
            $result->num_rows = $q->num_rows();
            $result->rows = $q->result();            
            return $result;
        }         
        else
            {
                return null;
            }
    }

}

/* End of file Products.php */