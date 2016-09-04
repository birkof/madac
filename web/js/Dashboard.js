'use strict';

var Dashboard = function () {
    this.widthContainer = $('.width.value');
    this.lengthContainer = $('.length.value');
    this.heightContainer = $('.height.value');
    this.volumeContainer = $('.volume.value');
    this.connectionStatus = $('.connection.status');
};

Dashboard.prototype.setMeasurements = function (width, length, height) {
    this.widthContainer.text(width);
    this.lengthContainer.text(length);
    this.heightContainer.text(height);
    this.volumeContainer.text(this.computeVolume(width, length, height));
};

Dashboard.prototype.computeVolume = function (width, length, height) {
    return width * length * height;
};

Dashboard.prototype.computeMetrics = function (sensorData) {
    var initialLength = 146;
    var initialWidth = 98;
    var initialHeight = 99;

    var aux = sensorData.sens_length_2;
    sensorData.sens_length_2 = sensorData.sens_width_1;
    sensorData.sens_width_1 = aux;

    var length = initialLength - sensorData.sens_length_1 - sensorData.sens_length_2;
    var width = initialWidth - sensorData.sens_width_1 - sensorData.sens_width_2;
    var height = initialHeight - sensorData.sens_height_1;

    length = Math.max(length, width);
    width = Math.min(length, width);

    return {
        length: length,
        width: width,
        height: height
    };
};

Dashboard.prototype.setConnectionStatus = function (status) {
    if (status) {
        this.connectionStatus.removeClass('red').addClass('green').text('Connected');
        return;
    }
    this.connectionStatus.removeClass('green').addClass('red').text('Disconnected');
}
