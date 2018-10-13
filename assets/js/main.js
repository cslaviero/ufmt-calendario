$(function(){

    //função onLoad para listar todos os períodos disponíveis e imprimir na página
   var requestPeriodos = $.ajax({
      method: "get",
      url:"app/public/periodos", // chama a rota para retornar os períodos disponíveis
      dataType: "json"
   });
   requestPeriodos.done(function (e) {
        var dropdown = '<a class="btn btn-link btn-lg dropdown-toggle" data-toggle="dropdown" aria-expanded="false" role="button" href="#periodos" onclick="getPrepara('+"'periodo'"+', '+e[0].id+', \''+e[0].nome+'\')" style="margin: 0px auto;color: rgb(0,0,0);text-transform: uppercase;">'+e[0].nome+'</a><div class="dropdown-menu" role="menu">';
        for(var k in e){
            dropdown += '<a class="dropdown-item" role="presentation" href="#periodos" onclick="getPrepara('+"'periodo'"+', '+e[k].id+', \''+e[k].nome+'\')" style="text-transform: uppercase;">'+e[k].nome+'</a>';
        }
        dropdown += '</div>';
        $('#drop-periodos').html(dropdown); // adiciona à div#drop-períodos as tags para listagem dos períodos
   });

    //função onLoad para listar todas as categorias disponíveis e imprimir na página
    var requestCategorias = $.ajax({
        method: "get",
        url:"app/public/categorias", // chama a rota para retornar as categorias disponíveis
        dataType: "json"
    });
    requestCategorias.done(function (e) {
        var button = '<p class="card-text">';
        for(var k in e){
            button += '<a class="badge badge-primary" href="#categorias" onclick="getPrepara('+"'categoria'"+', '+e[k].id+', \''+e[k].nome+'\')" style="padding:8px;margin:4px;background-color:'+e[k].cor+';">'+e[k].nome+'</a>';
        }
        button += '</p>';
        $('#categorias').html(button); // adiciona à div#categorias as tags para listagem das categorias
    });

    //função onLoad para listar todos os campus disponíveis e imprimir na página
    var requestCampus = $.ajax({
        method: "get",
        url:"app/public/campus", // chama a rota para retornar os campus disponíveis
        dataType: "json"
    });
    requestCampus.done(function (e) {
        var dropCamp = '<a class="btn btn-outline-link dropdown-toggle d-inline float-right" data-toggle="dropdown" aria-expanded="false" role="button" href="#campus" onclick="getPrepara('+"'campus'"+', 0, \'\')" style="font-size:12px;">Todos os campus</a><div role="menu" class="dropdown-menu">';
        for(var k in e){
            dropCamp += '<a role="presentation" href="#campus" onclick="getPrepara('+"'campus'"+', '+e[k].id+', \''+e[k].nome+'\')" class="dropdown-item" style="font-size:12px;">'+e[k].nome+'</a>';
        }
        dropCamp += '</div>';
        $('#dropdown-campus').html(dropCamp); // adiciona à div#dropdown-campus as tags para listagem dos campus
    });

    $('#form-busca').submit(function () { // quando o campo de busca for enviado para pesquisa
        getPrepara('busca', $(':input[name=busca]').val(), $(':input[name=busca]').val());
        return false;
    });
});

// função para listar todos os eventos, informando os parâmetros disponíveis
function eventos(cat, camp, prd, b) {
    var requestEventos = $.ajax({
        method: "post", // enviar os parâmetros via POST
        url:"app/public/eventos", // chama a rota para retornar os eventos disponíveis
        data:{
            cat: cat, // id da categoria (caso clicou em algum botão de categoria)
            camp: camp, // id do campus (caso clicou em alguma opção de campus)
            b: b, // termo da busca (caso digitou algo e clicou em buscar)
            prd: prd // id do período (caso clicou em algum período)
        },
        dataType: "json" // retorno em JSON
    });
    requestEventos.done(function (e) { // obtendo resposta
        var lista = ''; // variável para escrever o código que preencherá os eventos na div#accordion-1
        var item = 1; // contador para montar os itens dos accordions separados por mês
        var prd_url = ''; // variável para escrever o código do botão que abre o calendário no formato PDF

        for(var k in e){ // listando o resultado
            var param = e[k]; // recebe os dados
            lista += '<div class="card">\n' +
                '            <div class="card-header" role="tab">\n' +
                '                <h5 class="mb-0">\n' +
                '                    <a data-toggle="collapse" aria-expanded="false" aria-controls="accordion-1 .item-'+item+'" href="div#accordion-1 .item-'+item+'" style="font-size:16px;" class="collapsed">'+k+'</a>\n' + // o índice "k" representa os meses do array
                '                </h5>\n' +
                '            </div>\n' +
                '\n' +
                '            <div class="collapse item-'+item+'" role="tabpanel" data-parent="#accordion-1">\n' +
                '                <div class="card-body">\n' +
                '                    <div class="table-responsive" style="font-size:12px;">\n' +
                '                        <table class="table table-striped table-hover table-sm">\n' +
                '                            <thead>\n' +
                '                                <tr>\n' +
                '                                    <th style="min-width:28px;max-width:65px;">DATA</th>\n' +
                '                                    <th style="min-width:100px;">EVENTO</th>\n' +
                '                                </tr>\n' +
                '                            </thead>\n' +
                '                            <tbody>';
            for(var j in param){ // listando o índice de cada mês, por exemplo: param[JANEIRO].nome
                lista += '<tr>\n' +
                    '       <td style="min-width:28px;max-width:65px;"><a href="evento?id='+param[j].id+'">'+param[j].data_titulo+'</a></td>\n' +
                    '       <td style="min-width:100px;"><a href="evento?id='+param[j].id+'">'+param[j].nome+'</a></td>\n' +
                    '     </tr>';
                prd_url = '<a class="btn btn-danger btn-lg" role="button" href="'+param[j].prd_url+'" target="_blank" style="margin:0px;margin-top:0px;margin-bottom:0px;font-size:16px;">\n' +
                    '           <i class="fa fa-file-pdf-o" style="font-size:20px;margin-right:8px;"></i>\n' +
                    '           Visualize a versão em PDF\n' +
                    '      </a>'; // escrever o código do botão para arquivo em PDF. Sem concatenação.
            }
            lista += '</tbody>\n' +
                '                        </table>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '        </div>';
            item++;
        }
        $('#accordion-1').html(lista); // adicionando as tags da lista para a div#accordion-1
        $('#bot-visualizar').html(prd_url); // adicionando o botão da variavel prd_url para a div#bot-visualizar
    });
}

// função para listar os próximos eventos, informando os parâmetros disponíveis
function prox_eventos(cat, camp, prd, b) {
    var requestProxEventos = $.ajax({
        method: "post",
        url:"app/public/prox_eventos", // chama a rota para retornar os próximos eventos disponíveis
        data:{
            cat: cat,
            camp: camp,
            b: b,
            prd: prd
        },
        dataType: "json"
    });
    requestProxEventos.done(function (e) {
        var list = '';
        for(var k in e){
            if(e[0].cont > 0) {
                if (k != 0) {
                    list += '<hr style="margin-top:0px;margin-bottom:2px;">'; // se o índice não for o primeiro, começar a imprimir a tag hr para separar com uma linha um evento do outro
                }
                list += '<div class="media"><a href="evento?id='+e[k].id+'"><img src="assets/img/icon-calendar.png" class="mr-3" style="width:64px;height:64px;background-color:'+e[k].cor+';" /></a><div class="media-body"><h5><a href="evento?id='+e[k].id+'">' + e[k].data_titulo + '</a></h5><p><a href="evento?id='+e[k].id+'">'+e[k].nome+'</a></p></div></div>';
            } else {
                list = '<div class="media"><div class="media-body"><p>Nenhum evento encontrado</p></div></div>';
            }
        }
        $('#prox-eventos').html(list); // adicionando as tags de list para a div#prox-eventos
    });
}

// função para ativar o modal blockUI e mostrar alguma mensagem
var block = function(mensagem) {
    $.blockUI({
        message: '<h5 style="color: #fff;">'+mensagem+'</h5>',
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
        },
        overlayCSS: {
            backgroundColor: '#000'
        }
    });
}

// função para desativar o modal blockUI
var unblock = function() {
    $.unblockUI();
}