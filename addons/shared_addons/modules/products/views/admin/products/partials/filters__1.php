<fieldset id="filters">
	<legend><?php echo lang('global:filters'); ?></legend>
	<?php $atr = array("id"=>"form_filter"); ?>
	<?php echo form_open('admin/products/ajax_filter/1',$atr); ?>
	<?php echo form_hidden('f_module', $module_details['slug']); ?>
		<ul>  
			<li>
             	<?php echo lang('products_search_key_label'); ?>                            
                <?php echo form_hidden('typeid','1'); ?>	
				<?php echo form_input('f_keywords','','placeholder="'.lang('products_search_key_placeholder').'"'); ?>
				&nbsp;&nbsp;
        		<?php echo lang('products_accountowner_label'); ?>
        		<?php echo form_input('f_account','',' class="medium" id="f_account" placeholder="'.lang('products_ajax_label').'"'); ?>
                <?php echo form_hidden('f_account_id'); ?>	
                &nbsp;&nbsp;
        		<?php echo lang('products_category_sh_label'); ?> 				
                <?php echo form_dropdown('f_category_id', array(''=>lang('products_all_label')) + $dd_array->type_with_cat_products_multiarray, '',' id="f_category_id" style="width: 200px;" data-placeholder="'.lang('products_no_category_select_label').'" ') ?>					
                &nbsp;&nbsp; 				
				<?php echo anchor(current_url() . '/', lang('buttons:cancel'), 'class="btn gray" id="btnCancel"'); ?>
			</li>
		</ul>
	<?php echo form_close(); ?>
</fieldset>