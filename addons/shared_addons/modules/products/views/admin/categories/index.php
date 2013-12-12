<div class="one_full">
	<section class="title">
		<h4><?php echo lang('cat_list_title'); ?></h4>
	</section>
	<section class="item">
		<div class="content">
			<?php if ($categories): ?>
                <?php echo $this->load->view('admin/categories/partials/filters'); ?>  
                <div id="filter-stage">			
            		<?php echo $this->load->view('admin/categories/partials/categories'); ?>
                </div>              		
			<?php else: ?>
		<div class="no_data"><?php echo lang('cat_no_categories'); ?></div>
			<?php endif; ?>
		</div>
	</section>
</div>