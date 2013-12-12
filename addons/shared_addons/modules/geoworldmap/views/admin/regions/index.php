<div class="one_full">
	<section class="title">
		<h4><?php echo lang('geo_regions_list_title'); ?><?=$country->Country ?></h4>
	</section>
	<section class="item">
		<div class="content">	
			<?php if ($regions) : ?>
				<?php echo $this->load->view('admin/tables/regions'); ?>
			<?php else : ?>
				<div class="no_data"><?php echo lang('geo_currently_no_regions'); ?></div>
			<?php endif; ?>
		</div>	
	</section>
</div>