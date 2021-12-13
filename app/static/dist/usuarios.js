$(function () {

});

setTimeout(function () {
	$('#msg').fadeToggle(3300);//elemento de msg
}, 2000);

$('#dataTables-example').DataTable({
	responsive: true
});

var url = document.URL;
var n = url.length;

if (url[n-1] == '1'){
	$('#perfil').modal('show');
}else if (url[n-1] == 'F') {
	$('#ins_usuario').modal('show');
}