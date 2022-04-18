(function SumValues() {

    var procCejas = document.getElementById('ProcCejas');
    var procLabios = document.getElementById('ProcLabios');
    var procEyeliner = document.getElementById('ProcEyeliner');
    var valorReserva = document.getElementById('valorReserva');


    // procCejas.checked = false;
    // procLabios.checked = false;
    // procEyeliner.checked = false;

    var cejas = 0;
    var labios = 0;
    var Eyeliner = 0;

    procCejas.addEventListener('change', validaProcCejas);
    function validaProcCejas()
    {
    if(procCejas.checked){
        cejas = +valorReserva.value;
        sumProceduresValues();
    } else{
        cejas = 0;
        sumProceduresValues();
    }
    }

    procLabios.addEventListener('change', validaProcLabios);
    function validaProcLabios()
    {
    if(procLabios.checked){
        labios = +valorReserva.value;
        sumProceduresValues();
    } else{
        labios = 0;
        sumProceduresValues();
    }
    }

    procEyeliner.addEventListener('change', validaProcEyeliner);
    function validaProcEyeliner()
    {
    if(procEyeliner.checked){
        Eyeliner = +valorReserva.value;
        sumProceduresValues();
    } else{
        Eyeliner = 0;
        sumProceduresValues();
    }
    }


    function sumProceduresValues()
    {
    var sumValues = cejas + labios + Eyeliner;
    
    if (sumValues != 0) {
        document.getElementById('InformativoValor').value = 'Valor total de la reserva ' + sumValues + ',00 EUR';
    } else {
        document.getElementById('InformativoValor').value = 'Elija los procedimientos para calcular el valor';

    }

    }

    validaProcCejas();
    validaProcLabios();
    validaProcEyeliner();

})();
    
