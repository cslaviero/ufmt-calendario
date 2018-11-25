$(function () {

    var token = localStorage.getItem('token');
    //função onLoad para verificar se o token do login está correto
    var requestLogin = $.ajax({
        method: "post",
        url:"../../app/public/consulta/login", // chama a rota para confirmar login
        async: true,
        crossDomain: true,
        //dataType: "json",
        data: {"token": token}
    });
    requestLogin.done(function (e) {
        if (e.success == true){

            carrega();

        } else {
            logout();
        }
    });
    requestLogin.fail(function (e) {
        logout();
    });

    // inserir período
    $('#insere').submit(function () {
        var form = $(this).serialize();
        var requestInsere = $.ajax({
            method: "post",
            url:"../../app/public/inserir/periodo", // chama a rota para inserir os periodos disponíveis
            dataType: "json",
            data: form
        });
        requestInsere.done(function (e) {
            if (e.sucesso == 1){
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Período inserido com Sucesso!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            } else {
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Falha ao inserir período!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            }
        });
        requestInsere.fail(function (e) {
            $('#mensagem').modal('show');
            $('#msg').html('<div class="alert alert-success text-center">\n' +
                '<strong>Erro ao inserir período!</strong>\n' +
                '</div>');
        });

        $('#insere input').val("");

        return false;
    });

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

// alterar evento
function altera(cod) {
    var request = $.ajax({
        method: "get",
        url:"../../app/public/listar/periodo", // chama a rota para retornar os dados do periodo
        dataType: "json",
        data: {
            id: cod
        }
    });
    request.done(function (e) {
        // Incluindo dados do período nos elementos dos inputs
        $('#id2').val(e[0].id); // campo id
        $('#nome2').val(e[0].nome); // campo nome
        $('#dataini2').val(e[0].di); // campo data_ini
        $('#datafim2').val(e[0].df); // campo data_fim
        $('#url2').val(e[0].url); // campo url

    });
}

// deletar evento
function deleta(cod) {
    $('#cod_periodo').val(cod);
}

// ativar select de importação dos periodos
function ativa_imp() {
    $('#periodo').attr("disabled", false);
}

function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('id');
    $(location).attr("href", "index.php");
}