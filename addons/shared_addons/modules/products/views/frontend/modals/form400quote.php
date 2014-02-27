<script>
    var BASE_URL = '{{ global:base_url }}';
</script>
<div class="modal fade" id="amrformmessage400quote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title amrpink" id="myModalLabel">Pedido de presupuesto a <?php echo $result->item->loc_name; ?></h4>
    </div>
    <div class="modal-body">
        <div class="contact-form">
            <form id="amrform400quote" class="form-horizontal" role="form">
                <div class="form-group">
                    <label for="reference" class="col-lg-2 control-label">Referencia</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control pink" name="reference" value="<?php echo $result->item->loc_name.' ('.$result->item->loc_city.')'.' - '.$result->item->space_denomination.' '.$result->item->space_name; ?>" readonly="readonly">
                        <?php foreach($view['urifields'] as $field): ?>
                        <input type="hidden" name="dataF<?php echo $field; ?>" value="<?php echo $result->item->$field; ?>"> 
                        <?php endforeach; ?>
                        <input type="hidden" name="dataFviewid" value="form400quote">
                    </div>
                </div>
                <hr>
                <h4 class="pink"><span class="badge badge-pink">1</span> Datos personales</h4>
                <div class="form-group">
                    <label for="Name" class="col-lg-2 control-label">Nombre</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control input-sm" name="name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="Email" class="col-lg-2 control-label">Email</label>
                    <div class="col-lg-10">
                        <input type="email" class="form-control input-sm" name="email">
                    </div>
                </div> 
                <div class="form-group">
                    <label for="Email" class="col-lg-2 control-label">Telefono</label>
                    <div class="col-lg-10">
                        <input type="telefono" class="form-control input-sm" name="telefono">
                    </div>
                </div>
                <hr>
<!-- DIAS Y HORARIOS  -->                 
                <h4 class="pink"><span class="badge badge-pink">2</span> Dias y horarios</h4>
                <p><i class="fa fa-caret-right"></i> Seleccione si ingresa por <strong>fecha</strong> (calendario) ó <strong>día semana</strong> (lunes/domingo):</p>
                <div class="row">
                    <div class="col-xs-12">                    
                        <div class="btn-group btn-group-justified fixmrgB" data-toggle="buttons">
                            <label class="btn btn-amrgray btn-sm" id="btnDT1">
                                <input type="radio" name="DToption"> fechas calendario
                            </label>
                            <label class="btn btn-sm btn-amrgray" id="btnDT2">
                                <input type="radio" name="DToption"> dias de la semana
                            </label>
                        </div>
                    </div>
                </div>
<!-- ##### OPTION DATES -->
                <div id="opt2-dates" class="opt2box">                 
                    <div class="row datetimebox" id="optDates">
                        <div class="col-xs-12">
                            <div class="closer"><button type="button" id="btnDT1-hide" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button></div>                             
                            <p><i class="fa fa-caret-right"></i> Seleccione si ingresa por <strong>rango</strong> de fechas (desde/hasta) ó <strong>lista</strong> de fechas:</p>
                            <div class="btn-group fixmrgB btn-group-justified" data-toggle="buttons">
                                <label class="btn btn-xs btn-amrgray" id="btnDT1-1">
                                    <input type="radio" name="DT1suboption"> rango de fechas
                                </label>
                                <label class="btn btn-xs btn-amrgray" id="btnDT1-2">
                                    <input type="radio" name="DT1suboption"> fechas individuales
                                </label>
                            </div>
                        </div>
                    </div>
<!-- DATE: calendar-range  -->                    
                    <div class="row datetimebox" id="optDates-range">
                        <div class="col-xs-12">
                            <div class="closer"><button type="button" id="btnDT1-1-hide" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button></div>                                                
                            <p>
                                <i class="fa fa-caret-right"></i> seleccione <strong>rango de fechas</strong> (desde / hasta)<br>
                                <i class="fa fa-caret-right"></i> desmarque <strong>sábados</strong>, si no los incluye<br>
                                <i class="fa fa-caret-right"></i> desmarque <strong>domingos</strong>, si no los incluye<br>
                                <i class="fa fa-caret-right"></i> seleccione <strong>rango horario </strong> (desde / hasta)<br>
                                <i class="fa fa-caret-right"></i> presione <strong>Agregar </strong> para confirmar
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

<!-- #### DAYS -->
<!-- DAYS: multi date  -->                    
                    <div class="row datetimebox" id="optDays-multi">
                        <div class="col-xs-12"> 
                            <div class="closer"><button type="button" id="btnDT2-hide" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button></div>                                                                                                                                           
                            <p><i class="fa fa-caret-right"></i> Seleccione <strong>dias</strong> y su horario:</p>
                        </div>
                        <div class="col-xs-4">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default btn-xs btn-amrgray">
                                <input type="checkbox"> Lu
                                </label>
                                <label class="btn btn-default btn-xs btn-amrgray">
                                <input type="checkbox"> Ma
                                </label>
                                <label class="btn btn-default btn-xs btn-amrgray">
                                <input type="checkbox"> Mi
                                </label>
                                <label class="btn btn-default btn-xs btn-amrgray">
                                <input type="checkbox"> Ju
                                </label>
                                <label class="btn btn-default btn-xs btn-amrgray">
                                <input type="checkbox"> Vi
                                </label>
                                <label class="btn btn-default btn-xs btn-amrgray">
                                <input type="checkbox"> Sa
                                </label>
                                <label class="btn btn-default btn-xs btn-amrgray">
                                <input type="checkbox"> Do
                              </label>      
                            </div>                           
                        </div>
                        <div class="col-xs-2 inner">                          
                            <div class='input-group bootstrap-timepicker'>
                                <span class="input-group-addon dt21-time1"><span class="glyphicon glyphicon-time"></span></span>                                
                                <input type='text' class="form-control input-sm f400-optDays-multi-time1" readonly />
                            </div>
                            <span class="help-block">inicia</span>                              
                        </div>
                        <div class="col-xs-2 inner">                                             
                            <div class='input-group bootstrap-timepicker'>
                                <span class="input-group-addon dt21-time2"><span class="glyphicon glyphicon-time"></span></span>                                
                                <input type='text' class="form-control input-sm f400-optDays-multi-time2" readonly />
                            </div>
                            <span class="help-block">finaliza</span>                              
                        </div>      
                        <div class="col-xs-2 inner">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-amrgray btn-xs cbx">
                                    <input type="checkbox"> Repite <i class="fa fa-check"></i>
                                </label>
                            </div> 
                            <select class="form-control input-sm xs">
                                <option value="">0 veces</option>
                                <option value="1">1 vez</option>
                            <?php for($i=2;$i<=20;$i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> veces</option>
                            <?php endfor; ?>    
                            </select>
                        </div>                                          
                        <div class="col-xs-2">
                            <button class="btn btn-sm btn-default dateadd">Agregar</button> 
                        </div>
                    </div>
<!-- END DATE: x rango fecha  -->  
                    <table id="datetimeTable" class="table table-condensed">
                        <thead>
                            <tr>
                                <th>dia/s</th><th>horario</th><th>cant dias</th><th>cant horas</th><th>detalles</th><th><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody id="datetimeTablebody">   
                        <tbody>
                        <tfoot id="datetimeTablefoot">
                            <tr>
                                <td colspan="2">Totales:</td>
                                <td>0 días</td>
                                <td>0 horas</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>                  
                    </table>
                </div>
                <!-- END option dates -->                                        
                <hr>
                <h4 class="pink"><span class="badge badge-pink">6</span> Aclaraciones</h4>                             
                <div class="form-group">
                    <label class="control-label col-lg-2" for="message">Detalles</label>
                    <div class="col-lg-10">
                        <textarea class="form-control input-sm" name="message" rows="3"></textarea>
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