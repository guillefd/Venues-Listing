<script>
	var amrMapData = '<?php echo json_encode($this->front->page->map); ?>';
</script>
<div class="inner-page space-view">
	<div class="container">	
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-7 col-md-offset-1">
				<h2><strong><?php echo $item->space_denomination.' '.$item->space_name; ?></strong> - <?php echo $item->loc_name; ?></h2>
				<ul class="list-inline">
				  <li><i class="fa fa-home"></i> <?php echo $item->loc_type.' ('.$item->space_denomination.')'; ?></li>
				  <li><i class="fa fa-map-marker"></i> <?php echo $item->loc_geo_street_name.' '.round_number($item->loc_geo_street_number).' ('.$item->loc_area.'), '.$item->loc_city.', '.$item->loc_country ?></li>				  				  
				</ul>
				<!-- PHOTOS AND MAP -->
				<div class="itempanel">
					<div class="inner-tab">
						<!-- Nab Bar Tan Menu list -->
						<ul id="itempanelnav" class="nav nav-tabs">
							<li class="active"><a href="#photo" data-toggle="tab">Fotos</a></li>
							<li><a id="btnMap" href="#map" data-toggle="tab">Mapa</a></li>
						</ul>
						<!-- Content for each menu item -->
						<div class="tab-content">
							<!-- First Content for Nav bar -->
							<div class="tab-pane fade in active" id="photo">
								<div class="photoslider">								
									<div id="crsl-itemview-<?php echo $item->id ?>" class="carousel slide crsl-itemview" data-ride="carousel">					
									<div class="carousel-inner">
										<?php foreach($item->cloud_md_images as $index=>$img): ?>
											<div class="item <?php if($index==0) echo 'active'; ?>">
												<a>
													<img class="photosliderimg" src="<?php echo $data->media->cdnUri.$img; ?>" width="640px" height="430px" alt="<?php echo $item->space_denomination.' '.$item->space_name ?>" />
													<div class="carousel-caption">
														<p><?php echo $item->space_denomination.' '.$item->space_name ?></p>
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
				</div>
				<!-- SPACE USETYPES -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Usos del espacio</h3>
					</div>
					<div id="usetypesdetailpanel" class="panel-body">
						<p>El espacio es adecuado y esta sugerido para realizar:</p>
						<div>
						<?php foreach($this->front->page->get_categoryauxiliars('usetypes') as $obj): ?>									
							<?php if(array_key_exists($obj->id, $item->data_usetypes)): ?>
								<span class="label label-primary usetype" data-toggle="tooltip" title="<?php echo $obj->description; ?>"><span class="glyphicon glyphicon-ok"></span>&nbsp;
							<?php else: ?>
								<span class="label label-default usetype"><span class="glyphicon glyphicon-remove"></span>&nbsp;
							<?php endif; ?>
							<?php echo $obj->name; ?></span>
						<?php endforeach; ?>
						</div>
					</div>
				</div>	

				<!-- SPACE LOCATION DETAILS -->				
				<div class="itempanel spacedetails">
					<div class="inner-tab">
						<!-- Nab Bar Tan Menu list -->
						<ul id="itempanelnav" class="nav nav-tabs">
							<li class="active"><a href="#spacedetail" data-toggle="tab"><?php echo $item->space_denomination; ?></a></li>
							<li><a href="#locationdetail" data-toggle="tab">Locación</a></li>
							<li><a href="#facilitiesdetail" data-toggle="tab">Facilidades</a></li>				
						</ul>	
						<!-- Content for each menu item -->
						<div class="tab-content">
							<div class="tab-pane fade in active" id="spacedetail">								
								<ul class="list-group spacedetails pull-right">
									<li class="list-group-item">
										<span class="badge white"><?php echo $item->space_denomination; ?></span>
										Tipo de espacio
									</li>
									<li class="list-group-item">
										<span class="badge white"><?php echo $item->space_max_capacity; ?></span>
										Capacidad máxima
									</li>
									<li class="list-group-item">
										<span class="badge white"><?php echo $item->space_width.' x '.$item->space_length.' mt (alto '.$item->space_height.' mt)'; ?></span>
										Medidas
									</li>	
									<li class="list-group-item">
										<span class="badge white"><?php echo $item->space_square_mt; ?> m2</span>
										Metros cuadrados
									</li>																	
									<li class="list-group-item">
										<span class="badge white"><?php echo $item->space_shape; ?></span>
										Forma
									</li>	
									<li class="list-group-item">
										<span class="badge white"><?php echo $item->space_level; ?></span>
										Nivel
									</li>
									<li class="list-group-item">
										<span class="badge white"><?php echo $item->loc_country; ?></span>
										Pais
									</li>
									<li class="list-group-item">
										<span class="badge white"><?php echo $item->loc_city; ?></span>
										Ciudad
									</li>
									<li class="list-group-item">
										<span class="badge white"><?php echo $item->loc_area; ?></span>
										Zona
									</li>
								</ul>
								<p class="half">Detalles y especificaciones del Espacio.</p>								
							</div>
							<div class="tab-pane fade in" id="locationdetail">
								<ul class="list-group spacedetails pull-right">
									<li class="list-group-item">
										<span class="badge white"><?php echo $item->loc_type; ?></span>
										Tipo de locación
									</li>
									<li class="list-group-item">
										<span class="badge white"><?php echo $item->loc_name; ?></span>
										Nombre
									</li>
									<li class="list-group-item">
										<span class="badge white"><?php echo $item->loc_geo_street_name; ?></span>
										Dirección (Calle)
									</li>
									<li class="list-group-item">
										<span class="badge white"><?php echo round_number($item->loc_geo_street_number); ?></span>
										Dirección (Altura)
									</li>
									<li class="list-group-item">
										<span class="badge white"><?php echo $item->loc_country; ?></span>
										Pais
									</li>
									<li class="list-group-item">
										<span class="badge white"><?php echo $item->loc_city; ?></span>
										Ciudad
									</li>
									<li class="list-group-item">
										<span class="badge white"><?php echo $item->loc_area; ?></span>
										Zona
									</li>
								</ul>
								<p class="half">Detalles y especificaciones de la Locación.</p>		
							</div>
							<div class="tab-pane fade in" id="facilitiesdetail">
								<p>Facilidades disponibles en el espacio, pueden ser opcionales o incluidas en el alquiler.</p>	
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
														<span class="label label-default facility"><span class="glyphicon glyphicon-remove"></span>&nbsp;
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
				</div>	
				<!-- SPACE LAYOUTS -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Armados</h3>
					</div>
					<div id="layoutsdetailpanel" class="panel-body">
						<p>Armados disponibles y su capacidad máxima.</p>	
						<table>
						<tr>
						<?php foreach($layoutsArr as $id=>$lyt): ?>
						<td>
							<?php if(array_key_exists($id, $item->data_layouts)): ?>
							<?php $class = 'panel-success';  $cap = '<span class="badge">'.$item->data_layouts[$id]->capacity.'</span>'; ?>
							<?php else: ?>
							<?php $class = 'panel-default'; $cap = '&nbsp;'; ?>
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
			</div>
			<!-- Side Bar Start -->
			<div class="col-xs-12 col-sm-12 col-md-3">
				<div class="inner-sidebar">
					<!-- Sidebar widget -->
					<div class="side-widget">
						<div class="widget-content">
							<h2><?php echo $item->loc_name; ?></h2>
							<p>
								<i class="fa fa-home"></i> <?php echo $item->loc_type ?><br>
								<i class="fa fa-map-marker"></i> <?php echo $item->loc_geo_street_name.' '.round_number($item->loc_geo_street_number).', '.$item->loc_city; ?>
							</p>							
							<button class="btn large amrblue btn-block" data-toggle="modal" data-target="#amrformmessage">Envianos una consulta</button>
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