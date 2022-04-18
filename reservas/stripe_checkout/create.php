<?php error_reporting(E_ERROR);

header('Content-Type: application/json');

require_once("../vendor/stripe/stripe-php/init.php");
require_once("../vendor/autoload.php");
require_once("../config/config.php");
require_once("./stripe_php_keys.php");

// This is your test secret API key.

\Stripe\Stripe::setApiKey($spripe_api_key);
$stripe = new \Stripe\StripeClient($spripe_api_key);


function calculateOrderAmount(array $registers): int {
        
    $sum = 0;
    $i=0; foreach($registers as $value){

        $sum += $registers[$i]->reservationPrice;
        $i++;

    }
    return ( $sum * 100 );
}

function setCustomerId(array $registers, $stripe): string{
    $customer = $stripe->customers->all(['email' => $registers[0]->email ]);
    $customer_id = $customer["data"][0]["id"];
    if ( ! isset($customer_id) ){
        
        $stripe->customers->create([
            'name' => $registers[0]->nombre.' '.$registers[0]->apellido,
            'email' => $registers[0]->email,
            'description' => 'NIF: '.$registers[0]->nif,
            'phone'=> $registers[0]->telefono,
        ]);
        
        $customer = $stripe->customers->all(['email' => $registers[0]->email ]);
        $customer_id = $customer["data"][0]["id"];    
        
        }   
    return $customer_id;
}

function escribirProcedimientos(array $registers): string {
        
    $procedimientos = "";
    $i=0; foreach($registers as $value){

        if ($i == 0) {
            $procedimientos .= strtoupper($registers[$i]->procedure);  
        };
        if ($i >= 1) {   
            $q=$i+1;     
           if ($q == count($registers)){
             $procedimientos .= " y ".strtoupper($registers[$i]->procedure);
           } else{
             $procedimientos .= " , ".strtoupper($registers[$i]->procedure);
           } 

        };
        $i++;
    }

     return $procedimientos;
}

try {
    // retrieve JSON from POST body

    $jsonStr = file_get_contents('php://input');
    $jsonObj = json_decode($jsonStr);
    $registers = $jsonObj->registers;

    $condicionClienta = $registers[0]->condicionBasica == "nuevo" ? "NUEVA CLIENTA de" : "REPASO de" ;

    // Create a PaymentIntent with amount and currency
    $paymentIntent = \Stripe\PaymentIntent::create([
        
        'customer' => setCustomerId($registers, $stripe),

        'receipt_email' => $registers[0]->email,

        'amount' => calculateOrderAmount($registers),
        'currency' => 'eur',

        'payment_method_types' => [
            'card',
        ],
    
        'description' => 'Hola '.$registers[0]->nombre.' ('.$registers[0]->nif.')'.', tu reserva para '.$condicionClienta.' lo(s) procedimiento(s) '.escribirProcedimientos($registers).' para el mes de '.strtoupper($registers[0]->mesNombre).' ha sido recibida y procesada correctamente. Me gustaría agradecer tu confianza en mi trabajo, estaré  encantada de recibirte y dejarte aún más guapa. Con un mes de antelación mi secretaría contactará contigo para concretar la cita, si tienes cualquier duda puedes contactar a través del correo electrónico receipts@tamarafreitas.com o a través del whatsapp +34 662 296 124.',
    ]);

    $output = [
        'clientSecret' => $paymentIntent->client_secret,
    ];

    echo json_encode($output);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}