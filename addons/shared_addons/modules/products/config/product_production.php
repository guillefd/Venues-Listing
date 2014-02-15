<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/* GENERAL --------------------------------------------------------------*/

$config['product_unix_starttime_point'] = '1363752000'; // unix20/3/2013

// product TYPE config
$config['alq_espacios_typeid'] = 1;
$config['servicios_typeid'] = 2;
$config['alq_equipamiento_typeid'] = 3;

/* FRONT RECORDS PER Request */
$config['front_records_per_page'] = 7;

//wildcard for inner segment
$config['front_wildcard_all'] = 'all';

//empty result message
$config['front_print_messages'] = array(
										100 => array(
													'result' => '<p>No hemos encontrado ningun resultado que coincida con tu criterio, pero ajustando tu búsqueda puede ayudar.'
															   .'<ul><li>Intenta eliminar algunos filtros.</li><li>Intenta buscar en otros areas o ciudades.</li></ul></p>',
													'spacenotfound' => '<strong>No se ha encontrado el espacio</strong>, es posible que ya no este disponible.',
													),
										102 => array(),
										103 => array(),									
										);

$config['front_layouts_images'] = array(
										1=>'u-shape.png',
										2=>'board.png',
										3=>'reception.png',
										4=>'dinner.png',
										5=>'theatre.png',
										6=>'classroom.png',
										7=>'cabaret.png',
										);

/* URI SEGMENTS --------------------------------------------------------------*/

//uri segments for product category
$config['front_cat_total_uri_segments'] = array(
												/* alquiler-de-salas */
												100 => 4,
												102 => 0,
												103 => 0,
												);

/* FRONT URL SEGMENTS ARRAY PER CATEGORY */
$config['front_segments_db_values_array'] = array(
											/* product category ID */
											100 => array(
														/* segment number -> values */
														1 => array('prod_cat_slug', 'space_usetype_slug'),
														2 => array('loc_city_slug', 'loc_area_slug', 'loc_type'),
														3 => array('loc_slug'),
														4 => array('space_slug', 'front_version'),
														),
											102 => array(),
											103 => array(),
											);

/* VIEWS --------------------------------------------------------------*/

/* FRONT VIEWS AVAILABLES PER CATEGORYID */
$config['front_cat_views_index'] = array(
											/* alquiler-de-salas */	
											100 => array(
														0 => array(
																	'id'=>100,
																	'name'=>'homelist_space', 
																	'view'=>'cat_1/list/home_list_spaces',
																	'urifields' => array('prod_cat_slug', 'loc_city_slug'),
																	),
														1 => array(
																	'id'=>200,
																	'name'=>'homelist_product', 
																	'view'=>'cat_1/list/home_list_products',
																	'urifields' => array('prod_cat_slug', 'space_usetype_slug', 'loc_city_slug'),
																	),													
														2 => array(
																	'id'=>300,
																	'name'=>'space', 
																	'view'=>'cat_1/item/space_view',
																	'urifields' => array('prod_cat_slug', 'loc_city_slug', 'loc_slug', 'space_slug'),
																	),
														3 => array(
																	'id'=>400,
																	'name'=>'product', 
																	'view'=>'cat_1/item/product_view',
																	'urifields' => array('prod_cat_slug', 'space_usetype', 'loc_city_slug', 'loc_slug', 'space_slug', 'front_version'),
																	),
														4 => array(
																	'id'=>500,
																	'name'=>'location', 
																	'view'=>'cat_1/item/location_view',
																	'urifields' => array(),
																	),												
														),
											102 => array(),
											103 => array(),
											);

/* URI FILTERS --------------------------------------------------------------*/

//front uri filters allowed per category
$config['front_cat_filters_index'] = array(
											/* alquiler-de-salas */	
											100 => array(
														'capacity',
														'layouts', 
														'usetypes',
														'loctypes',
														'facilities',
														'features',
														'page'	
														),
											102 => array(),
											103 => array(),
											);

/* GOOGLE MAPS API -------------------------------------------------------------------- */

// cities available for search ( slug => (lat, lng, zoom) )
// Would be replaced with google maps autocomplete API and obtain lat-lng
$config['front_cities_search_index'] = array(
											'default' => array('name'=>'Mundo', 'lat'=>'8', 'lng'=>'-85', 'zoom'=>2),
											'buenos-aires' => array('name'=>'Buenos Aires','lat'=>'-34.605', 'lng'=>'-58.43', 'zoom'=>12),
											);

/* Google Maps API KEY */
$config['google_maps_v3_api_key'] = 'AIzaSyCALh28eeOof07ncjrfNHL80fYPUpz4nVc';

/* Gmap marker image */
$config['front_gmap_marker_image'] = 	'amr_marker.png';
$config['front_gmap_marker_image_hover'] = 	'amr_marker-pink.png';

/* Gmap Result Items db fields */
$config['front_gmap_result_items_fields'] = array(
												'prod_cat_id',
												'space_denomination',
												'space_name',
												'space_max_capacity',
												'loc_area',
												'loc_type',
												'loc_geo_street_name',
												'loc_geo_street_number',
												'loc_lat',
												'loc_lng',
												'itemUri',
												'cloud_th_images',				
												);


/* Google Cloud Storage GCS ----------------------------------------------------------------*/

/* buckets ID/name by product product_category_id */
$config['gcs_buckets_list'] = array(
									100 =>'cdn.spaces.americameetingrooms.com',
									102 =>'cdn.services.americameetingrooms.com',
									103 =>''
									);
/* CDN URLs by product product product_category_id */
$config['gcs_cdn_url_list'] = array(
									100 =>'http://cdn.spaces.americameetingrooms.com/',
									102 =>'http://cdn.services.americameetingrooms.com/',
									103 =>''
									);

/* images size to be stored in GCS */
// array( 0{width}, 1{height}, 2{description}, 3{minimum required for front publish}, 4{front DB images FIELD} )
$config['gcs_images_size'] = array(
									'th'=> array('200','133','Gmap Infobox', 1, 'cloud_th_images'),
									'sm'=> array('300','200','Home list carousel', 1, 'cloud_sm_images'),
									'md'=> array('640','430','Space view', 1, 'cloud_md_images'),
									'bg'=> array('900','600','Product view', 0, 'cloud_bg_images'),
									'lg'=> array('1900','600','Home Banner', 0, 'cloud_lg_images')
							);
$config['gcs_filename_prefix'] = array('loc_country_iso3', 'prod_id');
$config['gcs_gen_thumb_mode'] = 'fit';
$config['gcs_session_queue_var'] = 'cloud_queues'; 


/* LIST RESULT INFO DISPLAY */
// facilities to display pero usetype
$config['facilities_to_display_in_list_front_usetypes'] = array(
																0=>array(1,2,15),
																1=>array(1,2,15),
																2=>array(1,2,15),
																3=>array(1,2,15),
																4=>array(1,2,15),																																
																5=>array(1,2,15),
																6=>array(1,2,15),
																7=>array(1,2,17),
																8=>array(1,2,15),
																9=>array(1,2,15),
																10=>array(1,2,15),
																11=>array(1,2,15),															
																);

$config['features_to_display_in_list_front_usetypes'] = array(
																0=>array(19,3,5),
																1=>array(19,3,5),
																2=>array(19,3,5),
																3=>array(19,3,5),
																4=>array(9,14,17),																																
																5=>array(19,3,5),
																6=>array(19,3,5),
																7=>array(3,32,22),
																8=>array(19,3,25),
																9=>array(19,3,5),
																10=>array(58,57,56),
																11=>array(52,55,54),															
																);