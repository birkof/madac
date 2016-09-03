var MeasurementItem = function (barcode, width, height, length, volume) {
    this.$element = $(
        '<div class="measurement item"> ' +
            '<img class="left floated image" src="./images/box.png"> ' +
            '<div class="left floated barcode content"> ' +
                '<div class="header">' + barcode + '</div> ' +
                'Barcode ' +
            '</div> ' +
            '<div class="left floated width content"> ' +
                '<div class="header">' + width + ' cm</div> ' +
                'Width ' +
            '</div> ' +
            '<div class="left floated length content"> ' +
                '<div class="header">' + length + ' cm</div> ' +
                'Length ' +
            '</div> ' +
            '<div class="left floated height content"> ' +
                '<div class="header">' + height + ' cm</div> ' +
                'Height ' +
            '</div> ' +
            '<div class="left floated volume content"> ' +
                '<div class="header">' + volume + ' cm </div> ' +
                'Volume ' +
            '</div> ' +
        '</div>'
    );
};

MeasurementItem.prototype.$element = null;
MeasurementItem.prototype.getElement = function () {
    return this.$element;
};