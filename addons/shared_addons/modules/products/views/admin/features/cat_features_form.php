<div style="height:450px">
<section class="title">
    <h4><?php echo lang('features:add_cat_feature').': '; ?>
    </h4>            
</section>
<section class="item">
    <div class="content">
        <?php echo form_open( uri_string(), ' id="cat_feature"'); ?>
        <!-- Info tab -->
        <div class="form_inputs">		
            <fieldset>	
                <ul>                               
                    <li>
                        <label for="name"><?php echo lang('features:cat_name'); ?> <span>*</span></label>
                        <div class="input"><?php echo form_input('name', $category->name) ?></div>
                    </li>           
                    <li>
                        <label for="description"><?php echo lang('features:cat_description'); ?> <span>*</span></label>
                        <div class="input"><?php echo form_textarea('description', $category->description,'class="med"') ?></div>
                    </li>                                                    
                </ul>		
            </fieldset>		
        </div>
        <div class="buttons">
            <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel'))); ?>
        </div>
        <?php echo form_close(); ?>  
    </div>                                
</section>
</div>