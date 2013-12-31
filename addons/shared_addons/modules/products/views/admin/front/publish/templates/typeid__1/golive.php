<script>

var CLOUDIMGQTY = <?php echo $cloudimgsQTY ?>; 
var processmode = "<?php echo $processMode ?>";
var VECqueueProcess = new Array("cloudimages","checkcloudimages", processmode);
var draftID = <?php echo $draft->id ?>;
var typeID = <?php echo $typeid ?>;
var frontID = <?php if(isset($draft->frontid)){ echo $draft->frontid; } else { echo '"undefined"'; } ?>;
var prodcatID = <?php echo $draft->prod_cat_id ?>;
var img_loader = '<img src="' + IMG_PATH + 'indicator.gif" style="float:right; margin:5px;" id="loader" alt="" /></div>';


</script>

<section class="title">
		<h4><?php echo lang('front:title').' | '.lang('front:subtitle_publish_'.$processMode) ?></h4>
</section>
<section class="item">
	<div class="content">
		<table class="tableBox max">
			<tr>	
				<td width="25%"><strong><?php echo lang('front:publishcategory') ?></strong></td>
				<td>[<?php echo $draft->prod_cat_slug; ?>]</td>
			</tr>
			<tr>	
				<td><strong><?php echo lang('front:product_usetype') ?></strong></td>
				<td>
					<?php if($draft->space_usetype==''): ?>
						[PUBLICACION BASICA]
					<?php else: ?>
						[<?php echo $draft->space_usetype ?>]
					<?php endif; ?>						
				</td>
			</tr>
			<tr>	
				<td><strong><?php echo lang('front:product_name') ?></strong></td>
				<td><?php echo $draft->name; ?></td>
			</tr>	
			<tr>	
				<td><strong><?php echo lang('front:located') ?></strong></td>
				<td>
					<?php 
						echo '['.$draft->loc_type.'] '.$draft->loc_name.' / '; 
						echo $draft->loc_city.' / '.$draft->loc_country; 
					?>
				</td>
			</tr>					
		</table>
		<table class="tableBox max">
			<tr>	
				<td width="25%"><strong><?php echo lang('front:'.$processMode) ?></strong></td>
				<td width="50%"></td>
				<td></td>				
			</tr>
			<tr>
				<td>
					<?php echo lang('front:proc_images') ?>
				</td>
				<td>
					<div id="cloudimagesProgressbar"><div class="cloudimages-progress-label"><?php echo lang('front:initiating') ?></div></div>
				</td>
				<td><span id="cloudimages-queueTxt"><?php echo lang('front:waiting') ?></span></span></td>				
			</tr>
			<tr>
				<td>
					<?php echo lang('front:images_verification') ?>
				</td>
				<td>
					<div id="cloudimagesProgressbar2"><div class="cloudimages-progress-label2"><?php echo lang('front:initiating') ?></div></div>
				</td>
				<td><span id="cloudimages-queueTxt2"><?php echo lang('front:waiting') ?></span></span></td>				
			</tr>			
			<tr>
				<td>
					<?php echo lang('front:proc_publication') ?>
				</td>
				<td>
					<div id="productProgressbar"><div class="product-progress-label"><?php echo lang('front:initiating') ?></div></div>
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
					<div style="text-align:center;"><button class="btn gray disabled" type="button" id="btnReturn" onclick="return btnReturn_onclick()" disabled>Volver al listado de publicaciones </button></div>
				</td>			
			</tr>							
		</table>
	</div>
</section>