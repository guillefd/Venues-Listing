<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Categories model
 *
 * @package		PyroCMS
 * @subpackage	Categories Module
 * @category	Modules
 * @author		Phil Sturgeon - PyroCMS Dev Team
 */
class Products_categories_m extends MY_Model
{

	protected $_table = 'products_categories';

	/**
	 * Insert a new category into the database
	 * @access public
	 * @param array $input The data to insert
	 * @return string
	 */
	public function insert($input = array())
	{
		$this->load->helper('text');
		parent::insert(array(
            'type_id'=>$input['type_id'],
			'title'=>$input['title'],
			'description'=>$input['description'],                    
			'slug'=>url_title(strtolower(convert_accented_characters($input['title'])))
		));
		
		return $input['title'];
	}

	/**
	 * Update an existing category
	 * @access public
	 * @param int $id The ID of the category
	 * @param array $input The data to update
	 * @return bool
	 */
	public function update($id, $input)
	{
		return parent::update($id, array(
            'type_id'=>$input['type_id'],
			'title'	=> $input['title'],
			'description'=>$input['description'],                      
		    'slug'	=> url_title(strtolower(convert_accented_characters($input['title'])))
		));
	}

	// /**
	//  * Callback method for validating the title
	//  * @access public
	//  * @param string $title The title to validate
	//  * @return mixed
	//  */
	// public function check_title($title = '')
	// {
	// 	return parent::count_by('slug', url_title($title)) > 0;
	// }

	/**
	 * Callback method for validating the title
	 *
	 * @param string $title The title to validate
	 * @param int    $id    The id to check
	 *
	 * @return mixed
	 */
	public function check_title($title = '', $id = 0)
	{
		return (bool)$this->db->where('slug', url_title($title))
			->where('id != ', $id)
			->from('products_categories')
			->count_all_results();
	}

	
	/**
	 * Insert a new category into the database via ajax
	 * @access public
	 * @param array $input The data to insert
	 * @return int
	 */
	public function insert_ajax($input = array())
	{
		$this->load->helper('text');
		return parent::insert(array(
            'type_id'=>$input['type_id'],
			'title'=>$input['title'],
			'description'=>$input['description'],                      
			//is something wrong with convert_accented_characters?
			//'slug'=>url_title(strtolower(convert_accented_characters($input['title'])))
			'slug' => url_title(strtolower($input['title']))
		));
	}

	public function get_id_by_slug($slug = "")
	{
		$this->db->where('slug', $slug);
		return $this->db->get($this->_table)->row();
	}


    	/**
	 * Searches items based on supplied data array
	 * @param $mode var to select return type: counts or results
         * @param $data array
	 * @return array
	 */
	function search($mode,$data = array())
	{
	    $conditions = array();
            $query = "SELECT * FROM (`default_".$this->_table."`)"; 
            if (array_key_exists('product_type_id', $data) && $data['product_type_id']!=0)
            {
                $conditions [] = ' `type_id` = '.$data['product_type_id'];
            }          
            if (array_key_exists('keywords', $data))
            {
                $conditions [] = " `title` LIKE '%".$data['keywords']."%' ";
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
            $query.= " ORDER BY `id` ASC";            
            // Limit the results based on 1 number or 2 (2nd is offset)
            if (isset($data['pagination']['limit']) && is_array($data['pagination']['limit']))
            {
                    $query.= " LIMIT ".$data['pagination']['limit'][1].", ".$data['pagination']['limit'][0];
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

}