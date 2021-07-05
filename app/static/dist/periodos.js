$(function () {

});

setTimeout(function () {
    $('#msg').fadeToggle(3300);//elemento de msg
}, 2000);

// ativar select de importação dos periodos
function ativa_imp() {
    if ($('#importar').is(':checked')) {
        $('#periodo').attr("disabled", false);
        $('#periodo').val('1');
    }else{
        $('#periodo').attr("disabled", true);
        $('#periodo').val('0');
    }
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

function setDatepicker(idpicker){

    $('#datetimepicker3'+idpicker).datetimepicker({
        language: 'pt-BR',
        format: 'DD/MM/YYYY HH:mm:ss'
    });
    $('#datetimepicker4'+idpicker).datetimepicker({
        language: 'pt-BR',
        format: 'DD/MM/YYYY HH:mm:ss'
    });

}

var url = document.URL;
var n = url.length;

if (url[n-1] == 'F') {
    $('#ins_periodo').modal('show');
}
