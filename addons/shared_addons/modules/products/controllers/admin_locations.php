<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package  	PyroCMS
 * @subpackage  locations
 * @category  	Module
 * @author  	PyroCMS Dev Team
 */
class Admin_Locations extends Admin_Controller {

	/**
	 * The current active section
	 * @access protected
	 * @var int
	 */
	protected $section = 'locations';
    protected $dd_array = '';
	
	/**
	 * The constructor
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('products_locations_m');
        $this->load->helper(array('date','locations'));
		$this->lang->load(array('products','categories','locations','features','spaces'));		
		// Loads libraries
		$this->load->library(array('form_validation','accounts','social','geoworldmap', 'products','locations_type'));            
        // template addons
        $this->template->append_css('module::products.css') 
                       ->prepend_metadata('<script>var IMG_PATH = "'.BASE_URL.SHARED_ADDONPATH.'modules/'.$this->module.'/img/"; </script>');                                             
        /* gen dropdwons arrays */
        $this->dd_array = gen_dropdown_arrays();
	}
	
	/**
	 * Index method, lists all locations
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->pyrocache->delete_all('modules_m');
		
		// Create pagination links
		$total_rows = $this->products_locations_m->search('counts');
		$pagination = create_pagination('admin/products/locations/index', $total_rows, 5, 5);
        $post_data['pagination']  = $pagination;                       			
		// Using this data, get the relevant results
		$locations = $this->products_locations_m->search('results',$post_data);
        //CONVERT ID TO TEXT
        _convertIDtoText($locations, $this->dd_array);
        _formatValuesForView($locations);
		$this->template
				->title($this->module_details['name'], lang('location:list_title'))
				->set('locations', $locations)
				->set('pagination', $pagination)  
                ->set('total_rows', $total_rows)                                      
	            ->append_js('module::locations_index.js')
	            ->append_js('module::model.js')                        
				->build('admin/locations/index');
	}
	
	/**
	 * Create method, creates a new location
	 * @access public
	 * @return void
	 */
	public function create()
	{
        // Set the validation rules from the array above
        $this->form_validation->set_rules(validation_rules());           
        // Validate the data
        if ($this->form_validation->run())
        {
            $this->load->helper('text');
            $data = array('account_id'=>$this->input->post('account_id'),
                          'name' =>$this->input->post('name'),
                          'slug'=>$this->input->post('slug'), 
                          'intro' => $this->input->post('intro'),
                          'location_type_id' => $this->input->post('location_type_id'),                              
                          'description' => $this->input->post('description'),                    
                          'address_l1' => $this->input->post('address_l1'),
                          'address_l2' => $this->input->post('address_l2'),
                          'geo_street_name' => $this->input->post('geo_street_name'),
                          'geo_street_number' => $this->input->post('geo_street_number'),                                                            
                          'location_slug' => gen_location_slug($this->input->post('geo_street_name'), $this->input->post('geo_street_number') ),
                          'area' => $this->input->post('area'), 
                          'CityID'=>$this->input->post('CityID'),
                          'zipcode' => $this->input->post('zipcode'), 
                          'Latitude' => $this->input->post('Latitude'), 
                          'Longitude' => $this->input->post('Longitude'), 
                          'latlng_precision' => $this->input->post('latlng_precision'), 
                          'phone_area_code' => $this->input->post('phone_area_code'), 
                          'phone' => $this->input->post('phone'), 
                          'fax' => $this->input->post('fax'), 
                          'mobile' => $this->input->post('mobile'),                     
                          'email' => $this->input->post('email'),                    
                          'chatSocial_accounts'=>$this->_cleanString_socialAccounts($this->input->post('chatSocial_accounts')),  
                          'author_id' => $this->current_user->id,
                          'created_on' => now(), 
                          'updated_on' => now() 
                          );
            if($this->products_locations_m->insert($data))
			{
                // All good...
                $this->session->set_flashdata('success', lang('location:add_success'));
                redirect('admin/products/locations');
			}
			// Something went wrong. Show them an error
			else
	            {
					$this->session->set_flashdata('error', lang('location:add_error'));
	                redirect('admin/products/locations/create');
	            }
        }                                                 
        // Loop through each validation rule
        foreach (validation_rules() as $rule)
        {
            $location->{$rule['field']} = set_value($rule['field']);                  
        }           
        $this->template
                ->title($this->module_details['name'], lang('cat_create_title'))
                ->append_metadata($this->load->view('fragments/wysiwyg', '', TRUE)) 
                ->append_js('module::jquery/jquery.tagsinput.min.js')                                    
	            ->append_js('module::ws_autocomplete.js')                      
	            ->append_js('module::locations_form.js') 
	            ->append_js('module::model.js')
                ->append_css('module::jquery/jquery.tagsinput.css')                                                                               
                ->set('location', $location)
                ->set('dd_array', $this->dd_array)
                ->build('admin/locations/form');	
	}
	
	/**
	 * Edit method, edits an existing location
	 * @access public
	 * @param int id The ID of the location to edit
	 * @return void
	 */
	public function edit($id = 0)
	{			
            if($id==0)
            {
                $this->session->set_flashdata('error', lang('location:error_id_empty'));
				redirect('admin/products/locations/index');
            }
            else
            {                    
                //consulta SQL
                $location = $this->products_locations_m->get($id); 
                if($location == FALSE)
                {
                    $this->session->set_flashdata('error', lang('location:error_id_empty'));
                    redirect('admin/products/locations/index');
                }                
            }                                  
            //CONVERT ID TO TEXT
            $location = _convertIDtoText($location, $this->dd_array);                        
            // Set the validation rules from the array above
            $this->form_validation->set_rules(validation_rules());            
            // Validate the results
            if ($this->form_validation->run())
            {		
                $this->load->helper('text');
                $data = array('account_id'=>$this->input->post('account_id'),
                              'name' =>$this->input->post('name'),
                              'slug'=>$this->input->post('slug'), 
                              'location_type_id' => $this->input->post('location_type_id'),                                 
                              'intro' => $this->input->post('intro'),
                              'description' => $this->input->post('description'),                    
                              'address_l1' => $this->input->post('address_l1'),
                              'address_l2' => $this->input->post('address_l2'), 
                              'geo_street_name' => $this->input->post('geo_street_name'),
                              'geo_street_number' => $this->input->post('geo_street_number'),
                              'location_slug' => gen_location_slug($this->input->post('geo_street_name'), $this->input->post('geo_street_number'), $this->input->post('name'), $id ),                                                             
                              'area' => $this->input->post('area'), 
                              'CityID'=>$this->input->post('CityID'),
                              'zipcode' => $this->input->post('zipcode'), 
                              'Latitude' => $this->input->post('Latitude'), 
                              'Longitude' => $this->input->post('Longitude'), 
                              'latlng_precision' => $this->input->post('latlng_precision'), 
                              'phone_area_code' => $this->input->post('phone_area_code'), 
                              'phone' => $this->input->post('phone'), 
                              'fax' => $this->input->post('fax'), 
                              'mobile' => $this->input->post('mobile'),                     
                              'email' => $this->input->post('email'),                    
                              'chatSocial_accounts'=>$this->_cleanString_socialAccounts($this->input->post('chatSocial_accounts')), 
                              //'author_id' => $this->current_user->id,
                              'updated_on' => now() 
                              );                    
                if($this->products_locations_m->update($id, $data))
                {        
                    // All good...
                    $this->session->set_flashdata('success', lang('location:edit_success'));
                    redirect('admin/products/locations/index');
                }
                else
                    {
                        $this->session->set_flashdata('error', lang('location:edit_error'));
                        redirect('admin/products/location/edit/'.$id);
                    }
            }

            // Loop through each rule
            foreach (validation_rules() as $rule)
            {
                if ($this->input->post($rule['field']) !== null)
                {
                    $location->{$rule['field']} = $this->input->post($rule['field']);
                }
            }           
            $this->template
                    ->title($this->module_details['name'], sprintf(lang('cat_edit_title'), $location->name))
                    ->append_metadata($this->load->view('fragments/wysiwyg', '', TRUE))  
                    ->append_js('module::jquery/jquery.tagsinput.min.js')                                    
	            	->append_js('module::ws_autocomplete.js')                      
	            	->append_js('module::locations_form.js')
                    ->append_js('module::model.js')
                    ->append_css('module::jquery/jquery.tagsinput.css')                                         
                    ->set('location', $location)
                    ->set('dd_array', $this->dd_array)                    
                    ->build('admin/locations/form');
	}
        
        
	/**
	 * Preview location
	 * @access public
	 * @param int $id the ID of the location
	 * @return void
	 */
	public function preview($id = 0)
	{
        $location = $this->products_locations_m->get($id);
        $location = _convertIDtoText($location, $this->dd_array);
        // set template
		$this->template
				->set_layout('modal','admin')
				->set('location', $location)
				->build('admin/locations/partials/location');                         
	} 
        
/**
* Delete - no se borra, se deja inactivo
* @param type $id 
*/
public function delete($id = 0)
{
    if( ($result = $this->products->consistency_delete_location($id)) == null)
    {
        if($this->products_locations_m->soft_delete($id))
        {        
            $this->session->set_flashdata('success', sprintf(lang('locations:delete_success'),$id));
            redirect('admin/products/locations/index');
        }
        else
            {
                $this->session->set_flashdata('error', lang('locations:delete_error'));
                redirect('admin/products/locations/index');
            }
    }
    else
        {
            $this->session->set_flashdata('error', sprintf(lang('location:delete_consistency_error'), " '".$result->table."', total registros: ".$result->num_rows ));
            redirect('admin/products/locations/index');           
        }
}       
        


// VALIDATION FUNCTIONS ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::        
                 
        
	/**
	 * Callback method that checks the title of the location
	 * @access public
	 * @param string title The title to check
	 * @return bool
	 */
	public function _check_name($name = '')
	{
        if ($reg = $this->products_locations_m->check_name($name))
        {
               if($this->method == "edit")
               {        
                   //Check name does not exist in DB and ID not equal to ID of current edited record
                   if($this->products_locations_m->check_name_edited($name,$this->uri->segment(5)))
                   {    
                        $this->form_validation->set_message('_check_name', sprintf(lang('location:already_exist_error'), $name));
                        return FALSE;                           
                   }
                   else
                       {
                            return TRUE;
                       }
               }
               else
                    {
                        $this->form_validation->set_message('_check_name', sprintf(lang('location:already_exist_error'), $name));
                        return FALSE;
                    }
        }
        return TRUE;
	}

        
	/**
	 * Callback method that checks the slug of the location
	 * @access public
	 * @param string title The title to check
	 * @return bool
	 */
	public function _check_slug($slug = '')
	{
            if ($reg = $this->products_locations_m->check_slug($slug))
            {
                if($this->method == "edit")
                {        
                       //Check name does not exist in DB and ID not equal to ID of current edited record
                       if($this->products_locations_m->check_slug_edited($slug,$this->uri->segment(5)))
                       {    
                            $this->form_validation->set_message('_check_slug', sprintf(lang('location:slug_already_exist_error'), $slug));
                            return FALSE;                           
                       }
                       else
                           {
                                return TRUE;
                           }
                }
                else
                    {			
                        $this->form_validation->set_message('_check_slug', sprintf(lang('location:slug_already_exist_error'), $slug));
                        return FALSE;
                    }    
            }
            return TRUE;
	}        
        
	/**
	 * Callback method that checks the account id
	 * @access public
	 * @param string reg The id to check
	 * @return bool
	 */        
    public function _check_validAccountId($id)
    {
        if(!$this->accounts->get_account($id))
        {
            $this->form_validation->set_message('_check_validAccountId', sprintf(lang('location:account_id_not_valid')));
            return FALSE;
        }else
            {
                return TRUE;
            }
    }
    
    /**
    * Quita valores residuales del string de dias/horarios de pago a proveedores 
    * @param type string $string 
    * return type string
    */
    public function _cleanString_socialAccounts($string)
    {
        return str_replace('EMPTY;','',$string);    
    }   
   

//:::::::::: AJAX :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        
	/**
	 * Create method, creates a new location via ajax
	 * @access public
	 * @return void
	 */
	public function create_ajax()
	{
		// Loop through each validation rule
		foreach (validation_rules() as $rule)
		{
			$location->{$rule['field']} = set_value($rule['field']);
		}
		
		$this->data->method = 'create';
		$this->data->location =& $location;
		
		if ($this->form_validation->run())
		{
			$id = $this->products_locations_m->insert_ajax($_POST);
			
			if ($id > 0)
			{
				$message = sprintf( lang('location:add_success'), $this->input->post('title'));
			}
			else
			{
				$message = lang('location:add_error');
			}

			return $this->template->build_json(array(
				'message'		=> $message,
				'title'			=> $this->input->post('title'),
				'location_id'	=> $id,
				'status'		=> 'ok'
			));
		}	
		else
		{
			// Render the view
			$form = $this->load->view('admin/locations/form', $this->data, TRUE);

			if ($errors = validation_errors())
			{
				return $this->template->build_json(array(
					'message'	=> $errors,
					'status'	=> 'error',
					'form'		=> $form
				));
			}

			echo $form;
		}
	}
        
                
        /**
	 * method to fetch filtered results for account list
	 * @access public
	 * @return void
	 */
	public function ajax_filter()
	{
        //captura post
        $post_data = array();
        if($this->input->post('f_account'))
        {
            $post_data['account_id'] = $this->input->post('f_account');                  
        }    
        if($this->input->post('f_keywords'))
        {    
            $post_data['keywords'] = $this->input->post('f_keywords');
        }    
        if($this->input->post('f_city'))
        {
            $post_data['CityID'] = $this->input->post('f_city');                   
        }                
        //pagination
        $total_rows = $this->products_locations_m->search('counts',$post_data);
        //params (URL -for links-, Total records, records per page, segmnet number )
		$pagination = create_pagination('admin/products/locations/index', $total_rows, 10, 5);
        $post_data['pagination'] = $pagination;                
        //query with limits
        $locations = $this->products_locations_m->search('results',$post_data);                             
        $locations = _convertIDtoText($locations, $this->dd_array);
        _formatValuesForView($locations);                
		//set the layout to false and load the view
        $this->input->is_ajax_request() ? $this->template->set_layout(FALSE) : '';                 
		$this->template
				->title($this->module_details['name'], lang('location:list_title'))
				->set('locations', $locations)
				->set('pagination', $pagination) 
                ->set('total_rows', $total_rows)                                        
	            ->append_js('module::locations_index.js')
	            ->build('admin/locations/partials/locations');
	}
        
        
        /**
         * Returns json response with locations list, given "keyword" search
         */
        public function locations_autocomplete_ajax()
        {
            $respond->locations = array();
            $respond->count = 0;
            if($this->input->get('term'))
            {             
				$post_data['keywords'] = $this->input->get('term');
	            $post_data['pagination']['limit'] = $this->input->get('limit');
	            if ($result = $this->products_locations_m->search('results',$post_data))
				{
					foreach ($result as $location)
					{
		                            _convertIDtoText($location, $this->dd_array);
		                            $respond->locations[] = $location;
		                            $respond->count++;
					}
				}                                   
            }            
            echo json_encode($respond);    
        }      

        /**
         * Returns json response with locations list, of given accountid
         */
        public function locations_by_accountid_ajax()
        {
            $respond->locations = array();
            $respond->count = 0;
            if($this->input->post('account_id') && $this->input->post('limit'))
            {             
				$respond->status = 'OK';            	
				$post_data['account_id'] = $this->input->post('account_id');
	            $post_data['pagination']['limit'] = $this->input->post('limit');
	            if ($result = $this->products_locations_m->search('results',$post_data))
				{
					foreach ($result as $location)
					{
		                            _convertIDtoText($location, $this->dd_array);
		                            $respond->locations[] = $location;
		                            $respond->count++;
					}
				}                                   
            }            
            echo json_encode($respond);    
        }            
}

