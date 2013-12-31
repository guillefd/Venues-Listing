<!-- capacity -->				
		<form id="homefilterform" action="" method="post" name="homefilterform">
<!-- usetype / category -->								
			<div class="row filter">
				<div class="col-xs-12 col-sm-12 col-md-2">
					<h6><i class="fa fa-folder-open"></i> &nbsp;CATEGORIA <a id="btnusetypesinfo" data-toggle="popover" title="" data-original-title="Categorías de los espacios segun el uso"><i class="fa fa-question-circle"></i></a></h6>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-10">
					<div class="btn-group color" data-toggle="buttons">
					<?php for($i=0; $i<=6; $i++): ?>
						<label id="category-filter-<?php echo $i; ?>" class="btn unique category-filter">
							<input type="radio" value="<?php echo $usetypes_txt[$i]->slug; ?>"><?php echo $usetypes_txt[$i]->name; ?>
						</label>									
					<?php if($i==3) echo '<br>'; ?>
					<?php endfor; ?>
						<label class="btn select">
							<?php echo form_dropdown('select_category', $usetypes_select, '', ' id="select_category" multiple style="width:130px;" ') ?>														
						</label>
						<label id="category-filter-7" class="btn all btn-sm unique category-filter">
							<input type="radio" value=""><span class="glyphicon glyphicon-remove"></span>
						</label>										
					</div>																		
				</div>		
			</div>										
			<div class="row filter">
				<div class="col-xs-12 col-sm-12 col-md-2">
					<h6><i class="fa fa-users"></i> CAPACIDAD <a data-toggle="tooltip" title="Rango estimado de capacidad requerida en el espacio"><i class="fa fa-question-circle"></i></a></h6>			
				</div>
				<div class="col-xs-12 col-sm-12 col-md-10">
					<div class="btn-group color" data-toggle="buttons">
						<label id="cap-filter-0" class="btn unique cap-filter">
							<input type="radio" value="1-5"> 1 a 5
						</label>	
						<label id="cap-filter-1" class="btn unique cap-filter">
							<input type="radio" value="5-10"> 5 a 10
						</label>	
						<label id="cap-filter-2" class="btn unique cap-filter">
							<input type="radio" value="10-20"> 10 a 20
						</label>	
						<label id="cap-filter-3" class="btn unique cap-filter">
							<input type="radio" value="20-30"> 20 a 30
						</label>	
						<label id="cap-filter-4" class="btn unique cap-filter">
							<input type="radio" value="30-50"> 30 a 50
						</label>	
						<label id="cap-filter-5" class="btn unique cap-filter">
							<input type="radio" value="50plus"> 50 ó mas
						</label>
						<label id="cap-filter-6" class="btn all btn-sm unique cap-filter">
							<input type="radio" value=""><span class="glyphicon glyphicon-remove"></span>
						</label>							
					</div>																		
				</div>		
			</div>	
<!-- Locationtype -->										
			<div class="row filter">
				<div class="col-xs-12 col-sm-12 col-md-2">
					<h6><i class="fa fa-home"></i> LOCACION <a id="btnlocationtypesinfo" data-toggle="popover" title="" data-original-title="Tipos de Locación"><i class="fa fa-question-circle"></i></a></h6>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-10">				
					<div class="btn-group color" data-toggle="buttons">
						<label id="loc-filter-1" class="btn multiple loc-filter lg">
							<input type="checkbox" value="1">Centro de<br>salas
						</label>									
						<label id="loc-filter-2" class="btn multiple loc-filter">
							<input type="checkbox" value="2">Hotel
						</label>									
						<label id="loc-filter-3" class="btn multiple loc-filter">
							<input type="checkbox" value="3">Co-working
						</label>									
						<label id="loc-filter-4" class="btn multiple loc-filter">
							<input type="checkbox" value="4">Restaurant
						</label>									
						<label id="loc-filter-5" class="btn multiple loc-filter lg">
							<input type="checkbox" value="5">Instituto<br>educativo
						</label>									
					</div>																								
				</div>		
			</div>			
	
<!-- advanced filter -->	
			<div class="row filter last">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<input type="button" id="btnfiltersearch" class="btn btn-primary btn-bg" value="BUSCAR">
					<!-- advanced filter -->
				</div>
			</div>						
		</form>	