<section class="title">
	<h4><?php echo lang('product_view_title').' '.$product->name; ?></h4>
</section>
<section class="item">
	<table>
		<thead>
			<tr>
				<th width="250px"><?php echo lang('product_detail_label') ?></th>
				<th></th>
			</tr>	
		</thead>
		<tbody>
			<tr>				
				<td><?php echo lang('products_category_label') ?>: </td>
				<td><?php echo $product->category ?></td>
			</tr>				
			<tr>
				<td><?php echo lang('products_name_label') ?>: </td>
				<td><?php echo $product->name ?></td>
			</tr>				
			<tr>
				<td><?php echo lang('products_slug_label') ?>: </td>
				<td><?php echo $product->slug ?></td>
			</tr>				
			<tr>				
				<td><?php echo lang('products_account_label') ?>: </td>
				<td><?php echo $product->account ?></td>
			</tr>				
			<tr>				
				<td><?php echo lang('products_chk_seller_account_label') ?>: </td>
				<td><?php echo $product->outsourced_txt ?></td>
			</tr>				
			<tr>				
				<td><?php echo lang('products_seller_account_label') ?>: </td>
				<td><?php echo $product->seller_account ?></td>
			</tr>				
			<tr>				
				<td><?php echo lang('products_location_label') ?>: </td>
				<td><?php echo $product->location ?></td>
			</tr>				
			<tr>				
				<td><?php echo lang('products_space_label') ?>: </td>
				<td><?php echo $product->space ?></td>
			</tr>				
			<tr>				
				<td><?php echo lang('products_status_label') ?>: </td>
				<td><?php echo $product->active ?></td>
			</tr>				
			<tr>				
				<td><?php echo lang('products_intro_label') ?>: </td>
				<td><?php echo $product->intro ?></td>
			</tr>				
			<tr>				
				<td><?php echo lang('products_content_label') ?>: </td>
				<td><?php echo $product->body ?></td>
			</tr>		
		</tbody>
	</table>
    <table class="tableBox max">
    	<th colspan="5"><?php echo lang('products_features_label') ?></th>
    	<tr><th>Nombre</th><th>Magnitud</th><th>Cantidad</th><th>Descripcion</th><th>Opcional</th></tr>
    	<tbody>
    	<?php foreach($product->features_array as $f): ?>	
    	<tr>
    		<td><?php echo $f->name ?></td>
    		<td><?php echo $f->usageunit ?> </td>
    		<td><?php echo $f->value ?></td>
    		<td><?php echo $f->description ?></td>
    		<td><?php echo $f->is_optional ?></td>
    	</tr>
    	<?php endforeach; ?>
    	</tbody>
    </table> 	
</section>
