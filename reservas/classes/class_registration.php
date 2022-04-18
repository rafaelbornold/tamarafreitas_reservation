<?php

class Registration implements \JsonSerializable {

    private $nombre = null;
    private $apellido = null;
    private $nif = null;
    private $email = null;
    private $telefono = null;

    private $manana = false;
    private $tarde = false;

    private $procedure = null;
    private $condicionBasica = null;
    private $condicionEspecifica = null;
    private $reservationPrice = null;
    private $procedurePrice = null;

    private $ano = null;
    private $mesNumero = null;
    private $mesNombre = null;


    private $paymentStatus = null;
    private $clientSecret = null;
    private $paymentIntent = null;

    public function __construct(array $person, array $availability, Procedure $procedure, array $period, array $payment){

        $this->nombre = $person['nombre'];
        $this->apellido = $person['apellido'];
        $this->nif = $person['nif'];
        $this->email = $person['email'];
        $this->telefono = $person['telefono'];

        $this->manana = $availability['manana'];
        $this->tarde = $availability['tarde'];

        $this->procedure = $procedure->getProcedure();
        $this->condicionBasica = $procedure->getCondicionBasica();
        $this->condicionEspecifica = $procedure->getCondicionEspecifica();
        $this->reservationPrice = $procedure->getReservationPrice();
        $this->procedurePrice = $procedure->getProcedurePrice();
        
        $this->ano = $period['ano'];
        $this->mesNumero = $period['mesNumero'];
        $this->mesNombre = $period['mesNombre'];


        $this->paymentStatus = $payment['paymentStatus'];
        $this->clientSecret = $payment['clientSecret'];
        $this->paymentIntent = $payment['paymentIntent'];

    }

    public function getNombre(){ return $this->nombre; }
    private function setNombre($nombre): self { $this->nombre = $nombre; return $this; }

    public function getApellido(){ return $this->apellido; }
    private function setApellido($apellido): self { $this->apellido = $apellido; return $this; }

    public function getNif(){ return $this->nif; }
    private function setNif($nif): self { $this->nif = $nif; return $this; }

    public function getEmail(){ return $this->email; }
    private function setEmail($email): self { $this->email = $email; return $this; }

    public function getTelefono(){ return $this->telefono; }
    private function setTelefono($telefono): self { $this->telefono = $telefono; return $this; }

    public function getManana(){ return $this->manana; }
    private function setManana($manana): self { $this->manana = $manana; return $this; }

    public function getTarde(){ return $this->tarde; }
    private function setTarde($tarde): self { $this->tarde = $tarde; return $this; }

    public function getProcedure(){ return $this->procedureName; }
    private function setProcedure($procedure): self { $this->procedure = $procedure; return $this; }

    public function getReservationPrice(){ return $this->reservationPrice; }
    private function setReservationPrice($reservationPrice): self { $this->reservationPrice = $reservationPrice; return $this; }

    public function getProcedurePrice(){ return $this->procedurePrice; }
    private function setProcedurePrice($procedurePrice): self { $this->procedurePrice = $procedurePrice; return $this; }

    public function getAno(){ return $this->ano; }
    private function setAno($ano): self { $this->ano = $ano; return $this; }

    public function getMesNumero(){ return $this->mes; }
    private function setMesNumero($mesNumero): self { $this->mesNumero = $mesNumero; return $this; }

    public function getMesNombre(){ return $this->mesNombre; }
    private function setMesNombre($mesNombre): self { $this->mesNombre = $mesNombre; return $this; }

    public function getClientSecret(){ return $this->clientSecret; }
    private function setClientSecret($clientSecret): self { $this->clientSecret = $clientSecret; return $this; }


    public function jsonSerialize() {
        $vars = get_object_vars($this);
        return $vars;    
    }


}