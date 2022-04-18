function TelAdjust(){

    var inputTelefono = document.getElementById('telefono');
    var telValue = inputTelefono.value;
    var stepOne = telValue.replace(/[^0-9]/g, '');

    if (stepOne.length == 11){
        var stepTwo = stepOne.replace(/^(\d{2})(\d{3})(\d{3})(\d{3})/, "+$1 $2 $3 $4" );
        inputTelefono.value = stepTwo;
    } else if (stepOne.length == 9){
        stepOne = "34" + stepOne;
        var stepTwo = stepOne.replace(/^(\d{2})(\d{3})(\d{3})(\d{3})/, "+$1 $2 $3 $4" );
        inputTelefono.value = stepTwo;
    } else if (stepOne.length == 13){
        var stepTwo = stepOne.replace(/^(\d{2})(\d{2})(\d{1})(\d{4})(\d{4})/, "+$1 $2 $3 $4 $5" );
        inputTelefono.value = stepTwo;
    }
}