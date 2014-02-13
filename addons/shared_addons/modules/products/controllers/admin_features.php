<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @package  	PyroCMS
 * @subpackage  locations
 * @category  	Module
 * @author  	PyroCMS Dev Team
 */
class Admin_Features extends Admin_Controller {

    /**
     * The current active section
     * @access protected
     * @var int
     */
    protected $section = 'features';
    public $dd_arrays = array();

    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct() 
    {
        parent::__construct();

        $this->load->model(array('products_features_m', 'products_categories_m'));
        $this->load->helper(array('date', 'products_features'));
        $this->lang->load(array('products', 'categories', 'locations', 'features', 'spaces'));
        // Loads libraries
        $this->load->library(array('form_validation', 'features_categories', 'usageunit','product_type','categories'));
        // generate dropdowns
        $this->dd_arrays = _gen_dropdown_list();
        // template addons
        $this->template->append_css('module::products.css')
                       ->prepend_metadata('<script>var IMG_PATH = "' . BASE_URL . SHARED_ADDONPATH . 'modules/' . $this->module . '/img/"; </script>');
    }


    /**
     * Index method, lists all locations
     * @access public
     * @return void
     */
    public function index() 
    {
        // Create pagination links               
        $total_rows = $this->products_features_m->search('counts');
        //params (URL -for links-, Total records, records per page, segment number )
        $post_data['pagination'] = create_pagination('admin/products/features/index/', $total_rows, 10, 5);                           			
        // Using this data, get the relevant results
        $features = $this->products_features_m->search('results', $post_data); 
        //CONVERT ID TO TEXT
        $features = _convertIDtoText($features, $this->dd_arrays);
        $this->template
                ->title($this->module_details['name'], lang('features:list_title'))
                ->append_js('module::features_index.js')
                ->set('features', $features)
                ->set('pagination', $post_data['pagination'])
                ->set('dd', $this->dd_arrays)                     
                ->build('admin/features/index');
    }

    /**
     * Create method, creates a new feature
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
                'cat_product_id' => $this->input->post('cat_product_id'),
                'cat_feature_id' => $this->input->post('cat_feature_id'),
                'name' => $this->input->post('name'),
                'short_name' => $this->input->post('short_name'),
                'description' => $this->input->post('description'),
                'usageunit_id' => $this->input->post('usageunit_id'),
                'value' => $this->input->post('value'),
                // 'group' => $this->input->post('group'),
                );
            if ($this->products_features_m->insert($data)) 
            {
                // All good...
                $this->session->set_flashdata('success', lang('features:add_success'));
                redirect('admin/products/features');
            }
            // Something went wrong. Show them an error
            else 
                {
                    $this->session->set_flashdata('error', lang('features:add_error'));
                    redirect('admin/products/features/create');
                }
        }

        // Loop through each validation rule
        foreach (validation_rules() as $rule) {
            $feature->{$rule['field']} = set_value($rule['field']);
        }

        $this->template
                ->append_js('module::features_categories.js')        
                ->title($this->module_details['name'], lang('cat_create_title'))
                ->append_metadata($this->load->view('fragments/wysiwyg', TRUE))                                      
                ->set('feature', $feature)
                ->set('dd', $this->dd_arrays)                
                ->build('admin/features/form');
    }
    

    /**
     * Edit method, edits an existing category
     *
     * @param int $id The ID of the category to edit
     */
    public function edit($id = 0)
    {
        // Get the category
        $feature = $this->products_features_m->get($id);
        // ID specified?
        $feature or redirect('admin/products/features/index');
        // Set the validation rules from the array above
        $this->form_validation->set_rules(validation_rules());
        // Validate the results
        if ($this->form_validation->run())
        {
            $data = array(
                'cat_product_id' => $this->input->post('cat_product_id'),
                'cat_feature_id' => $this->input->post('cat_feature_id'),
                'name' => $this->input->post('name'),
                'short_name' => $this->input->post('short_name'),
                'description' => $this->input->post('description'),
                'usageunit_id' => $this->input->post('usageunit_id'),
                'value' => $this->input->post('value'),
                // 'group' => $this->input->post('group'),
                );            
            $this->products_features_m->update($id, $data)
                ? $this->session->set_flashdata('success', sprintf(lang('features:edit_success'), $this->input->post('name')))
                : $this->session->set_flashdata('error', lang('features:edit_error'));

            redirect('admin/products/features');
        }

        // Loop through each rule
        foreach (validation_rules() as $rule)
        {
            if ($this->input->post($rule['field']) !== null)
            {
                $feature->{$rule['field']} = $this->input->post($rule['field']);
            }
        }
        $this->template
            ->title($this->module_details['name'], sprintf(lang('features:edit_title'), $feature->name))
            ->append_js('module::features_categories.js')               
            ->set('feature', $feature)
            ->set('dd', $this->dd_arrays)            
            ->set('mode', 'edit')
            ->build('admin/features/form');
    }        
       

// :::::::::: MODALS ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::    

    /**
     * Create method, creates a new category via ajax
     */
    public function create_cat_feature_ajax()
    {
        $category = new stdClass();
        // Loop through each validation rule
        $this->form_validation->set_rules(validation_rules_cat_features());
        foreach (validation_rules_cat_features() as $rule)
        {
            $category->{$rule['field']} = $this->input->post($rule['field']);
        }
        $data = array(
            'mode' => 'create',
            'category' => $category,
        );
        if ($this->form_validation->run())
        {
            $id = $this->products_features_m->insert_cat_feature_ajax($this->input->post());
            if ($id > 0)
            {
                $message = sprintf(lang('features:addcat_success'), $this->input->post('name', true));
            }
            else
            {
                $message = lang('features:addcat_error');
            }
            return $this->template
                        ->build_json(array(
                            'message' => $message,
                            'name' => $this->input->post('name'),
                            'category_id' => $id,
                            'status' => 'ok'
                        ));
        }
        else
            {            
                // Render the view
                $form = $this->load->view('admin/features/cat_features_form', $data, true);
                $this->form_validation->set_error_delimiters('<p style="padding:10px;background-color:#fbdfde;color:red">', '</p>');                
                if ($errors = validation_errors())
                {
                    return $this->template->build_json(array(
                            'message' => $errors,
                            'status' => 'error',
                            'form' => $form
                            ));
                }              
                echo $form;
            }
    }    
    
// ::::::::::: AJAX :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::     


    /**
     * method to fetch filtered results for account list
     * @access public
     * @return void
    */
    public function ajax_filter()
    {
        //captura post
        $post_data = array(); 
        if($this->input->post('f_keywords'))
        {    
            $post_data['keywords'] = $this->input->post('f_keywords');
        }    
        if($this->input->post('f_cat_product'))
        {
            $post_data['cat_product_id'] = $this->input->post('f_cat_product');                   
        }     
        if($this->input->post('f_cat_feature'))
        {
            $post_data['cat_feature_id'] = $this->input->post('f_cat_feature');                   
        }                    
        //pagination
        $total_rows = $this->products_features_m->search('counts',$post_data);
        //params (URL -for links-, Total records, records per page, segmnet number )
        $pagination = create_pagination('admin/products/features/index', $total_rows, 10, 5);
        $post_data['pagination'] = $pagination;                
        //query with limits
        $features = $this->products_features_m->search('results', $post_data);
        //CONVERT ID TO TEXT
        $features = _convertIDtoText($features, $this->dd_arrays);
        //set the layout to false and load the view
        $this->input->is_ajax_request() ? $this->template->set_layout(FALSE) : '';                         
        $this->template
                ->title($this->module_details['name'], lang('features:list_title'))
                ->append_js('module::features_index.js')
                ->set('features', $features)
                ->set('pagination', $pagination)                
                ->build('admin/features/partials/features');
    }

    
    /**
     * Returns json response with features list, given category ID
     */
    public function get_features_ajax()
    {
        $respond->count = 0;
        $respond->items = array();
        if($this->input->post('cat_id'))
        {             
	//flag
            $respond->status = 'OK';
            $post_data['cat_product_id'] = $this->input->post('cat_id');
            if ($result = $this->products_features_m->search('results',$post_data))
        	{                                
                foreach ($result as $feature)
                {
                    //load stuff
                    $this->load->library(array('features_categories', 'usageunit'));
                    $this->dd_arrays->cat_features_array = $this->features_categories->gen_dd_array();
                    $this->dd_arrays->usageunit_array = $this->usageunit->gen_dd_array();
                    //add fields
                    $feature->usageunit = $this->dd_arrays->usageunit_array[$feature->usageunit_id];
                    $feature->cat_feature = $this->dd_arrays->cat_features_array[$feature->cat_feature_id]; 
                    //add item to array
                    $respond->items[] = $feature;
                    $respond->count++;
                }
        	}                                   
        }            
        echo json_encode($respond);    
    }     
}