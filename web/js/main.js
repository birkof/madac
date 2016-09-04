function writeToScreen(message) {
    var output = document.getElementById("output");
    var pre = document.createElement("p");
    pre.style.wordWrap = "break-word";
    pre.innerHTML = message;
    $(output).prepend(pre);
}
var socketConectionUrl = "ws://192.168.160.75:8080/";
var dashboard = new Dashboard();
var measurementList = new MeasurementsList();
// var clientSocket = new ClientSocket("ws://echo.websocket.org/");
var clientSocket = new ClientSocket(socketConectionUrl);

clientSocket.registerEventListener(ClientSocket.EVENT_ON_MESSAGE, function (event) {
    writeToScreen('<span style="color: blue;">RESPONSE: ' + event.data + '</span>');
    var data = JSON.parse(event.data);
    dashboard.setMeasurements(data.width, data.length, data.height);
    var listItem = new MeasurementItem(
        $('#barcode').val(),
        data.width,
        data.height,
        data.length,
        dashboard.computeVolume(data.width, data.length, data.height)
    );
    measurementList.prependElement(listItem);
    $.post({
        url: '/measurement',
        data: JSON.stringify({
            ean: $('#barcode').val(),
            width: data.width,
            height: data.height,
            length: data.length
        })
    });
    $('#barcode').val('');
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
    isError = false;
    do {
        try {
            clientSocket = new ClientSocket(socketConectionUrl);
            isError = false;
        } catch (msg) {
            isError = true;
        }
    } while (isError);
});

$('#barcode-form').on('submit', function (e) {
    e.preventDefault();
    setTimeout(function(){
        clientSocket.sendMessage("GET_MEASUREMENTS");
    }, 1000);
});

$('.measurements.log').slimScroll({height: '600px'});
