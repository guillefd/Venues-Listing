<script>
	var amrMapData = '<?php echo json_encode($this->front->page->map); ?>';
</script>
<?php $lastURL = $this->front->page->sessiondata['lasturl']; ?>
<div class="inner-page space-view">
	<div class="container">	
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-7 col-md-offset-1">
				<div class="breadcrumb-amr">
					<h2><a class="returnlink amr-tooltip" href="<?php echo $lastURL; ?>" data-toggle="tooltip" title="Volver al listado"><i class="fa fa-chevron-left"></i></a>{{ theme:image file="amr-isologo-sm.png" class="amrlogo-sm" }}Alquiler de <?php echo $item->space_denomination ?> <i class="fa fa-caret-right"></i><span class="blue"><?php echo $item->space_usetype; ?></span></h2>
				</div>
				<h2 class="sub"><strong><?php echo $item->space_denomination.' '.$item->space_name; ?></strong> <span class="loctit">en <?php echo $item->loc_name ?></span></h2>
				<ul class="list-inline sub">
				  <li><i class="fa fa-home"></i> <?php echo $item->loc_type; ?></li>
				  <li><i class="fa fa-map-marker"></i> <?php echo $item->loc_geo_street_name.' al '.round_number($item->loc_geo_street_number).' ('.$item->loc_area.'), '.$item->loc_city; ?></li>				  				  
				</ul>				
				<!-- PHOTOS AND MAP -->
				<div class="itempanel">
					<div class="inner-tab">
						<!-- Nab Bar Tan Menu list -->
						<ul id="itempanelnav" class="nav nav-tabs">
							<li class="active"><a href="#photo" data-toggle="tab"><i class="fa fa-camera"></i> Fotos</a></li>
							<li><a id="btnMap" href="#map" data-toggle="tab"><i class="fa fa-map-marker"></i> Mapa</a></li>
						</ul>
						<!-- Content for each menu item -->
						<div class="tab-content">
							<!-- First Content for Nav bar -->
							<div class="tab-pane fade in active" id="photo">
								<div class="photoslider">								
									<div id="crsl-itemview-<?php echo $item->id ?>" class="carousel slide crsl-itemview" data-ride="carousel">					
									<div class="carousel-inner">
										<?php $n = 0; ?>
										<?php foreach($item->cloud_md_images as $index=>$img): ?>
											<?php $n++; ?>
											<div class="item <?php if($index==0) echo 'active'; ?>">
												<a>
													<img class="photosliderimg" src="<?php echo $data->media->cdnUri.$img; ?>" width="640px" height="430px" alt="<?php echo $item->space_denomination.' '.$item->space_name ?>" />
													<div class="carousel-caption top">
														<p><?php echo $n; ?> <i class="fa fa-caret-right"></i> <?php echo count($item->cloud_md_images); ?></p>
													</div>													
													<div class="carousel-caption">
														<p><?php echo '<strong>'.$item->space_denomination.' '.$item->space_usetype.'</strong> | '.$item->space_denomination.' '.$item->space_name ?></p>
													</div>
												</a>
											</div>
										<?php endforeach; ?>													
									</div>
									<a class="left carousel-control" href="#crsl-itemview-<?php echo $item->id ?>" data-slide="prev">
										<span class="glyphicon glyphicon-chevron-left"></span>
									</a>
									<a class="right carousel-control" href="#crsl-itemview-<?php echo $item->id ?>" data-slide="next">
										<span class="glyphicon glyphicon-chevron-right"></span>
									</a>
									</div>								
								</div>
							</div>
							<!-- Second Content for Nav bar -->
							<div class="tab-pane fade" id="map">
								<div class="item-map">
						            <!-- google maps api v3-->
						            <div id="itemMap-canvas"></div>
						        </div>	
							</div>
						</div>
					</div>
					<div class="inner-tab">
					</div>
				</div>

				<div class="lightbox-item lbblue">
					<h3><i class="amrlogo-sm"></i> Alquiler de <?php echo $item->space_denomination ?> <i class="fa fa-caret-right"></i> <?php echo $item->space_usetype; ?></h3>
					<p class="lbyellow"><i class="fa fa-check-circle"></i> Alquiler de <strong><?php echo $item->space_denomination ?> <?php echo $item->space_name; ?></strong> para <strong><?php echo $item->space_usetype; ?></strong></p>
					<p><?php echo $item->body ?></p>
					<table class="table table-bordered table-condensed">
						<tr class="lbyellow">
							<th>Detalles del Servicio</th>
							<th>Descripción</th>
							<th>
								<span class="glyphicon glyphicon-ok service-icon-included" data-toggle="tooltip" title="Incluido en el servicio"></span>
								/ <span class="glyphicon glyphicon-info-sign service-icon-included" data-toggle="tooltip" title="Disponible en el servicio, consultar al contratar."></span>
							</th>
						<tr>	
						<?php foreach($item->data_features as $ftr): ?>
						<tr>
							<td width="40%"><strong><?php echo $ftr->name ; ?></strong></td>
							<td><?php echo $ftr->description ; ?></td>
							<td width="10%" class="centered">
								<?php if( $ftr->is_optional == '0'): ?>
								<span class="glyphicon glyphicon-ok service-icon-included" data-toggle="tooltip" title="Incluido en el servicio"></span>
								<?php else: ?>
								<span class="glyphicon glyphicon-info-sign service-icon-included" data-toggle="tooltip" title="Disponible en el servicio, consultar al contratar."></span>										
								<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; ?>		
					</table>
				</div>

				<div class="lightbox-item lbgreen">
					<h3><i class="fa fa-sign-in"></i> <?php echo $item->space_denomination; ?> <?php echo $item->space_name; ?></h3>
					<p><?php echo $item->space_description; ?></p>
					<ul class="list-group spacedetails">															
						<li class="list-group-item">
							<span class="pull-right"><?php echo $item->space_max_capacity; ?> pax</span>
							<i class="fa fa-users"></i> Capacidad máxima
						</li>
						<li class="list-group-item">
							<span class="pull-right"><?php echo $item->space_width.' x '.$item->space_length.' mt (alto '.$item->space_height.' mt)'; ?> / <?php echo $item->space_square_mt; ?> m2</span>
							<i class="fa fa-arrows-alt"></i> Medidas
						</li>																
						<li class="list-group-item">
							<span class="pull-right"><?php echo $item->space_shape; ?></span>
							<i class="fa fa-external-link-square"></i> Forma
						</li>	
						<li class="list-group-item">
							<span class="pull-right"><?php echo $item->space_level; ?></span>
							<i class="fa fa-bars"></i> Nivel
						</li>					
					</ul>
					<h3><i class="fa fa-home"></i> <?php echo $item->loc_name; ?></h3>
					<p><strong><?php echo $item->loc_type ?></strong></p>
					<p><?php echo $item->loc_description; ?></p>					
					<ul class="list-group spacedetails">															
						<li class="list-group-item">
							<span class="pull-right"><?php echo $item->loc_type; ?></span>
							<i class="fa fa-home"></i> Tipo de locación
							<li class="list-group-item">
								<span class="pull-right"><?php echo $item->loc_geo_street_name.' al '.round_number($item->loc_geo_street_number); ?><br>
									(<?php echo $item->loc_area; ?>)</span>
								<i class="fa fa-location-arrow"></i> Ubicación
							</li>												
						</li>
					</ul>										
				</div>				
				<!-- SPACE LAYOUTS -->
				<div class="panel panel-default layouts">
					<div class="panel-heading">
						<i class="fa fa-wrench"></i> Armados<p>Armados disponibles y su capacidad máxima.</p>
					</div>
					<div id="layoutsdetailpanel" class="panel-body">
						<table>
						<tr>
						<?php foreach($layoutsArr as $id=>$lyt): ?>
						<td>
							<?php if(array_key_exists($id, $item->data_layouts)): ?>
							<?php $class = 'panel-success';  $cap = '<span class="badge">'.$item->data_layouts[$id]->capacity.'</span>'; ?>
							<?php else: ?>
							<?php $class = 'panel-default'; $cap = '<i class="fa fa-times"></i>'; ?>
							<?php endif; ?>
							<div class="panel <?php echo $class; ?> layouts">
								<div class="panel-heading">
									<h3 class="panel-title hidden-xs hidden-sm"><?php echo $lyt->name ?></h3>
								</div>
								<div class="panel-img">
									<img src="<?php echo $this->front->page->map->imgUrl.$this->front->CFG->page->layoutsimages[$id]; ?>" alt="<?php echo $lyt->name ?>" />
								</div>
								<div class="panel-body">
									<?php echo $cap; ?></span>
								</div>
							</div>									
						</td>
						<?php endforeach; ?>
						</tr>
						</table>
					</div>
				</div>	
				<!-- SPACE USETYPES -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-gears"></i> Servicios<p>El espacio es adecuado y esta sugerido para realizar:</p>
					</div>
					<div id="usetypesdetailpanel" class="panel-body">
						<div>
						<?php foreach($this->front->page->get_categoryauxiliars('usetypes') as $obj): ?>									
							<?php if(array_key_exists($obj->id, $item->data_usetypes)): ?>
								<span class="label label-success usetype" data-toggle="tooltip" title="<?php echo $obj->description; ?>"><span class="glyphicon glyphicon-ok"></span>&nbsp;
							<?php else: ?>
								<span class="label label-default usetype"><span class="glyphicon glyphicon-remove"></span>&nbsp;
							<?php endif; ?>
							<?php echo $obj->name; ?></span>
						<?php endforeach; ?>
						</div>
					</div>
				</div>
				<!-- SPACE FACILITIES -->
				<div class="panel panel-default facilities">
					<div class="panel-heading"><i class="fa fa-plus-square"></i> Facilidades
						<p>Facilidades disponibles en el espacio, pueden ser opcionales o incluidas en el alquiler.</p>
					</div>
					<div class="panel-body">
							<div id="facilitiesdetail">	
								<table class="detail">	
									<?php foreach($this->front->page->get_categoryauxiliars('facilities_dd') as $catname=>$listcatArr): ?>	
									<tr>	
										<td>
											<dl class="dl-horizontal">
												<dt><?php echo $catname ?></dt>
												<dd>									
												<?php $i=0; ?>
												<?php foreach($listcatArr as $id=>$name): ?>
												<?php $i++; ?>
													<?php if(in_array($id, $item->data_facilities)): ?>
														<span class="label label-success facility" data-toggle="tooltip" title="<?php echo $facilitiesArr[$id]->description; ?>"><span class="glyphicon glyphicon-ok"></span>&nbsp;
													<?php else: ?>
														<span class="label label-default facility" data-toggle="tooltip" title="No disponible"><span class="glyphicon glyphicon-remove"></span>&nbsp;
													<?php endif; ?>
													<?php echo $name; ?></span>
												<?php endforeach; ?>
												</dd>												
											</dl>
										</td>
									</tr>		
									<?php endforeach; ?>
								</table>	
							</div>
					</div>
				</div>	
				<a class="returnlink bottom" href="<?php echo $lastURL; ?>"><i class="fa fa-chevron-circle-left"></i> Volver</a>																		
			</div>
			<!-- Side Bar Start -->
			<div class="col-xs-12 col-sm-12 col-md-3">
				<div class="inner-sidebar">
					<!-- Sidebar widget -->
					<div class="side-widget">
						<div class="widget-content">
							<h2><?php echo $item->loc_name; ?></h2>
							<ul class="fa-ul">
								<li><i class="fa-li fa fa-home"></i> <?php echo $item->loc_type ?></li>
								<li><i class="fa-li fa fa-map-marker"></i> <?php echo $item->loc_geo_street_name.' al '.round_number($item->loc_geo_street_number); ?>
								<br>(<?php echo $item->loc_area ?>) </li>	
								<li><i class="fa-li fa fa-globe"></i><?php echo $item->loc_city ?>, <?php echo $item->loc_country ?></li>
							</ul>
							<div class="space-panel">
								<h3><?php echo $item->space_denomination; ?> <?php echo $item->space_name; ?><br>
									<?php echo $item->space_usetype; ?></h3>
								<ul class="fa-ul">
									<li><i class="fa-li fa fa-gears"></i> Servicios:<br>
										<?php foreach($this->front->page->get_categoryauxiliars('usetypes') as $obj): ?>									
											<?php if(array_key_exists($obj->id, $item->data_usetypes)): ?>
												<i class="fa fa-check"></i> <?php echo $obj->name; ?><br>
											<?php endif; ?>
										<?php endforeach; ?> 
									</li>
									<li><i class="fa-li fa fa-users"></i> <?php echo $item->space_max_capacity ?> pax.</li>
								</ul>
							</div>
							<button class="btn btn-lg amrblue btn-block" data-toggle="modal" data-target="#amrformmessage">Envianos una consulta</button>						
						</div>					
					</div>
				</div>
			</div>
			<!-- Side BAr End -->
		</div>	
	</div>
</div>

<!-- Modal -->
<?php echo $modalform; ?>