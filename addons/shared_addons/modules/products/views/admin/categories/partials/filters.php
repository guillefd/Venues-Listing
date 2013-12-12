<fieldset id="filters">
	<legend><?php echo lang('global:filters'); ?></legend>
	<?php $atr = array("id"=>"form_filter"); ?>
	<?php echo form_open('admin/products/categories/ajax_filter',$atr); ?>
	<?php echo form_hidden('f_module', $module_details['slug']); ?>
		<ul>  
			<li>
				<?php echo lang('features:search_key_label'); ?>                            
				<?php echo form_input('f_keywords','','placeholder="'.lang('features:search_key_placeholder').'"'); ?> 
				&nbsp;&nbsp;	
				<?php echo form_dropdown('f_product_type',array(''=>'') + $type_array, '',' data-placeholder="'.lang('categories:f_select_product_type').'"id="f_product_type"') ?>
				&nbsp;&nbsp;							
				<?php echo anchor(current_url() . '/', lang('buttons:cancel'), 'class="btn gray" id="btnCancel"'); ?>
			</li>
		</ul>
	<?php echo form_close(); ?>
</fieldset>