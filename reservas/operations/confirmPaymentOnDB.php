<?php error_reporting(E_ERROR);

require_once("../config/config.php");

// RECEBENDO DADOS DO JAVASCRIPT

//  $jsonStr = '{"confirmPaymentResult":{"paymentIntent":{"id":"pi_3KPntFHlZb6k71EV1xItonJG","object":"payment_intent","amount":15000,"automatic_payment_methods":null,"canceled_at":null,"cancellation_reason":null,"capture_method":"automatic","client_secret":"pi_3KPntFHlZb6k71EV1xItonJG_secret_Y7Vsov0Td2oBo8WDZAtAhl1yr","confirmation_method":"automatic","created":1644066081,"currency":"eur","description":"Hola Rafael (Y7782080K), tu reserva para REPASO de lo(s) procedimiento(s) MICROCEJAS , MICROLABIOS y MICROEYELINER para el mes de JULIO ha sido recibida y procesada correctamente. Me gustaría agradecer tu confianza en mi trabajo, estaré  encantada de recibirte y dejarte aún más guapa. Con un mes de antelación mi secretaría contactará contigo para concretar la cita, si tienes cualquier duda puedes contactar a través del correo electrónico receipts@tamarafreitas.com o a través del whatsapp +34 662 296 124.","last_payment_error":null,"livemode":false,"next_action":null,"payment_method":"pm_1KPntNHlZb6k71EVoZGGq6HZ","payment_method_types":["card"],"processing":null,"receipt_email":"raffab@gmail.com","setup_future_usage":null,"shipping":null,"source":null,"status":"succeeded"}},"registers":[{"nombre":"Rafael","apellido":"Bornold","nif":"Y7782080K","email":"raffab@gmail.com","telefono":"+34 123 123 123","manana":true,"tarde":true,"procedure":"MicroCejas","condicionBasica":"repaso","condicionEspecifica":"repaso","reservationPrice":"50","procedurePrice":0,"ano":"2022","mesNumero":"7","mesNombre":"Julio","paymentStatus":"waiting","clientSecret":"pi_3KPntFHlZb6k71EV1xItonJG_secret_Y7Vsov0Td2oBo8WDZAtAhl1yr","paymentIntent":"waiting"},{"nombre":"Rafael","apellido":"Bornold","nif":"Y7782080K","email":"raffab@gmail.com","telefono":"+34 123 123 123","manana":true,"tarde":true,"procedure":"MicroLabios","condicionBasica":"repaso","condicionEspecifica":"repaso","reservationPrice":"50","procedurePrice":0,"ano":"2022","mesNumero":"7","mesNombre":"Julio","paymentStatus":"waiting","clientSecret":"pi_3KPntFHlZb6k71EV1xItonJG_secret_Y7Vsov0Td2oBo8WDZAtAhl1yr","paymentIntent":"waiting"},{"nombre":"Rafael","apellido":"Bornold","nif":"Y7782080K","email":"raffab@gmail.com","telefono":"+34 123 123 123","manana":true,"tarde":true,"procedure":"MicroEyeliner","condicionBasica":"repaso","condicionEspecifica":"repaso","reservationPrice":"50","procedurePrice":0,"ano":"2022","mesNumero":"7","mesNombre":"Julio","paymentStatus":"waiting","clientSecret":"pi_3KPntFHlZb6k71EV1xItonJG_secret_Y7Vsov0Td2oBo8WDZAtAhl1yr","paymentIntent":"waiting"}]}';


$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr);
$confirmPaymentResult = $jsonObj->confirmPaymentResult;
$registers = $jsonObj->registers;


// echo('<pre>');
// print_r($confirmPaymentResult);
// print_r($registers);
// echo('</pre>');
// die();

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