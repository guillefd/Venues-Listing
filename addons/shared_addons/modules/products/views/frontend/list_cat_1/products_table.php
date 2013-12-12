<?php $i = 0; ?>	
<?php foreach($result->list->items as $item): ?>
<?php $itemUri = base_url().$item->prod_cat_slug.'+'.$item->space_usetype_slug.'/'.$item->loc_city_slug.'/'.$item->loc_slug.'/'.$item->space_slug.'+'.$item->front_version; ?>	
<div class="table-responsive">
	<table class="table table-bordered table-homelist" onmouseover="markers[<?php echo $i; ?>].setIcon(gimage_hover)" onmouseout="markers[<?php echo $i; ?>].setIcon(gimage)">
		<tr>
			<td rowspan="4" width="300px">
				<div id="crsl-homelist-<?php echo $item->id ?>" class="carousel slide crsl-homelist" data-ride="carousel">
				<div class="carousel-inner">
					<?php foreach($item->cloud_sm_images as $index=>$img): ?>
						<div class="item <?php if($index==0) echo 'active'; ?>">
							<a href="<?php echo $itemUri; ?>">
								<img src="<?php echo $media->cdnUri.$img; ?>" alt="<?php echo $item->space_denomination.' '.$item->space_name ?>" />
								<div class="carousel-caption">
									<p><?php echo $item->space_denomination.' '.$item->space_name ?></p>
								</div>
							</a>
						</div>
					<?php endforeach; ?>											
				</div>			  
				<a class="left carousel-control" href="#crsl-homelist-<?php echo $item->id ?>" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left"></span>
				</a>
				<a class="right carousel-control" href="#crsl-homelist-<?php echo $item->id ?>" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right"></span>
				</a>
				</div>
			</td>
			<td colspan="2" class="theader">
				<h4>				
					<span class="label-usetype"><?php echo $item->space_usetype_slug ?> <span class="glyphicon glyphicon-ok-sign"></span></span> 						
					<a href="<?php echo $itemUri; ?>">
						<span class="glyphicon glyphicon-tag"></span> 
						<?php echo $item->space_denomination ?>&nbsp;<?php echo $item->space_name ?>
					</a>
					<span class="pull-right">
						<span class="glyphicon glyphicon-map-marker"></span> <?php echo $item->loc_area ?>
					</span>
					<span class="pull-right"> 
						<span class="glyphicon glyphicon-user"></span> <?php echo $item->space_max_capacity ?> &nbsp;&nbsp;&nbsp;
					</span>						
				</h4>						
			</td>
		</tr>
		<tr>
			<td class="content" width="35%">
				<ul class="list-group">
					<li class="list-group-item">
						<span class="badge badge-inv"><?php echo $item->space_width ?> x <?php echo $item->space_length ?> (<?php echo $item->space_square_mt ?> m2)</span>
						<p><span class="glyphicon glyphicon-fullscreen"></span> medidas</p>
					</li>
					<li class="list-group-item">
						<span class="badge badge-inv"><?php echo $item->space_shape ?></span>
						<p><span class="glyphicon glyphicon-stop"></span> forma</p>
					</li>				
				</ul>
			</td>
			<td class="content">
				<ul class="list-group">
					<li class="list-group-item">
						<span class="badge badge-inv"><?php echo $item->space_max_capacity ?></span>
						<p><span class="glyphicon glyphicon-user"></span> capacidad max</p>
					</li>			
				</ul>			
			</td>
		</tr>	
		<tr>
			<td class="content">
				<ul class="list-group">
					<li class="list-group-item">
						<span class="badge badge-inv"><?php echo $item->loc_name ?></span>
						<h5>Locación</h5>
					</li>			
				</ul>					
			</td>
			<td class="content">
				<ul class="list-group">
					<li class="list-group-item">
						<span class="badge badge-inv"><?php echo $item->loc_type ?></span>
						<p><span class="glyphicon glyphicon-home"></span> Tipo de Locación</p>
					</li>			
				</ul>				
			</td>
		</tr>	
		<tr>
			<td class="content last">
				<ul class="list-group">
					<li class="list-group-item">
						<span class="badge badge-inv"><?php echo $item->loc_area ?>, <?php echo $item->loc_city ?><br><small class="pull-right"><?php echo $item->loc_country ?></small></span>
						<h5>Ubicación</h5>	
					</li>			
				</ul>			
			</td>
			<td class="content last">
				<ul class="list-group">
					<li class="list-group-item">
						<a class="btn btn-primary btn-sm pull-right" href="<?php echo $itemUri; ?>">Ver mas</a>					
						<span class="badge badge-inv"></span>
					</li>				
				</ul>
			</td>
		</tr>		
	</table>
</div>
<?php $i++; ?>
<?php endforeach; ?>	
