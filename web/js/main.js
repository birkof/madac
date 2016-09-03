function writeToScreen(message) {
    var output = document.getElementById("output");
    var pre = document.createElement("p");
    pre.style.wordWrap = "break-word";
    pre.innerHTML = message;
    $(output).prepend(pre);
}

var dashboard = new Dashboard();
var clientSocket = new ClientSocket("ws://echo.websocket.org/");

clientSocket.registerEventListener(ClientSocket.EVENT_ON_MESSAGE, function (event) {
    writeToScreen('<span style="color: blue;">RESPONSE: ' + event.data + '</span>');
    //AJAX
});

clientSocket.registerEventListener(ClientSocket.EVENT_ON_OPEN, function () {
    writeToScreen("CONNECTED");
    dashboard.setConnectionStatus(true);
});
clientSocket.registerEventListener(ClientSocket.EVENT_ON_ERROR, function (event) {
    writeToScreen('<span style="color: red;">ERROR:</span> ' + event.data);
});
clientSocket.registerEventListener(ClientSocket.EVENT_ON_CLOSE, function () {
    writeToScreen("DISCONNECTED");
    dashboard.setConnectionStatus(false);
});

$('#barcode-form').on('submit', function (e) {
    e.preventDefault();
    setTimeout(function(){
        clientSocket.sendMessage("GET_MEASUREMENTS");
        $('#barcode').val('');
    }, 1000);
});

$('.measurements.log').slimScroll({height: '400px'});
