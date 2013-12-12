            <table border="0" class="table-list" id="indexTable">
                <thead>
                <tr>
                    <th><?php echo lang('cat_category_label'); ?></th>                    
                    <th><?php echo lang('cat_type_label'); ?></th>
                    <th width="250px"><?php echo lang('cat_slug_label'); ?></th>                    
                    <th><?php echo lang('cat_description_label'); ?></th>                                
                    <th width="50"></th>
                </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo $category->title; ?></td>                        
                        <td><?php echo $type_array[$category->type_id]; ?></td>
                        <td><?php echo $category->slug; ?></td>
                        <td><small><?php echo $category->description; ?></small></td>                                        
                        <td class="align-center buttons buttons-small">
                            <?php echo anchor('admin/products/categories/edit/' . $category->id, '<i class="icon-edit icon-white"></i>', 'class="btn orange edit"'); ?>
<!--                        <?php //echo anchor('admin/products/categories/delete/' . $category->id, lang('global:delete'), 'class="confirm red btn delete disabled"') ;?>
 -->                    </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
