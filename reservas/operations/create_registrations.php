<?php error_reporting(E_ERROR);

require_once("../config/config.php");
require_once('../classes/class_procedure.php');
require_once('../classes//class_registration.php');

////////////////////////////////////

function getSelectedProcedures(array $procedures){

    $i=0;
    foreach($procedures as $key => $value){
        
        if ($value === true) {

        $selectedProcedures[$i] =  new Procedure($key, $procedures);
        $i++;
        }
    }
    
    return $selectedProcedures;

} // Detecta quais procedimentos foram selecionados no formulario

////////////////////////////////////

// RECEBENDO DADOS DO JAVASCRIPT

$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr);
$form = $jsonObj->formDatas;


//-->COLETANDO DADOS

    $operation = $form[0]->operation;
    $id =        $form[0]->id ?? 0;


    $person = [
        'nombre'    => $form[0]->nombre,
        'apellido'  => $form[0]->apellido,
        'nif'       => strtoupper(str_replace(" ","",$form[0]->nif)),
        'email'     => strtolower(str_replace(" ","",$form[0]->email)),
        'telefono'  => $form[0]->telefono,
    ];

    $availability = [
        'manana' => boolval($form[0]->dispManana), 
        'tarde'  => boolval($form[0]->dispTarde),
    ];

    $procedures =    [
        'Profesional'         => 'Tamara',
        'CondicionBasica'     => $form[0]->condicionBasica,   
        'CondicionEspecifica' => $form[0]->condicionEspecifica,   
        'MicroCejas'          => boolval($form[0]->procCejas), 
        'MicroLabios'         => boolval($form[0]->procLabios), 
        'MicroEyeliner'       => boolval($form[0]->procEyeliner),

    ]; $selectedProcedures = getSelectedProcedures($procedures);

    $period =       [
        'ano'       => $form[0]->periodoAno, 
        'mesNumero' => $form[0]->periodoMesNumero,
        'mesNombre' => $form[0]->periodoMesNombre,

    ];

    $payment =      [
        'paymentStatus' => "waiting",
        'clientSecret'  => "waiting",
        'paymentIntent' => "waiting",
    ]; 



//--> CRIANDO REGISTROS

    $i=0; foreach($procedures as $key => $value){
    
        if ($value === true) {
            $registers[$i] =  new Registration($person,$availability,$selectedProcedures[$i],$period,$payment);
            $i++;


        }
    }

///////////////////////////////////

$jsonData = json_encode($registers, JSON_PRETTY_PRINT);
echo($jsonData);
?>