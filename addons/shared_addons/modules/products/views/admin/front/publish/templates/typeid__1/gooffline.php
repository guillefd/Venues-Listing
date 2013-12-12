<script>

var VECqueueProcess = new Array("deletecloudimages", "unpublishproduct");
var typeID = <?php echo $typeid ?>;
var frontID = <?php if(isset($front->id)){ echo $front->id; } else { echo '"undefined"'; } ?>;
var img_loader = '<img src="' + IMG_PATH + 'indicator.gif" style="float:right; margin:5px;" id="loader" alt="" /></div>';


</script>

<section class="title">
		<h4><?php echo lang('front:title_unpublish').' | '.lang('front:subtitle_unpublish') ?></h4>
</section>
<section class="item">
	<div class="content">
		<table class="tableBox max">
			<tbody>
				<tr>	
					<td><strong><?php echo lang('front:product_name') ?></strong></td>
					<td><?php echo $front->name; ?></td>
				</tr>	
				<tr>	
					<td><strong><?php echo lang('front:located') ?></strong></td>
					<td>
						<?php 
							echo '['.$front->loc_type.'] '.$front->loc_name.' / '; 
							echo $front->loc_city.' / '.$front->loc_country; 
						?>
					</td>
				</tr>
				<tr>	
					<td width="25%"><strong><?php echo lang('front:publishcategory') ?></strong></td>
					<td>[<?php echo $front->prod_cat_slug; ?>]</td>
				</tr>
				<tr>	
					<td><strong><?php echo lang('front:product_usetype') ?></strong></td>
					<td>[<?php echo $front->space_usetype; ?>]</td>
				</tr>
			</tbody>
		</table>
		<p class="alert warning">	
			<strong><?php echo lang('front:offline_title_comment') ?></strong> <?php echo lang('front:offline_comment') ?>
		</p>
		<div style="text-align:center;">
			<p><button class="btn red large" type="button" id="btnUnpublish"><?php echo lang('front:offline_button') ?></button></p>
		</div>
		<table class="tableBox max">
			<tr>	
				<td width="25%"><strong><?php echo lang('front:title_unpublish_process') ?></strong></td>
				<td width="50%"></td>
				<td></td>				
			</tr>
			<tr>
				<td>
					<?php echo lang('front:proc_unpublish_images') ?>
				</td>
				<td>
					<div id="cloudimagesProgressbar"><div class="cloudimages-progress-label"><?php echo lang('front:initiating_unpublish') ?></div></div>
				</td>
				<td><span id="cloudimages-queueTxt"><?php echo lang('front:waiting') ?></span></span></td>				
			</tr>			
			<tr>
				<td>
					<?php echo lang('front:proc_unpublish_publication') ?>
				</td>
				<td>
					<div id="productProgressbar"><div class="product-progress-label"><?php echo lang('front:initiating_unpublish') ?></div></div>
				</td>
				<td><span id="product-queueTxt"><?php echo lang('front:waiting') ?></span></td>				
			</tr>	
			<tr>
				<td></td>
				<td><span id="txtcompleted"></span></td>			
				<td></td>
			</tr>			
			<tr>
				<td colspan="3">
					<div style="text-align:center;"><button class="btn orange" type="button" id="btnReturn" onclick="return btnReturn_onclick()">Cancelar</button></div>
				</td>			
			</tr>							
		</table>
	</div>
</section>