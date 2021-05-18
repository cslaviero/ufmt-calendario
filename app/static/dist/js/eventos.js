$(function () {
    //var token = localStorage.getItem('token');
    //função onLoad para verificar se o token do login está correto
    //var requestLogin = $.ajax({
       // method: "post",
       // url:"../../app/public/consulta/login", // chama a rota para confirmar login
       // async: true,
       // crossDomain: true,
        //dataType: "json",
        //data: {"token": token}
   // });
    /*requestLogin.done(function (e) {
        if (e.success == true){

            carrega();

        } else {
            logout();
        }
    });
    requestLogin.fail(function (e) {
        logout();
    });*/

    // inserir evento
    $('#insere').submit(function () {
        var form = $(this).serialize();
        var requestInsere = $.ajax({
            method: "post",
            url:"../../app/public/inserir/evento", // chama a rota para retornar as categorias disponíveis
            dataType: "json",
            data: form
        });
        requestInsere.done(function (e) {
            if (e.sucesso == 1){
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Evento inserido com Sucesso!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            } else {
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Falha ao inserir evento!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            }
        });
        requestInsere.fail(function (e) {
            $('#mensagem').modal('show');
            $('#msg').html('<div class="alert alert-success text-center">\n' +
                '<strong>Erro ao inserir evento!</strong>\n' +
                '</div>');
        });

        $('#insere input').val("");

        return false;
    });

    $('#altera').submit(function () {
        var form2 = $(this).serialize();
        var requestAltera = $.ajax({
            method: "post",
            url:"../../app/public/alterar/evento", // chama a rota para alterar os dados do evento
            dataType: "json",
            data: form2
        });
        requestAltera.done(function (e) {
            if (e.sucesso == 1){
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Evento alterado com Sucesso!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            } else {
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Falha ao alterar evento!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            }
        });
        requestAltera.fail(function (e) {
            $('#mensagem').modal('show');
            $('#msg').html('<div class="alert alert-success text-center">\n' +
                '<strong>Erro ao alterar evento!</strong>\n' +
                '</div>');
        });

        $('#altera input').val("");

        return false;
    });

    $('#deletar').submit(function () {
        var form3 = $(this).serialize();
        var requestDeleta = $.ajax({
            method: "post",
            url:"../../app/public/deletar/evento", // chama a rota para alterar os dados do evento
            dataType: "json",
            data: form3
        });
        requestDeleta.done(function (e) {
            if (e.sucesso == 1){
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Evento excluído com Sucesso!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            } else {
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Falha ao excluir evento!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            }
        });
        requestDeleta.fail(function (e) {
            $('#mensagem').modal('show');
            $('#msg').html('<div class="alert alert-success text-center">\n' +
                '<strong>Erro ao excluir evento!</strong>\n' +
                '</div>');
        });

        $('#deletar input').val("");

        return false;
    });

    // carregar modal quando chamado pelo #insere<função>
    if(location.search.indexOf('insere') != -1){
        $('#ins_evento').modal('show');
    }
});

// funções onload
function carrega(){

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

    //função onLoad para listar todos os campus disponíveis e imprimir na página
    var requestCampus = $.ajax({
        method: "get",
        url:"../../app/public/campus", // chama a rota para retornar os campus disponíveis
        dataType: "json"
    });
    requestCampus.done(function (e) {
        var dropCamp = '<option selected value="0">Todos os campus</option>';
        for(var k in e){
            dropCamp += '<option value="'+e[k].id+'">'+e[k].nome+'</option>';
        }
        $('#campus').html(dropCamp); // adiciona à select#campus as tags para listagem dos campus
    });

    //função onLoad para listar todas as categorias disponíveis e imprimir na página
    var requestCategorias = $.ajax({
        method: "get",
        url:"../../app/public/categorias", // chama a rota para retornar as categorias disponíveis
        dataType: "json"
    });
    requestCategorias.done(function (e) {
        var categoria = '';
        for(var k in e){
            categoria += '<option value="'+e[k].id+'">'+e[k].nome+'</option>';
        }
        $('#categoria').html(categoria); // adiciona à select#categoria as tags para listagem das categorias
    });
}

// alterar evento
function altera(cod) {
    var request = $.ajax({
        method: "get",
        url:"../../app/public/evento", // chama a rota para retornar os dados do evento
        dataType: "json",
        data: {
            id: cod
        }
    });
    request.done(function (e) {
        // Incluindo dados do evento nos elementos dos inputs
        $('#id2').val(e[0].id); // campo id
        $('#nome2').val(e[0].nome); // campo nome

        // preencher select periodos
        var requestPeriodos = $.ajax({
            method: "get",
            url:"../../app/public/periodos", // chama a rota para retornar os períodos disponíveis
            dataType: "json"
        });
        requestPeriodos.done(function (p) {
            var dropdown = '';
            for(var k in p){
                if(e[0].periodo == p[k].id){
                    dropdown += '<option selected="selected" value="'+p[k].id+'">'+p[k].nome+'</option>';
                } else {
                    dropdown += '<option value="'+p[k].id+'">'+p[k].nome+'</option>';
                }
            }
            $('#periodo2').html(dropdown); // adiciona à select#períodos as tags para listagem dos períodos
        });

        //função onLoad para listar todos os campus disponíveis e imprimir na página
        var requestCampus = $.ajax({
            method: "get",
            url:"../../app/public/campus", // chama a rota para retornar os campus disponíveis
            dataType: "json"
        });
        requestCampus.done(function (c) {
            var dropCamp = '<option selected value="0">Todos os campus</option>';
            for(var k in c){
                if(e[0].campus == c[k].id) {
                    dropCamp += '<option selected="selected" value="' + c[k].id + '">' + c[k].nome + '</option>';
                } else {
                    dropCamp += '<option value="' + c[k].id + '">' + c[k].nome + '</option>';
                }
            }
            $('#campus2').html(dropCamp); // adiciona à select#campus as tags para listagem dos campus
        });

        //função onLoad para listar todas as categorias disponíveis e imprimir na página
        var requestCategorias = $.ajax({
            method: "get",
            url:"../../app/public/categorias", // chama a rota para retornar as categorias disponíveis
            dataType: "json"
        });
        requestCategorias.done(function (s) {
            var categoria = '';
            for(var k in s){
                if(e[0].categoria == s[k].id) {
                    categoria += '<option selected="selected" value="' + s[k].id + '">' + s[k].nome + '</option>';
                } else {
                    categoria += '<option value="' + s[k].id + '">' + s[k].nome + '</option>';
                }
            }
            $('#categoria2').html(categoria); // adiciona à select#categoria as tags para listagem das categorias
        });

        $('#dataini2').val(e[0].di);
        $('#datafim2').val(e[0].df);
        $('#local2').val(e[0].local);
        $('#url2').val(e[0].url);
        $('#desc2').val(e[0].desc);
    });
}

// deletar evento
function deleta(cod) {
    $('#cod_evento').val(cod);
}

function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('id');
    //$(location).attr("href", "index.php");
}