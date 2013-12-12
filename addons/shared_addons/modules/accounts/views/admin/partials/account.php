        <section class="title">
            <h4><?php echo lang('accounts:account').': '.$account->name; ?></h4>               
        </section>
        <section class="item">
	        <table class="blue">
				<tbody>	        	
	        		<tr>
	        			<td><strong><?php echo lang('accounts:account'); ?></strong></td>
	        			<td><strong><?php echo lang('accounts:account_type') ?></strong></td>
            			<td><strong><?php echo lang('accounts:industry') ?></strong></td>
	        		</tr>
	        		<tr>
	        			<td><?php echo $account->name; ?></td>
	        			<td><?php echo $account->account_type; ?></td>
	        			<td><?php echo $account->industry; ?></td>
	        		</tr>
	                <tr>
						<td><strong><?php echo lang('accounts:address_l1')?></strong></td>                                     
						<td><strong><?php echo lang('accounts:area')?></strong></td>                                         
						<td><strong><?php echo lang('accounts:city')?></strong></td>                                         
	                </tr>
	                <tr>
						<td><?php echo $account->address_l1.' '.$account->address_l2; ?></td>
						<td><?php echo $account->area; ?></td>  
						<td><?php echo $account->City; ?><? echo !empty($account->zipcode) ? ' ('.$account->zipcode.')' : '' ?></td>  						  	                	
	                </tr>                                
					<tr>
						<td><strong><?php echo lang('accounts:phone')?></strong></td>
						<td><strong><?php echo lang('accounts:fax')?></strong></td>                                         
						<td><strong><?php echo lang('accounts:email')?></strong></td>                                         
	                </tr>
	                <tr>
						<td><? echo !empty($account->phone_area_code) ? '('.$account->phone_area_code.') ' : '' ?><?php echo $account->phone; ?></td>                                        
						<td><? echo !empty($account->phone_area_code) ? '('.$account->phone_area_code.') ' : '' ?><?php echo $account->fax; ?></td>  
						<td><?php echo $account->email; ?></td>  	                	
	                </tr>
	                <tr>
	                    <td colspan="3" class="subtitle"><div><? echo lang('accounts:fiscal'); ?></div></td>
	                </tr>
					<tr>
						<td><strong><?php echo lang('accounts:razon_social')?></strong></td>
						<td><strong><?php echo lang('accounts:cuit_label')?></strong></td>                                         
						<td><strong><?php echo lang('accounts:iva')?></strong></td>                                         
	                </tr> 
	                <tr>
						<td><?php echo $account->razon_social; ?></td>                                        
						<td><?php echo $account->cuit; ?></td>   
						<td><?php echo $account->iva; ?></td>  	                	
	                </tr>
	                <tr>
	                    <td colspan="3"><div><? echo lang('accounts:pago_proveedores'); ?></div></td>
	                </tr> 
					<tr>
						<td><strong><?php echo lang('accounts:pago_proveedores_mail')?></strong></td>                                   
						<td><strong><?php echo lang('accounts:pago_proveedores_tel')?></strong></td>                                         
						<td><strong><?php echo lang('accounts:pago_proveedores_horario')?></strong></td>                                         
	                </tr>
					<tr>
						<td><?php echo $account->pago_proveedores_mail; ?></td>                                                                                
						<td><?php echo $account->pago_proveedores_tel; ?></td>                                           
						<td><?php echo $account->pago_proveedores_dias_horarios; ?></td>  
	                </tr>	                
	                <tr>
						<td><strong><?php echo lang('accounts:pago_proveedores_detalle')?></strong></td>                                         
						<td colspan="2"><?php echo $account->pago_proveedores_detalle; ?></td>  
	                </tr>                                
	                <tr>
	                    <td colspan="3"><div><? echo lang('accounts:cuentas_por_cobrar'); ?></div></td>
	                </tr>                                 
					<tr>
						<td><strong><?php echo lang('accounts:cuentas_por_cobrar_mail')?></strong></td>                                    
						<td><strong><?php echo lang('accounts:cuentas_por_cobrar_tel')?></strong></td>                                         
						<td><strong><?php echo lang('accounts:cuentas_por_cobrar_detalle')?></strong></td>                                         
	                </tr>  
					<tr>
						<td><?php echo $account->cuentas_por_cobrar_mail; ?></td>                                                                                 
						<td><?php echo $account->cuentas_por_cobrar_tel; ?></td>                                            
						<td><?php echo $account->cuentas_por_cobrar_detalle; ?></td>  
	                </tr>	                                                
				</tbody>
			</table>            
		</section>



