<?php error_reporting(E_ERROR);

require_once("../config/config.php");

$jsonStr = file_get_contents('php://input');

// $jsonStr = '{"registers":[{"nombre":"Rafael","apellido":"Bornold","nif":"Y7782080K","email":"raffab@gmail.com","telefono":"+34 123 123 123","manana":true,"tarde":false,"procedure":"MicroCejas","condicionBasica":"nuevo","condicionEspecifica":"nuevo","reservationPrice":"50","procedurePrice":"390","ano":"2022","mesNumero":"7","mesNombre":"Julio","paymentStatus":"waiting","clientSecret":"waiting","paymentIntent":"waiting"},{"nombre":"Rafael","apellido":"Bornold","nif":"Y7782080K","email":"raffab@gmail.com","telefono":"+34 123 123 123","manana":true,"tarde":false,"procedure":"MicroLabios","condicionBasica":"nuevo","condicionEspecifica":"nuevo","reservationPrice":"50","procedurePrice":"390","ano":"2022","mesNumero":"7","mesNombre":"Julio","paymentStatus":"waiting","clientSecret":"waiting","paymentIntent":"waiting"},{"nombre":"Rafael","apellido":"Bornold","nif":"Y7782080K","email":"raffab@gmail.com","telefono":"+34 123 123 123","manana":true,"tarde":false,"procedure":"MicroEyeliner","condicionBasica":"nuevo","condicionEspecifica":"nuevo","reservationPrice":"50","procedurePrice":"390","ano":"2022","mesNumero":"7","mesNombre":"Julio","paymentStatus":"waiting","clientSecret":"waiting","paymentIntent":"waiting"}]}';

$jsonObj = json_decode($jsonStr);
$registers = $jsonObj->registers;


function escribirProcedimientos(array $registers): string {

    $procedimientos = "";
    $i=0; foreach($registers as $value){

        if ($i == 0) {
            $procedimientos .= strtoupper($registers[$i]);
        };
        if ($i >= 1) {
            $q=$i+1;
           if ($q == count($registers)){
             $procedimientos .= " y ".strtoupper($registers[$i]);
           } else{
             $procedimientos .= " , ".strtoupper($registers[$i]);
           }

        };
        $i++;
    }

     return $procedimientos;
}


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

/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////

$GlobalConnection = setConnection();

/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////

function verifyRegisterPayments($register, $connection){

    $sql = "SELECT * FROM ".TABLE_RESERVAS." WHERE nif = :_nif AND procedimiento = :_procedimiento AND mesNumero = :_mesNumero AND ano = :_ano AND paymentStatus = :_paymentStatus";

    try {
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(":_nif", $register->nif, \PDO::PARAM_STR);
        $stmt->bindValue(":_procedimiento", $register->procedure, \PDO::PARAM_STR);
        $stmt->bindValue(":_paymentStatus", 'succeeded', \PDO::PARAM_STR);
        $stmt->bindValue(":_mesNumero", $register->mesNumero, \PDO::PARAM_STR);
        $stmt->bindValue(":_ano", $register->ano, \PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e)
        {
        die("ERROR 001 findRegister - La conexión con la tabla ha fallado: " . $e->getMessage());
        }

        switch (true){

            case (count($result) == 0):
                return http_response_code(200); // OK
                break;

            case (count($result) == 1):
                return $result[0]['procedimiento'];
                break;

            case (count($result) >= 1):
                die("ERROR 002 verifyRegisterPayment - Encontrado mais de um registro com o mesmo pagamento - Algo deu errado !");
                break;

        }

}


$i=0; foreach($registers as $key => $value) {

    if (verifyRegisterPayments($registers[$i], $GlobalConnection ) != http_response_code(200)){
        $paydProcedure[] = $registers[$i]->procedure;
    }
    $i++;
}

switch (true){

    case (count($paydProcedure) == 0):
        $output = http_response_code(200); // OK
        break;

    case (count($paydProcedure) >= 1):
        $output = "Con este NIF ya se ha realizado el pago de los procedimientos ".escribirProcedimientos($paydProcedure).". Solo se permite 1 pago por trámite por cada NIF. Si estás haciendo la reserva para otra persona, introduce el NIF y información de esa otra persona en el formulario. O seleccione otro procedimiento para continuar";
        break;

}


///////////////////////////////////



$jsonData = json_encode($output, JSON_PRETTY_PRINT);
echo($jsonData);
?>
