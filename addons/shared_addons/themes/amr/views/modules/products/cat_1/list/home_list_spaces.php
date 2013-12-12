<!-- JS VARS -->
<script>
    var BASE_URL = '{{ global:base_url }}';
    var FILTER_VALUES_json = '<?php echo json_encode($filtervalues); ?>';
    var CAT_SLUG = '<?php echo $data->validurisegments[1]->prod_cat_slug; ?>';
    var CITY_SLUG = '<?php echo $data->validurisegments[2]->loc_city_slug; ?>';
    var LOCTYPE_SLUG_json = '<?php echo json_encode($htmlFilterArr->location_types_slug); ?>';
	var layout_img = '{{ theme:image file="layouts_filter.png" }}';
	var amrMapData = '<?php echo json_encode($data->map); ?>';
</script>
<div class="row mapfilter">
	<div class="col-xs-12 col-sm-12 col-md-5">
		<div id="fixedmap" class="home-map">
            <!-- google maps api v3-->
            <div id="map-canvas"></div>
        </div>	
	</div>
	<div class="col-xs-12 col-sm-12 col-md-7 mapfilter">
<!-- filter -->
			<?php echo $filter; ?>
<!-- fitler result-->
		<div id="fixedFilterRow" class="row filter-result">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<h4>
					<span class="glyphicon glyphicon-map-marker"></span>
					<strong><?php echo $data->searchparams['cityname']; ?></strong>
					<span class="glyphicon glyphicon-chevron-right"></span> 
					<?php echo $data->searchparams['prodcatname']; ?>
					<small class="pull-right">
						espacios en alquiler &nbsp;&nbsp;&nbsp;<strong><?php echo $data->result->list->totrows; ?></strong>
					</small>
				</h4>																		
			</div>
		</div>			
<!-- end filter -->
<!-- flash message -->
			<?php if($this->session->flashdata('homelist_message')): ?>
			<div class="alert alert-danger alert-dismissable flashdata">
  				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  				<?php echo $this->session->flashdata('homelist_message'); ?>
  			</div>
			<?php endif; ?>
<!-- end flash message -->
<!-- pagemessage -->
			<?php echo $data->get_message('result'); ?>
<!-- end pagemessage -->
<!-- list -->				
			<?php echo $table;	?>
<!-- end list -->		
	</div>
</div>				

<!-- info TEXT location types-->
<div id="location_types_table" style="display:none">
	<table class="table table-striped table-condensed table-popover">
		<?php foreach($htmlFilterArr->location_types_txt as $type): ?>
		<tr>
			<td width="30%"><strong><?php echo $type->name ?></strong></td><td><?php echo $type->description ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>

<!-- info TEXT usetypes -->
<div id="usetypes_table" style="display:none">
	<table class="table table-striped table-condensed table-popover-usetype">
		<?php foreach($htmlFilterArr->usetypes_txt as $type): ?>
		<tr>
			<td width="30%"><strong><?php echo $type->name ?></strong></td><td><?php echo $type->description ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
