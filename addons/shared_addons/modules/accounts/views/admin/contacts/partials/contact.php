        <section class="title">
            <h4><?php echo lang('accounts:contact') ?> </h4>            
        </section>
        <section class="item">
        <table class="blue">
			<tbody>
				<tr>
					<td><strong><?php echo lang('accounts:name') ?></strong></td>   
					<td><strong><?php echo lang('accounts:surname') ?></strong></td>   
					<td><strong><?php echo lang('accounts:account') ?></strong></td>   										
				</tr>
				<tr>
					<td><?php echo $contact->name; ?></td>   
					<td><?php echo $contact->surname; ?></td>   
					<td><?php echo $contact->account; ?></td>   										
				</tr>				
	            <tr>
					<td><strong><?php echo lang('accounts:title')?></strong></td>                       
					<td><strong><?php echo lang('accounts:section')?></strong></td>                                         
					<td><strong><?php echo lang('accounts:position')?></strong></td>                                         
	            </tr>  
	            <tr>
					<td><?php echo $contact->title; ?></td>                                                                                 
					<td><?php echo $contact->section; ?></td>                                         
					<td><?php echo $contact->position; ?></td>  
	            </tr>	                               
	            <tr>
					<td><strong><?php echo lang('accounts:address_l1')?></strong></td>                                       
					<td><strong><?php echo lang('accounts:area')?></strong></td>                                         
					<td><strong><?php echo lang('accounts:city')?></strong></td>                                          
	            </tr>     
	            <tr>
					<td><?php echo $contact->address_l1.' '.$contact->address_l2; ?></td>                                                                               
					<td><?php echo $contact->area; ?></td>                                       
					<td><?php echo $contact->City; ?><? echo !empty($contact->zipcode) ? ' ('.$contact->zipcode.')' : '' ?></td>  
	            </tr> 	                                       
				<tr>
					<td><strong><?php echo lang('accounts:phone')?></strong></td>                                       
					<td><strong><?php echo lang('accounts:fax')?></strong></td>                                          
					<td><strong><?php echo lang('accounts:email')?></strong></td>                                         
	            </tr>  
				<tr>
					<td><? echo !empty($contact->phone_area_code) ? '('.$contact->phone_area_code.') ' : '' ?><?php echo $contact->phone; ?></td>                                                                                
					<td><? echo !empty($contact->phone_area_code) ? '('.$contact->phone_area_code.') ' : '' ?><?php echo $contact->fax; ?></td>                                          
					<td><?php echo $contact->email; ?></td>  
	            </tr> 	                                           
			</tbody>
		</table>            
</section>

