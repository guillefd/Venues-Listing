					<button type="button" class="btn btn-default btn-sm" data-toggle="collapse" data-target="#advanced-filter"><span class="glyphicon glyphicon-tasks"></span> MAS FILTROS</button>						
					<div id="advanced-filter" class="collapse out">
			<!-- layout -->										
						<div class="row filter">
							<div class="col-md-2">
								<h6><span class="glyphicon glyphicon-cog"></span> ARMADO <a id="btnlayoutsinfo" data-toggle="popover" title="" data-original-title="Tipos de armados posibles en las salas"><i class="fa fa-question-circle"></i></a></h6>
							</div>
							<div class="col-md-10">									
								<div class="btn-group color" data-toggle="buttons">
									<label id="layout-filter-2" class="btn multiple layout-filter">
										<input type="checkbox" value="2"> Directorio
									</label>						
									<label id="layout-filter-5" class="btn multiple layout-filter">
										<input type="checkbox" value="5"> Auditorio
									</label>
									<label id="layout-filter-6" class="btn multiple layout-filter">
										<input type="checkbox" value="6"> Aula
									</label>
									<label id="layout-filter-1" class="btn multiple layout-filter">
										<input type="checkbox" value="1"> Mesa U
									</label>
									<br>																															
									<label id="layout-filter-3" class="btn multiple layout-filter">
										<input type="checkbox" value="3"> Recepcion
									</label>						
									<label id="layout-filter-4" class="btn multiple layout-filter">
										<input type="checkbox" value="4"> Banquete
									</label>
									<label id="layout-filter-7" class="btn multiple layout-filter">
										<input type="checkbox" value="7"> Cabaret
									</label>																										
								</div>
							</div>		
						</div>
<!-- Facilities -->										
						<div class="row filter">
							<div class="col-xs-12 col-sm-12 col-md-2">
								<h6><i class="fa fa-list-alt"></i> FACILIDADES <a data-toggle="tooltip" title="Facilidades disponibles en el espacio"><i class="fa fa-question-circle"></i></a></h6>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-10"> 									
								<?php echo form_dropdown('facilities', $facilities, '', 'id="select_facilities" multiple class="filter-fix-margin" style="width:84%;" ') ?>														
							</div>
						</div>			
					</div>