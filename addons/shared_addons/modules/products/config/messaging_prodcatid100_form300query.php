<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////////////
// message FORM configuration
// Name: form300query
// Type: form
// Prodcatid: 100
// View: 300
// form: query
// 
/////////////////////////////////

$config['msgform_db_fields'] = array(
										'prod_id',
										'prod_cat_id',
										'prod_account_id',
										'front_version',
										'form_view_id',
										'subject',
										'account_agent_email',
										'sender_email',
										'sender_name',
										'sender_ip',
										'sender_user_agent',																								
										'sender_userid',
										'parent_msg_id',
										'timestamp',										
										);

$config['msg_dbname_form_fields_messages'] = array(
													'fields'=>'products_formmessages__100__form300query',
													);

$config['msgform_db_form_fields'] = array(
											'fields' => array(
																'fm_id',
																'query',
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
					                                'field' => 'message',
					                                'label' => 'lang:front:form-message',
					                                'rules' => 'trim|required'
					                            ),                                                                 
			                    			);

/* TEMPLATES asigned to forms in views */
/* formname , views/frontend/modals/form{formname} */
$config['msgform_template'] = array(
										'msgreference'=>'space-new-query',
										'templatename'=>'amrbasic',
										'queue'=>array(
														array(
																'type'=>'conversation',
																'queuename'=>'space-new-query_to-location',
																/* $this->data array key */
																'from'=>'{sender_name} <{sender_email}>',
																'to'=>'{account_agent_email}',
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
																'type'=>'notification',																			
																'queuename'=>'space-new-query_copy-to-sender',
																'from'=>'{space_full_name} - {loc_name} <{amrnoticeemail}>',
																'to'=>'{sender_email}',
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
									);