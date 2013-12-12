<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* @package  	PyroCMS
* @subpackage  spaces
* @category  	Module
* @author  	PyroCMS Dev Team
*/
class Admin_Spaces extends Admin_Controller 
{

/**
* The current active section
* @access protected
* @var int
*/
protected $section = 'spaces';

public $dd_array = array();

/**
* The constructor
* @access public
* @return void
*/
public function __construct()
{
    parent::__construct();

    $this->load->model('products_spaces_m');
    $this->load->helper(array('spaces','date'));
    $this->lang->load(array('products','categories','locations','features','spaces'));		
    // Loads libraries
    $this->load->library(array('form_validation','accounts','spaces_denominations', 'spaces_usetype', 'products','shapes','layouts','facilities','geoworldmap'));            
    // template addons
    $this->template->append_css('module::products.css') 
                   ->prepend_metadata('<script>var IMG_PATH = "'.BASE_URL.SHARED_ADDONPATH.'modules/'.$this->module.'/img/"; </script>');                     
    //generate dropdown array               
    $this->dd_array = _gen_dropdown_list();               
}        


/**
* Index method, lists all spaces
* @access public
* @return void
*/
public function index()
{	
    // Create pagination links
    $total_rows = $this->products_spaces_m->search('counts');
    $pagination = create_pagination('admin/products/spaces/index', $total_rows, 10, 5);
    $post_data['pagination']  = $pagination;                   			
    // Using this data, get the relevant results
    $spaces = $this->products_spaces_m->search('results',$post_data);   
    //CONVERT ID TO TEXT
    $spaces = _convertIDtoText($spaces, $this->dd_array);
    //$this->_formatValuesForView($spaces);
    $this->template
        ->title($this->module_details['name'], lang('spaces:list_title'))
        ->append_js('module::spaces_index.js')
        ->set('spaces', $spaces)
        ->set('pagination', $pagination)    
        ->set('dd_array', $this->dd_array)                                                 
        ->build('admin/spaces/index');
}

/**
* Create method, creates a new space
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
        $data = array(
            'location_id'=>$this->input->post('location_id'),
            'denomination_id'=>$this->input->post('denomination_id'),          
            'name' =>$this->input->post('name'),
            'description' => $this->input->post('description'),                    
            'level' => $this->input->post('level'),
            'width' => $this->input->post('width'), 
            'height' => $this->input->post('height'), 
            'length' => $this->input->post('length'),                     
            'square_mt'=> $this->input->post('square_mt'), 
            'shape_id'=>$this->input->post('shape_id'),
            'layouts' => $this->input->post('layouts'),  
            'facilities' => serialize($this->input->post('facilities')),
            'usetypes' => serialize($this->input->post('usetypes')),             
            'author_id' => $this->current_user->id,
            'created_on' => now(), 
            'updated_on' => now() 
            );
        if($this->products_spaces_m->insert($data))
        {
            // All good...
            $this->session->set_flashdata('success', lang('spaces:add_success'));
            redirect('admin/products/spaces');
        }
            // Something went wrong. Show them an error
        else
        {
            $this->session->set_flashdata('error', lang('spaces:add_error'));
            redirect('admin/products/spaces/create');
        }
    }  
    // Loop through each validation rule
    foreach (validation_rules() as $rule)
    {           
        $space->{$rule['field']} = set_value($rule['field']);      
    }    
    //facilities[] array value for re-populating form
    $space->facilities = $this->input->post('facilities');
    $space->usetypes = $this->input->post('usetypes');    
    $this->template
        ->title($this->module_details['name'], lang('spaces:create_title'))
        ->append_js('module::spaces_form.js')
        ->append_js('module::spaces_form_model.js')  
        ->set('dd_array', $this->dd_array)                                                                    
        ->set('space', $space)
        ->build('admin/spaces/form');	     
}


/**
* Edit method, edits an existing space
* @access public
* @param int id The ID of the location to edit
* @return void
*/
public function edit($id = 0)
{			
    if($id==0)
    {
        $this->session->set_flashdata('error', lang('spaces:error_id_empty'));
        redirect('admin/products/spaces/index');
    }
    else
    {                    
    //consulta SQL
        $space = $this->products_spaces_m->get_where(array('space_id'=>$id));                
        if($space == FALSE)
        {
            $this->session->set_flashdata('error', lang('spaces:error_id_empty'));
            redirect('admin/products/spaces/index');
        }
        //convert facilities value to array
        $space->facilities =  unserialize($space->facilities);
        $space->usetypes =  unserialize($space->usetypes);        
    }                      
    // Set the validation rules from the array above
    $this->form_validation->set_rules(validation_rules());           

    // Validate the results
    if ($this->form_validation->run())
    {		
        $data = array(
            'location_id'=>$this->input->post('location_id'),
            'denomination_id'=>$this->input->post('denomination_id'),          
            'name' =>$this->input->post('name'),
            'description' => $this->input->post('description'),                    
            'level' => $this->input->post('level'),
            'width' => $this->input->post('width'), 
            'height' => $this->input->post('height'), 
            'length' => $this->input->post('length'),                     
            'square_mt'=> $this->input->post('square_mt'), 
            'shape_id'=>$this->input->post('shape_id'),
            'layouts' => $this->input->post('layouts'),  
            'facilities' => serialize($this->input->post('facilities')),
            'usetypes' => serialize($this->input->post('usetypes')),              
            'updated_on' => now() 
            );                 
        if($this->products_spaces_m->update($id, $data))
        {        
            // All good...
            $this->session->set_flashdata('success', lang('spaces:edit_success'));
            redirect('admin/products/spaces/index');
        }
        else
        {
            $this->session->set_flashdata('error', lang('spaces:edit_error'));
            redirect('admin/products/spaces/edit/'.$id);
        }
    }
    //CONVERT ID TO TEXT
    _convertIDtoText($space, $this->dd_array);  
    // Loop through each rule
    foreach (validation_rules() as $rule)
    {
        if ($this->input->post($rule['field']) !== null)
        {
            $space->{$rule['field']} = $this->input->post($rule['field']);
        }
    }         
    $this->template
        ->title($this->module_details['name'], lang('spaces:edit_title'))
        ->append_js('module::spaces_form.js')
        ->append_js('module::spaces_form_model.js')                                                          
        ->set('space', $space)
        ->set('dd_array', $this->dd_array)        
        ->build('admin/spaces/form');
}

/**
* Delete - no se borra, se deja inactivo
* @param type $id 
*/
public function delete($id = 0)
{
    if( ($result = $this->products->consistency_delete_space($id)) == null)
    {
        if($this->products_spaces_m->soft_delete($id))
        {        
            $this->session->set_flashdata('success', sprintf(lang('spaces:delete_success'),$id));
            redirect('admin/products/spaces/index');
        }
        else
            {
                $this->session->set_flashdata('error', lang('spaces:delete_error'));
                redirect('admin/products/spaces/index');
            }
    }
    else
        {
            $this->session->set_flashdata('error', sprintf(lang('spaces:delete_consistency_error'), "'".$result->table."', total registros: ".$result->num_rows ));
            redirect('admin/products/spaces/index');           
        }
}          

/**
* Preview Space
* @access public
* @param int $id the ID of the location
* @return void
*/
public function preview($id = 0)
{
    $space = $this->products_spaces_m->get_where(array('space_id'=>$id));            
    //convert facilities value to array
    $space->facilities_txt =  convertFacilitiesToText(unserialize($space->facilities), $this->dd_array);               
    $space->usetypes_txt =  convertUseTypesToText(unserialize($space->usetypes), $this->dd_array);  
    $space = _convertIDtoText($space, $this->dd_array);
    // set template
    $this->template
    ->set_layout('modal','admin')
    ->set('space', $space)
    ->build('admin/spaces/partials/space');                         
}        


// AJAX CALLS :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

    /**
 * method to fetch filtered results for spaces list
 * @access public
 * @return void
 */
public function ajax_filter()
{
    //captura post
    $post_data = array();
    if($this->input->post('f_location'))
    {
        $post_data['location_id'] = $this->input->post('f_location');                  
    }    
    if($this->input->post('f_keywords'))
    {    
        $post_data['keywords'] = $this->input->post('f_keywords');
    }                 
    //pagination
    $total_rows = $this->products_spaces_m->search('counts',$post_data);
    //params (URL -for links-, Total records, records per page, segmnet number )
    $pagination = create_pagination('admin/products/spaces/index', $total_rows, 10, 5);
    $post_data['pagination'] = $pagination;                
    //query with limits
    $spaces = $this->products_spaces_m->search('results',$post_data);                             
    $spaces = _convertIDtoText($spaces, $this->dd_array);             
    //set the layout to false and load the view
    $this->input->is_ajax_request() ? $this->template->set_layout(FALSE) : '';                 
    $this->template
            ->title($this->module_details['name'], lang('location:list_title'))
            ->set('spaces', $spaces)
            ->set('pagination', $pagination)   
            ->set('dd_array', $this->dd_array)                                 
            ->append_js('module::spaces_index.js')
            ->build('admin/spaces/partials/spaces');
}



/**
* Returns json response with spaces list, given "keyword" search
*/
public function spaces_autocomplete_ajax()
{
    $respond->spaces = array();
    $respond->count = 0;
    if($this->input->get('term'))
    {             
        $post_data['keywords'] = $this->input->get('term');
        $post_data['pagination']['limit'] = $this->input->get('limit');
        $post_data['location_id'] = $this->input->get('location_id');
        if ($result = $this->products_spaces_m->search('results',$post_data))
        {
            foreach ($result as $space)
            {
                _gen_dropdown_list();
                $space = _convertIDtoText($space, $this->dd_array); 
                $respond->spaces[] = $space;
                $respond->count++;
            }
        }                                   
    }
    echo json_encode($respond);    
}


/**
* Returns json response with spaces list, of given locationid
*/
public function spaces_by_locationid_ajax()
{
    $respond->spaces = array();
    $respond->count = 0;
    if($this->input->post('location_id') && $this->input->post('limit'))
    {             
        $location_id = intval($this->input->post('location_id'));
        if(is_numeric($location_id))
        {
            $respond->status = 'OK';  
            $post_data['location_id'] = $this->input->post('location_id');
            $post_data['pagination']['limit'] = $this->input->post('limit');
            if ($result = $this->products_spaces_m->search('results',$post_data))
            {
                //unserialize usetypes
                $result = unserialize_and_json_spaces_usetypes($result);
                $respond->status = 'OK';
                foreach ($result as $space)
                {
                    $respond->spaces[] = $space;
                    $respond->count++;
                }
            } 
        }
        else
            {
                $respond->status = 'ERROR';            
            }                                  
    }            
    echo json_encode($respond);    
}           


// CHECK ID ::::::::::::::::::::::::::::::::::::::::::::::::::
/**
* Callback method that checks the location id
* @access public
* @param id The id to check
* @return bool
*/        
public function _check_validLocationId($id)
{
    if(!$this->products->get_location($id))
    {
        if($this->method == "edit" && $this->products->get_location($id))
        {
            return TRUE; //devuelve TRUE porque el ID fue seleccionado cuando locacíon estuvo activa
        }
        $this->form_validation->set_message('_check_validLocationId', sprintf(lang('spaces:location_id_not_valid')));
        return FALSE;
    }
    else
        {
            return TRUE;
        }
}


public function _check_facilities_id()
{
//multiple
    return $this->input->post('facilities');
}

/**
 * Callback method that checks the title of the location
 * @access public
 * @param string title The title to check
 * @return bool
 */
public function _check_name($name = '')
{
    if ($reg = $this->products_spaces_m->check_name($name, $this->input->post('location_id')))
    {
           if($this->method == "edit")
           {        
               //Check name does not exist in DB and ID not equal to ID of current edited record
               if($this->products_spaces_m->check_name_edited($name, $this->uri->segment(5), $this->input->post('location_id')))
               {    
                    $this->form_validation->set_message('_check_name', sprintf(lang('spaces:already_exist_error'), $name));
                    return FALSE;                           
               }
               else
                   {
                        return TRUE;
                   }
           }
           else
                {
                    $this->form_validation->set_message('_check_name', sprintf(lang('spaces:already_exist_error'), $name));
                    return FALSE;
                }
    }
    return TRUE;
}

}