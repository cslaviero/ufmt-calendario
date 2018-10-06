// aqui voce informa o Client ID da sua app
var CLIENT_ID = '367824337283-og7td64nbimbicgiov2evn5uddhosm6f.apps.googleusercontent.com';
// permissoes que serao concedidas ao usuario que esta logando no calendario
var SCOPES = "https://www.googleapis.com/auth/calendar"; // leitura e escrita ou calendar.readonly para apenas leitura
var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];

// esta funcao sera chamada logo apos a carga do script calendar em https://apis.google.com/js/api.js
function handleClientLoad() {
    gapi.load('client:auth2', initClient);
}

function initClient() {
    gapi.client.init({
        discoveryDocs: DISCOVERY_DOCS,
        clientId: CLIENT_ID,
        scope: SCOPES
    }).then(function () {
        //authorizeButton.onclick = handleAuthClick;
        //signoutButton.onclick = handleSignoutClick;
    });
}

// conecta
function handleAuthClick(event) {
    gapi.auth2.getAuthInstance().signIn();
}

// desconecta
function handleSignoutClick(event) {
    gapi.auth2.getAuthInstance().signOut();
}

function criarEvento(titulo, localizacao, texto, horaInicio, horaFim){

    var event = {
        'summary': titulo,
        'location': localizacao,
        'description': texto,
        'start': {
            'dateTime': horaInicio
        },
        'end': {
            'dateTime': horaFim
        },
        'reminders': {
            'useDefault': false,
            'overrides': [
                {'method': 'email', 'minutes': 24 * 60},
                {'method': 'popup', 'minutes': 10}
            ]
        }
    };

    var request = gapi.client.calendar.events.insert({
        'calendarId': 'primary',
        'resource': event
    });

    request.execute(function(event) {
        console.log(event);
    });

}