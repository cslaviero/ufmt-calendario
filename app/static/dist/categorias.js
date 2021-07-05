$(function () {
	$("#cor").pickAColor();
	$("#cor input").on("change", function () {
		$("#cor2").val($(this).val());
	});
});

setTimeout(function () {
	$('#msg').fadeToggle(3300);//elemento de msg
}, 2000);

$('#dataTables-example').DataTable({
	responsive: true
});

var url = document.URL;
var n = url.length;

if (url[n-1] == 'F') {
	$('#ins_categoria').modal('show');
}

