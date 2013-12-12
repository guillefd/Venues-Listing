<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	www.your-site.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://www.codeigniter.com/user_guide/general/routing.html
*/

// public
/* see system/cms/config/routes.php */

// admin
$route['products/admin/front(/:any)?']	    = 'admin_front$1';
$route['products/admin/categories(/:any)?']	= 'admin_categories$1';
$route['products/admin/locations(/:any)?']	= 'admin_locations$1';
$route['products/admin/features(/:any)?']	= 'admin_features$1';
$route['products/admin/spaces(/:any)?']	    = 'admin_spaces$1';
$route['products/admin/checktempfolder']    = 'admin/check_temp_folder';
//AJAX
$route['products/admin/locations_autocomplete_ajax']    = 'admin_locations/locations_autocomplete_ajax';
$route['products/admin/locations_by_accountid_ajax']    = 'admin_locations/locations_by_accountid_ajax';
$route['products/admin/spaces_by_locationid_ajax']      = 'admin_spaces/spaces_by_locationid_ajax';
$route['products/admin/spaces_autocomplete_ajax']       = 'admin_spaces/spaces_autocomplete_ajax';
$route['products/admin/get_features_ajax']              = 'admin_features/get_features_ajax';
$route['products/admin/filetempupload_ajax']            = 'admin/filetemp_upload_ajax';
$route['products/admin/getfile_ajax(/:num)?']           = 'admin/getfile_ajax$1';

$route['products/admin/ajxrunqueue']           = 'admin_front/ajx_golive_gooffline_process';