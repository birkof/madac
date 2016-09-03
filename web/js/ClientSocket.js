var ClientSocket = function(url) {
  this.websocket = new WebSocket(url);
  var _this = this;

  this.websocket.onopen = function (event) {
    _this.eventListeners[ClientSocket.EVENT_ON_OPEN].forEach(function(listener){
      listener(event);
    });
  };

  this.websocket.onclose = function (event) {
    _this.eventListeners[ClientSocket.EVENT_ON_CLOSE].forEach(function(listener){
      listener(event);
    });
  };

  this.websocket.onmessage = function (event) {
    _this.eventListeners[ClientSocket.EVENT_ON_MESSAGE].forEach(function(listener){
      listener(event);
    });
  };

  this.websocket.onerror = function (event) {
    _this.eventListeners[ClientSocket.EVENT_ON_ERROR].forEach(function(listener){
      listener(event);
    });
  };
};

ClientSocket.prototype.eventListeners = {
  "on_open": [],
  "on_close": [],
  "on_message": [],
  "on_error": []
};
ClientSocket.prototype.websocket = null;

ClientSocket.EVENT_ON_OPEN = 'on_open';
ClientSocket.EVENT_ON_CLOSE = 'on_close';
ClientSocket.EVENT_ON_MESSAGE = 'on_message';
ClientSocket.EVENT_ON_ERROR = 'on_error';

ClientSocket.prototype.registerEventListener = function (event, listenerCallback) {
  this.eventListeners[event].push(listenerCallback);
};

ClientSocket.prototype.sendMessage = function(message) {
  this.websocket.send(message);
};
