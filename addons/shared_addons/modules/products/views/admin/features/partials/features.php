<table border="0" class="table-list" id="indexTable">
    <thead>
        <tr>
            <!-- <th width="20"><?php //echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th> -->
            <th><?php echo lang('features:name'); ?></th>
            <th><?php echo lang('features:cat_product'); ?></th>            
            <th><?php echo lang('features:category_label'); ?></th>
            <th><?php echo lang('features:usageunit'); ?></th>    
            <th><?php echo lang('features:description'); ?></th>                                                               
            <th width="150"></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="8">
                <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <?php foreach ($features as $feature): ?>
        <tr>
            <!--                    <td><?php //echo form_checkbox('action_to[]', $feature->id); ?></td> -->
            <td><?php echo $feature->name; ?></td>
            <td><?php echo $feature->cat_product; ?></td>            
            <td><?php echo $feature->cat_feature; ?></td>                                        
            <td><?php echo $feature->usageunit; ?></td>
            <td><small><?php echo $feature->description; ?></small></td>
            <td class="align-center buttons buttons-small" width="100px">
                <?php echo anchor('admin/products/features/edit/' . $feature->id, '<i class="icon-edit icon-white"></i>', 'class="btn orange edit "'); ?>
<!--                 <?php //echo anchor('admin/products/features/delete/' . $feature->id, '<i class="icon-trash icon-white"></i>', 'class="confirm red btn delete disabled"') ;?>
 -->            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
</table>
