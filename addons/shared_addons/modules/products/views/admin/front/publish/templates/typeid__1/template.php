<section class="title">
		<h4><?php echo lang('front:title').' | '.lang('front:subtitle_publish') ?></h4>
</section>
<section class="item">
	<div class="content">	
<!-- Category :::::::::::::::::: -->	
		<h3><?php echo lang('front:publishcategory') ?></h3>

		<?php echo form_open('admin/products/front/golive/'.$item->id, 'class="crud"'); ?>

		<table class="tableBox max">
			<tr>	
				<td width="15%"><strong>[ <?php echo lang('front:category') ?> ]</strong></td>
				<td width="50%"><?php echo $item->category ?> [<?php echo $item->prod_cat_slug; ?>]</td>
				<td>	
					<?php echo lang('front:id') ?>: <?php echo $item->prod_cat_id; ?>							
				</td>
			</tr>
		</table>
<!-- Product :::::::::::::::::: -->	 
		<h3><?php echo lang('front:item_title') ?></h3>	
		<table class="tableBox max">
			<thead>
				<tr>
					<th width="15%">
						<h2><?php echo lang('front:product') ?></h2>
					</th>
					<th width="50%">
					</th>
					<th>
						<?php echo lang('front:details') ?>
					</th>
				</tr>
			</head>
			<tbody>	
				<tr>				
					<td>
						<strong><?php echo lang('front:product_name') ?></strong>
					</td>
					<td>
						<?php echo $item->name ?>
					</td>
					<td>
						<?php echo lang('front:id') ?>: <?php echo $item->prod_id; ?><br>
						<?php echo lang('front:slug') ?>: <?php echo $item->name_slug; ?><br>
					</td>							
				</tr>		
				<tr>				
					<td>
						<strong><?php echo lang('front:product_usetype') ?></strong>
					</td>
					<td>
						[<?php echo $item->space_usetype ?>]
					</td>
					<td>
						<?php echo lang('front:id') ?>: <?php echo $item->space_usetype_id; ?><br>
					</td>							
				</tr>												
				<tr>				
					<td><strong><?php echo lang('front:account') ?></strong></td>
					<td><?php echo $item->account ?></td>	
					<td>					
						<?php echo lang('front:seller') ?>: <?php if($item->seller_account) echo $item->seller_account; ?>
					</td>						
				</tr>									
				<tr>				
					<td><strong><?php echo lang('front:links_publish') ?></strong></td>
					<td>
						<?php echo $item->prod_cat_slug.'/'.
						           $item->loc_city_slug;
						           if(!empty($item->loc_area_slug)) echo '+'.$item->loc_area_slug;
						      echo '/'.$item->loc_slug.'/'.
						           $item->space_slug;       
						?>
						<br>
						<?php echo $item->prod_cat_slug.'/'.
						           $item->loc_city_slug;
						           if(!empty($item->loc_area_slug)) echo '+'.$item->loc_area_slug;
						      echo '/'.$item->loc_slug.'/'.
						           $item->name_slug;       
						?>										
					</td>
					<td></td>							
				</tr>
				<tr>
					<td><strong><?php echo lang('front:intro') ?></strong></td>		
					<td><?php echo $item->intro ?></td>									
					<td></td>						
				</tr>				
				<tr>
					<td><strong><?php echo lang('front:description') ?></strong></td>
					<td><?php echo $item->body ?></td>		
					<td></td>														
				</tr>
				<tr>
					<td><strong><?php echo lang('front:images') ?></strong> <?php echo show_validation($item->images_size_checked['validated']) ?></td>		
					<td> 
						<div class="imgviewdraft-container">
							<?php $i=0; ?>
							<?php foreach($item->images_params as $img): ?>
								<div class="imgviewdraft-thumb">				
									<?php echo anchor('admin/products/front/previewimage/?'.http_build_query($img), '<img src="'.$basethumbimguri.$img['id'].'/150/100/fit" width="150px" height=100px" />', 'rel="modal-preview" target="_blank" title="'.lang('global:view').'"'); ?>
									<br>
									<p>
										<input type="radio" name="imghomebannerselected_id" value="<?php echo $img['id'] ?>" 
										<?php if(!check_imagesize_before_crop($img, $imgSizes['lg'], 'lg')) echo 'disabled' ?> /> 
										<?php echo lang('front:img-home-banner-selected') ?>
									</p>
								</div>
							<?php endforeach; ?>
						</div>							
						<p><input type="radio" name="imghomebannerselected_id" value="none" /> <?php echo lang('front:img-home-banner-selected-none') ?></p>
					</td>
					<td>
						<table>
							<tr>
								<th colspan="4"><?php echo lang('front:imagevalidationresult'); ?></th>
							</tr>
							<tr>
								<td><?php echo lang('front:size'); ?></td>
								<td><?php echo lang('front:quantity'); ?></td>
								<td><?php echo lang('front:min-required'); ?></td>
								<td><?php echo lang('front:valid'); ?></td>
							</tr>
							<?php foreach($item->images_size_checked as $sz=>$reg): ?>
							<?php if(isset($reg->imgids)): ?>
								<tr>
									<td><?php echo $sz.' ('.$imgSizes[$sz][0].'x'.$imgSizes[$sz][1].')'; ?></td>
									<td>
										<?php echo count($reg->imgids); ?>
									</td>
									<td>
										<?php echo $imgSizes[$sz][3]; ?>
									</td>
									<td>
										<?php if($reg->validation) echo '&#10004;'; ?>
									</td>
								</tr>
							<?php endif; ?>
							<?php endforeach; ?>
						</table>
					</td>														
				</tr>
				<tr>
					<td><strong><?php echo lang('front:features') ?></strong></td>
					<td>
						<table>
							<tr>
								<th><?php echo lang('front:name') ?></th>
								<th><?php echo lang('front:description') ?></th>
								<th><?php echo lang('front:value') ?></th>
								<th><?php echo lang('front:usageunit') ?></th>
								<th><?php echo lang('front:isoptional') ?></th>
							</tr>
							<?php foreach($item->features as $feat): ?>
							<tr>									
								<td><?php echo $feat->name ?></td>
								<td><?php echo $feat->description ?></td>
								<td><?php echo $feat->value ?></td>
								<td><?php echo $feat->usageunit ?></td>
								<td><?php echo $dd_yes_no[$feat->is_optional] ?></td>
							</tr>	
							<?php endforeach; ?>
						</table>
					</td>		
					<td></td>	
				</tr>								
			</tbody>
		</table>	
<!-- Locacion :::::::::::::::::: -->				
		<table class="tableBox max">
			<thead>
				<tr>
					<th width="15%"><h2><?php echo lang('front:location') ?></h2></th>	
					<th width="50%">
					</th>
					<th>
						<?php echo lang('front:details') ?>
					</th>											
				</tr>	
			</thead>
			<tbody>
				<tr>
					<td><strong><?php echo lang('front:name') ?></strong></td>
					<td><?php echo $item->loc_name ?></td>
					<td>
						<?php echo lang('front:id') ?>: <?php echo $item->loc_id; ?><br>
						<?php echo lang('front:slug') ?>: <?php echo $item->loc_slug; ?><br>
					</td>					
				</tr>
				<tr>
					<td><strong><?php echo lang('front:loc_type') ?></strong></td>
					<td><?php echo $item->loc_type ?></td>
					<td>
					</td>					
				</tr>				
				<tr>
					<td><strong><?php echo lang('front:address') ?></strong></td>
					<td>
						<div class="one_third">
							<?php echo lang('front:country') ?>:<br>	
							<?php echo lang('front:city') ?>:<br>
							<?php echo lang('front:area') ?>:<br>	
							<?php echo lang('front:address_l1') ?>:<br>																																																	
						</div>
						<div class="two_third">
							<?php echo $item->loc_country ?><br>
							<?php echo $item->loc_city ?><br>
							<?php echo $item->loc_area ?><br>
							<?php echo $item->loc_address ?><br>							
						</div>						
					</td>
					<td>
					</td>					
				</tr>	
				<tr>
					<td><strong><?php echo lang('front:contact') ?></strong></td>
					<td>
						<div class="one_third">
							<?php echo lang('front:phone') ?>:<br>	
							<?php echo lang('front:mobile') ?>:<br>
							<?php echo lang('front:email') ?>:<br>																																																	
						</div>
						<div class="two_third">
							<?php echo $item->loc_phone ?><br>
							<?php echo $item->loc_mobile ?><br>
							<?php echo $item->loc_email ?><br>							
						</div>						
					</td>
					<td>
					</td>					
				</tr>					
				<tr>
					<td><strong><?php echo lang('front:geo_coord') ?></td>
					<td>
						<div class="one_third">
							<?php echo lang('front:geo_lat') ?>:<br>
							<?php echo lang('front:geo_lng') ?>:<br>
							<?php echo lang('front:geo_street') ?>:<br>	
							<?php echo lang('front:geo_number') ?>:<br>															
						</div>
						<div class="two_third">
							<?php echo $item->loc_lat ?><br>
							<?php echo $item->loc_lng ?><br>
							<?php echo $item->loc_geo_street_name ?><br>
							<?php echo $item->loc_geo_street_number ?><br>													
						</div>
					</td>
					<td>
						<?php echo $loc_map ?>
					</td>
				</tr>									
			</tbody>
		</table>	
<!-- Espacio :::::::::::::::::: -->							
		<table class="tableBox max">
			<thead>
				<tr>
					<th width="15%"><h2> <?php echo lang('front:space') ?> </h2></th>	
					<th width="50%">
					</th>
					<th>
						<?php echo lang('front:details') ?>
					</th>											
				</tr>	
			</thead>
			<tbody>
				<tr>
					<td>
						<strong><?php echo lang('front:denomination') ?></strong><br>
						<strong><?php echo lang('front:name') ?></strong>
					</td>
					<td>
						<?php echo $item->space_denomination ?><br>
						<?php echo $item->space_name ?></td>
					<td>
						<?php echo lang('front:id') ?>: <?php echo $item->space_id; ?><br>
						<?php echo lang('front:slug') ?>: <?php echo $item->space_slug; ?><br>
					</td>					
				</tr>
				<tr>
					<td><strong><?php echo lang('front:level') ?></strong></td>
					<td><?php echo $item->space_level ?></td>		
					<td></td>	
				</tr>					
				<tr>
					<td><strong><?php echo lang('front:dimentions') ?></strong></td>
					<td>
						<div class="one_third">
							<?php echo lang('front:width') ?>:<br>
							<?php echo lang('front:length') ?>:<br>
							<?php echo lang('front:height') ?>:<br>	
							<?php echo lang('front:square_mt') ?>:<br>															
						</div>
						<div class="two_third">
							<?php echo $item->space_width ?> mts<br>
							<?php echo $item->space_length ?> mts<br>
							<?php echo $item->space_height ?> mts<br>
							<?php echo $item->space_square_mt ?> m2<br>													
						</div>											
					</td>
					<td></td>
				</tr>
				<tr>
					<td><strong><?php echo lang('front:shape') ?></strong></td>
					<td><?php echo $item->space_shape ?></td>		
					<td></td>	
				</tr>
				<tr>
					<td><strong><?php echo lang('front:product_usetypes') ?></strong></td>					
					<td>
				        <?php foreach($item->usetypes_array as $usetypeid): ?>
				        	<?php if(isset($usetypes_list[$usetypeid])) echo '['.$usetypes_list[$usetypeid].'] ' ?>
				        <?php endforeach; ?>							
					</td>
					<td></td>
				</tr>
				<tr>
					<td><strong><?php echo lang('front:max_capacity') ?></strong></td>
					<td><?php echo $item->space_max_capacity ?> pax </td>		
					<td></td>	
				</tr>														
				<tr>	
					<td><strong><?php echo lang('front:layouts') ?></strong></td>					
					<td>
						<div class="one_half">
							<table>
								<th><?php echo lang('front:layout') ?></th><th><?php echo lang('front:capacity') ?></th>
								<?php 
								foreach($item->layouts as $layout): ?>	
								<tr>
									<td><?php echo $layouts_list[$layout->layout_id]; ?></td>
									<td><?php echo $layout->capacity; ?> pax</td>
								</tr>
								<?php endforeach; ?>
							</table>	
						</div>										
					</td>
					<td></td>
				</tr>	
				<tr>	
					<td><strong><?php echo lang('front:facilities') ?></strong></td>					
					<td>
						<div class="one_full">
						    <table>
						    	<th><?php echo lang('front:category') ?></th>
						    	<th><?php echo lang('front:available') ?></th>
								    <?php foreach($facilities_list as $key => $fac_cat): ?>
								    <tr>
								    	<td><?php echo $key ?></td>
								    	<td>
									        <?php foreach($fac_cat as $skey =>$fac): ?>
									        	<?php if(in_array($skey, $item->facilities_array)) echo '['.$fac.'] ' ?>
									        <?php endforeach; ?>
								        </td>
								    </tr>
								    <?php endforeach; ?>
						    </table>							
						</div>										
					</td>
					<td></td>
				</tr>
	    	</tbody>
	    </table> 
	    <table class="tableBox max">
		    <tr>
		    	<th>Confirmar publicación en Vivo</th>
	    	</tr>
	    	<tr>
	    		<td>	 	
					<p class="block-message warning" style="width: 300px">
						<?php echo form_checkbox('authorize', '1'); ?> <?php echo lang('front:authorize_long') ?><br>
						<small>Autorizado por: <?php echo $this->current_user->username ?></small>
					</p>
					<?php if($update): ?>
						<button class="btn large orange" name="btnAction" value="save"><span> <i class="icon-retweet icon-white"></i> Actualizar publicación</span></button>
					<?php else: ?>
						<button class="btn large blue" name="btnAction" value="save"><span> <i class="icon-play icon-white"></i> Publicar en Vivo</span></button>
					<?php endif; ?>
				</td>
			</tr>
		</table>  
		<?php echo form_hidden('draft_id', $item->id); ?>
		<?php echo form_hidden('draft_prod_id', $item->prod_id); ?>		
		<?php echo form_hidden('prod_cat_id', $item->prod_cat_id); ?>				
		<?php echo form_hidden('type_id', $typeid); ?>			
		<?php echo form_close(); ?>		 										
	</div>
</section>
