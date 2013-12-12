<section class="title">
    <h4><?php echo lang('spaces:space').': '.$space->denomination.' '.$space->name; ?>
        [ <?php echo $space->account; ?> ]
    </h4>            
</section>
<section class="item">
    <table class="blue">
        <tbody>
            <tr>
                <td><strong><?php echo lang('spaces:space')?></strong></td>
                <td><?php echo $space->denomination.' '.$space->name; ?></td>                                          
                <td><strong><?php echo lang('spaces:account')?></strong></td>                                         
                <td><?php echo $space->account; ?></td>  
                <td><strong><?php echo lang('spaces:location')?></strong></td>                                         
                <td><?php echo $space->location; ?></td>                                          
            </tr>       
            <tr>
                <td><strong><?php echo lang('spaces:address')?></strong></td>
                <td><?php echo $space->address; ?></td>                                        
                <td><strong><?php echo lang('spaces:area')?></strong></td>                                         
                <td><?php echo $space->area; ?></td>  
                <td><strong><?php echo lang('spaces:city')?></strong></td>                                         
                <td><?php echo $space->city; ?></td>  
            </tr>                                
            <tr>
                <td><strong><?php echo lang('spaces:dimensions')?></strong></td>
                <td><?php echo $space->width.' '.lang('spaces:mts').', '.$space->length.' '.lang('spaces:mts').', '.$space->height.' '.lang('spaces:mts') ?></td>                                        
                <td><strong><?php echo lang('spaces:square_mt')?></strong></td>                                         
                <td><? echo $space->square_mt ?></td>  
                <td><strong><?php echo lang('spaces:shape')?></strong></td>                                         
                <td><?php echo $space->shape; ?></td>  
            </tr>
            <tr>
                <td><strong><?php echo lang('spaces:layouts')?></strong></td>
                <td colspan="5"><?php echo $space->layouts_txt ?></td>    
            </tr>
            <tr>                                              
                <td colspan="6"><?php echo $space->facilities_txt ?></td>
            </tr>
            <tr>                                              
                <td colspan="6"><?php echo $space->usetypes_txt ?></td>
            </tr>            
            <tr>
                <td><strong><?php echo lang('spaces:description')?></strong></td>                                         
                <td colspan="5"><?php echo $space->description; ?></td>  
            </tr>
        </tbody>
    </table>            
</section>
