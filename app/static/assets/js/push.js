if('serviceWorker' in navigator){ // verifica se o serviço firebase já foi iniciado no navegador
    navigator.serviceWorker.register('./firebase-messaging-sw.js') // chama o js para incorporar o serviço
        .then(registration => {
        const messaging = firebase.messaging(); // promise para retornar o código de registro do usuário
    let userToken = null;

    messaging.requestPermission() // após a permissão, gerar um token para registro ao serviço
        .then(function() {
            return messaging.getToken()
        })
        .then(function(currentToken) {
            //console.log(currentToken);
            userToken = currentToken;

            $.ajax({
                url: 'app/public/push/clientes', // chama a API para enviar ao Firebase o token do usuário e inscrevê-lo ao serviço
                data: {token: userToken}, // envia o token
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    //console.log(response);
                }
            });
        });

    // sempre que um novo token é gerado, atualizar no serviço
    messaging.onTokenRefresh(function () {
        messaging.getToken().then(function (refreshedToken) {
            //console.log(currentToken);
            userToken = currentToken;

            $.ajax({
                url: 'app/public/push/clientes', // chama a API para enviar ao Firebase o token do usuário e inscrevê-lo ao serviço
                data: {token: userToken},
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    //console.log(response);
                }
            });
        });
    });
})
}