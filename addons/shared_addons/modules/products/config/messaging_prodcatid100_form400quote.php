<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////////////
// message FORM configuration
// Name: form400quote
// Type: form
// Prodcatid: 100
// View: 400
// form: quote
// 
/////////////////////////////////

$config['msgform_db_fields'] = array(
										'prod_cat_id',
										'prod_account_id',
										'front_version',
										'form_view_id',
										'subject',
										'account_agent_email',
										'sender_email',
										'sender_name',
										'sender_phone',
										'sender_ip',
										'sender_user_agent',																								
										'sender_userid',
										'parent_msg_id',
										'timestamp',									
										);


$config['msg_dbname_form_fields_messages'] = array(
													'fields'=>'products_formmessages__100__form400quote',
													'datetimes'=>'products_formmessages__100__form400quote__datetimes',
													);

$config['msgform_db_form_fields'] = array(
											'fields'=>array(
																'fm_id',
																'pax',
																'layouts_ids',
																'features_ids',
																'comments_features',
																'comments_general',
																'datetime_subtdays',
																'datetime_subthours',
																'activity_use'
															),
											'datetimes'=>array(
																'fm_id',
																'datetype',
																'datestart',
																'dateend',
																'datelist',
																'timestart',
																'timeend',
																'timerangehours',
																'incsaturday',
																'incsunday',
																'repeats',
															),											
											);

$config['msgform_validation_rules'] = array(
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
					                                'field' => 'phone',
					                                'label' => 'lang:front:phone',
					                                'rules' => 'trim'
					                            ),   
					                            array(
					                                'field' => 'pax',
					                                'label' => 'lang:front:pax',
					                                'rules' => 'numeric|trim|required'
					                            ),   			                                                                                          
					                            array(
					                                'field' => 'activity',
					                                'label' => 'lang:front:activity',
					                                'rules' => 'trim|required'
					                            ),   
					                            array(
					                                'field' => 'comments_ftr',
					                                'label' => 'lang:front:comments_ftr',
					                                'rules' => 'trim'
					                            ),   
					                            array(
					                                'field' => 'comments_gral',
					                                'label' => 'lang:front:comments_gral',
					                                'rules' => 'trim'
					                            ), 
					                            array(
					                                'field' => 'layoutsids',
					                                'label' => 'lang:front:layoutsids',
					                                'rules' => 'trim|check_integer_values'
					                            ), 
					                            array(
					                                'field' => 'featureids',
					                                'label' => 'lang:front:featureids',
					                                'rules' => 'trim'
					                            ), 		
					                            array(
					                                'field' => 'datetimeObj',
					                                'label' => 'lang:front:datetimeObj',
					                                'rules' => 'trim|required'
					                            ),                                                                
			                    			);

/* TEMPLATES asigned to forms in views */
/* formname , views/frontend/modals/form{formname} */
$config['msgform_template'] = array(
									'msgreference'=>'space-usetype-new-quote',
									'templatename'=>'amrbasic',
									'queue'=>array(
													array(
															'type'=>'conversation',
															'queuename'=>'space-usetype-new-quote_to-location',
															/* $this->data array key */
															'from'=>'{sender_name} <{sender_email}>',
															'to'=>'{account_agent_email}',
															'subject'=>'Pedido de presupuesto para {space_slug}@{loc_slug} de #{sender_name}',
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
																							   	'txtintro'=>'Tienes un pedido de presupuesto para <strong>{space_full_name}</strong>:',
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
															'type'=>'notification',																			
															'queuename'=>'space-usetype-new-quote_copy-to-sender',
															'from'=>'{space_full_name} - {loc_name} <{amrnoticeemail}>',
															'to'=>'{sender_email}',
															'subject'=>'Tu pedido de presupuesto para {space_slug}@{loc_slug} de #{sender_name}',										
															'html'=>array(
																			'opentag'=>array(),
																			'hiddenline'=>array('txt1'=>'Te copiamos el pedido de presupuesto enviado.'),
																			'header'=>array(
																							'logolink'=>'http://www.americameetingrooms.com', 
																							'logoalt'=>'America Meeting Rooms', 
																							'logowidth'=>'250px', 
																							'logoheight'=>'73px', 
																							'logosrc'=>'http://cdn.spaces.americameetingrooms.com/logos/AMR-sm-short.png',
																							),
																			'bodyintro'=>array(
																								'txthello'=>'Hola, {sender_name}', 
																							   	'txtintro'=>'Enviaste un pedido de presupuesto para <strong>{space_full_name}, en {loc_name}</strong>:',
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
									);