$(function () {
    //alert('periodos js')
    setTimeout(function () {
        $('#msg').fadeToggle(2800);//elemento de msg
    }, 2000);

    // inserir período

    $('#altera').submit(function () {
        var form2 = $(this).serialize();
        var requestAltera = $.ajax({
            method: "post",
            url:"../../app/public/alterar/periodo", // chama a rota para alterar os dados do periodo
            dataType: "json",
            data: form2
        });
        requestAltera.done(function (e) {
            if (e.sucesso == 1){
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Período alterado com Sucesso!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            } else {
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Falha ao alterar período!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            }
        });
        requestAltera.fail(function (e) {
            $('#mensagem').modal('show');
            $('#msg').html('<div class="alert alert-success text-center">\n' +
                '<strong>Erro ao alterar período!</strong>\n' +
                '</div>');
        });

        $('#altera input').val("");

        return false;
    });

    $('#deletar').submit(function () {
        var form3 = $(this).serialize();
        var requestDeleta = $.ajax({
            method: "post",
            url:"../../app/public/deletar/periodo", // chama a rota para alterar os dados do periodo
            dataType: "json",
            data: form3
        });
        requestDeleta.done(function (e) {
            if (e.sucesso == 1){
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Período excluído com Sucesso!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            } else {
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Falha ao excluir período!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            }
        });
        requestDeleta.fail(function (e) {
            $('#mensagem').modal('show');
            $('#msg').html('<div class="alert alert-success text-center">\n' +
                '<strong>Erro ao excluir período!</strong>\n' +
                '</div>');
        });

        $('#deletar input').val("");
        return false;
    });

    // carregar modal quando chamado pelo #insere<função>
    if(location.search.indexOf('insere') != -1){
        $('#ins_periodo').modal('show');
    }

});

// funções onload
function carrega() {
    //função onLoad para listar todos os períodos disponíveis e imprimir na página
    var requestPeriodos = $.ajax({
        method: "get",
        url:"../../app/public/periodos", // chama a rota para retornar os períodos disponíveis
        dataType: "json"
    });
    requestPeriodos.done(function (e) {
        var dropdown = '';
        for(var k in e){
            dropdown += '<option value="'+e[k].id+'">'+e[k].nome+'</option>';
        }
        $('#periodo').html(dropdown); // adiciona à select#períodos as tags para listagem dos períodos
    });
}


// ativar select de importação dos periodos
function ativa_imp() {
    if ($('#importar').is(':checked')) {
        $('#periodo').attr("disabled", false);
        alert($('#importar').is(':checked'));
    }else{
        $('#periodo').attr("disabled", true);
        alert($('#importar').is(':checked'));
    }
    $('#msg').html('<div class="alert alert-danger text-center">\n' +
                    '<strong>Falha ao excluir período!</strong>\n' +
                    '</div>');
    $('#mensagem').modal('show');
}

$('#dataTables-example').DataTable({
    responsive: true
});

$('#datetimepicker1').datetimepicker({
    language: 'pt-BR',
    format: 'DD/MM/YYYY HH:mm:ss'
});
$('#datetimepicker2').datetimepicker({
    language: 'pt-BR',
    format: 'DD/MM/YYYY HH:mm:ss'
});
$('#datetimepicker42').datetimepicker({
    language: 'pt-BR',
    format: 'DD/MM/YYYY HH:mm:ss'
});

function setDatepickerFim(idpicker){

    $('#datetimepicker4'+idpicker).datetimepicker({
        language: 'pt-BR',
        format: 'DD/MM/YYYY HH:mm:ss'
    });

}
