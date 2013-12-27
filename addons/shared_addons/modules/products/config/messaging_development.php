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
																				'subject'=>'Nueva consulta para {space_slug}@{loc_slug} de #{sender_name}',
																				'html'=>array(
																								'opentag'=>array(),
																								'hiddenline'=>array('txt1'=>'Respondele rápido para aumentar tus ventas.'),
																								'header'=>array(
																												'logolink'=>'http://www.americameetingrooms.com', 
																												'logoalt'=>'America Meeting Rooms', 
																												'logowidth'=>'250px', 
																												'logoheight'=>'73px', 
																												'logosrc'=>'http://cdn.spaces.americameetingrooms.com/logos/AMR-sm-short.png',
																												),
																								'bodyintro'=>array(
																													'txthello'=>'Hola, {loc_name}', 
																												   	'txtintro'=>'Tienes una nueva consulta para <strong>{space_full_name}</strong>:',
																												   	),
																								'bodymsg'=>array(
																												'field1'=>'Consulta',
																												'field2'=>'Nombre',
																												'field3'=>'Email',
																												'value1'=>'{comments}',
																												'value2'=>'{sender_name}',
																												'value3'=>'{sender_email}',																																																																																																																
																												),
																								'bodyfooter'=>array('txt1'=>'Una respuesta rápida, aumenta las ventas!', 'txtbye'=>'Saludos,', 'linkref'=>'ttp://www.americameetingrooms.com', 'linktxt'=>'America Meeting Rooms'),
																								'footer'=>array('txt1'=>'¿Tenés preguntas? Contactanos a ', 'linkref'=>'mailto:info@amrooms.com', 'linktxt'=>'info@amrooms.com'),								
																								'closetag'=>array(),
																								),																			
																			),
																		array(
																				'queuename'=>'space-new-query_copy-to-sender',
																				'from'=>'amrfromaddress',
																				'to'=>'sender_email',
																				'subject'=>'Tu consulta para {space_slug}@{loc_slug} de #{sender_name}',										
																				'html'=>array(
																								'opentag'=>array(),
																								'hiddenline'=>array('txt1'=>'Te copiamos la consulta enviada.'),
																								'header'=>array(
																												'logolink'=>'http://www.americameetingrooms.com', 
																												'logoalt'=>'America Meeting Rooms', 
																												'logowidth'=>'250px', 
																												'logoheight'=>'73px', 
																												'logosrc'=>'http://cdn.spaces.americameetingrooms.com/logos/AMR-sm-short.png',
																												),
																								'bodyintro'=>array(
																													'txthello'=>'Hola, {sender_name}', 
																												   	'txtintro'=>'Enviaste una consulta para <strong>{space_full_name}, en {loc_name}</strong>:',
																												   	),
																								'bodymsg'=>array(
																												'field1'=>'Consulta',
																												'field2'=>'Nombre',
																												'field3'=>'Email',
																												'value1'=>'{comments}',
																												'value2'=>'{sender_name}',
																												'value3'=>'{sender_email}',																																																																																																																
																												),
																								'bodyfooter'=>array('txt1'=>'{loc_name} ya recibió la consulta y en breve te estará respondiendo.<p>Mientras tanto te sugerimos seguir con tu búsqueda de otras opciones, mas y mas salas publican sus servicios en America Meeting Rooms.</p>', 'txtbye'=>'Saludos,', 'linkref'=>'ttp://www.americameetingrooms.com', 'linktxt'=>'America Meeting Rooms'),
																								'footer'=>array('txt1'=>'Gracias por visitarnos en ', 'linkref'=>'http://www.americameetingrooms.com', 'linktxt'=>'America Meeting Rooms'),								
																								'closetag'=>array(),
																								),																				
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

