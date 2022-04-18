<?php


class Procedure {

    private $procedure = null;
    private $profesional = null;
    private $condicionBasica = null;
    private $condicionEspecifica = null;
    private $reservationPrice = null;
    private $procedurePrice = null;
    private $AllProceduresPrices = [];

    public function __construct(string $procedureName, array $procedureDatas){
        $this->procedure = $procedureName;
        $this->profesional = $procedureDatas['Profesional'];
        $this->condicionBasica = $procedureDatas['CondicionBasica'];
        $this->condicionEspecifica = $procedureDatas['CondicionEspecifica'];

        $this->setReservationPrice();
        $this->setProcedurePrice();
        $this->setAllProceduresPrices();

    }

    private function setConnection(){ 
            
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

    public function getProcedure(){ return $this->procedure; }
    private function setProcedure($procedure): self { $this->procedure = $procedure; return $this; }

    public function getReservationPrice(){ return $this->reservationPrice; }
    private function setReservationPrice(){

        $connection = $this->setConnection();
        $sql = "SELECT * FROM ".TABLE_PROCEDURES." WHERE Profesional = :_profesional AND Procedimiento = :_procedure AND CondicionEspecifica = :_condicion";
        
        try {
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(":_profesional", $this->profesional, \PDO::PARAM_STR);
            $stmt->bindValue(":_procedure", $this->procedure, \PDO::PARAM_STR);
            $stmt->bindValue(":_condicion", $this->condicionEspecifica, \PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e)
            {
            die("ERROR 002 - La conexión o la operación con la tabla ha fallado: " . $e->getMessage());
            }

            switch (true){

                case (count($result) > 1):
                    die("ERROR 003 - Erro: Encontrado mais de um valor de reserva para o mesmo procedimento");
                    break;

                case (count($result) == 0):
                    $this->reservationPrice = $this->getNearestReservationPrice();
                    // die("ERROR 004 -  Não foi encontrado nenhum valor de reserva para este procedimento ({$this->profesional} - {$this->procedure} - {$this->condicionEspecifica} [{$sql}] ");
                    break;

                case (count($result) == 1):
                    $this->reservationPrice = $result[0]['PrecioReserva'];
                    break;
            }           

    }
    private function getNearestReservationPrice(){

        $connection = $this->setConnection();
        $sql = "SELECT * FROM ".TABLE_PROCEDURES." WHERE Profesional = :_profesional AND Procedimiento = :_procedure AND CondicionBasica = :_condicion";
        
        try {
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(":_profesional", $this->profesional, \PDO::PARAM_STR);
            $stmt->bindValue(":_procedure", $this->procedure, \PDO::PARAM_STR);
            $stmt->bindValue(":_condicion", $this->condicionBasica, \PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e)
            {
            die("ERROR 004 A - La conexión o la operación con la tabla ha fallado: " . $e->getMessage());
            }

            switch (true){

                case (count($result) > 1):
                    return $result[0]['PrecioReserva'];
                    break;

                case (count($result) == 0):
                    die("ERROR 005 A -  Não foi encontrado nenhum valor de reserva para este procedimento ({$this->profesional} - {$this->procedure} - {$this->condicionBasica} [{$sql}] ");
                    break;

                case (count($result) == 1):
                    return $result[0]['PrecioReserva'];
                    break;
            }           

    }

    public function getProcedurePrice(){ return $this->procedurePrice; }
    private function setProcedurePrice(){
       
        $connection = $this->setConnection();
        $sql = "SELECT * FROM ".TABLE_PROCEDURES." WHERE Profesional = :_profesional AND Procedimiento = :_procedure AND CondicionEspecifica = :_condicion";
        
        try {
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(":_profesional", $this->profesional, \PDO::PARAM_STR);
            $stmt->bindValue(":_procedure", $this->procedure, \PDO::PARAM_STR);
            $stmt->bindValue(":_condicion", $this->condicionEspecifica, \PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e)
            {
            die("ERROR 006 - La conexión con la tabla ha fallado: " . $e->getMessage());
            }

            switch (true){

                case (count($result) > 1):
                    die("ERROR 007 - Encontrado mais de um valor o mesmo procedimento");
                    break;

                case (count($result) == 0):
                    $this->procedurePrice = 0;
                    // die("ERROR 007 - Não foi encontrado nenhum valor para este procedimento");
                    break;

                case (count($result) == 1):
                    $this->procedurePrice = $result[0]['PrecioProcedimiento'];
                    break;
            }           
            
    }

    public function getAllProceduresPrices(){ return $this->AllproceduresPrices; }
    private function setAllProceduresPrices(){
       
        $connection = $this->setConnection();
        $sql = "SELECT * FROM ".TABLE_PROCEDURES;
        
        try {
            $stmt = $connection->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e)
            {
            die("ERROR 001 AllProceduresPrice - La conexión con la tabla ha fallado: " . $e->getMessage());
            }

            switch (true){

                case (count($result) > 1):
                    $this->AllproceduresPrices = $result;
                    break;

                case (count($result) == 0):
                    die("ERROR 002 AllProceduresPrice - No encontrado ningún procedimiento en el sistema");
                    break;

                case (count($result) == 1):
                    $this->AllproceduresPrices= $result;
                    break;
            }           
            
    }

    public function getCondicionBasica(){ return $this->condicionBasica; }
    private function setCondicionBasica($condicionBasica): self { $this->condicionBasica = $condicionBasica; return $this; }

    public function getCondicionEspecifica(){ return $this->condicionEspecifica; }
    private function setCondicionEspecifica($condicionEspecifica): self { $this->condicionEspecifica = $condicionEspecifica; return $this; }
}




?>