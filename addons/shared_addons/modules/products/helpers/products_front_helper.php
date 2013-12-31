<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * [gen_dropdowns_array 
 * generates products dropdowns]
 * @return [type] [description]
 */
function gen_dropdowns_array()
{
    $dd_array = new stdClass();
    $dd_array->type_array = ci()->product_type->gen_dd_array();
    // categoria de espacio a publicar ----------------
    $dd_array->spaces_usetype_array = ci()->spaces_usetype->gen_dd_array();  
    /* ----------------------------------------------- */
    $dd_array->type_with_cat_products_multiarray = ci()->categories->gen_dd_multiarray();
    $dd_array->cat_products_array = ci()->categories->gen_dd_array();
    $dd_array->cat_features_array = ci()->features_categories->gen_dd_array();
    $dd_array->usageunit_array = ci()->usageunit->gen_dd_array();  
    $dd_array->dd_yes_no  = gen_dd_yes_no_filter();
    $dd_array->dd_status = gen_dd_status();     
	$dd_array->hours = array_combine($hours = range(0, 23), $hours);
	$dd_array->minutes = array_combine($minutes = range(0, 59), $minutes);
	return $dd_array;   		 	
} 

function gen_dd_yes_no_filter()
{
    return array('0'=>lang('products_no_label'),'1'=>lang('products_yes_label'));
}

function gen_dd_status()
{
    return array(''=>lang('products_all_label'),'1'=>lang('products_published'),'0'=>lang('products_not_published'));
}


function golive_validation_rules($typeid)
{
	switch($typeid)
	{
		case 1:
				return array(       
								array(
									'field' => 'authorize',
									'label' => 'lang:front:authorize',
									'rules' => 'trim|numeric|required'
								),
								array(
									'field' => 'draft_id',
									'label' => 'lang:front:draft_id',
									'rules' => 'trim|numeric|required'
								),
								array(
									'field' => 'prod_cat_id',
									'label' => 'lang:front:prod_cat_id',
									'rules' => 'trim|numeric|required'
								),					
								array(
									'field' => 'type_id',
									'label' => 'lang:front:type_id',
									'rules' => 'trim|numeric|required'
								),			
								array(
									'field' => 'imghomebannerselected_id',
									'label' => 'lang:front:img-home-banner-selected',
									'rules' => 'required'
								),									
						);
				break;

		default: false;		
	}	
}

function get_GCS_config()
{
	ci()->load->library('session');
	$gcs = new stdClass();
	ci()->load->config('product_'.ENVIRONMENT);
	$gcs->baseThumbImgUri = BASE_URL.'files/thumb/';
	$gcs->imgSizes = ci()->config->item('gcs_images_size');
	$gcs->buckets = ci()->config->item('gcs_buckets_list');
	$gcs->filename_prefix = ci()->config->item('gcs_filename_prefix');
	$gcs->thumb_mode = ci()->config->item('gcs_gen_thumb_mode');
	$gcs->session_queue_var = ci()->config->item('gcs_session_queue_var');
	return $gcs;	
}


// FRONT DRAFT TEMPLATE

function check_imagesize_before_crop($imgdata = array(), $sz = array(), $key = '')
{
	$check = false;
	switch($key)
	{
		case 'th':
		case 'sm':
		case 'lg':		
				if(intval($imgdata['width']) >= intval($sz[0]) && intval($imgdata['height']) >= intval($sz[1]))
				{
					$check = true;
				}
				else
					{
						$check = false;
					}
		case 'md':
		case 'bg':		
				if(intval($imgdata['width']) >= intval($sz[0]) || intval($imgdata['height']) >= intval($sz[1]))
				{
					$check = true;
				}
				else
					{
						$check = false;
					}					

	}		
	return $check;
}

function check_images_before_publish($imgsData, $imgSizes)
{
	$result = array();
	foreach ($imgSizes as $szkey => $size)
	{
		$result[$szkey] = new stdClass();
		$result[$szkey]->imgids = array();
		foreach ($imgsData as $img) 
		{
			if( check_imagesize_before_crop($img, $size, $szkey))
			{
				array_push($result[$szkey]->imgids, $img['id']);
			}
		}
		$result[$szkey]->validation = count($result->imgids[$szkey]) >= $imgSizes[3] ? true : false;
	}
	if(empty($result) != true)
	{
		$result['validated'] = true;
		foreach ($result as $reg) 
		{
			if($reg->validation == false) $result['validated'] = false;
			break;
		}
	}
	else
		{
			$result['validated'] = false;	
		}	
	return $result;
}

function show_validation($value)
{
	if($value)
	{
		echo '<strong>[&#10004;]</strong>';
	}	
	else
		{
			echo '<strong>[x]</strong>';
		}
}



function populate_front_items_ids($items, $dd_array)
{
	if(is_array($items))
	{
		foreach ($items as $item) 
		{
			$item->category = isset($dd_array->cat_products_array[$item->draft_prod_cat_id]) ? $dd_array->cat_products_array[$item->draft_prod_cat_id] : '';
			$item->account = ci()->products->get_account_field_by_id($item->draft_account_id, 'name');
			$item->seller_account = ci()->products->get_account_field_by_id($item->draft_seller_account_id, 'name');
		}
	}
	return $items;
}


function populate_front_publish_item_ids($item, $dd_array)
{
	$item->category = isset($dd_array->cat_products_array[$item->prod_cat_id]) ? $dd_array->cat_products_array[$item->prod_cat_id] : '';
	$item->account = ci()->products->get_account_field_by_id($item->account_id, 'name');
	$item->seller_account = ci()->products->get_account_field_by_id($item->seller_account_id, 'name');
	return $item;
}


function convert_item_facilities_to_array($facilities)
{
	$vec = array();
	if(is_array($facilities))
	{
		foreach ($facilities as $value) 
		{
			$vec[] = $value->facility_id;
		}
	}
	return $vec;
}

function convert_item_usetypes_to_array($usetypes)
{
	$vec = array();
	if(is_array($usetypes))
	{
		foreach ($usetypes as $value) 
		{
			$vec[] = $value->usetype_id;
		}
	}
	return $vec;
}


function get_facilities_list()
{
	return ci()->facilities->gen_dd_array();
}

function get_spaces_usetypes_list()
{
	return ci()->spaces_usetype->gen_dd_array();
}


////////////////////
// CLOUD FILES // //
////////////////////


function gen_cloud_images_queue($draft, $typeid, $GCS_config)
{
	//get images array
	$draft->images_array = gen_imageIDs_array($draft->images);
	$draft->images_params = get_images_metadata_by_ids($draft->images_array);
	//set default values
	$cloud_images = array('processed'=>true);
	$cloud_images['bysize'] = check_images_before_publish($draft->images_params, $GCS_config->imgSizes);
	if($cloud_images['bysize']['validated'] == false)
	{
		$cloud_images['error'] = 'images_not_validated';
		$cloud_images['processed'] = false;
		return $cloud_images;
	} 
	//load Google API library
	ci()->load->library('googleapiclient');
	$bucketName = $GCS_config->buckets[$draft->prod_cat_id];
	$GCSbucket = ci()->googleapiclient->get_bucket($bucketName);
	if($GCSbucket == false || $GCSbucket == null || isset($GCSbucket->error))
	{
			$cloud_images['error'] = 'gcs-error';
			$cloud_images['processed'] = false;
			$cloud_images['code'] = isset($GCSbucket->code) ? $GCSbucket->code : 0;
			return $cloud_images;
	}
	$params = array(); 
	foreach ($cloud_images['bysize'] as $size => $img) 
	{
		foreach($img->imgids as $imgid)
		{			
			if($size!='lg' || ($size=='lg' && ci()->input->post('imghomebannerselected_id') == $imgid) )
			{
				$params = array(
					'bucket' => $bucketName,
					'fileUri' => set_fileUri($GCS_config, $imgid, $size),
					'name' => set_gcsFilename($draft, $GCS_config, $draft->images_params, $imgid, $size),
					'contentType' => set_mimetype($draft->images_params, $imgid),
					'fileid' => $imgid,
					'size' => $size
					);	 
				$prm_array[] = $params;
			}
		}
	}
	if(save_queueVar_to_sessionVar($prm_array) == false)
	{
		$cloud_images['processed'] = false;
	}
	$cloud_images['objects'] = $prm_array;	
	return $cloud_images;
}

function save_queueVar_to_sessionVar($array)
{
	//save to session var
	$GCS_config = get_GCS_config();
	delete_queueVar_session();
	ci()->session->set_userdata(array($GCS_config->session_queue_var=>serialize($array)));	
	if(ci()->session->userdata($GCS_config->session_queue_var))
	{
		return true;
	}
	else
		{
			return false;
		}
}

function delete_queueVar_session()
{
	$GCS_config = get_GCS_config();
	ci()->session->unset_userdata($GCS_config->session_queue_var);	
}

function get_queueVar_session()
{
	$GCS_config = get_GCS_config();
	$session_queue = unserialize(ci()->session->userdata($GCS_config->session_queue_var));
	if(is_array($session_queue))
	{
		return $session_queue;	
	}
	else
		{
			return null;
		}	
}

function check_validUploadedImages_queueVar_session()
{
	$valid = true;
	$queue_array = get_queueVar_session();
	if(is_array($queue_array))
	{
		foreach ($queue_array as $key => $queue) 
		{
			if(!isset($queue['gcsName']))
			{
				$valid = false;			
			}
		}
	}
	else
		{
			$valid = false;
		}	
	return $valid;
}

function get_uploaded_cloud_images_ids_bySize()
{
	$result = null;	
	if(check_validUploadedImages_queueVar_session())
	{
		$result = array();		
		$GCS_config = get_GCS_config();	
		//generate all size empty array	
		foreach($GCS_config->imgSizes as $size=>$data)
		{
			$result[$size] = array();
		}		
		$queue_array = get_queueVar_session();
		foreach ($queue_array as $queue) 
		{
			$size = $queue['size'];
			$result[$size][] = $queue['gcsName'];
		}	
	}
	return $result;
}

function get_session_cloud_images_grouped()
{
	$result = null;	
	$result = array();		
	$GCS_config = get_GCS_config();	
	//generate all size empty array	
	$queue_array = get_queueVar_session();
	if(count($queue_array)>0)
	{
		foreach ($queue_array as $queue) 
		{
			$result[] = $queue['name'];
		}	
	}
	return $result;
}


function set_fileUri($gcs, $imgid, $size)
{
	return $gcs->baseThumbImgUri.$imgid.'/'.$gcs->imgSizes[$size][0].'/'.$gcs->imgSizes[$size][1].'/'.$gcs->thumb_mode;
}


function set_gcsFilename($draft, $gcs, $imgdata, $imgid, $size)
{
	$folder = '';
	foreach($gcs->filename_prefix as $prefix) 
	{
		$folder.= $draft->{$prefix}.'/';
	}
	return $folder.$size.$imgid.$imgdata[$imgid]['extension'];
}

function set_mimetype($imgdata, $id)
{
	return $imgdata[$id]['mimetype'];
}


function gen_front_imgString_bySize($gcs_array)
{
	$result = array();
	foreach ($gcs_array as $size=>$array) 
	{
		$result[$size] = gen_imageIDS_string($array);			
	}
	return $result;
}

/**
 * [extract_front_images_bySize FROM db record]
 * @param  [type] $front db record
 * @return [type]        array
 */
function extract_front_images_bySize($front)
{
	$result = array();
	$GCS_config = get_GCS_config();
	//generate all size empty array	
	foreach($GCS_config->imgSizes as $size=>$data)
	{
		$result[$size] = gen_imageIDs_array($front->{$data[4]});
	}	
	return $result;
}

/**
 * [extract_front_images_groupes FROM db record]
 * @param  [type] $front db record
 * @return [type]        array
 */
function extract_front_images_grouped($front)
{
	$result = array();
	$GCS_config = get_GCS_config();
	//generate all size empty array	
	foreach($GCS_config->imgSizes as $size=>$data)
	{
		$array = gen_imageIDs_array($front->{$data[4]});
		$result = array_merge($result, $array);
	}	
	return $result;
}

//////////////////
// FRONT STATUS //
//////////////////


/**
 * [publish_status_view 
 * generates index publication view status]
 * @param  [type] $products [description]
 * @return [type]           [description]
 */
function front_status_view($items = array(), $items_typeid = 0)
{
	foreach($items as $item)
	{
		switch($items_typeid)
		{
			/* Alquiler de sala */
			case 1:			
					$front_exist = $item->front_id == null ? false : true;
					$updated = ($front_exist && $item->current_version == $item->front_version) || $front_exist == false ? true : false;	
					// GO LIVE BTN TXT
					$item->btn_front_golive = $front_exist ? 'class="btn small gray disabled" onclick="return false;"' : 'class="btn small blue"';
					$item->icon_online = $front_exist ? '<i class="icon-ok-circle"></i>' : '<i class="icon-ok-circle icon-white"></i>';
					$item->txt_online = $front_exist ? '' : 'muted';
					$item->icon_offline = $front_exist ? '<i class="icon-remove-sign icon-white"></i>' : '<i class="icon-remove-sign"></i>';
					$item->txt_offline = $front_exist ? 'muted' : '';					
					// GO OFFLINE BTN TXT	
					$item->btn_front_gooffline = $front_exist ? 'class="btn small red"' : 'class="btn small gray disabled " onclick="return false;"';			 
					$item->icon_update = $updated ? '<i class="icon-exclamation-sign icon-white"></i>' : '<i class="icon-exclamation-sign"></i>';
					$item->txt_update = $updated ? 'muted' : '';
					$item->btn_front_update = $front_exist && $updated == false ? 'class="btn small blue"': 'class="btn small gray disabled " onclick="return false;"';						
					break;

			/* Alquiler de sala */
			default:			
					// GO LIVE BTN TXT
					$item->btn_front_golive = 'class="btn small gray disabled" onclick="return false;"';
					$item->icon_online = '<i class="icon-ok-circle icon-white"></i>';
					$item->txt_online = 'muted';
					$item->icon_offline = '<i class="icon-remove-sign icon-white"></i>';
					$item->txt_offline = 'muted';					
					// GO OFFLINE BTN TXT	
					$item->btn_front_gooffline = 'class="btn small gray disabled" onclick="return false;"';			 
					$item->icon_update = '<i class="icon-exclamation-sign icon-white"></i>';
					$item->txt_update = 'muted';
					$item->btn_front_update = 'class="btn small gray disabled " onclick="return false;"';						
					break;					
		}
	}
	return $items;
}




