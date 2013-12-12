<div class="one_full">
	<section class="title">
		<h4><?php echo lang('products_create_title'); ?></h4>
	</section>
	<section class="item">
		<div class="content">
			<table>
				<tr><th><?php echo lang('products_type_create_title'); ?></th><th><?php echo lang('products_description_label') ?></th><th></th></tr>
				<?php foreach($type_array as $type): ?>
					<tr>
						<td><?php echo $type->title; ?></td><td><?php echo $type->description; ?></td><td><?php echo anchor('admin/products/create/'.$type->id, lang('products_btn_create'),' class="btn blue"'); ?></td> 
					</tr>	
				<?php endforeach; ?>
			</table>
		</div>
	</section>
</div>