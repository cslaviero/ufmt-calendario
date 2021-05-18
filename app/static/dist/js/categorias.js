$(function () {

    //var token = localStorage.getItem('token');
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

            // faz nada

        } else {
            logout();
        }
    });
    requestLogin.fail(function (e) {
        logout();
    });

    // inserir categoria
    $('#insere').submit(function () {
        var form = $(this).serialize();
        var requestInsere = $.ajax({
            method: "post",
            url:"../../app/public/inserir/categoria", // chama a rota para retornar as categorias disponíveis
            dataType: "json",
            data: form
        });
        requestInsere.done(function (e) {
            if (e.sucesso == 1){
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Categoria inserida com Sucesso!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            } else {
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Falha ao inserir categoria!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            }
        });
        requestInsere.fail(function (e) {
            $('#mensagem').modal('show');
            $('#msg').html('<div class="alert alert-success text-center">\n' +
                '<strong>Erro ao inserir categoria!</strong>\n' +
                '</div>');
        });

        $('#insere input').val("");

        return false;
    });

    $('#altera').submit(function () {
        var form2 = $(this).serialize();
        var requestAltera = $.ajax({
            method: "post",
            url:"../../app/public/alterar/categoria", // chama a rota para alterar os dados do categoria
            dataType: "json",
            data: form2
        });
        requestAltera.done(function (e) {
            if (e.sucesso == 1){
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Categoria alterada com Sucesso!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            } else {
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Falha ao alterar categoria!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            }
        });
        requestAltera.fail(function (e) {
            $('#mensagem').modal('show');
            $('#msg').html('<div class="alert alert-success text-center">\n' +
                '<strong>Erro ao alterar categoria!</strong>\n' +
                '</div>');
        });

        $('#altera input').val("");

        return false;
    });

    $('#deletar').submit(function () {
        var form3 = $(this).serialize();
        var requestDeleta = $.ajax({
            method: "post",
            url:"../../app/public/deletar/categoria", // chama a rota para alterar os dados do evento
            dataType: "json",
            data: form3
        });
        requestDeleta.done(function (e) {
            if (e.sucesso == 1){
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Categoria excluída com Sucesso!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            } else {
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Falha ao excluir categoria!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            }
        });
        requestDeleta.fail(function (e) {
            $('#mensagem').modal('show');
            $('#msg').html('<div class="alert alert-success text-center">\n' +
                '<strong>Erro ao excluir categoria!</strong>\n' +
                '</div>');
        });

        $('#deletar input').val("");

        return false;
    });

    // carregar modal quando chamado pelo #insere<função>
    if(location.search.indexOf('insere') != -1){
        $('#ins_categoria').modal('show');
    }
});

// alterar evento
function altera(cod) {
    var request = $.ajax({
        method: "get",
        url:"../../app/public/listar/categoria", // chama a rota para retornar os dados da categoria
        dataType: "json",
        data: {
            id: cod
        }
    });
    request.done(function (e) {
        // Incluindo dados da categoria nos elementos dos inputs
        $('#id2').val(e[0].id); // campo id
        $('#nome2').val(e[0].nome); // campo nome
        $('#cor2').val(e[0].cor); // campo cor
    });
}

// deletar categoria
function deleta(cod) {
    $('#cod_categoria').val(cod);
}

function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('id');
    $(location).attr("href", "index.php");
}