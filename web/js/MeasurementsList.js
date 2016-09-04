var MeasurementsList = function(){
    this.$element = $('#measurements-list table tbody');
};

MeasurementsList.prototype.$element = null;

MeasurementsList.prototype.prependElement = function(listElement) {
    this.$element.prepend(listElement.getElement());
};