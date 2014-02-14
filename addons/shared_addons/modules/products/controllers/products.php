<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Products extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();
		//load stuff	
		$this->load->helper(array('url','frontend'));
		$this->lang->load('front_live');
		$this->load->library(array('geoworldmap', 'categories', 'spaces_usetype','locations_type', 'facilities', 'front', 'layouts', 'features_categories'));		
		// category constants //improve?? as is fixed value, why waste a db search?	
		define('ALQUILERDESALAS_CATID', 100); 
		define('ALQUILERDESALONES_CATID', 101);
		define('ALQUILEREQUIPOAV_CATID', 102);		
	}


///////////////////////////////////////////////////////
// INDEX  -------------------------------------- // //
///////////////////////////////////////////////////////

	/**
	 * [index check category and send to index_router]
	 * @return [type]
	 */
	public function index($prodCatID = 0)
	{		
		switch($prodCatID)
		{
			case ALQUILERDESALAS_CATID: 
				    				$this->load->model('products_frontend_1_m');			    									
									break;
			default: redirect('/catidUndefined');
		}
		$this->front->init_page($prodCatID);
		$this->front->init_cfg();					
		//$this->___dump(__METHOD__.' line:'.__LINE__); /* --------------------TESTpoint--------------------- */ 	
		products::index_router();
	}
	

	/**
	 * [index_router process URI, get segments clean and valid, send to search]
	 * @param  [type] $productcategoryid [description]
	 * @return [type]                    [description]
	 */
	public function index_router()
	{			
				//process URI
		$urisegments = $this->front->validate_urisegments();		
				// if not valid uri, redirect 404
		if(empty($urisegments) )
		{
			redirect('/');
		}
				//process URI values				
		if( $this->front->validate_urisegments_values($urisegments) == false )
		{
			redirect('/404'); 
		}		
				//set uri
		$this->front->page->set_validurisegments($urisegments);					
				//process URI filters
		$this->front->page->set_validurifilters($this->front->validate_urifilters());	
				//$this->___dump(__METHOD__.' line:'.__LINE__); /* --------------------TESTpoint--------------------- */ 
				//go on, search with valid segments and URIs
		$view = $this->front->route_request_view();		
		if($view === null)
		{
			redirect('/viewUndefined');			
		}
		$this->front->page->set_view($view);			
				//$this->___dump(__METHOD__.' line:'.__LINE__); /* --------------------TESTpoint--------------------- */ 	
		products::search();
		$this->front->set_search_params();		
				//$this->___dump(__METHOD__.' line:'.__LINE__); /* --------------------TESTpoint--------------------- */ 		
		switch($this->front->page->catid)
		{
			case ALQUILERDESALAS_CATID: 	
										products::build_and_load_view_cat1();	
										break;

			default:
										redirect('/buildloadUndefined');								
		}		
	}


///////////////////////////////////////////////////////
// SEARCH  -------------------------------------- // //
///////////////////////////////////////////////////////
	

	public function search()
	{
		switch($this->front->page->catid)
		{
			case ALQUILERDESALAS_CATID: 	
									$this->front->page->set_uri_page_number_requested();						
									$result = $this->front->cat1_search();	
									break;

			default:
									log_message('error','search() case not defined');						
		}
				//set result in page object
		$this->front->page->set_result($result);	
	}

///////////////////////////////////////////////////////
// BUILD AND LOAD VIEWS - BY CATEGORIES --------- // //
///////////////////////////////////////////////////////

	/**
	 * [index_view prepare and build VIEW for CAT1]
	 * @param  [type] $productcategoryid [description]
	 * @param  [type] $data              [description]
	 * @return [type]                    [description]
	 */
	public function build_and_load_view_cat1()
	{		
		switch($this->front->page->view['id'])
		{
			/* BUILD VIEW -------------------- */
			case 100:
			case 200:			
						$this->front->create_pagination();	
						$this->front->load_media_resources();											
						$this->front->format_and_populate_result_for_view();
						$this->front->load_listMap();					
						$this->front->load_listview_messages();												
						$this->front->set_htmlfilterdata();					
						$filtervalues = $this->front->populate_filter_values();										
						$filter = $this->load->view('frontend/list_cat_1/filter', $this->front->page->htmlfilterdata, true);													
						break;			
			case 300:
			case 400:				
						if( $this->front->page->get_item_result() )
						{
							$this->front->load_itemMap();
							$this->front->load_media_resources();						
							$this->front->format_and_populate_result_for_view();	
						}					
						else
							{
								$this->front->redirect_to_last_list_view();
							}
						break;

			default: redirect('/');							
		}
		//$this->___dump(__METHOD__.' line:'.__LINE__); /* --------------------TESTpoint--------------------- */ 										
		switch($this->front->page->view['id'])
		{
			/* LOAD VIEW -------------------- */			
			case 100: 								
						if($this->input->is_ajax_request())	
						{
							$result = new stdClass();
					        if($this->front->page->result->list->numrows>0)
					        {
					        	$result->result = true;
								$result->html = $this->load->view('frontend/list_cat_1/spaces_table', $this->front->page, true);						
								$result->map = $this->front->page->map;
								$result->pagination = $this->front->page->pagination;
							}	
							else
								{
									$result->result = false;
								}	
							echo json_encode($result);
						}
						else
							{
							$table = $this->load->view('frontend/list_cat_1/spaces_table', $this->front->page, true);
							$this->template
								->title($this->module_details['name'])
								->set_layout('L_list_cat_1')
								->set('data', $this->front->page)
								->set('filtervalues', $filtervalues)
								->set('filter', $filter)
								->set('table', $table)
								->set('htmlFilterArr', $this->front->page->htmlfilterdata)							
								->build($this->front->page->view['view']);		
							}
						break;
	
			case 200: 			
						if($this->input->is_ajax_request())	
						{
							$result = new stdClass();
					        if($this->front->page->result->list->numrows>0)
					        {
					        	$result->result = true;
								$result->html = $this->load->view('frontend/list_cat_1/products_table', $this->front->page, true);						
								$result->map = $this->front->page->map;
								$result->pagination = $this->front->page->pagination;
							}	
							else
								{
									$result->result = false;
								}	
							echo json_encode($result);
						}
						else
							{			
								$table = $this->load->view('frontend/list_cat_1/products_table', $this->front->page, true);
								$this->template
									->title($this->module_details['name'])
									->set_layout('L_list_cat_1')
									->set('data', $this->front->page)
									->set('filtervalues', $filtervalues)										
									->set('filter', $filter)										
									->set('table', $table)
									->set('htmlFilterArr', $this->front->page->htmlfilterdata)
									->build($this->front->page->view['view']);		
							}		
						break;

			case 300:			
						$modalform = $this->load->view('frontend/modals/form300query', $this->front->page, true);
						$this->template
							->title($this->module_details['name'])
							->set_layout('L_item_cat_1')
							->set('data', $this->front->page)
							->set('item', $this->front->page->result->item)
							->set('facilitiesArr', $this->front->page->get_categoryauxiliars('facilities'))
							->set('layoutsArr', $this->front->page->get_categoryauxiliars('layouts'))
							->set('modalform', $modalform)
							->build($this->front->page->view['view']);
						break;

			case 400:
						$modalform = $this->load->view('frontend/modals/form400query', $this->front->page, true);			
						$this->template
							->title($this->module_details['name'])
							->set_layout('L_item_cat_1')
							->set('data', $this->front->page)
							->set('item', $this->front->page->result->item)
							->set('facilitiesArr', $this->front->page->get_categoryauxiliars('facilities'))
							->set('layoutsArr', $this->front->page->get_categoryauxiliars('layouts'))
							->set('modalform', $modalform)							
							->build($this->front->page->view['view']);
						break;	
			default: redirect('/');										
		}

	}


	//////////////////////////////////////////
	// MESSAGING AJAX CALL ------------ // //
	//////////////////////////////////////////

	public function process_product_message_ajax_request($prodCatID = 0)
	{
		$data = new stdClass();
		switch($prodCatID)
		{
			case ALQUILERDESALAS_CATID: 
											$params = array('prodcatid'=>$prodCatID);
						    				$this->load->library('messaging', $params);			
						    				$this->messaging->process_request();	    									
											break;
			default: 
								            $data->response = true;
								            $data->Error = true;
								            $data->message = 'Error (codigo:catidNotExist)';
								            return $data;
		}
        echo json_encode($this->messaging->result);			
	}



	//////////////////////////////
	// AUX -----------------// //
	//////////////////////////////

	/* Testing porpose */
	private function ___dump($dump = '')
	{
		print '<pre>'.__FILE__.' <br> '.$dump.'</pre>';
		var_dump($this->front->page, $this->front->CFG);
		// first result if isset
		var_dump($this->front->page->get_result());
		die;	
	}



}
