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
                <h4 class="pink"><span class="badge badge-pink">2</span> Dias y horarios</h4>
                <p>Seleccione como desea especificar los días:</p>
                <div class="row">
                    <div class="col-xs-12">                    
                        <div class="btn-group btn-group-justified fixmrgB" data-toggle="buttons">
                            <label class="btn btn-pink btn-sm">
                                <input type="radio" name="opt2-mainoptions" id="option1"> Fechas de calendario
                            </label>
                            <label class="btn btn-sm btn-pink">
                                <input type="radio" name="opt2-mainoptions" id="option2"> Dias de la semana
                            </label>
                        </div>
                    </div>
                </div>
                <!-- option dates -->
                <div id="opt2-dates" class="opt2box">                 
                    <div class="row">
                        <div class="col-xs-12">
                            <p>Seleccione como desea ingresar las fechas:</p>
                            <div class="btn-group fixmrgB btn-group-justified" data-toggle="buttons">
                                <label class="btn btn-xs btn-pink">
                                    <input type="radio" name="opt2-suboptions" id="option1"> una sola fecha
                                </label>
                                <label class="btn btn-xs btn-pink">
                                    <input type="radio" name="opt2-suboptions" id="option2"> periodo
                                </label>
                                <label class="btn btn-xs btn-pink">
                                    <input type="radio" name="opt2-suboptions" id="option3"> fechas individuales
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">                        
                            <p>Seleccione <strong>1 fecha:</strong></p>
                        </div>
                        <div class="col-xs-4">
                            <div class="input-group date datepickermodalform400-single">
                                <input type="text" class="form-control input-sm" readonly>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                            <span class="help-block">Clic para elegir fecha</span>
                        </div>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" placeholder=".col-xs-3">
                        </div>
                        <div class="col-xs-2">
                            <input type="text" class="form-control" placeholder=".col-xs-4">
                        </div>
                        <div class="col-xs-2">
                            <input type="text" class="form-control" placeholder=".col-xs-4">
                        </div>
                    </div>
                    <table class="table table-condensed">
                        <tr>
                            <th>fechas</th><th>horario</th><th>cant dias</th><th>cant horas</th><th>repetición</th><th>borrar</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn btn-xs"><i class="fa fa-times"></i></button></td>
                        </tr>                    
                    </table>
                </div>
                <!-- END option dates -->
                <!-- option days-->
                <div id="opt2-days" class="opt2box">                 
                    <p>DAYS</p>
                </div>       
                <!-- END option days-->                                          
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