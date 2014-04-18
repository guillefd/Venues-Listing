<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/* API */

$config['msg_mailgun_api']				= 'key-49n8i0y0t69m4u3g20mkws3irkkatmg1';
$config['msg_mailgun_domain']			= 'americameetingrooms.com';
//
$config['msg_db_api_queue']				= 'messaging_email_api_queue';
$config['msg_db_api_queue_response']	= 'messaging_email_api_queue_response';
$config['msg_db_api_queue_events']		= 'messaging_email_api_queue_events';

$config['msg_db_api_queue_fields'] = array(
											'msg_id',
											'queuetype',
											'from',
											'to',
											'subject',
											'html'	
											);

/* MESSAGGING --------------------------------------------------------------*/

$config['msg_system_params'] = array(
									'amrfromaddress'=>'America Meeting Rooms <info@amrooms.com>',
									'amremail'=>'info@amrooms.com',									
									'amrnoticeaddress'=>'America Meeting Rooms <notifications@amrooms.com>',
									'amrnoticeemail'=>'notifications@amrooms.com',
									'amrname'=>'America Meeting Rooms',
									);

$config['msg_dbname_form_messages'] = array(
												100 => 'products_formmessages__100',
												101 => 'products_formmessages__101',
												102 => 'products_formmessages__102',										
											);