<div id="indexView">
	<table class="table-list" border="0" cellspacing="0">
		<thead>
			<tr>
				<th><?php echo lang('front:name'); ?></th>
				<th><?php echo lang('front:product_category_slug'); ?></th>
				<th><?php echo lang('front:account'); ?></th>
				<th><?php echo lang('front:publicationtype'); ?></th>
				<th><?php echo lang('front:current_version'); ?></th>
				<th width="100"><small>Online</small></th>				
				<th><small><?php echo lang('front:status'); ?></small></th>				
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8">
					<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
				</td>
			</tr>
			<tr>	
				<td colspan="8"><small class="muted"><?php echo lang('front:items').': '.$total_rows; ?></small></td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($items as $item) : ?>
				<tr>
					<td>
						<?php echo $item->prod_name; ?>
						<?php if(isset($item->draft_loc_name)) echo '<br><small class="muted">'.$item->draft_loc_name.'</small>'; ?>
						<?php if(isset($item->draft_space_name)) echo '<small class="muted">- '.$item->draft_space_denomination.' '.$item->draft_space_name.'</small>'; ?>						
					</td>
					<td><?php echo $item->draft_prod_cat_slug; ?></td>
					<td><?php echo $item->account; ?></td>
					<td><?php echo $item->publicationtype; ?></td>
					<td><?php echo $item->current_version; ?></td>
					<td width="150px">
						<?php echo anchor('admin/products/front/viewdraft/'.$item->draft_prod_cat_id.'/'.$item->draft_id, '<i class="icon-play icon-white"></i>', $item->btn_front_golive.' title="Poner online"'); ?>						
						<?php echo anchor('admin/products/front/viewdraft/'.$item->draft_prod_cat_id.'/'.$item->draft_id, '<i class="icon-retweet icon-white"></i>', $item->btn_front_update.' title="Actualizar"'); ?>	
						<?php echo anchor('admin/products/front/gooffline/'.$item->draft_prod_cat_id.'/'.$item->front_id, '<i class="icon-stop icon-white"></i>', $item->btn_front_gooffline.' title="Poner offline"'); ?>
					</td>
					<td>
						<?php echo '<span class="'.$item->txt_offline.'">'.$item->icon_offline.' <small>Offline</small></span><br>'; ?>				
						<?php echo '<span class="'.$item->txt_online.'">'.$item->icon_online.' <small>En vivo</small></span><br>'; ?>
						<?php echo '<span class="'.$item->txt_update.'">'.$item->icon_update.' <small>Actualizar</small></span><br>'; ?>
					</td>					
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

