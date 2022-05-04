<?php error_reporting(E_ERROR);

require_once("../config/config.php");

// RECEBENDO DADOS DO JAVASCRIPT

$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr);
$confirmPaymentResult = $jsonObj->confirmPaymentResult;
$registers = $jsonObj->registers;

function setConnection(){ 
            
    try {
        $connection = new \PDO("mysql:host=".SERVER.";dbname=".DBNAME,USER,PASSWORD);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

    catch(PDOException $e)
        {
        die("ERROR 001 - La conexión con el banco de datos ha fallado: " . $e->getMessage());
        }

    return $connection;
}


//////////////////////////////////////
//////////////////////////////////////
//////////////////////////////////////

$GlobalConnection = setConnection();

//////////////////////////////////////
//////////////////////////////////////
//////////////////////////////////////

function UpdateRegister($confirmPaymentResult, $connection) {

    $sql_update = 
        "UPDATE ".TABLE_RESERVAS." SET 
        `paymentStatus`=        :_paymentStatus,
        `paymentIntent`=        :_paymentIntent,
        `modified`=             NOW()
        WHERE clientSecret = :_clientSecret";
    
    $sql = $sql_update ;

    try {
        $stmt = $connection->prepare($sql);

        $stmt->bindValue(":_paymentStatus", $confirmPaymentResult->paymentIntent->status , \PDO::PARAM_STR);
        $stmt->bindValue(":_paymentIntent", $confirmPaymentResult->paymentIntent->id , \PDO::PARAM_STR);
        $stmt->bindValue(":_clientSecret", $confirmPaymentResult->paymentIntent->client_secret , \PDO::PARAM_STR);

        $stmt->execute();

        http_response_code(201); // created
        return true;
    
    } catch(PDOException $e)
        {
        http_response_code(500); // Internal Server Error
        return ("ERROR 001 UpdateRegister - Internal Server Error: " . $e->getMessage());
        }    

}


function UpdatePlazas($register, $connection) {

    $sql_update = 
        "UPDATE ".TABLE_PLAZAS." SET 
        plazas = plazas -1
        WHERE mesNumero = :_mesNumero AND condicionBasica = :_condicionBasica";
    

    $sql = $sql_update ;

    try {
        $stmt = $connection->prepare($sql);

        $stmt->bindValue(":_mesNumero", $register->mesNumero , \PDO::PARAM_STR);
        $stmt->bindValue(":_condicionBasica", $register->condicionBasica , \PDO::PARAM_STR);

        $stmt->execute();

        http_response_code(200); // OK UPDATED
        return true;
    
    } catch(PDOException $e)
        {
        http_response_code(500); // Internal Server Error
        return ("ERROR 001 UpdatePlazas - Internal Server Error: " . $e->getMessage());
        }    

}


//--> INCLUINDO REGISTROS NO BANCO DE DADOS

$confirmation['DB_registerUpdated'] = UpdateRegister($confirmPaymentResult, $GlobalConnection);

$i=0; foreach($registers as $key => $value) {

    if (UpdatePlazas($registers[$i], $GlobalConnection) === true) {

        $confirmation['DB_plazasUpdated'] = true;
        
    } else {
        $confirmation['DB_plazasUpdated'] = false;
        break;
    }
    $i++;
}

///////////////////////////////////


$jsonData = json_encode($confirmation, JSON_PRETTY_PRINT);
echo($jsonData);
?>