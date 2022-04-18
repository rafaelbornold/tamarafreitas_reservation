<?php error_reporting(E_ERROR);

require_once("../config/config.php");
require_once('../classes/class_plazas.php');

////////////////////////////////////
////////////////////////////////////

// RECEBENDO DADOS DO JAVASCRIPT

$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr);
$form = $jsonObj->formDatas;


function countSelectedProcedures(array $procedures){

    $i=0;
    foreach($procedures as $key => $value){
        
        if ($value === true) {
        $i++;
        }
    }
    
    return $i;

} // Detecta quais procedimentos foram selecionados no formulario


//-->COLETANDO DADOS

    $procedures =    [
        'MicroCejas'          => boolval($form[0]->procCejas), 
        'MicroLabios'         => boolval($form[0]->procLabios), 
        'MicroEyeliner'       => boolval($form[0]->procEyeliner),

    ]; $countSelectedProcedures = countSelectedProcedures($procedures);

    $period = [
        'ano'             => $form[0]->periodoAno, 
        'mesNumero'       => $form[0]->periodoMesNumero,
        'mesNombre'       => $form[0]->periodoMesNombre,
        'condicionBasica' => $form[0]->condicionBasica,   
    ];

//--> VERIFICANDO PLAZAS

    $plazas = new Plazas($period['condicionBasica'], $period['mesNumero'], $period['ano'], $countSelectedProcedures);

///////////////////////////////////

$jsonData = json_encode($plazas, JSON_PRETTY_PRINT);
echo($jsonData);

?>