<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/* MESSAGGING --------------------------------------------------------------*/

$config['msg_system_params'] = array(
									'amrfromaddress'=>'America Meeting Rooms <info@amrooms.com>',
									'amremail'=>'info@amrooms.com',
									'amrname'=>'America Meeting Rooms',
									);

$config['msg_db_table_name'] = array(
										100 => 'products_messages__100',
										101 => 'products_messages__101',
										102 => 'products_messages__102',										
									);
$config['msg_db_fields'] = array(
									100 =>array(
												'prod_id',
												'prod_cat_id',
												'prod_account_id',
												'front_version',
												'view_id',
												'data',
												'subject',
												'dates_slots',
												'dates_list',
												'days_qty',
												'hours_slots',
												'hours_per_day',
												'pax',
												'comments',
												'account_agent_email',
												'sender_email',
												'sender_name',
												'sender_userid',
												'parent_msg_id',
												'timestamp',										
												),
									101 =>array(),
									102 =>array(),
									);

/* TEMPLATES asigned to forms in views */
$config['msg_template'] = array(
								100 => array( 
									        /* formname , views/frontend/modals/form{formname} */
											'300query'=>array(
														'msgreference'=>'space-new-query',
														'templatename'=>'amrbasic',
														'queue'=>array(
																		array(
																				'queuename'=>'space-new-query_to-location',
																				/* $this->data array key */
																				'from'=>'sender_name+email',
																				'to'=>'account_agent_email',
																				'subject'=>array(
																								'string'=>'Nueva consulta para {space_slug}@{loc_slug} de #{sender_name}',
																								'vars' => array('space_slug', 'loc_slug', 'sender_name'),
																								),
																				'html'=>array(

																								),																			
																			),
																		array(
																				'queuename'=>'space-new-query_copy-to-sender',
																				'from'=>'amrfromaddress',
																				'to'=>'sender_email',
																				'subject'=>array(
																								'string'=>'Tu consulta para {space_slug}@{loc_slug} de #{sender_name}',
																								'vars' => array('space_slug', 'loc_slug', 'sender_name'),
																								),																								
																				'html'=>array(),																			
																			),
																		),
														),
											'400query'=>array(
														'name'=>'amrbasic',
														'bodyparams'=>array(
																		''=>'',
																		),
														),
											),
								101 => array(),
								102 => array(),										
							);

