<?php $i = $result->list->offset; ?>
<?php foreach($result->list->items as $item): ?>
<div id="amrresulttable" class="table-responsive">
	<table class="table table-bordered table-homelist product" onmouseover="markers[<?php echo $i; ?>].setIcon(gimage_hover)" onmouseout="markers[<?php echo $i; ?>].setIcon(gimage)">
		<tr>
			<td rowspan="4" width="300px">
				<div id="crsl-homelist-<?php echo $item->id ?>" class="carousel slide crsl-homelist" data-ride="carousel">
				<div class="carousel-inner">
					<?php foreach($item->cloud_sm_images as $index=>$img): ?>
						<div class="item <?php if($index==0) echo 'active'; ?>">
							<a href="<?php echo $item->itemUri; ?>">
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
			<td colspan="3" class="theader">
				<h4>				
				<a href="<?php echo $item->itemUri; ?>"><h4><span class="glyphicon glyphicon-chevron-right"></span> <?php echo $item->space_denomination ?>&nbsp;<?php echo $item->space_name ?></a>
					<span class="pull-right">
						<span class="glyphicon glyphicon-map-marker"></span> <?php echo $item->loc_area ?>
					</span>
					<span class="pull-right"> 
						<span class="glyphicon glyphicon-user"></span> <?php echo $item->space_max_capacity ?>  <a data-toggle="tooltip" title="Capacidad máxima de personas">pax</a> &nbsp;&nbsp;&nbsp;
					</span>						
				</h4>						
			</td>
		</tr>
		<tr width="100%">
			<td class="content" width="25%">
				<ul class="list-group">
					<span class="label label-success usetype"><span class="glyphicon glyphicon-ok"></span> <?php echo $item->space_usetype_slug ?></span>
				</ul>
			</td>
			<td class="content" colspan="2">
				<div class="pull-right">
					<span class="label label-info-product "><i class="fa fa-check"></i> Feature</span>
					<span class="label label-info-product "><i class="fa fa-check"></i> Feature</span>
					<span class="label label-info-product "><i class="fa fa-check"></i> Feature</span>
					<span class="label label-info-product "><i class="fa fa-check"></i> Feature</span>
					<span class="label label-info-product "><i class="fa fa-check"></i> Feature</span>
					<span class="label label-info-product "><i class="fa fa-check"></i> Feature</span>
					<span class="label label-info-product "><i class="fa fa-check"></i> Feature</span>
					<span class="label label-info-product "><i class="fa fa-check"></i> Feature</span>
					<span class="label label-info-product "><i class="fa fa-check"></i> Feature</span>
				</div>
			</td>					
		</tr>	
		<tr>
			<td class="content" colspan="3">
				<p>
					<span class="loc-color"><span class="glyphicon glyphicon-home"></span> <?php echo $item->loc_name ?></span>
					<span class="pull-right"><i class="fa fa-flag"></i> <?php echo $item->loc_type ?></span>
				</p>			
			</td>
		</tr>	
		<tr>
			<td class="content last" colspan="3">
				<p>
					<i class="fa fa-location-arrow"></i> <?php echo $item->loc_city ?> ( <?php echo $item->loc_area ?> )<br>
					<small class="pull-left"><i class="fa fa-globe"></i> <?php echo $item->loc_country ?></small>
			    </p>					
				<a class="btn btn-primary btn-md pull-right" href="<?php echo $item->itemUri; ?>">Ver <strong>servicios</strong> y <strong>consultar</strong></a>					
			</td>
		</tr>		
	</table>
</div>
<?php $i++; ?>
<?php endforeach; ?>	
