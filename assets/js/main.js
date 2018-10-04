$(function(){
   var requestPeriodos = $.ajax({
      method: "get",
      url:"app/public/periodos",
      dataType: "json"
   });
   requestPeriodos.done(function (e) {
        var dropdown = '<a class="btn btn-link btn-lg dropdown-toggle" data-toggle="dropdown" aria-expanded="false" role="button" href="#periodos" onclick="getPrepara('+"'periodo'"+', '+e[0].id+', \''+e[0].nome+'\')" style="margin: 0px auto;margin-right: auto;margin-left: auto;margin-top: 0px;margin-bottom: 0px;color: rgb(0,0,0);">'+e[0].nome+'</a><div class="dropdown-menu" role="menu">';
        for(var k in e){
            dropdown += '<a class="dropdown-item" role="presentation" href="#periodos" onclick="getPrepara('+"'periodo'"+', '+e[k].id+', \''+e[k].nome+'\')">'+e[k].nome+'</a>';
        }
        dropdown += '</div>';
        $('#drop-periodos').html(dropdown);
   });

    var requestCategorias = $.ajax({
        method: "get",
        url:"app/public/categorias",
        dataType: "json"
    });
    requestCategorias.done(function (e) {
        var button = '<p class="card-text">';
        for(var k in e){
            button += '<a class="badge badge-primary" href="#categorias" onclick="getPrepara('+"'categoria'"+', '+e[k].id+', \''+e[k].nome+'\')" style="padding:8px;margin:4px;background-color:'+e[k].cor+';">'+e[k].nome+'</a>';
        }
        button += '</p>';
        $('#categorias').html(button);
    });

    var requestCampus = $.ajax({
        method: "get",
        url:"app/public/campus",
        dataType: "json"
    });
    requestCampus.done(function (e) {
        var dropCamp = '<a class="btn btn-outline-link dropdown-toggle d-inline float-right" data-toggle="dropdown" aria-expanded="false" role="button" href="#campus" onclick="getPrepara('+"'campus'"+', 0, \'\')" style="font-size:12px;">Todos os campus</a><div role="menu" class="dropdown-menu">';
        for(var k in e){
            dropCamp += '<a role="presentation" href="#campus" onclick="getPrepara('+"'campus'"+', '+e[k].id+', \''+e[k].nome+'\')" class="dropdown-item" style="font-size:12px;">'+e[k].nome+'</a>';
        }
        dropCamp += '</div>';
        $('#dropdown-campus').html(dropCamp);
    });

    $('#form-busca').submit(function () {
        getPrepara('busca', $(':input[name=busca]').val(), $(':input[name=busca]').val());
        return false;
    });
});

function eventos(cat, camp, prd, b) {
    var requestEventos = $.ajax({
        method: "post",
        url:"app/public/eventos",
        data:{
            cat: cat,
            camp: camp,
            b: b,
            prd: prd
        },
        dataType: "json"
    });
    requestEventos.done(function (e) {
        var lista = '';
        var item = 1;
        var prd_url = '';

        for(var k in e){
            var param = e[k];
            lista += '<div class="card">\n' +
                '            <div class="card-header" role="tab">\n' +
                '                <h5 class="mb-0">\n' +
                '                    <a data-toggle="collapse" aria-expanded="false" aria-controls="accordion-1 .item-'+item+'" href="div#accordion-1 .item-'+item+'" style="font-size:16px;" class="collapsed">'+k+'</a>\n' +
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
            for(var j in param){
                lista += '<tr>\n' +
                    '       <td style="min-width:28px;max-width:65px;"><a href="evento?id='+param[j].id+'">'+param[j].data_titulo+'</a></td>\n' +
                    '       <td style="min-width:100px;"><a href="evento?id='+param[j].id+'">'+param[j].nome+'</a></td>\n' +
                    '     </tr>';
                prd_url = '<a class="btn btn-danger btn-lg" role="button" href="'+param[j].prd_url+'" target="_blank" style="margin:0px;margin-top:0px;margin-bottom:0px;font-size:16px;">\n' +
                    '           <i class="fa fa-file-pdf-o" style="font-size:20px;margin-right:8px;"></i>\n' +
                    '           Visualize a versão em PDF\n' +
                    '      </a>';
            }
            lista += '</tbody>\n' +
                '                        </table>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '        </div>';
            item++;
        }
        $('#accordion-1').html(lista);
        $('#bot-visualizar').html(prd_url);
    });
}

function prox_eventos(cat, camp, prd, b) {
    var requestProxEventos = $.ajax({
        method: "post",
        url:"app/public/prox_eventos",
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
                    list += '<hr style="margin-top:0px;margin-bottom:2px;">';
                }
                list += '<div class="media"><a href="evento?id='+e[k].id+'"><img src="assets/img/icon-calendar.png" class="mr-3" style="width:64px;height:64px;background-color:'+e[k].cor+';" /></a><div class="media-body"><h5><a href="evento?id='+e[k].id+'">' + e[k].data_titulo + '</a></h5><p><a href="evento?id='+e[k].id+'">'+e[k].nome+'</a></p></div></div>';
            } else {
                list = '<div class="media"><div class="media-body"><p>Nenhum evento encontrado</p></div></div>';
            }
        }
        $('#prox-eventos').html(list);
    });
}

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

var unblock = function() {
    $.unblockUI();
}