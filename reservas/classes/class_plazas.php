<?php

class Plazas implements \JsonSerializable{

    private $ano = null;
    private $mesNumero = null;
    private $mesNombre = null;
    private $plazas = null;
    private $countSelectedProcedures = null;
    private $condicionBasica = null;
    private $todosPeriodosDisponibles = [];

    public function __construct($condicionBasica, $mesNumero, $ano, $countSelectedProcedures){
        $this->ano = $ano;
        $this->mesNumero = $mesNumero;
        $this->condicionBasica = $condicionBasica;
        $this->countSelectedProcedures = $countSelectedProcedures;
        $mesNumero != "" ? $this->setMesNombre() : null;
        $ano != "" ? $this->setPlazas() : null;
        $this->setTodosPeriodosDisponibles();

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

    private function setPlazas(): self {

        $connection = $this->setConnection();
        $sql = "SELECT * FROM ".TABLE_PLAZAS." WHERE condicionBasica = :_condicionBasica AND ano = :_ano AND mesNumero = :_mesNumero";

        try {
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(":_condicionBasica", $this->condicionBasica, \PDO::PARAM_STR);
            $stmt->bindValue(":_ano", $this->ano, \PDO::PARAM_STR);
            $stmt->bindValue(":_mesNumero", $this->mesNumero, \PDO::PARAM_STR);

            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e)
            {
            die("ERROR 001 - La conexión o la operación con la tabla ha fallado: " . $e->getMessage());
            }

            switch (true){

                case (count($result) >= 1):
                    $this->plazas = $result[0]['plazas'];
                    break;

                case (count($result) <= 0):
                    $this->plazas = 0;
                    break;
            }

        return $this;
    }

    public function getAno(){ return $this->ano; }

    public function getMesNumero(){ return $this->mesNumero; }

    private function setMesNombre(): self {

        $connection = $this->setConnection();
        $sql = "SELECT * FROM ".TABLE_PLAZAS." WHERE mesNumero = :_mesNumero";

        try {
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(":_mesNumero", $this->mesNumero, \PDO::PARAM_STR);

            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e)
            {
            die("ERROR 002 - La conexión o la operación con la tabla ha fallado: " . $e->getMessage());
            }

            switch (true){

                case (count($result) >= 1):
                    $this->mesNombre = $result[0]['mesNombre'];
                    break;

                case (count($result) == 0):
                    die("ERROR 003 - No encontrado ningún resultado en la pesquisa ({$sql})");
                    break;
            }

        return $this;

    }

    public function getMesNombre(){ return $this->mesNombre; }

    public function getPlazas(){ return $this->plazas; }

    public function getCondicionBasica(){ return $this->condicionBasica; }

    public function setTodosPeriodosDisponibles(): self {

        $connection = $this->setConnection();
        $sql = "SELECT * FROM ".TABLE_PLAZAS." WHERE condicionBasica = :_condicionBasica AND plazas >= 1 ORDER BY mesNumero";

        try {
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(":_condicionBasica", $this->condicionBasica, \PDO::PARAM_STR);

            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e)
            {
            die("ERROR 001 - La conexión o la operación con la tabla ha fallado: " . $e->getMessage());
            }

            switch (true){

                case (count($result) >= 1):
                    $this->todosPeriodosDisponibles = $result;
                    break;

                case (count($result) <= 0):
                    $this->todosPeriodosDisponibles = $result;
                    // No hay periodo disponible
                    break;
            }

        return $this;
    }
    public function getTodosPeriodosDisponibles(){ return $this->todosPeriodosDisponibles; }



    public function jsonSerialize() {
        $vars = get_object_vars($this);
        return $vars;
    }




}


?>
