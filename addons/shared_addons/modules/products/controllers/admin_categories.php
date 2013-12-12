<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package  	PyroCMS
 * @subpackage  Categories
 * @category  	Module
 * @author  	PyroCMS Dev Team
 */
class Admin_Categories extends Admin_Controller {

	/**
	 * The current active section
	 * @access protected
	 * @var int
	 */
	protected $section = 'categories';
	
	/**
	 * The constructor
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('products_categories_m');
        $this->load->helper(array('products_categories'));
		$this->lang->load(array('products','categories','locations','features','spaces'));
		
		// Load the validation library along with the rules
		$this->load->library(array('form_validation','product_type'));
		$this->form_validation->set_rules(validation_rules());
        // template addons
        $this->template->append_css('module::products.css')
                       ->prepend_metadata('<script>var IMG_PATH = "' . BASE_URL . SHARED_ADDONPATH . 'modules/' . $this->module . '/img/"; </script>');               
	}
        
	/**
	 * Index method, lists all categories
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->pyrocache->delete_all('modules_m');
		$_type_array = _gen_dropdown_list();
		// Create pagination links
		$total_rows = $this->products_categories_m->count_all();
		$pagination = create_pagination('admin/products/categories/index', $total_rows, NULL, 5);
			
		// Using this data, get the relevant results
		$categories = $this->products_categories_m->order_by('title')->limit($pagination['limit'])->get_all();

		$this->template
			->title($this->module_details['name'], lang('cat_list_title'))
			->append_js('module::categories_index.js')
			->set('categories', $categories)
			->set('pagination', $pagination)
            ->set('type_array', $_type_array)
			->build('admin/categories/index');
	}
	
	/**
	 * Create method, creates a new category
	 * @access public
	 * @return void
	 */
	public function create()
	{	
		// check title
		$this->form_validation->set_rules('title','lang:cat_title_label','trim|required|callback__check_title'); 		
		// Validate the data
		if ($this->form_validation->run())
		{
			$this->products_categories_m->insert($this->input->post())
			? $this->session->set_flashdata('success', sprintf( lang('cat_add_success'), $this->input->post('title')) )
			: $this->session->set_flashdata('error', lang('cat_add_error'));
			redirect('admin/products/categories');
		}		
		// Loop through each validation rule
		foreach (validation_rules() as $rule)
		{
			$category->{$rule['field']} = $this->input->post($rule['field']);
		}
	    $_type_array = _gen_dropdown_list();
		$this->template
			->title($this->module_details['name'], lang('cat_create_title'))
			->set('category', $category)
			->set('type_array', $_type_array)
			->build('admin/categories/form');	
	}
	
	/**
	 * Edit method, edits an existing category
	 * @access public
	 * @param int id The ID of the category to edit
	 * @return void
	 */
	public function edit($id = 0)
	{	
		// Get the category
		$category = $this->products_categories_m->get($id);
		// ID specified?
		$category or redirect('admin/products/categories/index');
		// check title
		$this->form_validation->set_rules('title','lang:cat_title_label','trim|required|callback__check_title['.$id.']');               
		if ($this->form_validation->run())
		{							
			$this->products_categories_m->update($id, $this->input->post())
			? $this->session->set_flashdata('success', sprintf( lang('cat_edit_success'), $this->input->post('title')) )
			: $this->session->set_flashdata('error', lang('cat_edit_error'));
			redirect('admin/products/categories/index');
		}
		// Loop through each rule
		foreach (validation_rules() as $rule)
		{
			if ($this->input->post($rule['field']) !== null)
			{
				$category->{$rule['field']} = $this->input->post($rule['field']);
			}
		}	
	    $_type_array = _gen_dropdown_list();	    
		$this->template
			->title($this->module_details['name'], sprintf(lang('cat_edit_title'), $category->title))
			->set('category', $category)
			->set('type_array', $_type_array)		
			->build('admin/categories/form');
	}	

	/**
	 * Delete method, deletes an existing category (obvious isn't it?)
	 * @access public
	 * @param int id The ID of the category to edit
	 * @return void
	 */
	public function delete($id = 0)
	{	
		$id_array = (!empty($id)) ? array($id) : $this->input->post('action_to');
		
		// Delete multiple
		if (!empty($id_array))
		{
			$deleted = 0;
			$to_delete = 0;
			foreach ($id_array as $id)
			{
				if ($this->products_categories_m->delete($id))
				{
					$deleted++;
				}
				else
				{
					$this->session->set_flashdata('error', sprintf(lang('cat_mass_delete_error'), $id));
				}
				$to_delete++;
			}
			
			if ( $deleted > 0 )
			{
				$this->session->set_flashdata('success', sprintf(lang('cat_mass_delete_success'), $deleted, $to_delete));
			}
		}		
		else
		{
			$this->session->set_flashdata('error', lang('cat_no_select_error'));
		}
		
		redirect('admin/products/categories/index');
	}

// VALIDATIONS ::::::::::::::::::::::::::::::::::::::::::::::::::
		
	/**
	 * Callback method that checks the title of the category
	 * @access public
	 * @param string title The title to check
	 * @return bool
	 */
	public function _check_title($title = '', $id = 0)
	{
		if ( $this->products_categories_m->check_title( $title, $id ) )
		{
			$this->form_validation->set_message('_check_title', sprintf(lang('cat_already_exist_error'), $title));
			return FALSE;
		}
		return TRUE;
	}

// AJAX ::::::::::::::::::::::::::::::::::::::::::::::::

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
        if($this->input->post('f_product_type'))
        {
            $post_data['product_type_id'] = $this->input->post('f_product_type');                   
        }                        
        //pagination
        $total_rows = $this->products_categories_m->search('counts',$post_data);
        //params (URL -for links-, Total records, records per page, segmnet number )
        $pagination = create_pagination('admin/products/categories/index', $total_rows, 10, 5);
        $post_data['pagination'] = $pagination;                
        //query with limits
        $categories = $this->products_categories_m->search('results', $post_data);
        $_type_array = _gen_dropdown_list();      
        //set the layout to false and load the view
        $this->input->is_ajax_request() ? $this->template->set_layout(FALSE) : '';                         
        $this->template
			    ->title($this->module_details['name'], lang('cat_list_title'))
			    ->set('categories', $categories)
                ->set('pagination', $pagination)   
				->set('type_array', $_type_array)                             
                ->build('admin/categories/partials/categories');
    }

	
	/**
	 * Create method, creates a new category via ajax
	 * @access public
	 * @return void
	 */
	public function create_ajax()
	{
		// Loop through each validation rule
		foreach (validation_rules() as $rule)
		{
			$category->{$rule['field']} = set_value($rule['field']);
		}
		
		$this->data->method = 'create';
		$this->data->category =& $category;
		
		if ($this->form_validation->run())
		{
			$id = $this->products_categories_m->insert_ajax($_POST);
			
			if ($id > 0)
			{
				$message = sprintf( lang('cat_add_success'), $this->input->post('title'));
			}
			else
			{
				$message = lang('cat_add_error');
			}

			return $this->template->build_json(array(
				'message'		=> $message,
				'title'			=> $this->input->post('title'),
				'category_id'	=> $id,
				'status'		=> 'ok'
			));
		}	
		else
		{
			// Render the view
			$form = $this->load->view('admin/categories/form', $this->data, TRUE);

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
}