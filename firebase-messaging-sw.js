importScripts('https://www.gstatic.com/firebasejs/5.5.3/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/5.5.3/firebase-messaging.js');

firebase.initializeApp({
    "messagingSenderId": "367824337283"
});

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    return self.registration.showNotification({}, {});
});