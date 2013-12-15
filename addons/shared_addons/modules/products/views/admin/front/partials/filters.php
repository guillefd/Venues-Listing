<div class="one_half">
	<fieldset id="filters">
		<legend><?php echo lang('global:filters'); ?></legend>
		<?php $atr = array("id"=>"form_filter"); ?>
		<?php echo form_open('admin/products/ajax_filter',$atr); ?>
		<?php echo form_hidden('f_module', $module_details['slug']); ?>
			<ul>  
				<li>				
	             	<?php echo lang('products_search_key_label'); ?>                            
					<?php echo form_input('f_keywords','','placeholder="'.lang('products_search_key_placeholder').'"'); ?>    
					&nbsp;                  				
					<?php echo anchor(current_url() . '/', lang('buttons:cancel'), 'class="btn gray" id="btnCancel"'); ?>
				</li>
			</ul>
		<?php echo form_close(); ?>
	</fieldset>
</div>