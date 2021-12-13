$(function () {

});

setTimeout(function () {
	$('#msg').fadeToggle(3300);//elemento de msg
}, 2000);

$('#dataTables-example').DataTable({
	responsive: true
});

function setDatepicker(idpicker){
	$('#datetimepicker1'+idpicker).datetimepicker({
		language: 'pt-BR',
		format: 'DD/MM/YYYY HH:mm:ss'
	});
	$('#datetimepicker2'+idpicker).datetimepicker({
		language: 'pt-BR',
		format: 'DD/MM/YYYY HH:mm:ss'
	});
}