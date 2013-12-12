<fieldset id="filters">
	<legend><?php echo lang('global:filters'); ?></legend>
	<?php $atr = array("id"=>"form_filter"); ?>
	<?php echo form_open('admin/products/rooms/ajax_filter',$atr); ?>
	<?php echo form_hidden('f_module', $module_details['slug']); ?>
	<ul>  
		<li>
			<?php echo lang('location:search_key_label'); ?>                            
			<?php echo form_input('f_keywords','','placeholder="'.lang('location:search_key_placeholder').'"'); ?>
			<?php echo '&nbsp;'.lang('spaces:location'); ?>
			<?php echo form_input('f_location','','id="f_location" class="med" placeholder="'.lang('location:ajax_label').'"'); ?>
			<?php echo form_hidden('f_location_id'); ?>                            
			<?php echo '&nbsp;'.anchor(current_url() . '/', lang('buttons:cancel'), 'class="cancel" id="btnCancel"'); ?>
		</li>
	</ul>
	<?php echo form_close(); ?>
</fieldset>