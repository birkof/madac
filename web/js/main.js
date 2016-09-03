function writeToScreen(message) {
    var output = document.getElementById("output");
    var pre = document.createElement("p");
    pre.style.wordWrap = "break-word";
    pre.innerHTML = message;
    output.appendChild(pre);
}

var clientSocket = new ClientSocket("ws://echo.websocket.org/");

clientSocket.registerEventListener(ClientSocket.EVENT_ON_MESSAGE, function(event) {
    writeToScreen('<span style="color: blue;">RESPONSE: ' + event.data + '</span>');
    //AJAX
});

clientSocket.registerEventListener(ClientSocket.EVENT_ON_OPEN, function() {
    writeToScreen("CONNECTED");
});
clientSocket.registerEventListener(ClientSocket.EVENT_ON_ERROR, function(event) {
    writeToScreen('<span style="color: red;">ERROR:</span> ' + event.data);
});
clientSocket.registerEventListener(ClientSocket.EVENT_ON_CLOSE, function() {
    writeToScreen("DISCONNECTED");
});


$('#barcode-form').on('submit', function(e) {
    e.preventDefault();
    clientSocket.sendMessage("GET_MEASUREMENTS");

});