var MeasurementsList = function(){
    this.$element = $('#measurements-list');
};

MeasurementsList.prototype.$element = null;

MeasurementsList.prototype.prependElement = function(listElement) {
    this.$element.prepend(listElement.getElement());
};