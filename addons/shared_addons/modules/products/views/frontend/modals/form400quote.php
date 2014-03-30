<script>
    var BASE_URL = '{{ global:base_url }}';
</script>
<?php
    $_item = $result->item;
    $layoutsArr = $categoryparams->auxiliars['layouts'];
    $featuresCatArr = $categoryparams->auxiliars['features_defaults'];    
?>
<div class="modal fade" id="amrformmessage400quote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title amrpink" id="myModalLabel">Pedido de presupuesto a <?php echo $_item->loc_name; ?></h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <h4 class="topmargin">Tiempo estimado: 1 minuto.</h4>
                <span class="help-block"><i class="fa fa-question-circle"></i> Para completar el formulario.</span>
            </div>
        </div>
        <hr>
        <div class="contact-form">           
            <form id="amrform400quote" class="form-horizontal" role="form">
                <div class="form-group fixmrgT">
                    <label for="reference" class="col-lg-2 control-label">Referencia</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control pink srlz" name="reference" value="<?php echo $_item->loc_name.' ('.$_item->loc_city.')'.' - '.$_item->space_denomination.' '.$_item->space_name; ?>" readonly="readonly">
                        <?php foreach($view['urifields'] as $field): ?>
                            <input type="hidden" class="srlz" name="dataF<?php echo $field; ?>" value="<?php echo $_item->$field; ?>"> 
                        <?php endforeach; ?>
                        <input type="hidden" class="srlz" name="dataFviewid" value="form400quote">
                    </div>
                </div>
                <hr>
                <h4 class="pink"><span class="badge badge-pink">1</span> Datos personales</h4>
                <div class="form-group">
                    <label for="name" class="col-lg-3 control-label">Nombre</label>
                    <div class="col-lg-9">
                        <input type="text" class="form-control input-sm srlz" name="name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-lg-3 control-label">Email</label>
                    <div class="col-lg-9">
                        <input type="email" class="form-control input-sm srlz" name="email">
                    </div>
                </div> 
                <div class="form-group">
                    <label for="phone" class="col-lg-3 control-label">Telefono</label>
                    <div class="col-lg-9">
                        <input type="telefono" class="form-control input-sm srlz" name="phone">
                    </div>
                </div>
                <hr>
<!-- DIAS Y HORARIOS  -->                 
                <h4 class="pink"><span class="badge badge-pink">2</span> Dias y horarios</h4>
                <div class="row">
                    <div class="col-xs-3">
                        <p class="pull-right"><i class="fa fa-caret-right"></i> Ingrese</p>
                    </div>
                    <div class="col-xs-9">                    
                        <div class="btn-group btn-group-justified fixmrgB" data-toggle="buttons">
                            <label class="btn btn-amrgray btn-sm" id="btnDT1">
                                <input type="radio" name="DToption"> fechas calendario
                            </label>
                            <label class="btn btn-sm btn-amrgray" id="btnDT2-1">
                                <input type="radio" name="DToption"> dias de la semana
                            </label>
                        </div>
                        <span class="help-block"><i class="fa fa-question-circle"></i> Seleccione si ingresa por <strong>fecha</strong> (calendario) ó <strong>día de la semana</strong></span>
                    </div>
                </div>
<!-- ##### OPTION DATES -->
                <div id="opt2-dates" class="opt2box">                 
                    <div class="row datetimebox" id="optDates">
                        <div class="col-xs-12">
                            <div class="closer"><button type="button" id="btnDT1-hide" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button></div>                                
                        </div>
                        <div class="col-xs-3 rowbtn">
                            <p class="pull-right"><i class="fa fa-caret-right"></i> Ingrese</p>                            
                        </div>
                        <div class="col-xs-9 rowbtn">
                                                         
                            <div class="btn-group fixmrgB btn-group-justified" data-toggle="buttons">
                                <label class="btn btn-xs btn-amrgray" id="btnDT1-1">
                                    <input type="radio" name="DT1suboption"> rango de fechas
                                </label>
                                <label class="btn btn-xs btn-amrgray" id="btnDT1-2">
                                    <input type="radio" name="DT1suboption"> fechas individuales
                                </label>
                            </div>
                            <span class="help-block"><i class="fa fa-question-circle"></i> Seleccione si ingresa por <strong>rango</strong> de fechas ó <strong>lista</strong> de fechas</p>                            
                        </div>
                    </div>
<!-- DATE: calendar-range  -->                    
                    <div class="row datetimebox" id="optDates-range">
                        <div class="col-xs-12">
                            <div class="closer"><button type="button" id="btnDT1-1-hide" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button></div>                                                
                            <p>
                                <i class="fa fa-caret-right"></i> seleccione <strong>rango de fechas</strong> (desde / hasta)<br>
                                <i class="fa fa-caret-right"></i> desmarque <strong>sábados</strong> y/o <strong>domingos</strong>, si no los incluye<br>
                                <i class="fa fa-caret-right"></i> seleccione <strong>rango horario </strong> (desde / hasta)<br>
                                <i class="fa fa-exclamation-circle"></i> si tiene rangos de fechas con distintos horarios, ingréselos por separado<br>                                
                                <i class="fa fa-caret-right"></i> presione <strong>Agregar </strong> para confirmar<br>                                
                            </p>
                        </div>
                        <div class="col-xs-4">
                            <div class="input-daterange input-group f400-optDate-range-date" id="datepicker">
                                <input type="text" class="input-sm range form-control" name="start" id="DT1-1-date1" placeholder="desde" />
                                <span class="input-group-addon">a</span>
                                <input type="text" class="input-sm range form-control" name="end" id="DT1-1-date2" placeholder="hasta" />
                            </div>
                            <span class="help-block">rango fecha</span>                            
                        </div>
                        <div class="col-xs-2 inner">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-amrgray btn-xs active cbx" id="DT1-1-sat">
                                    <input type="checkbox"> Sabados <i class="fa fa-check"></i>
                                </label>
                            </div>  
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-amrgray btn-xs active cbx" id="DT1-1-sun">
                                    <input type="checkbox"> Domingos <i class="fa fa-check"></i>
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-2 inner">                          
                            <div class='input-group bootstrap-timepicker'>
                                <span class="input-group-addon dt11-time1"><span class="glyphicon glyphicon-time"></span></span>                                
                                <input type='text' id="DT1-1-time1" class="form-control input-sm f400-optDate-range-time1" readonly />
                            </div>
                            <span class="help-block">inicia</span>                              
                        </div>
                        <div class="col-xs-2 inner">                                             
                            <div class='input-group bootstrap-timepicker'>
                                <span class="input-group-addon dt11-time2"><span class="glyphicon glyphicon-time"></span></span>                                
                                <input type='text' id="DT1-1-time2" class="form-control input-sm f400-optDate-range-time2" readonly />
                            </div>
                            <span class="help-block">finaliza</span>                              
                        </div>                        
                        <div class="col-xs-2">
                            <button type="button" id="btnaddDT1-1" class="btn btn-sm btn-default dateadd">Agregar</button> 
                        </div>
                    </div>
<!-- END DATE: calendar-range  -->     
<!-- DATE: calendar-multi  -->                    
                    <div class="row datetimebox" id="optDates-multi">
                        <div class="col-xs-12">    
                            <div class="closer"><button type="button" id="btnDT1-2-hide" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button></div>                                                                                            
                            <p>
                                <i class="fa fa-caret-right"></i> seleccione <strong>una o varias fechas</strong><br>
                                <i class="fa fa-caret-right"></i> seleccione <strong>rango horario </strong> (desde / hasta)<br>
                                (<i class="fa fa-question-circle"></i> si tienes fechas con distintos horarios, ingrésalas por separado)<br>                                  
                                <i class="fa fa-caret-right"></i> presione <strong>Agregar </strong> para confirmar
                            </p>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group date f400-optDate-multi-date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>                                
                                <input type="text" class="form-control input-sm" id="DT1-2-date" readonly/>
                            </div>
                            <span class="help-block">seleccione fechas</span>                            
                        </div>
                        <div class="col-xs-2 inner">                          
                            <div class='input-group bootstrap-timepicker'>
                                <span class="input-group-addon dt12-time1"><span class="glyphicon glyphicon-time"></span></span>                                
                                <input type='text' class="form-control input-sm f400-optDate-multi-time1" id="DT1-2-time1" readonly />
                            </div>
                            <span class="help-block">inicia</span>                              
                        </div>
                        <div class="col-xs-2 inner">                                             
                            <div class='input-group bootstrap-timepicker'>
                                <span class="input-group-addon dt12-time2"><span class="glyphicon glyphicon-time"></span></span>                                
                                <input type='text' class="form-control input-sm f400-optDate-multi-time2" id="DT1-2-time2" readonly />
                            </div>
                            <span class="help-block">finaliza</span>                              
                        </div>                        
                        <div class="col-xs-2">
                            <button type="button" id="btnaddDT1-2" class="btn btn-sm btn-default dateadd">Agregar</button>
                        </div>
                    </div>
<!-- END DATE: calendar-multi  -->

<!-- #### WEEK -->
<!-- WEEK: multi day  -->                    
                    <div class="row datetimebox" id="optDays-multi">
                        <div class="col-xs-12"> 
                            <div class="closer"><button type="button" id="btnDT2-1-hide" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button></div>                                                                                                                                           
                            <p>
                                <i class="fa fa-caret-right"></i> seleccione <strong>uno o varios días</strong><br>
                                <i class="fa fa-caret-right"></i> seleccione <strong>rango horario </strong> (desde / hasta)<br>
                                (<i class="fa fa-question-circle"></i> si tienes días con distintos horarios, ingrésalos por separado)<br>                                  
                                <i class="fa fa-caret-right"></i> si los días de la semana elegidos se repiten de igual forma por una o varias semanas seleccione <strong>Replica</strong> y luego seleccione la cantidad de <strong>veces</strong><br>                                                    
                                <i class="fa fa-caret-right"></i> presione <strong>Agregar </strong> para confirmar
                            </p>
                        </div>
                        <div class="col-xs-4 inner">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default btn-xs btn-amrgray" id="btn-DT2-1-day-1">
                                    <input type="checkbox"> Lu
                                </label>
                                <label class="btn btn-default btn-xs btn-amrgray" id="btn-DT2-1-day-2">
                                    <input type="checkbox"> Ma
                                </label>
                                <label class="btn btn-default btn-xs btn-amrgray" id="btn-DT2-1-day-3">
                                    <input type="checkbox"> Mi
                                </label>
                                <label class="btn btn-default btn-xs btn-amrgray" id="btn-DT2-1-day-4">
                                    <input type="checkbox"> Ju
                                </label>
                                <label class="btn btn-default btn-xs btn-amrgray" id="btn-DT2-1-day-5">
                                    <input type="checkbox"> Vi
                                </label>
                                <label class="btn btn-default btn-xs btn-amrgray" id="btn-DT2-1-day-6">
                                    <input type="checkbox"> Sa
                                </label>
                                <label class="btn btn-default btn-xs btn-amrgray" id="btn-DT2-1-day-0">
                                    <input type="checkbox"> Do
                              </label>      
                            </div>                           
                        </div>
                        <div class="col-xs-2 inner">                          
                            <div class='input-group bootstrap-timepicker'>
                                <span class="input-group-addon dt21-time1"><span class="glyphicon glyphicon-time"></span></span>                                
                                <input type='text' class="form-control input-sm f400-optDays-multi-time1" id="DT2-1-time1" readonly />
                            </div>
                            <span class="help-block">inicia</span>                              
                        </div>
                        <div class="col-xs-2 inner">                                             
                            <div class='input-group bootstrap-timepicker'>
                                <span class="input-group-addon dt21-time2"><span class="glyphicon glyphicon-time"></span></span>                                
                                <input type='text' class="form-control input-sm f400-optDays-multi-time2" id="DT2-1-time2" readonly />
                            </div>
                            <span class="help-block">finaliza</span>                              
                        </div>      
                        <div class="col-xs-2 inner">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-amrgray btn-xs cbx" id="DT2-1-repeat">
                                    <input type="checkbox"> Replica <i class="fa fa-check"></i>
                                </label>
                            </div> 
                            <select class="form-control input-sm xs" id="DT2-1-repeattimes" disabled="disabled">
                                <option value="1">1 vez</option>
                            <?php for($i=2;$i<=20;$i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> veces</option>
                            <?php endfor; ?>    
                            </select>
                        </div>                                          
                        <div class="col-xs-2">
                            <button type="button" id="btnaddDT2-1" class="btn btn-sm btn-default dateadd">Agregar</button> 
                        </div>
                    </div>
<!-- END DATE: x rango fecha  -->  
                    <table id="datetimeTable" class="table table-condensed">
                        <thead>
                            <tr>
                                <th>dia/s</th><th>horario</th><th>cantidad<br>dias</th><th>cantidad<br>horas</th><th>detalles</th><th><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody id="datetimeTablebody">   
                        <tbody>
                        <tfoot id="datetimeTablefoot">
                            <tr>
                                <td colspan="2"><strong>Dias y horas totalizadas:</strong></td>
                                <td><strong>0 días</strong></td>
                                <td><strong>0 horas</strong></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>                  
                    </table>
                </div>                                  
                <hr>
<!-- END option dates -->     
<!-- Detalles actividad -->                     
                <h4 class="pink"><span class="badge badge-pink">3</span> Detalles actividad </h4>
                <div class="form-group">                                       
                    <label for="activity" class="col-lg-3 control-label">Actividad</label>
                    <div class="col-lg-9">
                        <textarea class="form-control input-sm srlz" name="activity" rows="2"></textarea>
                        <span class="help-block"><i class="fa fa-question-circle"></i> Brevemente, describa el uso que le dará al espacio.</span>
                    </div>
                </div>  
                <div class="form-group">
                    <label for="pax" class="col-lg-3 control-label">Participantes</label>
                    <div class="col-lg-3">
                        <select class="form-control input-sm srlz" name="pax" placeholder="seleccione">
                            <option value=""></option>
                        <?php for($i=1; $i<=$_item->space_max_capacity; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>    
                        </select>
                        <span class="help-block"><i class="fa fa-question-circle"></i> total de personas.</span>                        
                    </div>
                </div>
                <div class="form-group">
                    <label for="layout" class="col-lg-3 control-label">Armado</label>
                    <div class="col-lg-9">
                        <?php $activelyt = count($_item->data_layouts)==1 ? 'active' : ''; ?>
                        <?php foreach ($_item->data_layouts as $layout): ?>
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-xs btn-amrgray <?php echo $activelyt;?>"  name="layout" id="<?php echo $layout->layout_id; ?>">
                                    <input type="checkbox"><?php echo $layoutsArr[$layout->layout_id]->name; ?>
                                </label>
                            </div>                                                        
                        <?php endforeach; ?>
                        <span class="help-block"><i class="fa fa-question-circle"></i> Seleccione el armado del espacio para su actividad.</span>                        
                    </div>
                </div>                 
                <hr> 
<!-- Equipamiento y servicios -->                                   
                <h4 class="pink"><span class="badge badge-pink">4</span> Equipamiento, catering y servicios </h4>
                <?php if(count($_item->data_features)>0): ?>
                    <p><i class="fa fa-question-circle"></i> Seleccione las características que desea incluir en su requerimiento:<br> 
                    (<i class="fa fa-check-circle"></i> incluido)</p>
                <?php endif; ?>    
                <!-- row features per category -->
                <?php foreach ($featuresCatArr as $ftrcatid => $ftrArr): ?>
                    <?php $catnameprinted = false; ?>
                    <?php foreach ($ftrArr as $ftrid => $ftr): ?>
                        <?php if(array_key_exists($ftr->ftrID, $_item->data_features)): ?>
                            <?php if(!$catnameprinted): ?>
                            <div class="row formfield">  
                                <div class="col-lg-3">                                               
                                    <label class="pull-right form400ftrtooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $ftr->catDesc?>"><?php echo $ftr->catName; ?></label>
                                </div>           
                                <div class="col-lg-9">
                            <?php $catnameprinted = true; ?>                                                       
                            <?php endif; ?>
                            <?php $activeclass = ($_item->data_features[$ftrid]->is_optional == 0) ? 'active' : ''; ?> 
                            <?php $checkicon = ($_item->data_features[$ftrid]->is_optional == 0) ? '<i class="fa fa-check-circle"></i>' : ''; ?>
                            <?php $includedtxt = ($_item->data_features[$ftrid]->is_optional == 0) ? '(Incluido) - ' : ''; ?>
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-xs btn-amrgray form400ftrtooltip <?php echo $activeclass; ?>" name="feature" id="<?php echo $ftr->ftrID; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $includedtxt; ?><?php echo $ftr->ftrDesc; ?>">
                                    <input type="checkbox"><?php echo $checkicon; ?> <?php echo $ftr->ftrName; ?>
                                </label>
                            </div> 
                        <?php endif; ?>    
                    <?php endforeach; ?>
                    <?php if($catnameprinted): ?>
                            </div><!-- close div.col -->
                        </div><!-- close div.row -->
                    <?php endif; ?>
                <?php endforeach; ?>
                <div class="form-group">                                       
                    <label for="comments_ftr" class="col-lg-3 control-label">Detalles</label>
                    <div class="col-lg-9">
                        <textarea class="form-control input-sm srlz" name="comments_ftr" rows="2"></textarea>
                        <span class="help-block"><i class="fa fa-question-circle"></i> Agregue aclaraciones y/o detalles sobre su requerimiento.</span>
                    </div>
                </div>                                                     
                <hr>           
                <h4 class="pink"><span class="badge badge-pink">5</span> Aclaraciones generales</h4>                             
                <div class="form-group">
                    <div class="col-lg-12">
                        <textarea class="form-control input-sm srlz" name="comments_gral" rows="4"></textarea>
                        <span class="help-block"><i class="fa fa-question-circle"></i> Agregue aquí comentarios y/o detalles para aclarar aspectos generales de su requerimiento.</span>                        
                    </div>
                </div>
            </form>
            <div class="msgbox-sm" id="msgbox400quote"></div>
        </div>         
    </div>
<div class="modal-footer">
    <button type="button" class="btn btn-gray" data-dismiss="modal">Cancelar</button>
    <button type="button" id="amrbtnsendspace400quote" class="btn btn-bg amrpink">Enviar</button>
</div>
</div><!-- /.modal-content --> 
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->