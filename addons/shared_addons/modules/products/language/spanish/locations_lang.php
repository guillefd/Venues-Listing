<?php defined('BASEPATH') OR exit('No direct script access allowed');

# labels
$lang['location:add']            		= 'Agregar';
$lang['location:account_label'] 		= 'Cuenta';
$lang['location:products_label'] 		= 'Productos';
$lang['location:accountAjax_label'] 		= 'Cuenta (auto-búsqueda)';
$lang['location:accountAjax']   		= 'Ingrese primeras letras de la cuenta y seleccione';
$lang['location:name_label'] 		        = 'Nombre de la locación';
$lang['location:slug_label'] 		        = 'URL (auto-generado)';
$lang['location:slugSH_label'] 		        = 'URL';
$lang['location:type_label'] 		        = 'Tipo de locación';
$lang['location:type_select']               = '---Seleccione tipo ---';
$lang['location:link_label'] 		        = 'Link';
$lang['location:title_label'] 		        = 'Título';
$lang['location:label'] 		        = 'Locación';
$lang['location:description_label']             = 'Descripción';
$lang['location:description_long_label']        = 'Descripción completa';
$lang['location:location_label']                = 'Ubicación';
$lang['location:contact_label']                = 'Contacto';
$lang['location:content_label']                = 'Contenido';
$lang['location:address_label']                 = 'Dirección';
$lang['location:addressl1_placeholder_label']     = 'Calle y Numero';
$lang['location:addressl2_placeholder_label']     = 'Piso | Dpto | Oficina | bloque';
$lang['location:country_label']                 = 'Pais';
$lang['location:country+city_label']            = 'Ciudad | Región | Pais ';
$lang['location:city_label']                    = 'Ciudad';
$lang['location:cityid_label']                  = 'ID Ciudad';
$lang['location:city_placeholder_label']        = 'Ingrese primera letras y seleccione';
$lang['location:region_label']                  = 'Region';
$lang['location:area_label']                    = 'Area';
$lang['location:no_country_select_label']       = '---Seleccione Pais ---';
$lang['location:no_region_select_label']        = '---Seleccione Region ---';
$lang['location:no_city_select_label']          = '---Seleccione Ciudad ---';
$lang['location:phone_label']                    = 'Teléfono';
$lang['location:phonearea_label']                = 'Código de área';
$lang['location:mobile_label']                    = 'Móvil';
$lang['location:email_label']                    = 'Email';
$lang['location:fax_label']                     = 'Fax';
$lang['location:info_label']                    = 'Información';
$lang['location:intro_label']                   = 'Introducción';
$lang['location:intro_short_label']             = 'Introducción (Descripción corta)';
$lang['location:zipcode_label']                 = 'Código Postal';
$lang['location:autocomplete_label']            = 'Autocompletado';
$lang['location:cityauto_label']                = 'Ciudad (auto-busqueda)';
$lang['location:area_label']                    = 'Area | Barrio';
$lang['location:chat_label']                     = 'Usuarios de chat';
$lang['location:social_accounts']               = 'Cuentas red social y chat:';
$lang['location:social_select_label']            = 'Seleccione servicio';
$lang['location:input_social']                   = 'Ingrese nombre de usuario';
$lang['location:skype_label']                    = 'Usuario Skype';
$lang['location:hotmail_label']                    = 'Usuario hotmail';
$lang['location:gmail_label']                    = 'Usuario Gmail';
$lang['location:social_label']                    = 'Cuentas Social';
$lang['location:twitter_label']                    = 'Cuenta Twitter';
$lang['location:facebook_label']                    = 'Cuenta Facebook';
$lang['location:google_label']                    = 'Cuenta Google+';
$lang['location:chatSocial_label']                = 'Cuentas Chat y Social';
$lang['location:latitude_label']                  = 'Latitud';
$lang['location:longitude_label']                 = 'Longitud';
$lang['location:latlng_label']                    = 'Latitud | Longitud';
$lang['location:latlng_precision_label']          = 'Precision GPS';
$lang['location:ws_label']                        = 'Validar';
$lang['location:webservice_lat_lng_label']        = 'Validar dirección con Webservice';
$lang['location:ws_list_label']                   = 'Presione Validar y seleccione';
$lang['location:address_no_select_label']         = 'Seleccione para capturar datos';
$lang['location:search_key_label']                 = 'Nombre';
$lang['location:search_key_placeholder']                 = 'Patrón de búsqueda';
$lang['location:ajax_label']        = 'Ingrese primera letras y seleccione';

# titles
$lang['location:create_title'] 			= 'Crear una locación';
$lang['location:edit_title'] 			= 'Editar locación "%s"';
$lang['location:list_title'] 			= 'Lista de Locaciones';
$lang['location:list']                          = 'Locaciones';

# messages
$lang['location:no_categories'] 		= 'No hay locaciones.';
$lang['location:add_success'] 			= 'La locación ha sido guardada.';
$lang['location:add_error'] 			= 'Ha ocurrido un error al agregar la locación.';
$lang['location:edit_success'] 			= 'El cambio ha sido guardado.';
$lang['location:edit_error'] 			= 'Ha ocurrido un error al guardar las modificaciones.';
$lang['location:error_id_empty'] 		= 'Ha ocurrido un error al consultar la locación.';
$lang['location:delete_success'] 		= 'La locación fue eliminada.';
$lang['location:delete_error'] 			= 'Ha ocurrido un error al intentar eliminar la locación.';
$lang['location:mass_delete_error'] 		= 'Ocurrió un error al tratar de eliminar la locación "%s".';
$lang['location:mass_delete_success']           = '%s locacions de %s completamente eliminadas.';
$lang['location:no_select_error'] 		= 'Necesitas seleccionar locaciones primero.';
$lang['location:already_exist_error']           = 'Una locación con el nombre "%s" ya existe.';
$lang['location:slug_already_exist_error']      = 'Una locación con el URL "%s" ya existe.';
$lang['location:account_id_not_valid'] 		= 'La Cuenta es inválida, vuelva a seleccionarla.';
$lang['location:add_social_account_error_msg']  = 'Seleccione e ingrese datos antes de agregar cuenta social';

$lang['location:delete_consistency_error'] = "No es posible eliminar la locación, esta en uso en %s.<br><small>Para eliminarlo, borre los items mencionados y vuelva a intentarlo.</small>";


/* End of file categories_lang.php */