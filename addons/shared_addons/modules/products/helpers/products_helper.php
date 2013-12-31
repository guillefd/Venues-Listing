<?php defined('BASEPATH') OR exit('No direct script access allowed');


function get_product_typeid($category_id = 0) 
{
	if($category = ci()->categories->get_by_id($category_id))
	{
		return $category->type_id;  
	}	
	else
		{
			return null;
		}
}

function get_categories_by_typeid($typeid = 0)
{
	if($typeid==0) return array();
	return ci()->categories->gen_dd_array($typeid);
}


function convert_empty_value_to_zero($var)
{
	return (empty($var) || is_null($var)) ? 0 : $var;
}


function check_product_slug($slug)
{
	return slugify_string($slug);
}

/**
 * [slugify_string description]
 * @param  [type] $text [description]
 * @return [type]       [description]
 */
function slugify_string($text) {
	// replace non letter or digits by -
	$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
	// trim
	$text = trim($text, '-');	
	// transliterate
	$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	// lowercase
	$text = strtolower($text);	
	// remove unwanted characters
	$text = preg_replace('~[^-\w]+~', '', $text);	
	if (empty($text))
	{
		return 'n-a';
	}
    return trim($text, '-');
}


////////////////////////////////////////////////////////////////////////////////////////////////////
// :::::::::::::: FILES PROCESS ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * [gen_imageIDs_array generates array with images IDs]
	 * @param  [string] $field [input]
	 * @return [array]        [array with id values]
	 */
	function gen_imageIDs_array($field)
	{
		if($field)
		{
			$imgIDs = explode(";", trim($field));
			for($i=0;$i<=count($imgIDs);$i++)
			{
				if($imgIDs[$i]=="")
				{
					unset($imgIDs[$i]);
				}
			}
			return $imgIDs;
		}
		return array();
	}

	function gen_imageIDS_string($array)
	{
		$string = '';
		if(is_array($array))
		{
			foreach($array as $value)
			{
				$string.= $value.';';
			}
		}
		return $string;
	}


	function get_images_metadata_by_ids($array = array())
	{
		ci()->load->library('files/files');
		$imgs = array();
		foreach ($array as $fileid) {
			$file = Files::get_file($fileid);
			$image = array();
			$image['id'] = $file['data']->id;			
			$image['width'] = $file['data']->width;
			$image['height'] = $file['data']->height;
			$image['mimetype'] = $file['data']->mimetype;
			$image['description'] = $file['data']->description;			
			$image['extension'] = $file['data']->extension;
			$imgs[$fileid] = $image;
		}
		return $imgs; 
	}   


	function check_folder($product_id)
	{
		$prodfoldername = 'Prod'.$product_id;
		$tree = Files::folder_tree();
		$notfound = true;
		$i = 0;
		while($notfound && $i <= count($tree) )
		{
			if( $tree[$i-1]['name'] == $prodfoldername )
			{
				$notfound = false;
				$prodfolderid = $tree[$i-1]['id'];
			}	
			$i++;
		}
		if($notfound)
		{
			$result = Files::create_folder(0, $prodfoldername );
			if($result['status']==true)
			{
				$prodfolderid = $result['data']['id'];
			}
			else
			{
				$prodfolderid = false;
			}		
		}
		return $prodfolderid;	
	}


	function move_tempfiles_to_prod_folder($prodfolderid, $imgarray)
	{
		foreach($imgarray as $imgid)
		{
			ci()->products_m->move_product_file($imgid, $prodfolderid);
		}
	}


//////////////////////
// PRODUCT   DRAFT  //
//////////////////////


function get_layouts_list()
{
	return ci()->layouts->gen_dd_array();
}