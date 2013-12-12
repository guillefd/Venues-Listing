<fieldset id="filters">
	<legend><?php echo lang('global:filters'); ?></legend>
	<?php $atr = array("id"=>"form_filter"); ?>
	<?php echo form_open('admin/products/features/ajax_filter',$atr); ?>
	<?php echo form_hidden('f_module', $module_details['slug']); ?>
		<ul>  
			<li>
				<?php echo lang('features:search_key_label'); ?>                            
				<?php echo form_input('f_keywords','','placeholder="'.lang('features:search_key_placeholder').'"'); ?> 
				&nbsp;&nbsp;
				<?php echo form_dropdown('f_cat_product_id',array(''=>'') + $dd->cat_products_multiarray, '',' data-placeholder="'.lang('features:f_select_cat_product').'"id="f_cat_product_id"') ?>
				&nbsp;&nbsp;
				<?php echo form_dropdown('f_cat_feature_id',array(''=>'') + $dd->cat_features_array, '',' data-placeholder="'.lang('features:f_select_cat_feature').'"id="f_cat_feature_id" style="width:275px"') ?>
				&nbsp;&nbsp;
				<?php echo anchor(current_url() . '/', lang('buttons:cancel'), 'class="btn gray" id="btnCancel"'); ?>
			</li>
		</ul>
	<?php echo form_close(); ?>
</fieldset>