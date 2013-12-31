<div id="indexView">
	<table>
		<thead>
			<tr>
<!-- 			<th width="20"><?php //echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th> -->
				<th><?php echo lang('products_name_label'); ?></th>
				<th><?php echo lang('products_category_label'); ?></th>
				<th><?php echo lang('products_publicationtype_label'); ?></th>				
				<th><?php echo lang('products_accountowner_label'); ?></th>
				<th><?php echo lang('products_outsourced_label'); ?></th>
				<th width="150"></th>								
				<th><small><?php echo lang('products_actions_label'); ?></small></th>
				<th><small><?php echo lang('products_status_sh_label'); ?></small></th>				
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8">
					<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
				</td>
			</tr>
			<tr>	
				<td colspan="8"><small class="muted"><?php echo 'Total: '.$total_rows; ?></small></td>
			</tr>			
		</tfoot>
		<tbody>
			<?php foreach ($products as $product): ?>
				<tr <?php if($product->deleted==1) echo 'class="deleted";'; ?> >
<!-- 					<td><?php //echo form_checkbox('action_to[]', $product->product_id); ?></td> -->
					<td>
						<?php echo $product->name; ?><br>
						<small class="muted"><?php echo $product->location; ?></small><span> - </span> 
						<small class="muted"><?php echo $product->space; ?></small><br>						
					</td>
					<td><?php echo $product->category; ?></td>
					<td><?php echo strtoupper($product->publication_type); ?></td>
					<td><?php echo $product->account; ?></td>
					<td><?php echo $product->outsourced == 1 ? $product->seller_account : $product->account; ?></td>
					<td>
						<?php echo anchor('admin/products/view/'.$product->typeid.'/'.$product->product_id, '<i class="icon-eye-open"></i>', 'class="modal btn gray" title="'.lang('global:view').'"'); ?>
						<?php echo anchor('admin/products/edit/'.$product->typeid.'/'.$product->product_id, '<i class="icon-edit"></i>', 'class="btn gray edit" title="'.lang('global:edit').'"'); ?>
						<?php echo anchor('admin/products/delete/'.$product->typeid.'/'.$product->product_id, '<i class="icon-trash"></i>', 'class="confirm btn gray delete" title="'.lang('global:delete').'"'); ?>
					</td>
					<td>
						<?php echo anchor('admin/products/publishdraft/'.$product->typeid.'/'.$product->product_id, '<i class="icon-share icon-white"></i>', $product->btn_publish_draft.' title="Solicitar publicación en vivo"'); ?>				
						<?php echo anchor('admin/products/publishdraft/'.$product->typeid.'/'.$product->product_id, '<i class="icon-retweet icon-white"></i>', $product->btn_update_draft.' title="Actualizar Publicación"'); ?>				
						<?php echo anchor('admin/products/deletedraft/'.$product->typeid.'/'.$product->product_id, '<i class="icon-remove icon-white"></i>', $product->btn_delete_draft.' title="Eliminar Publicación"'); ?>				
					</td>
					<td>
						<?php echo '<span class="'.$product->txt_validation_draft.'">'.$product->icon_validation_draft.' <small>Validación</small></span><br>'; ?>
						<?php echo '<span class="'.$product->txt_publish_draft.'">'.$product->icon_publish_draft.' <small>En vivo</small></span><br>'; ?>
						<?php echo '<span class="'.$product->txt_update_draft.'">'.$product->icon_update_draft.' <small>Actualizar</small></span><br>'; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<!-- 	<div class="table_action_buttons">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete', 'publish'))); ?>
	</div> -->
</div>

