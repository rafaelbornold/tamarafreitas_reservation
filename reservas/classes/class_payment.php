
<?php

class Payment {

    private $paymentStatus = null;
    private $paymentIntent = null;


    public function getPaymentStatus(){ return $this->paymentStatus; }
    public function setPaymentStatus($paymentStatus): self { $this->paymentStatus = $paymentStatus; return $this; }

    public function getPaymentIntent(){ return $this->paymentIntent; }
    public function setPaymentIntent($paymentIntent): self { $this->paymentIntent = $paymentIntent; return $this; }
    }

?>