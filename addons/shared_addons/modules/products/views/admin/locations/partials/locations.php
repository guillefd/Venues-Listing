<table border="0" class="table-list" id="indexTable">
    <thead>
        <tr>
<!--        <th width="20"><?php //echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th> -->
            <th><?php echo lang('location:label'); ?></th>
            <th><?php echo lang('location:type_label'); ?></th>
            <th><?php echo lang('location:intro_label'); ?></th>
            <th><?php echo lang('location:slugSH_label'); ?></th>
            <th><?php echo lang('location:account_label'); ?></th>    
            <th><?php echo lang('location:city_label'); ?></th>                                
            <th><?php echo lang('location:phone_label'); ?></th>                                
            <th width="150"></th>
        </tr>
    </thead>
    <tfoot>      
        <tr>
            <td colspan="8">
                <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
            </td>
        </tr>
            <tr>    
                <td colspan="8"><small class="muted"><?php echo 'Total: '.$total_rows; ?></small></td>
            </tr>        
    </tfoot>
    <tbody>
        <?php foreach ($locations as $location): ?>
        <tr>
<!--             <td><?php //echo form_checkbox('action_to[]', $location->id); ?></td> -->
            <td><?php echo $location->name; ?></td>
            <td><?php echo $location->location_type; ?></td>            
            <td><?php echo $location->intro; ?>...</td>                                        
            <td><?php echo $location->location_slug; ?></td>
            <td><?php echo $location->account; ?></td>
            <td><?php echo $location->City; ?></td>
            <td><?php echo $location->phone; ?></td>
            <td class="align-center buttons buttons-small" width="140px">
                <?php echo anchor('admin/products/locations/preview/' . $location->id, '<i class="icon-eye-open icon-white"></i>', 'rel="modal" class="btn green" target="_blank" title="'.lang('global:view').'"'); ?>
                <?php echo anchor('admin/products/locations/edit/' . $location->id, '<i class="icon-edit icon-white"></i>', 'class="btn orange edit" title="'.lang('global:edit').'"'); ?>
                <?php echo anchor('admin/products/locations/delete/' . $location->id, '<i class="icon-trash icon-white"></i>', 'class="confirm red btn delete" title="'.lang('global:delete').'"') ;?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
</table>
