<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Rooms model
 *
 * @package		PyroCMS
 * @subpackage          Products Module
 * @category            Modules
 * @author		Guillermo Dova - Rewrite
 */
class Products_spaces_m extends MY_Model
{

	public function __construct()
	{		
		parent::__construct();
		
		$this->_table = 'products_spaces';
	}

        /**
         * Returns unique location 
         * @param type $id
         * @return boolean 
         */
        public function get_where($data, $deleted = 0)
        {
            if(array_key_exists('deleted', $data) == false)
            {   
                $data['deleted'] = 0;
            }          
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
        function soft_delete($id)
        {            
            $data = array(
               'deleted' => 1,
               'updated_on' => now(),
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
            $this->db->where('space_id', $id);
            return $this->db->update($this->_table, $data); 
        }
        
        
	/**
	 * Searches rooms based on supplied data array
	 * @param $data array
	 * @return array
	 */
	function search($mode, $data = array())
	{          
	    $query = "SELECT * FROM (`default_".$this->_table."`)";
        // Solo cuentas activas
        if (array_key_exists('deleted', $data))
        {
            $query.= ' WHERE `deleted` = '.$data['deleted'];                
        }
        else
            {
                $query.= ' WHERE `deleted` = 0';                  
            }
        if (array_key_exists('location_id', $data) && $data['location_id']!=0)
        {
            $query.= ' AND `location_id` = '.$data['location_id'];
        }
        if (array_key_exists('keywords', $data) && is_string($data['keywords']) && strlen($data['keywords'])>2)
        {
            $query.= ' AND `name` LIKE "%'.$data['keywords'].'%"';
        }            
        //Ordenar alfabeticamente
        $query.= " ORDER BY `space_id` DESC";            
        // Limit the results based pagination
        if (isset($data['pagination']['offset']) && isset($data['pagination']['limit']))
        {
            $query.= " LIMIT ".$data['pagination']['offset'].", ".$data['pagination']['limit'];;
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
     * Callback method for validating the title
     * @access public
     * @param string $title The title to validate
     * @return mixed
     */
    public function check_name($name = '', $location_id = 0)
    {
            $data = array(
                'deleted'=>0,
                'name'=>$name,
                'location_id'=>$location_id,
                );
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
     * Callback method for validating the title
     * @access public
     * @param string $title The title to validate
     * @return mixed
     */
    public function check_name_edited($name = '',$id = 0, $location_id = 0)
    {
            $data = array(
                'deleted'=>0,
                'name'=>$name,
                'location_id'=>$location_id,
                'space_id !='=>$id
                );            
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
        
}
