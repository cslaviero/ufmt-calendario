importScripts('https://www.gstatic.com/firebasejs/5.5.3/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/5.5.3/firebase-messaging.js');

firebase.initializeApp({
    "messagingSenderId": "367824337283" // id do App criado no serviço do Firebase, copiar através do código fonte disponivel nas configurações do App no Firebase
});

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) { // enviar parâmetros de credenciais ao cabeçalho
    return self.registration.showNotification({}, {}); // obter registro com as notificações pedrões
});