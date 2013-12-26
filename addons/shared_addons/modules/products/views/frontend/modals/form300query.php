<script>
    var BASE_URL = '{{ global:base_url }}';
</script>
<div class="modal fade" id="amrformmessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Enviar una consulta a <?php echo $result->item->loc_name; ?></h4>
    </div>
    <div class="modal-body">
        <div class="contact-form">
            <form id="amrformmessage" class="form-horizontal" role="form">
                <div class="form-group">
                    <label for="reference" class="col-lg-2 control-label">Referencia</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name="reference" value="<?php echo $result->item->loc_name.' ('.$result->item->loc_city.')'.' - '.$result->item->space_denomination.' '.$result->item->space_name; ?>" readonly="readonly">
                        <?php foreach($view['urifields'] as $field): ?>
                        <input type="hidden" name="dataF<?php echo $field; ?>" value="<?php echo $result->item->$field; ?>"> 
                        <?php endforeach; ?>
                        <input type="hidden" name="dataFviewid" value="300query">
                    </div>
                </div>
                <div class="form-group">
                    <label for="Name" class="col-lg-2 control-label">Nombre</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name="name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="Email" class="col-lg-2 control-label">Email</label>
                    <div class="col-lg-10">
                        <input type="email" class="form-control" name="email">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2" for="message">Consulta</label>
                    <div class="col-lg-10">
                        <textarea class="form-control" name="message" rows="6"></textarea>
                    </div>
                </div>
            </form>
            <div id="msgbox"></div>
        </div>         
    </div>
<div class="modal-footer">
    <button type="button" class="btn btn-gray" data-dismiss="modal">Cancelar</button>
    <button type="button" id="amrbtnsendspacemessage" class="btn btn-primary">Enviar</button>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->