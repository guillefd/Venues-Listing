<script>
	var TYPEID = "<?php echo $typeid; ?>";
	var STATEVIEW = "<?php echo $stateview; ?>";
</script>
<div class="one_full">
	<section class="title">
		<h4><?php echo lang('front:title'); ?></h4>
	</section>
	<section class="item">
		<div class="content">		
			<?php echo $this->load->view('admin/front/partials/stateview_buttons'); ?>
			<?php if ($items) : ?>
				<?php echo $this->load->view('admin/front/partials/filters'); ?>
				<div id="filter-stage">
					<?php echo $this->load->view('admin/front/tables/items'); ?>
				</div>
			<?php else : ?>
				<div class="one_full">
					<table class="table-list" border="0" cellspacing="0">
						<tbody>
							<tr>
								<td>
									<div class="no_data"><?php echo lang('front:currently_no_items'); ?></div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		</div>
	</section>
</div>