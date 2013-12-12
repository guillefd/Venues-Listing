<?php defined('BASEPATH') OR exit('No direct script access allowed');

// labels
$lang['front:id']					= 'id';
$lang['front:publishcategory']		= 'Categoria Backend';
$lang['front:name']					= 'Nombre';
$lang['front:product_name']			= 'Nombre publicación<br><small>(Uso interno)</small>';
$lang['front:slug']					= 'slug';
$lang['front:product']				= 'Producto';
$lang['front:product_category_slug'] = 'Url Categoria';
$lang['front:product_usetype']      = 'Categoría Frontend';
$lang['front:product_usetypes']      = 'Tipos de uso (Apto para)';
$lang['front:current_version']      = 'Version actual';
$lang['front:status']				= 'Estado';
$lang['front:account']				= 'Cuenta';
$lang['front:seller']				= 'Gestiona';
$lang['front:intro']				= 'Introducción';
$lang['front:description']			= 'Descripción';
$lang['front:category']				= 'Categoria';
$lang['front:category_publish']		= 'Categoria: ';
$lang['front:slug_publish']		    = 'URL';
$lang['front:slugs_publish']		= 'URLs';
$lang['front:link_publish']		    = 'Enlace';
$lang['front:links_publish']		= 'Enlaces';
$lang['front:details']				= 'Detalles backend';
$lang['front:images']				= 'Imágenes';
$lang['front:city']				= 'Ciudad';
$lang['front:area']				= 'Area';
$lang['front:country']				= 'País';
$lang['front:located']				= 'Ubicación';

$lang['front:contact']				= 'Contacto';
$lang['front:phone']				= 'Telefono';
$lang['front:mobile']				= 'Movil';
$lang['front:email']				= 'Email';

$lang['front:location']				= 'Locación';
$lang['front:loc_type']				= 'Tipo de locación';
$lang['front:space'] 				= 'Espacio';
$lang['front:level'] 				= 'Nivel';
$lang['front:dimentions'] 			= 'Dimensiones';
$lang['front:denomination'] 		= 'Denominación';
$lang['front:facilities'] 			= 'Facilidades';
$lang['front:available'] 			= 'Disponible';
$lang['front:usageunit'] 			= 'Unidad de medida';
$lang['front:isoptional'] 			= 'Adicional';
$lang['front:value'] 			= 'Valor';
$lang['front:width'] 		= 'Ancho';
$lang['front:height'] 		= 'Alto';
$lang['front:length'] 		= 'Largo';
$lang['front:square_mt'] 		= 'Metros cuadrados';
$lang['front:shape'] 			= 'Forma';
$lang['front:capacity'] 		= 'Capacidad';
$lang['front:layouts'] 			= 'Armados';
$lang['front:layout'] 			= 'Formato';
$lang['front:max_capacity'] 		= 'Capacidad máxima';
$lang['front:address']				= 'Ubicación';
$lang['front:address_l1']			= 'Dirección linea 1';
$lang['front:address_l2']			= 'Dirección linea 2';
$lang['front:geo_street']			= 'Nombre calle (geo validado)';
$lang['front:geo_number']			= 'Numero calle (geo validado)';
$lang['front:geo_coord']			= 'Coordenadas';
$lang['front:geo_lat']				= 'Latitud';
$lang['front:geo_lng']				= 'Longitud';
$lang['front:size']					= 'Tamaño';
$lang['front:quantity']				= 'Cantidad';
$lang['front:valid']				= 'Validado';
$lang['front:min-required']			= 'Requeridas';

$lang['front:features']			= 'Características';
$lang['front:img-home-banner-selected'] = 'Asignar a Home Banner';
$lang['front:img-home-banner-selected-none'] = 'No asignar imagen a Home Banner.';


$lang['front:title'] 				= 'Front | Publicaciones en vivo';
$lang['front:title_publish'] 		= 'Front | publicación';
$lang['front:title_unpublish'] 		= 'Front';
$lang['front:subtitle_publish'] 	= 'Autorizar publicación de producto';
$lang['front:subtitle_unpublish'] 	= 'Pasar publicación a estado Offline';
$lang['front:subtitle_publish_proccesscreate'] = 'Alta';
$lang['front:subtitle_publish_processupdate'] = 'Actualizar';
$lang['front:item_title']			= 'Publicación';
$lang['front:publication']			= 'Publicación';
$lang['front:items'] 				= 'Items';
$lang['front:item_publish']			= 'Front item: ';
$lang['front:authorize']			= 'Autorizar';
$lang['front:authorize_long']		= 'Datos validados, autorizar publicación';
$lang['front:imghomebannerselected_id']	= 'Imagen -Home Banner-';
$lang['front:imagevalidationresult'] = 'Validacion de imágenes requeridas (por tamaño)';
$lang['front:processcreate']		 = 'Alta de publicación';
$lang['front:processupdate']		 = 'Actualización de publicación';
$lang['front:cloudimages']			 = 'Imágenes [cloud]';
$lang['front:initiating']			 = 'Inicializando...';
$lang['front:initiating_unpublish']	 = 'aguardando confirmación';
$lang['front:waiting']			     = 'En espera';
$lang['front:proc_images']				= 'Procesar imágenes';
$lang['front:proc_unpublish_images']	= 'Procesar baja de imágenes';
$lang['front:images_verification']	 = 'Verificación de imágenes';
$lang['front:proc_publication']			= 'Procesar publicación';
$lang['front:proc_unpublish_publication']	= 'Procesar baja de publicación';
$lang['front:title_unpublish_process']	= 'Baja de publicación en vivo (poner offline)';

//buttons
$lang['front:offline_button'] = "Poner publicación OFFLINE";
$lang['front:offline_title_comment'] = "¡Atención!";
$lang['front:offline_comment'] = "Estas por iniciar el proceso para poner OFFLINE la publicación. (No será visible para los visitantes del sitio)";


//messages
$lang['front:currently_no_items'] 	= 'No hay publicaciones aun.';
$lang['front:error_noexist'] = 'El item [%s] no existe o ya no está disponible.';
$lang['front:id_error'] = '(Acceso directo inválido) Haga clic en el item que desea publicar.';
$lang['front:golive_post_error'] = '(Acceso directo inválido) Inicie la publicación desde aquí.';
$lang['front:db_transtaction_error'] = 'Error al recuperar los datos del item, vuelva a intentarlo.';
$lang['front:insert_front_success'] = 'La publicacion [%s] esta en Vivo!';
$lang['front:insert_front_error'] = 'Error al intentar publicar en vivo, vuelva a intentarlo';
$lang['front:update_front_success'] = 'La publicacion [%s] se actualizo.';
$lang['front:update_front_error'] = 'Error al intentar actualizar la publicación, vuelva a intentarlo';
$lang['front:delete_success'] = 'La publicación esta ahora offline';
$lang['front:delete_error'] = 'Ocurrió un error al intentar poner offline la publicación, vuelva a intentarlo.';
$lang['front:error_alreadyexist_and_updated'] = 'La publicación ya esta en vivo y actualizada.';

$lang['front:update_front_error_cat_noexist'] = 'NO fue posible actualizar, la categoría no existe.';
$lang['front:imagesize_notbigenough'] = 'Tamaño de imagen insuficiente';

$lang['front:images_not_validated'] = 'Las imagenes son inválidas (o no suficientes), reviselas y vuelva a intentarlo.';
$lang['front:gcs-error'] = 'Error al intentar subir las imágenes (%s), vuelva a intentarlo.';
// date


//AJAX
$lang['front_search_key_label']     = 'Nombre';

/* End of file front_lang.php */