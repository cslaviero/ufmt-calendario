$(function () {

});

setTimeout(function () {
	$('#msg').fadeToggle(3300);//elemento de msg
}, 2000);

$('#dataTables-example').DataTable({
	responsive: true
});

$('#datetimepicker1').datetimepicker({
	locale: 'pt-br',
	format: 'DD/MM/YYYY HH:mm:ss'
});
$('#datetimepicker2').datetimepicker({
	locale: 'pt-br',
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
    $('#ins_evento').modal('show');
}