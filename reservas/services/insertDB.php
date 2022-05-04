<?php error_reporting(E_ERROR);

require_once("../config/config.php");

// RECEBENDO DADOS DO JAVASCRIPT

$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr);
$registers = $jsonObj->registers;

//////////////////////////////////
//////////////////////////////////
//////////////////////////////////

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

//////////////////////////////////
//////////////////////////////////
//////////////////////////////////

$GlobalConnection = setConnection();

//////////////////////////////////
//////////////////////////////////
//////////////////////////////////


function findRegister($nif, $procedimiento, $condicionBasica, $connection){

    $sql = "SELECT * FROM ".TABLE_RESERVAS." WHERE nif = :_nif AND procedimiento = :_procedimiento AND CondicionBasica = :_CondicionBasica";
    
    try {
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(":_nif", $nif, \PDO::PARAM_STR);
        $stmt->bindValue(":_procedimiento", $procedimiento, \PDO::PARAM_STR);
        $stmt->bindValue(":_CondicionBasica", $condicionBasica, \PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e)
        {
        die("ERROR 001 findRegister - La conexión con la tabla ha fallado: " . $e->getMessage());
        }

        switch (true){

            case (count($result) == 0):
                return false;
                break;

            case (count($result) == 1):
                return true;
                break;

            case (count($result) > 1):
                die("ERROR 002 findRegister - Foi encontrado mais de um registro o mesmo NIF e por isso não foi possível continuar");
                break;
        }           
}
function UpdateRegister($register, $connection) {

    $sql_include = 
        "INSERT INTO ".TABLE_RESERVAS." 
        (
        `id`,
        `nombre`, 
        `apellido`, 
        `nif`, 
        `email`, 
        `telefono`, 
        `dispManana`, 
        `dispTarde`, 
        `procedimiento`, 
        `ano`, 
        `mesNumero`, 
        `mesNombre`, 
        `CondicionBasica`, 
        `CondicionEspecifica`, 
        `precioReserva`, 
        `precioProcedimiento`, 
        `clientSecret`, 
        `created`, 
        `modified`) 
        VALUES (
            NULL,
            :_nombre, 
            :_apellido, 
            :_nif, 
            :_email, 
            :_telefono, 
            :_dispManana, 
            :_dispTarde, 
            :_procedimiento, 
            :_ano, 
            :_mesNumero, 
            :_mesNombre, 
            :_CondicionBasica, 
            :_CondicionEspecifica, 
            :_precioReserva, 
            :_precioProcedimiento, 
            :_clientSecret, 
            NOW(), 
            NOW()) 
                ";

    $sql_update = 
        "UPDATE ".TABLE_RESERVAS." SET 
        `nombre` =              :_nombre,
        `apellido` =            :_apellido,
        `nif`=                  :_nif,
        `email`=                :_email,
        `telefono`=             :_telefono,
        `dispManana`=           :_dispManana,
        `dispTarde`=            :_dispTarde,
        `procedimiento`=        :_procedimiento,
        `ano`=                  :_ano,
        `mesNumero`=            :_mesNumero,
        `mesNombre`=            :_mesNombre,
        `CondicionBasica`=      :_CondicionBasica,
        `CondicionEspecifica`=  :_CondicionEspecifica,
        `precioReserva`=        :_precioReserva,
        `precioProcedimiento`=  :_precioProcedimiento,
        `clientSecret`=         :_clientSecret,
        `modified`=             NOW()
        WHERE nif = :_nif AND procedimiento = :_procedimiento AND CondicionBasica = :_CondicionBasica";


    
    $sql = findRegister($register->nif, $register->procedure, $register->condicionBasica, $connection) == false ? $sql_include : $sql_update ;
    
    // $connection = setConnection();

    try {
        $stmt = $connection->prepare($sql);

        $stmt->bindValue(":_nombre", $register->nombre , \PDO::PARAM_STR);
        $stmt->bindValue(":_apellido", $register->apellido , \PDO::PARAM_STR);
        $stmt->bindValue(":_nif", $register->nif , \PDO::PARAM_STR);
        $stmt->bindValue(":_email", $register->email , \PDO::PARAM_STR);
        $stmt->bindValue(":_telefono", $register->telefono , \PDO::PARAM_STR);
        $stmt->bindValue(":_dispManana", $register->manana , \PDO::PARAM_STR);
        $stmt->bindValue(":_dispTarde", $register->tarde , \PDO::PARAM_STR);
        $stmt->bindValue(":_procedimiento", $register->procedure , \PDO::PARAM_STR);
        $stmt->bindValue(":_ano", $register->ano , \PDO::PARAM_STR);
        $stmt->bindValue(":_mesNumero", $register->mesNumero , \PDO::PARAM_STR);
        $stmt->bindValue(":_mesNombre", $register->mesNombre , \PDO::PARAM_STR);
        $stmt->bindValue(":_CondicionBasica", $register->condicionBasica , \PDO::PARAM_STR); 
        $stmt->bindValue(":_CondicionEspecifica", $register->condicionEspecifica , \PDO::PARAM_STR); 
        $stmt->bindValue(":_precioReserva", $register->reservationPrice , \PDO::PARAM_INT); 
        $stmt->bindValue(":_precioProcedimiento", $register->procedurePrice , \PDO::PARAM_INT); 
        // $stmt->bindValue(":_paymentStatus", $register->paymentStatus , \PDO::PARAM_STR);
        $stmt->bindValue(":_clientSecret", $register->clientSecret , \PDO::PARAM_STR);
        // $stmt->bindValue(":_paymentIntent", $register->paymentIntent , \PDO::PARAM_STR);

        $stmt->execute();

        http_response_code(201);
        return http_response_code(); // created
    
    } catch(PDOException $e)
        {
        http_response_code(500); // Internal Server Error
        return ("ERROR 001 UpdateRegister - ".http_response_code()." Internal Server Error: " . $e->getMessage());
        }    

}

//--> INCLUINDO REGISTROS NO BANCO DE DADOS E SETANDO A RESPONSE
    
    http_response_code(200);
    $status['finalStatus'] = http_response_code();

    $i=0; foreach($registers as $key => $value){

        $status[$registers[$i]->procedure] = UpdateRegister($registers[$i],$GlobalConnection);
        
        if (http_response_code() == 500) {
            $status['finalStatus'] = http_response_code();
        }

        $i++;

    }
    ///////////////////////////////////

if ($registers == null) {
    http_response_code(500); // No content
    $status['finalStatus'] = http_response_code();
}

$jsonData = json_encode($status, JSON_PRETTY_PRINT);
echo($jsonData);
?>