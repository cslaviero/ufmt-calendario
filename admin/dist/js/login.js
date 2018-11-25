$(function () {

    $('#login').submit(function () {
        var form = $(this).serialize();
        var request = $.ajax({
            method: "post",
            url:"../../app/public/login", // chama a rota para fazer login
            dataType: "json",
            data: form
        });
        request.done(function (e) {
            if (e.success == true){
                localStorage.setItem('token', e.token);
                localStorage.setItem('id', e.id);
                //console.log(e.token);
                $(location).attr("href", "inicio.php");
            } else {
                $('#msg').html('<div class="alert alert-danger text-center">\n' +
                    '<strong>'+e.msg+'</strong>\n' +
                    '</div>');
            }
        });
        request.fail(function (e) {
            $('#msg').html('<div class="alert alert-danger text-center">\n' +
                '<strong>Erro ao efetuar login!</strong>\n' +
                '</div>');
        });

        return false;
    });
});