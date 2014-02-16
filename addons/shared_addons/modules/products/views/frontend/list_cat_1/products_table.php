<?php //var_dump($this->front->page->get_categoryauxiliars('facilities')); die; ?>
<?php $i = $result->list->offset; ?>
<?php $facilitiesArr = $this->front->page->get_categoryauxiliars('facilities'); ?>
<?php $featuresArr = $this->front->page->get_categoryauxiliars('features_defaults_list'); ?>
<?php foreach($result->list->items as $item): ?>
<?php //var_dump($featuresArr); die; ?>		
<?php //var_dump($item); ?>		
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
				<a href="<?php echo $item->itemUri; ?>"><span class="glyphicon glyphicon-chevron-right"></span> <?php echo $item->space_denomination ?>&nbsp;<?php echo $item->space_name ?></a>
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
			<td class="content" colspan="3">
			<span class="label-usetype-list">{{ theme:image file="amr-isologo-sm.png" class="amrlogo-sm table-product-usetype" }} <?php echo $item->space_denomination; ?> para <strong><?php echo $item->space_usetype_slug; ?></strong></span>
				<div class="pull-left">
					<?php foreach($this->front->CFG->facilities_labels_display[$item->space_usetype_id] as $fldid ): ?>
						<?php if(in_array($fldid, $item->space_facilities_list)): ?>	
							<span class="label label-info-product "><i class="fa fa-check"></i> <?php echo $facilitiesArr[$fldid]->name; ?></span>
						<?php else: ?>
							<span class="label label-info-product none"> <?php echo $facilitiesArr[$fldid]->name; ?></span>
						<?php endif; ?>
					<?php endforeach; ?>
					<?php foreach($this->front->CFG->features_labels_display[$item->space_usetype_id] as $ftrid ): ?>
						<?php if(in_array($fldid, $item->space_features_list)): ?>	
							<span class="label label-info-product "><i class="fa fa-check"></i> <?php echo $featuresArr[$ftrid]->short_name; ?></span>
						<?php else: ?>
							<span class="label label-info-product none"> <?php echo $featuresArr[$ftrid]->short_name; ?></span>
						<?php endif; ?>					
					<?php endforeach; ?>					
				</div>
			</td>					
		</tr>	
		<tr>
			<td class="content" colspan="3">
				<p>
					<span class="loc-color"><span class="glyphicon glyphicon-home"></span> <?php echo $item->loc_name ?></span>
				</p>			
			</td>
		</tr>	
		<tr>
			<td class="content last" colspan="3">
				<p>
					<i class="fa fa-flag"></i> <?php echo $item->loc_type ?><br>
					<small class="pull-left"><i class="fa fa-location-arrow"></i> <?php echo $item->loc_city ?></small>
			    </p>					
				<a class="btn btn-primary btn-md pull-right btn-list-go" href="<?php echo $item->itemUri; ?>">Ver <strong>servicios</strong> y <strong>consultar</strong></a>					
			</td>
		</tr>		
	</table>
</div>
<?php $i++; ?>
<?php endforeach; ?>	
