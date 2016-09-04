var MeasurementItem = function (barcode, width, height, length, volume) {
    this.$element = $(
        '<tr class="measurement"> ' +
        '<td class="barcode content"> ' + barcode + '</td> ' +
        '<td class="width content"> ' + width + '</td> ' +
        '<td class="length content"> ' + length + '</td> ' +
        '<td class="length content"> ' + height + '</td> ' +
        '<td class="length content"> ' + volume + '</td> ' +
        '</tr>'
    );
};

MeasurementItem.prototype.$element = null;
MeasurementItem.prototype.getElement = function () {
    return this.$element;
};