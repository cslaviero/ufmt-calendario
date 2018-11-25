<!-- /#modal inserir categorias -->
<div class="modal fade" id="ins_campus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="insere">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Inserir campus</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Nome do Campus</label>
                                        <input name="nome" required="required" placeholder="Rondonópolis" class="form-control">
                                        <p class="help-block">Até 30 caracteres.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /#modal inserir categorias -->

<!-- /#modal alterar categorias -->
<div class="modal fade" id="alt_campus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="altera">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Alterar campus</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Nome do campus</label>
                                        <input name="nome" id="nome2" required="required" class="form-control">
                                        <input name="id" id="id2" type="hidden">
                                        <p class="help-block">Até 30 caracteres.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Alterar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /#modal alterar categorias -->

<!-- modal de exclusão -->
<div class="modal fade" id="del_campus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deletar">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir?
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="cod_campus">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Sim</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal de exclusão -->

<!-- modal de mensagens -->
<div class="modal fade" id="mensagem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div id="msg" class="modal-body">
                Mensagem
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="location.href='campus.php';" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal de mensagens -->