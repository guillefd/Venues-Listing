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
						$modalform = $this->load->view('frontend/modals/spacemodalform', $this->front->page, true);
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
						$this->template
							->title($this->module_details['name'])
							->set_layout('L_item_cat_1')
							->set('data', $this->front->page)
							->set('item', $this->front->page->result->item)
							->set('facilitiesArr', $this->front->page->get_categoryauxiliars('facilities'))
							->set('layoutsArr', $this->front->page->get_categoryauxiliars('layouts'))
							->build($this->front->page->view['view']);
						break;	
			default: redirect('/');										
		}

	}



	///////////////////////////////////////////////////
	// SNED MESSAGE (TO BE IMPROVED) ------------// //
	///////////////////////////////////////////////////

	public function send_message_ajax()
	{
		$this->load->model('products_frontend_1_m');		
		$validation_rules = array(
	                                array(
	                                    'field' => 'name',
	                                    'label' => 'lang:front:form-name',
	                                    'rules' => 'trim|required'
	                                ), 
	                                array(
	                                    'field' => 'email',
	                                    'label' => 'lang:front:form-email',
	                                    'rules' => 'trim|valid_email|required'
	                                ), 
	                                array(
	                                    'field' => 'message',
	                                    'label' => 'lang:front:form-message',
	                                    'rules' => 'trim|required'
	                                ),                                                                 
                            	);	
        //json response
        $data->response = null;
        $this->form_validation->set_rules($validation_rules);           
        // Validate the data
        if($this->form_validation->run())
        {
        	$item = $this->get_frontitem();
        	if($item)
        	{
        		if($this->process_message($item)=== true)
        		{
		            $data->response = true;
		            $data->Error = false;
		            $data->message = 'Mensaje enviado.';         			
        		}
        		else
	        		{
			            $data->response = true;
			            $data->Error = true;
			            $data->message = 'Error al enviar el mensaje, vuelve a intentarlo.';   
	        		}
        	}
        	else
	        	{
		            $data->response = true;
		            $data->Error = true;
		            $data->message = 'Error (codigo:itemNotFound)';
	        	}      	
		}
		else
			{
	            $data->response = true;
	            $data->Error = true;
	            $data->message = validation_errors(); 
			} 		
        echo json_encode($data);		
	}


	private function get_frontitem()
	{		
		switch($this->input->post('dataFviewid'))
		{
			case '300':		
						$data = array(
										'prod_cat_slug'=>$this->input->post('dataFprod_cat_slug'),
										'loc_city_slug'=>$this->input->post('dataFloc_city_slug'),
										'loc_slug'=>$this->input->post('dataFloc_slug'),
										'space_slug'=>$this->input->post('dataFspace_slug'),																																	
										);
						return $this->products_frontend_1_m->get_item_space_MSG($data);
						break;
			default: 
						return false;				
		}
	}


	private function process_message($item)
	{
		$data = array(
						'prod_cat_slug'=>$item->prod_cat_slug,
						'loc_city_slug'=>$item->loc_city_slug,
						'loc_slug'=>$item->loc_slug,
						'space_slug'=>$item->space_slug,
						'loc_name'=>$item->loc_name,
						'loc_id'=>$item->loc_id,
						'space_id'=>$item->space_id,
						'space_name'=>$item->space_name,	
						'reference'=>$this->input->post('dataFreference'),						
					);
		$db_data = array(
						'prod_cat_id'=>$item->prod_cat_id,
						'prod_account_id'=>$item->account_id,
						'prod_id'=>$item->prod_id,
						'front_version'=>$item->front_version,
						'view_id'=>$this->input->post('dataFviewid'),
						'data'=>serialize($data),
						'message'=>$this->input->post('message'),
						'account_agent_email'=>$this->get_product_agent_email($item),
						'sender_email'=>$this->input->post('email'),
						'sender_name'=>$this->input->post('name'),
						'subject'=>'Nueva consulta para '.$item->space_denomination.'-'.$item->space_name.'@'.$item->loc_slug.' de #'.$this->input->post('name'),
					);
		$this->products_frontend_1_m->save_message_data($db_data);
		return $this->send_email($db_data, $item, 'location');
	}


	private function get_product_agent_email($item)
	{
		if($item->seller_account_id > 0)
		{
			$this->load->library('accounts');	
			$account = get_account($item->seller_account_id);	
			return $account->email;				
		}
		else
			{
				return $item->loc_email;
			}
	}


    public function send_email($data, $item, $target)
    { 
        //config
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'utf-8';
        $this->email->initialize($config);        

        switch($target)
        {
            case 'location':
                        //message
                        $from      = $data['sender_email'];
                        $from_name = $data['sender_name'];
                        $reply_to  = $data['sender_email'];
                        $to        = $data['account_agent_email'];
                        $subject   = $data['subject'];
                        $body      = $this->gen_email_message($data, $target, $item); 
                        break;
            case 'sender':
                        //message
                        $from      = 'info@amrooms.com';
                        $from_name = 'America Meeting Rooms';
                        $reply_to  = 'info@amrooms.com';
                        $to        = $data['sender_email'];
                        $subject   = $data['subject'];
                        $body      = $this->gen_email_message($data, $target, $item); 
                        break;                        
        }        
//print_r($body);
//die;
        //send
        $this->email->from($from, $from_name);
        $this->email->reply_to($reply_to);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($body);        
        return $this->email->send();        
    } 

    public function gen_email_message($data, $target, $item)
    {
        date_default_timezone_set('America/Buenos_Aires');
        $now = time();
        switch($target)
        {
            case 'location':
                            $html = '<div style="padding:0">
										<span style="color:#fff;font-size:1px;display:none!important">Respóndele rápido para aumentar tus ventas.</span>  
										<table width="100%" cellspacing="0" cellpadding="0" border="0"> 
											<tbody>
												<tr> 
													<td align="left" style="padding:10px 20px 15px 0"> 
														<a href="http://www.americameetingrooms.com" target="_blank">
															<img alt="MercadoLibre" border="0" width="250" height="73" src="http://cdn.spaces.americameetingrooms.com/logos/AMR-sm-short.png"></a> 
													</td> 
												</tr> 
												<tr> 
													<td width="100%" height="1" style="border-top:solid 1px #e8e8e8;display:block;font-size:1px">&nbsp;
													</td> 
												</tr> 
												<tr>
													<td height="30" style="font-size:1px">&nbsp;</td>
												</tr> 
											</tbody>
										</table>  
										<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding-right:20px"> 
											<tbody>
												<tr> 
													<td> 
														<span style="color:#333333;font-size:14px;font-family:Arial">Hola "'.$item->loc_name.'",</span> 
													</td> 
												</tr> 
												<tr>
													<td height="10" style="font-size:1px">&nbsp;</td>
												</tr> 
												<tr> 
													<td> 
														<span style="margin:0;display:block;color:#333333;padding:0 0 5px 0;color:#333333;font-family:Arial;font-size:14px">
														Tienes una nueva consulta para <strong>'.$item->space_denomination.' '.$item->space_name.'</strong>:
														</span>
													</td>
													<td> 
													</td>
												</tr> 
												<tr> 
													<td colspan="2">
														<div style="border: 1px solid #ededed;margin:10px;padding:10px;"> 
															<span style="margin:0;display:block;color:#333333;padding:0 0 5px 0;color:#333333;font-family:Arial;font-size:14px">
															<p><strong>Consulta:</strong></p> '.$data['message'].'
															</span>
															<span style="margin:0;display:block;color:#333333;padding:0 0 5px 0;color:#333333;font-family:Arial;font-size:14px">
															<p><strong>Nombre:</strong></p> '.$data['sender_name'].'
															</span>
															<span style="margin:0;display:block;color:#333333;padding:0 0 5px 0;color:#333333;font-family:Arial;font-size:14px">
															<p><strong>Email:</strong></p> '.$data['sender_email'].'
															</span>
														</div>
													</td>
												</tr>												
												<tr> 
													<td height="10" style="font-size:1px">&nbsp;</td> 
												</tr> 
											</tbody>
										</table> 
										<table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top:40px;"> 
											<tbody>
												<tr>
													<td> 
														<div style="color:#333333;font-family:Arial;font-size:12px;margin-bottom:25px;">Respóndele rápido para aumentar tus ventas.</div>
													</td>
												</tr>
												<tr> 
													<td> 
														<span style="color:#333333;font-family:Arial;font-size:14px">Saludos,</span><br> 
														<span style="color:#333333;font-family:Arial;font-size:14px"><a href="http://www.americameetingrooms.com">America Meeting Rooms</a></span> 
													</td> 
												</tr> 
												<tr>
													<td height="15" style="font-size:1px">&nbsp;</td>
												</tr> 
											</tbody>
										</table>   
										<table width="100%" cellspacing="0" cellpadding="0" border="0"> 
											<tbody>
												<tr> 
													<td width="100%" style="border-bottom:solid 1px #e8e8e8;display:block;padding-top:5px"></td> 
												</tr> 
												<tr> 
													<td style="padding:10px 0 0 0">
														<span style="color:#999999;font-size:11px;font-family:Arial">Tenes preguntas? Contactanos a <a href="mailto:info@amrooms.com?Subject=[consulta de locatario]" target="_top">info@amrooms.com</a>.</span></td> </tr> 
												<tr>
													<td height="30" style="font-size:1px">&nbsp;</td>
												</tr> 
											</tbody>
										</table> 
									</div>';
                            break;
            case 'sender':    
                            $html = '';
                                    break;
        }
        return $html;        
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
