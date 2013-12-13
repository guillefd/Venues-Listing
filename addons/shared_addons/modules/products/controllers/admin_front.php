<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package  	PyroCMS
 * @subpackage  Products front
 * @category  	Module
 * @author  	PyroCMS Dev Team
 */
class Admin_Front extends Admin_Controller 
{

	/**
	 * The current active section
	 * @access protected
	 * @var int
	 */
	protected $section = 'front';
	public $dd_array = array();
	public $baseThumbImgUri = '';
	public $imgSize = array();
	public $GCS;

	/**
	 * The constructor
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();	
		$this->load->model('products_front_m');
        $this->load->helper(array('products','products_front'));
		$this->lang->load(array('front','products', 'categories', 'locations','features','spaces'));
		// Load the validation library along with the rules
		$this->load->library(array('form_validation','product_type', 'products', 'categories','features_categories','usageunit','geoworldmap', 'layouts', 'shapes', 'facilities','spaces_usetype'));            
        $this->template->append_css('module::products.css')
                       ->prepend_metadata('<script>var IMG_PATH = "'.BASE_URL.SHARED_ADDONPATH.'modules/'.$this->module.'/img/"; </script>');  		
		$this->dd_array = gen_dropdowns_array();
		$this->GCS = get_GCS_config(); 

// DEBUG ::::::::::::::::::::::::::::::::::::::::::::::
        //$this->output->enable_profiler(TRUE);
// DEBUG ::::::::::::::::::::::::::::::::::::::::::::::   			
	}


	/**
	 * [index Front items List]
	 * @return [type] [description]
	 */
	public function index($typeid = 0)
	{	
		// check product typeid selected
		// if not, redirect to typeid = 1
		if($typeid == 0) redirect('admin/products/front/index/1');
		// load index
 	    $post_data = array();  
	    $post_data['prod_type_id'] = $typeid;    	
	    // Create pagination links
		$total_rows = $this->products_front_m->search_draft('counts', $post_data);
        //params (URL -for links-, Total records, records per page, segment number )
		$pagination = create_pagination('admin/front/index', $total_rows, 10, 6);
        $post_data['pagination'] = $pagination;                
        //query with limits
        $items = $this->products_front_m->search_draft('results',$post_data);
        $items = populate_front_items_ids($items, $this->dd_array); 
        $items = front_status_view($items, $typeid);
		$this->template
			 ->title($this->module_details['name'])
			 ->set('items', $items)
			 ->set('total_rows', $total_rows)
			 ->build('admin/front/index');
	}


	/**
	 * [viewdraft Preview draft before going live]
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function viewdraft($prod_cat_id = 0, $draft_id = 0)
	{
		/* Category and ID ok? */
		if( ($draft_id == 0 || $prod_cat_id == 0) )
		{
			$this->session->set_flashdata(array('error' => sprintf(lang('front:id_error'), $draft_id)));
			redirect('admin/products/front/');
		}
		/* get product_type */
		$typeid = get_product_typeid($prod_cat_id);
		if($typeid === null)
		{
			$this->session->set_flashdata(array('error' => sprintf(lang('products_publish_draft_error_cat_noexist'), $draft_id)));
			redirect('admin/products/front/index/'.$typeid);			
		}		
		$draft = $this->products_front_m->get_front_draft($draft_id, $typeid);		
		/* draft exists? */
		if( $draft == null )
		{
			$this->session->set_flashdata(array('error' => sprintf(lang('front:error_noexist'), $draft_id)));
			redirect('admin/products/front/index/'.$typeid);
		}	
		/* front exists?*/
		$front = $this->products_front_m->get_front_by_prodid($draft->prod_id, $typeid);
		if( $front !== null )
		{	
			/* if exist and version not equal, show update button */
			if($draft->draft_version <= $front->front_version)
			{
				$this->session->set_flashdata(array('error' => sprintf(lang('front:error_alreadyexist_and_updated'), $draft_id)));
				redirect('admin/products/front/index/'.$typeid);				
			}
			else
				{
					$update = true;
				}
		}
		else
			{
				$update = false;
			}		
        $draft = populate_front_publish_item_ids($draft, $this->dd_array);	     		
		//convert layouts result to array
		$draft->facilities_array = convert_item_facilities_to_array($draft->facilities);
		$draft->images_array = gen_imageIDs_array($draft->images);
		$draft->images_params = get_images_metadata_by_ids($draft->images_array);
		$draft->images_size_checked = check_images_before_publish($draft->images_params, $this->GCS->imgSizes); 		
		//convert usetypes result to array
		$draft->usetypes_array = convert_item_usetypes_to_array($draft->usetypes);	
		$this->template
			 ->title($this->module_details['name'])
			 ->set('item', $draft)
			 ->set('update', $update)
			 ->set('typeid', $typeid)
			 ->set('loc_map', $this->geoworldmap->staticmap($draft->loc_lat, $draft->loc_lng))
			 ->set('layouts_list', get_layouts_list())	
			 ->set('usetypes_list', get_spaces_usetypes_list())	
			 ->set('facilities_list', get_facilities_list())
			 ->set('dd_yes_no', $this->dd_array->dd_yes_no)
	    	 ->set('basethumbimguri', $this->GCS->baseThumbImgUri)
	    	 ->set('imgSizes', $this->GCS->imgSizes)	    	 			 
			 // TEMPLATE must exist	 
			 ->build('admin/front/publish/templates/typeid__'.$typeid.'/template');
	}

	/**
	 * [golive publicar draft en vivo
	 * (copia draft a front)
	 * ]
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function golive($draft_id = 0)
	{
	
		$draft_id = intval($draft_id);
		$typeid = intval($this->input->post('type_id'));
		$draftpostid = intval($this->input->post('draft_id'));
		if($draft_id == 0 || $typeid == null || $draft_id != $draftpostid) 
		{
			$this->session->set_flashdata(array('error' => sprintf(lang('front:golive_post_error'), $draft_id)));			
			redirect('admin/products/front/index/1');
		}
		$this->form_validation->set_rules( golive_validation_rules($typeid) );
		if( !$this->form_validation->run() )
		{
			$this->viewdraft($this->input->post('prod_cat_id'), $draft_id);			
		}
		else
			{
				/* Get draft */
				$draft = $this->products_front_m->get_front_draft($draft_id, $this->input->post('type_id'));				
				/* cloud files */
				$cloudimgs = gen_cloud_images_queue($draft, $typeid, $this->GCS);			
				if($cloudimgs['processed'] == false)
				{
					$this->session->set_flashdata(array('error' => sprintf(lang('front:'.$cloudimgs['error']), $cloudimgs['code'])));
					redirect('admin/products/front/viewdraft/'.$draft->prod_cat_id.'/'.$draft_id);						
				}
				/* front exists? */
				$front = $this->products_front_m->get_front_by_prodid($this->input->post('draft_prod_id'), $this->input->post('type_id'));				
				/* if front exist AND version draft not equal version front */
				if($front !== null )
				{	 
					/* if versions equal*/
					if($draft->draft_version == $front->front_version)
					{	
						$this->session->set_flashdata(array('error' => sprintf(lang('front:error_alreadyexist_and_updated'), $id)));
						redirect('admin/products/front/index/'.$typeid);				
					}
					else
						{
							/* update front */
							$processMode = 'processupdate';
							$draft->frontid = $front->id;						
						}
				}
				else
					{
						/* insert front */
						$processMode = 'processcreate';	
						//$this->insert_front($draft_id, $this->input->post('type_id'), $this->input->post('prod_cat_id'));
					}				
				$this->template
					 ->title($this->module_details['name'])
	    			 ->append_js('module::products_golive.js')
	    			 ->append_css('module::jquery-ui-progressbar.css')				 
					 ->set('draft', $draft)
					 ->set('typeid', $typeid)
					 ->set('processMode', $processMode)
					 ->set('cloudimgsQTY', count($cloudimgs['objects']))    	 			 
					 // TEMPLATE must exist	 
					 ->build('admin/front/publish/templates/typeid__'.$typeid.'/golive');					
			}
	}


	/**
	* Preview Product Images
	* @access public
	* @param int $id the ID of the location
	* @return void
	*/
	public function previewimage()
	{
		$imgdata = $this->input->get(); 
	    // set template
	    $this->template
	    ->set_layout('modal','admin')
	    ->set('basethumbimguri', $this->GCS->baseThumbImgUri)
	    ->set('imgdata', $imgdata)
	    ->set('imgsizes', $this->GCS->imgSizes)
	    ->build('admin/front/partials/preview_images');                         
	} 


	/**
	* Delete - no se borra, se deja inactivo
	* @param type $id 
	*/
	public function gooffline($prod_cat_id = 0, $front_id = 0)
	{
		if( ($front_id == 0 || $prod_cat_id == 0) )
		{
			$this->session->set_flashdata(array('error' => sprintf(lang('front:id_error'), $front_id)));
			redirect('admin/products/front/index/1');
		}
		/* get product_type */
		$typeid = get_product_typeid($prod_cat_id);
		if($typeid === null)
		{
			$this->session->set_flashdata(array('error' => sprintf(lang('products_publish_draft_error_cat_noexist'), $front_id)));
			redirect('admin/products/front/index/1');		
		}			
		/* front exists? */
		$front = $this->products_front_m->get_front_by_id($front_id, $typeid);				
		if( $front === null )
		{	
			$this->session->set_flashdata(array('error' => sprintf(lang('front:error_noexist'), $front_id)));
			redirect('admin/products/front/index/'.$typeid);				
		}			
		// $this->load->model(array('products_front_m'));	    	
  //       if($this->products_front_m->delete_front($front_id, $typeid))
		// {
		// 	$this->session->set_flashdata(array('success' => sprintf(lang('front:delete_success'), $front_id)));			
		// }	
		// else
		// 	{
		// 		$this->session->set_flashdata(array('error' => sprintf(lang('front:delete_error'), $front_id)));				
		// 	}
		$this->template
			 ->title($this->module_details['name'])
			 ->append_js('module::products_gooffline.js')
			 ->append_css('module::jquery-ui-progressbar.css')				 
			 ->set('front', $front)
			 ->set('typeid', $typeid)	 			 
			 // TEMPLATE must exist	 
			 ->build('admin/front/publish/templates/typeid__'.$typeid.'/gooffline');	
	}



//////////////////////////////////////////////////
// AJAX - create, update or offline process -// //
//////////////////////////////////////////////////

	public function ajx_golive_gooffline_process()
	{
		$result = new stdClass();
		$result->done = null;
		$result->finished = false;
		$result->status = null;
		$result->data = null;				
		$index = $this->input->post('index');
		$process = $this->input->post('process');
		$processMode = $this->input->post('processMode');
		if($index !== null && $process !== null)
		{
			switch ($process)
			{
				/* golive */
				case 'cloudimages':     
										$result = $this->process_session_cloud_images_queue($index);
										break;

				case 'checkcloudimages':
										//if update, delete existing images
										if($processMode =='update')
										{
											$this->aux_delete_cloud_images_pre_update($this->input->post('draftid'), $this->input->post('typeid'), $this->input->post('frontid'));
										}				
										$result = $this->check_uploaded_cloud_images();
										break;						

				case 'processupdate':
										$result = $this->ajx_update_front($this->input->post('draftid'), $this->input->post('typeid'), $this->input->post('frontid'));			
										break;

				case 'processcreate':
										$result = $this->ajx_create_front($this->input->post('draftid'), $this->input->post('typeid'));			
										break;	

				/* gooffline */						

				case 'deletecloudimages':
										$result = $this->ajx_delete_front_cloud_images($this->input->post('frontid'), $this->input->post('typeid'));	
										break;	

				case 'unpublishproduct':  	
			  							$result = $this->ajx_delete_front_product($this->input->post('frontid'), $this->input->post('typeid'));
										break;						
										
				default: 				$result->done = false;
										$result->status = 'process.undefined';
										$result->data = null;
			}
		}
		else
			{
				log_message('error','ajx_golive_process:post('.$process.') null');
				$result->status = 'post.undefined';
			}	
		echo json_encode($result);
	}


	public function process_session_cloud_images_queue($index)
	{
		$result = new stdClass();
		$cloud_array = get_queueVar_session();
		if(isset($cloud_array[$index]))
		{
			set_time_limit(600);
			//load Google API library
			$this->load->library('googleapiclient');
			$queue = $cloud_array[$index];	
			$obj = $this->googleapiclient->insert_object($queue['bucket'], $queue['fileUri'], $queue['name'], $queue['contentType']);	
			if($obj == false)
			{
				$result->done = false;
				$result->status = 'error.api';
				$result->data = $obj->message;				
			}
			else
				{
					if(isset($obj['id']))
					{	
						// add reg to array
						$queue['gcsName'] = $obj['name'];
						$cloud_array[$index] = $queue;
						if(save_queueVar_to_sessionVar($cloud_array))
						{
							$result->done = true;
							$result->status = 'done';
							$result->data = $obj['generation'];	
						}
						else
							{
								$result->done = false;
								$result->status = 'error.sessVarUpdate';
							}
					}
					else
						{
							$result->done = false;
							$result->status = 'error.no-obj';						
						}				
				}
		}
		else
			{
				$result->done = false;			
				$result->status = 'error.empty.queues';
				$result->data = 'session array empty OR wrong index';				
				log_message('error','$cloud_array empty');
			}
		return $result;	
	}


	public function check_uploaded_cloud_images()
	{
		$result = new stdClass();
		$result->done = true;
		$result->status = '';
		if(check_validUploadedImages_queueVar_session() == false)
		{
			$result->done = false;
			$result->status = 'error.gcsid';				
		}
		return $result;		
	}

	public function ajx_update_front($draft_id, $type_id, $front_id)
	{
		$result = new stdClass();		
		$gcs_imgids_bysize_array = get_uploaded_cloud_images_ids_bySize();
		if($gcs_imgids_bysize_array == null)
		{
			$result->done = false;
			$result->status = 'error.imgCloudArray';					
		}
		else
			{
				$front_img_bySize = gen_front_imgString_bySize($gcs_imgids_bysize_array);
				$db = $this->products_front_m->update_product_front($draft_id, $type_id, $front_id, $front_img_bySize);
				if($db === true)
				{
					$result->done = true;
					$result->finished = true;
					delete_queueVar_session();						
				}
				else
					{
						$result->done = false;
						$result->status = 'error.db.update';									
					}
			}
		return $result;			
	}


	public function ajx_create_front($draft_id, $type_id)
	{
		$result = new stdClass();		
		$gcs_imgids_bysize_array = get_uploaded_cloud_images_ids_bySize();
		if($gcs_imgids_bysize_array == null)
		{
			$result->done = false;
			$result->status = 'error.imgCloudArray';					
		}
		else
			{
				$front_img_bySize = gen_front_imgString_bySize($gcs_imgids_bysize_array);
				$db = $this->products_front_m->insert_product_front($draft_id, $type_id, $front_img_bySize);
				if($db === true)
				{
					$result->done = true;
					$result->finished = true;
					delete_queueVar_session();						
				}
				else
					{
						$result->done = false;
						$result->status = 'error.db.create';									
					}
			}
		return $result;			
	}	

	public function ajx_delete_front_cloud_images($frontid, $typeid)
	{
		$result = new stdClass();
		$front = $this->products_front_m->get_front_by_id($frontid, $typeid);
		if($front == null)
		{
			$result->done = false;
			$result->status = 'error.frontid.noexist';
			$result->data = sprintf(lang('front:error_noexist'),$frontid);	
		}
		else
			{
				//load Google API library
				$this->load->library('googleapiclient');				
				$GCS_config = get_GCS_config();
				set_time_limit(600);
				$imgCloudArray = extract_front_images_grouped($front);
				$apiResponse->result = null;
				$apiResponse->data = array();
				foreach($imgCloudArray as $objName)
				{
					$response = $this->googleapiclient->delete_object($GCS_config->buckets[$typeid], $objName);
					if($response !== null)
					{
						$apiResponse->result = false;
					}
				 	$apiResponse->data[] = $response;	
				}
				if($apiResponse->result == null)
				{
					$result->done = true;
					$result->status = 'cloudimages.deleted';
					$result->data = '';				
				}
				else
				{
					log_message('error','ajx_delete_front_cloud_images[frontid-'.$frontid.'.typeid-'.$typeid.']:apiresponse('.$apiResponse.') not null');
					$result->done = true; // true, so delete can go on. We logged the error.
					$result->status = 'error.api.delete';
					$result->data = $apiResponse;
				}					
			}
		return $result;
	}	


	public function ajx_delete_front_product($frontid, $typeid)
	{
		$result = new stdClass();
        if($this->products_front_m->delete_front($frontid, $typeid))
        {
			$result->done = true;
			$result->status = 'product.'.$frontid.'.deleted';
			$result->data = '';
        }
        else
	        {
				$result->done = false;
				$result->status = 'error.deleted';
				$result->data = '';
	        }
	    return $result;    
	}


	public function aux_delete_cloud_images_pre_update($draftid = 0, $typeid = 0, $frontid = 0)
	{
		$front = $this->products_front_m->get_front_by_id($frontid, $typeid);
		$draft = $this->products_front_m->get_front_draft($draftid, $typeid);		
		if($front == null || $draft == null)
		{
			return false;	
		}		
		$oldCloudImgsArray = extract_front_images_grouped($front);
		$queue_cloud_images = get_session_cloud_images_grouped();
		$oldCloudImgsToDelete = array();
		foreach($oldCloudImgsArray as $img)
		{
			//if new img not found, include for deletion
			if(!in_array($img, $queue_cloud_images))
			{
				$oldCloudImgsToDelete[] = $img;
			}
		}
		$response = array();
		if(count($oldCloudImgsToDelete)>0)
		{
			//load Google API library
			$this->load->library('googleapiclient');	
			$GCS_config = get_GCS_config();						
			set_time_limit(600);
			foreach($oldCloudImgsToDelete as $objName)
			{
				$response[] = $this->googleapiclient->delete_object($GCS_config->buckets[$typeid], $objName);
			}			
		}
		return $response;
	}


	//////////////////////////////////////////////////////////////////////
	// BUCKETS GO PUBLIC -------------------------------------------// //
	//////////////////////////////////////////////////////////////////////

	/**
	 * [set_buckets_public_access description]
	 * This does need to be execetud only one, when public bucket is created.
	 */
	public function _set_buckets_public_access()
	{
		$result = array();
		//load Google API library
		$this->load->library('googleapiclient');				
		$GCS_config = get_GCS_config();
		foreach ($GCS_config->buckets as $key=>$bucket)
		{
			$result[$key] = $this->googleapiclient->insert_defaultObjectAccessControl($bucket, 'allUsers', 'READER');
		}
		var_dump($result);
	}


}