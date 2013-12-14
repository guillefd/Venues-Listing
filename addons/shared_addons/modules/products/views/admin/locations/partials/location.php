<section class="title">
    <h4><span class="titlePrev"><?php echo lang('location:label').': '.$location->name; ?></span>
        <span class="titlePrev"><?php echo lang('location:account_label').': '.$location->account; ?></span>
    </h4>            
</section>
<section class="item">
    <table class="blue">
        <tbody>

            <tr>
                <td><strong><?php echo lang('location:name_label')?></strong></td>
                <td><strong><?php echo lang('location:type_label')?></strong></td>
                <td><strong><?php echo lang('location:account_label')?></strong></td>        
            <tr>
            <tr>
                <td><?php echo $location->name; ?></td> 
                <td><?php echo $location->location_type; ?></td>                                                                                                 
                <td><?php echo $location->account; ?></td>  
            </tr>       
            <tr>  
                <td><strong><?php echo lang('location:address_label')?></strong></td>                                                        
                <td><strong><?php echo lang('location:area_label')?></strong></td>                                         
                <td colspan="2"><strong><?php echo lang('location:city_label')?></strong></td>                                         
              </tr>       
            <tr>
                <td><?php echo $location->address_l1.' '.$location->address_l2; ?></td>                     
                <td><?php echo $location->area; ?></td>  
                <td colspan="2"><?php echo $location->City; ?><? echo !empty($location->zipcode) ? ' ('.$location->zipcode.')' : '' ?></td>
            </tr>                         
            <tr>
                <td><strong><?php echo lang('location:phone_label')?></strong></td>
                <td><strong><?php echo lang('location:fax_label')?></strong></td>                                         
                <td><strong><?php echo lang('location:email_label')?></strong></td>                                         
            </tr>
            <tr>
                <td><? echo !empty($location->phone_area_code) ? '('.$location->phone_area_code.') ' : '' ?><?php echo $location->phone; ?></td>                                        
                <td><? echo !empty($location->phone_area_code) ? '('.$location->phone_area_code.') ' : '' ?><?php echo $location->fax; ?></td>  
                <td><?php echo $location->email; ?></td>                          
            </tr>
            <tr>
                <td colspan="3"><strong><?php echo lang('location:intro_label')?></strong></td>                                         
            </tr>
            <tr>
                <td colspan="3"><?php echo $location->intro; ?></td>  
            </tr>      
            <tr>
                <td colspan="3"><strong><?php echo lang('location:description_label')?></strong></td>                                         
            </tr>                          
            <tr>
                <td colspan="3"><?php echo $location->description; ?></td>  
            </tr>  
            <tr>
                <td colspan="3"><strong><?php echo lang('location:link_label')?></strong></td>                                                                           
            </tr>    
            <tr>
                <td colspan="3"><?php echo $location->slug; ?></td>                         
            </tr>                            
        </tbody>
    </table           
</section>
