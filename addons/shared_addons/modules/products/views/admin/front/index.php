<div class="one_full">
	<section class="title">
		<h4><?php echo lang('front:title'); ?></h4>
	</section>
	<section class="item">
		<div class="content">		
			<?php if ($items) : ?>
			<?php echo $this->load->view('admin/front/partials/filters'); ?>
			<div id="filter-stage">
				<?php echo $this->load->view('admin/front/tables/items'); ?>
			</div>
			<?php else : ?>
				<div class="no_data"><?php echo lang('front:currently_no_items'); ?></div>
			<?php endif; ?>
		</div>
	</section>
</div>