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

            // faz nada

        } else {
            logout();
        }
    });
    requestLogin.fail(function (e) {
        logout();
    });

    // Inserir usuários
    $('#insere').submit(function () {
        var form = $(this).serialize();
        var requestInsere = $.ajax({
            method: "post",
            url:"../../app/public/inserir/usuario", // chama a rota para inserir os usuarios disponíveis
            dataType: "json",
            data: form
        });
        requestInsere.done(function (e) {
            if (e.sucesso == 1){
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Usuário inserido com Sucesso!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            } else {
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Falha ao inserir usuário!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            }
        });
        requestInsere.fail(function (e) {
            $('#mensagem').modal('show');
            $('#msg').html('<div class="alert alert-success text-center">\n' +
                '<strong>Erro ao inserir usuário!</strong>\n' +
                '</div>');
        });

        $('#insere input').val("");

        return false;
    });

    $('#altera').submit(function () {
        var form2 = $(this).serialize();
        var requestAltera = $.ajax({
            method: "post",
            url:"../../app/public/alterar/usuario", // chama a rota para alterar os dados do usuario
            dataType: "json",
            data: form2
        });
        requestAltera.done(function (e) {
            if (e.sucesso == 1){
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Usuário alterado com Sucesso!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            } else {
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Falha ao alterar usuário!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            }
        });
        requestAltera.fail(function (e) {
            $('#mensagem').modal('show');
            $('#msg').html('<div class="alert alert-success text-center">\n' +
                '<strong>Erro ao alterar usuário!</strong>\n' +
                '</div>');
        });

        $('#altera input').val("");

        return false;
    });

    $('#deletar').submit(function () {
        var form3 = $(this).serialize();
        var requestDeleta = $.ajax({
            method: "post",
            url:"../../app/public/deletar/usuario", // chama a rota para alterar os dados do periodo
            dataType: "json",
            data: form3
        });
        requestDeleta.done(function (e) {
            if (e.sucesso == 1){
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Usuário excluído com Sucesso!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            } else {
                $('#msg').html('<div class="alert alert-success text-center">\n' +
                    '<strong>Falha ao excluir usuário!</strong>\n' +
                    '</div>');
                $('#mensagem').modal('show');
            }
        });
        requestDeleta.fail(function (e) {
            $('#mensagem').modal('show');
            $('#msg').html('<div class="alert alert-success text-center">\n' +
                '<strong>Erro ao excluir usuário!</strong>\n' +
                '</div>');
        });

        $('#deletar input').val("");

        return false;
    });

    // carregar modal quando chamado pelo #insere<função>
    if(location.search.indexOf('insere') != -1){
        $('#ins_usuario').modal('show');
    }

    // carregar modal quando chamado pelo #perfil<função>
    if(location.search.indexOf('perfil') != -1){
        $('#perfil').modal('show');
        altera_perfil(localStorage.getItem('id'));
    }
});

// alterar evento
function altera(cod) {
    var request = $.ajax({
        method: "get",
        url:"../../app/public/listar/usuario", // chama a rota para retornar os dados do usuário
        dataType: "json",
        data: {
            id: cod
        }
    });
    request.done(function (e) {
        for(var k in e) {
            var param = e[k];

            for (var j in param) {
                // Incluindo dados do usuário nos elementos dos inputs
                $('#id2').val(param[j].id); // campo id
                $('#nome2').val(param[j].nome); // campo nome
                $('#email2').val(param[j].email); // campo email
                $('#usuario2').val(param[j].usuario); // campo usuario
                $('#senha2').val(param[j].senha); // campo senha

                if(k == "Campus") {
                    if (param[j].inserir == "0") {
                        $('#inscampus2').prop("checked", false);
                    }
                    if (param[j].alterar == "0") {
                        $('#altcampus2').prop("checked", false);
                    }
                    if (param[j].deletar == "0") {
                        $('#delcampus2').prop("checked", false);
                    }
                }
                if(k == "Categorias") {
                    if (param[j].inserir == "0") {
                        $('#inscat2').prop("checked", false);
                    }
                    if (param[j].alterar == "0") {
                        $('#altcat2').prop("checked", false);
                    }
                    if (param[j].deletar == "0") {
                        $('#delcat2').prop("checked", false);
                    }
                }
                if(k == "Eventos") {
                    if (param[j].inserir == "0") {
                        $('#inseve2').prop("checked", false);
                    }
                    if (param[j].alterar == "0") {
                        $('#alteve2').prop("checked", false);
                    }
                    if (param[j].deletar == "0") {
                        $('#deleve2').prop("checked", false);
                    }
                }
                if(k == "Períodos") {
                    if (param[j].inserir == "0") {
                        $('#insprd2').prop("checked", false);
                    }
                    if (param[j].alterar == "0") {
                        $('#altprd2').prop("checked", false);
                    }
                    if (param[j].deletar == "0") {
                        $('#delprd2').prop("checked", false);
                    }
                }
                if(k == "Usuários") {
                    if (param[j].inserir == "0") {
                        $('#insusu2').prop("checked", false);
                    }
                    if (param[j].alterar == "0") {
                        $('#altusu2').prop("checked", false);
                    }
                    if (param[j].deletar == "0") {
                        $('#delusu2').prop("checked", false);
                    }
                }
            }
        }
    });
}

// alterar perfil
function altera_perfil(cod) {
    var request = $.ajax({
        method: "get",
        url:"../../app/public/listar/usuario", // chama a rota para retornar os dados do usuário
        dataType: "json",
        data: {
            id: cod
        }
    });
    request.done(function (e) {
        for(var k in e) {
            var param = e[k];

            for (var j in param) {
                // Incluindo dados do usuário nos elementos dos inputs
                $('#id3').val(param[j].id); // campo id
                $('#nome3').val(param[j].nome); // campo nome
                $('#email3').val(param[j].email); // campo email
                $('#usuario3').val(param[j].usuario); // campo usuario
                $('#senha3').val(param[j].senha); // campo senha

                if(k == "Campus") {
                    if (param[j].inserir == "0") {
                        $('#inscampus3').prop("checked", false);
                    }
                    if (param[j].alterar == "0") {
                        $('#altcampus3').prop("checked", false);
                    }
                    if (param[j].deletar == "0") {
                        $('#delcampus3').prop("checked", false);
                    }
                }
                if(k == "Categorias") {
                    if (param[j].inserir == "0") {
                        $('#inscat3').prop("checked", false);
                    }
                    if (param[j].alterar == "0") {
                        $('#altcat3').prop("checked", false);
                    }
                    if (param[j].deletar == "0") {
                        $('#delcat3').prop("checked", false);
                    }
                }
                if(k == "Eventos") {
                    if (param[j].inserir == "0") {
                        $('#inseve3').prop("checked", false);
                    }
                    if (param[j].alterar == "0") {
                        $('#alteve3').prop("checked", false);
                    }
                    if (param[j].deletar == "0") {
                        $('#deleve3').prop("checked", false);
                    }
                }
                if(k == "Períodos") {
                    if (param[j].inserir == "0") {
                        $('#insprd3').prop("checked", false);
                    }
                    if (param[j].alterar == "0") {
                        $('#altprd3').prop("checked", false);
                    }
                    if (param[j].deletar == "0") {
                        $('#delprd3').prop("checked", false);
                    }
                }
                if(k == "Usuários") {
                    if (param[j].inserir == "0") {
                        $('#insusu3').prop("checked", false);
                    }
                    if (param[j].alterar == "0") {
                        $('#altusu3').prop("checked", false);
                    }
                    if (param[j].deletar == "0") {
                        $('#delusu3').prop("checked", false);
                    }
                }
            }
        }
    });
}

// deletar usuario
function deleta(cod) {
    $('#cod_usuario').val(cod);
}

function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('id');
    $(location).attr("href", "index.php");
}