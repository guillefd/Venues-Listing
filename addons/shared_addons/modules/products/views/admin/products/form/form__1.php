<!-- JAVASCRIPT GLOBAL VARS -->
<script>
    var MSG_QUERY_FEATURES_FAIL = "<?php echo lang('products_features_fail') ?>";
    var MSG_QUERY_EMPTY = "<?php echo lang('products_empty_query_fail') ?>";
    var MSG_SELECT = "<?php echo lang('products_select') ?>";
    var MSG_ADD_ITEM_ERROR = "<?php echo lang('products_add_feature_empty') ?>";
    var MSG_ALERT_CATEGORY_CHANGE = "<?php echo lang('products_change_category_feature_alert') ?>";
    var LABEL_DELETE = "<?php echo lang('products_delete') ?>";    
    var LABEL_EDIT = "<?php echo lang('products_edit') ?>";
    var LABEL_SAVE = "<?php echo lang('products_save') ?>";  
	// Select location and space fix (for repopulate)
	var SPACE_USETYPEID = "<?php echo $product->space_usetype_id; ?>";
	var SPACEID = "<?php echo $product->space_id; ?>";
	var LOCATIONID = "<?php echo $product->location_id; ?>";
    var CHK_SELLER_ACCOUNT = "<?php echo $product->chk_seller_account; ?>";
    var SPACES_USETYPE_ARRAY = <?php echo json_encode($dd_array->spaces_usetype_array); ?>;	          
</script>
<!-- END JAVASCRIPT GLOBAL VARS -->

<section class="title">
	<h4>	
	<?php if ($this->method == 'create'): ?>  
	<?php echo lang('products_create_title'); ?>
	<?php else: ?>
	<?php echo sprintf(lang('products_edit_title'), $product->name); ?>
	<?php endif; ?>
	</h4>
</section>
<section class="item">
	<div class="content">
		<h3><?php echo lang('products_type_label').': '.$product_type_name ?></h3>
		<div class="tabs">
			<ul class="tab-menu">
				<li><a href="#products-setup-tab"><span><?php echo lang('products_setup_label'); ?></span></a></li>
				<li><a href="#products-spaceselect-tab"><span><?php echo lang('products_spaceselect_label'); ?></span></a></li>
				<li><a href="#products-features-tab"><span><?php echo lang('products_features_label'); ?></span></a></li>                
				<li><a href="#products-images-tab"><span><?php echo lang('products_images_label'); ?></span></a></li>        
			</ul>

			<?php echo form_open(uri_string(), 'class="crud" id="dzmainform" '); ?>
			<!-- Info tab -->
			<div class="form_inputs" id="products-setup-tab">		
				<fieldset>	
				<ul> 		                                                                                  
					<li class="even" style="height:120px">
						<div class="one_half">
							<label for="account_id"><?php echo lang('products_account_label').'<span> *</span><br><sub>'.lang('products_account_autoload_label').'</sub>' ?></label>
							<div class="input">
			                    <?php echo form_input('account', htmlspecialchars_decode($product->account), ' placeholder="'.lang('products_Ajax').'" id="accountAjax"'); ?>
			                    <?php echo  form_hidden('account_id', $product->account_id , 'id="account_id" '); ?>  
			                </div>
			            </div>
			            <div class="one_half">
			                <label for="status"><?php echo lang('products_chk_seller_account_label');?></label>
			                <div class="checker"><span class><?php echo form_checkbox('chk_seller_account', 1, $product->chk_seller_account == 1, ' id="product_chk_seller_account" '); ?></span><?php echo lang('products_chk_seller_account_txt_label'); ?></div>	
							<label for="seller_account"><?php echo lang('products_seller_account_label').'<span></span><br><sub>'.lang('products_seller_account_autoload_label').'</sub>' ?></label>
							<div class="input">
			                    <?php echo form_input('seller_account', htmlspecialchars_decode($product->seller_account), ' placeholder="'.lang('products_Ajax').'" id="seller_accountAjax"'); ?>
			                    <?php echo  form_hidden('seller_account_id', $product->seller_account_id , 'id="seller_account_id" '); ?>  
			                </div>	
			            </div>
					</li> 					                                                                                  
					<li class="even">
						<label for="name"><?php echo lang('products_name_label'); ?> <span>*</span></label>
						<div class="input"><?php echo form_input('name', htmlspecialchars_decode($product->name), 'class="med" maxlength="200" id="name"'); ?></div>				
					</li>					
					<li class="even">
						<label for="category_id"><?php echo lang('products_category_createform_label'); ?> <span> *</span></label>
						<div class="input">
		                 <?php echo form_dropdown('category_id', array(''=>'') + $dd_categories, $product->category_id,' data-placeholder="'.lang('products_no_category_select_label').'" id="category_id" ') ?>					
						</div>
					</li>															                    			                     
				</ul>		
				</fieldset>		
			</div>

			<!-- Infos tab -->
			<div class="form_inputs" id="products-spaceselect-tab">
		        <fieldset>	
		            <ul> 
						<li class="even">                			
							<label for="location_id"><?php echo lang('products_location_label'); ?> <span> *</span></label>				
							<div class="input">
								<?php echo form_dropdown('location_id', array(), '','class="med" data-placeholder="'.lang('products_no_locations_select_label').'"') ?>					                
			                </div>
						</li> 			                                                                                  
						<li class="even">                 
							<label for="space_id"><?php echo lang('products_space_label'); ?> <span> *</span></label>                                                   	                
			                <div class="input">                                    
								<?php echo form_dropdown('space_id', array(), '','class="med" data-placeholder="'.lang('products_no_spaces_select_label').'"') ?>					                
			                </div>				                                
						</li>
					</ul>		
				</fieldset>	                        
		    </div>  


			<!-- Infos tab -->
			<div class="form_inputs" id="products-features-tab">
		        <fieldset class="basicTargetFields">	
		            <ul>  
		            <li>
		                <p class="block-warning">
		                	<span class><?php echo form_checkbox('chk_basic_publication', 1, $product->chk_basic_publication == 1, ' id="chk_basic_publication" '); ?></span><?php echo lang('products_chk_basic_publication_txt_label'); ?>	
		                </p>		            	
		            </li>	
					<li class="even"> 
						<label for="space_usetype_id"><?php echo lang('products_space_usetype_label'); ?> <span> *</span></label>  				 		                                                                	                
		                <div class="input">                                    
							<?php echo form_dropdown('space_usetype_id', array(), '',' id="space_usetype_id" class="med" data-placeholder="'.lang('products_no_space_selected_label').'"') ?>					                
		                </div>			                		                                
					</li>		            		            						
					<li>
						<label for="intro"><?php echo lang('products_intro_label'); ?> <span></span></label>
						<div class="input">
							<br style="clear: both;" />
							<?php echo form_textarea(array('id' => 'intro', 'name' => 'intro', 'value' => $product->intro, 'rows' => 3, 'class'=>'med')); ?>
						</div>
					</li>			
					<li class="even editor">
						<label for="body"><?php echo lang('products_content_label'); ?> <span>*</span></label>				
						<div class="input">
							<br style="clear: both;" />
							<?php echo form_textarea(array('id' => 'body', 'name' => 'body', 'value' => $product->body, 'rows' => 6)); ?>    
						</div>
					</li> 					
		            <li class="even">
						<label for="features"><?php echo lang('products_features_label'); ?> <span>*</span></label>
		                    <table class="f_table"><tr>
		                    <td>
			                    &nbsp;&nbsp;
			                    <?php echo form_dropdown('dd_features', array(),'',' id="dd_features" class="med" data-placeholder="'.lang('products_no_features_select_label').'"') ?>					
			                    <?php echo form_hidden('f_id','',' id = "f_id"'); ?>  
			                    <?php echo form_input('usageunit','',' placeholder="'.lang('products_usageunit').'" class="f_small" id="usageunit" disabled'); ?>    
			                    <?php echo form_input('f_qty','',' placeholder="'.lang('products_qty').'" class="tiny" id="f_qty"'); ?>                      
		                    </td>
		                    <td>
		                    	<?php echo form_textarea(array('id' => 'f_description', 'name' => 'f_description', 'class' => 'f_tiny', 'placeholder' =>lang('products_f_description') )); ?>	
		                    </td>                        
		                    <td>
		                    	<?php echo form_dropdown('dd_isOptional', array(''=>'') + $dd_array->dd_yes_no,'','id="dd_isOptional" class="small" data-placeholder="'.lang('products_no_features_isOptional_label').'"') ?>	                    
		                    	<?php echo anchor('', lang('products_add'),'id="f_add" class="btn gray"'); ?> 
		                    </td></tr></table>
		                    <span><?php echo lang('products_features_list'); ?></span>
		                    <!--<div id="f_itemBox" class="f_itemBox"></div>-->
		                    <table class="tableBox max">
		                    	<tr><th>Nombre</th><th>Magnitud</th><th>Cantidad</th><th>Descripcion</th><th>Opcional</th><th width="75px">Eliminar</th></tr>
		                    	<tbody id="f_itemBox"></tbody>
		                    </table>   
		                    <?php echo form_hidden('features',$product->features_json,' id="features"'); ?> 
					</li>                                                                      
				</ul>		
				</fieldset>	                        
		        </div>  
		        
				<!-- Drop zone DATA FIELD -->
				<?php echo form_hidden('dzfileslistid', $product->dzfileslistid); ?>
			<?php echo form_close(); ?>

			<!-- Info images -->
			        <div class="form_inputs" id="products-images-tab">
			            <fieldset>
				            <?php echo $dzForm; //Dropzone form loader ?>  	
			            </fieldset>	
			        </div>        
		</div>
		<div class="buttons float-right padding-top">
			<button id="dzmainsubmit" class="btn blue" name="btnAction" value="save"><span><?php echo lang('products_save') ?></span></button>
			<a href="<?php echo base_url().'admin/products' ?>" class="btn gray cancel"><?php echo lang('products_cancel') ?></a>
		</div>
	</div>
</section>

