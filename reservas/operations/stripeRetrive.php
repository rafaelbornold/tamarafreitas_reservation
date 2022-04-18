<?php

require_once("../vendor/stripe/stripe-php/init.php");
require_once("../config/config.php");
require_once("../stripe_checkout/stripe_php_keys.php");

$stripe = new \Stripe\StripeClient($spripe_api_key);

$jsonStr = file_get_contents('php://input');

// $jsonStr = '{"paymentIntent":{"id":"pi_3KMdrWHlZb6k71EV0k4fCQhQ","object":"payment_intent","amount":5000,"automatic_payment_methods":null,"canceled_at":null,"cancellation_reason":null,"capture_method":"automatic","client_secret":"pi_3KMdrWHlZb6k71EV0k4fCQhQ_secret_T0RZJtBZu6YBhRo0ekC8PYPTX","confirmation_method":"automatic","created":1643312550,"currency":"eur","description":"Hola Maria, tu reserva para de lo(s) procedimiento(s) MICROCEJAS para el mes de JULIO ha sido recibida y procesada correctamente. Me gustaría agradecer tu confianza en mi trabajo, estaré  encantada de recibirte y dejarte aún más guapa. Con un mes de antelación mi secretaría contactará contigo para concretar la cita, si tienes cualquier duda puedes contactar a través del correo electrónico receipts@tamarafreitas.com o a través del whatsapp +34 662 296 124.","last_payment_error":null,"livemode":false,"next_action":null,"payment_method":"pm_1KMdrhHlZb6k71EVRbBerHHe","payment_method_types":["card"],"processing":null,"receipt_email":"maria@gmail.com","setup_future_usage":null,"shipping":null,"source":null,"status":"succeeded"}}';

$jsonObj = json_decode($jsonStr);
$paymentIntent = $jsonObj->paymentIntent;

$stripeRetrive = $stripe->paymentIntents->retrieve($paymentIntent->id);

$stripeReceiptURL['receipt_number'] = $stripeRetrive->charges->data[0]->receipt_number;
$stripeReceiptURL['receipt_url']    = $stripeRetrive->charges->data[0]->receipt_url;
$stripeReceiptURL['receipt_email']  = $stripeRetrive->charges->data[0]->receipt_email;
$stripeReceiptURL['description']    = $stripeRetrive->charges->data[0]->description;



$jsonData = json_encode($stripeReceiptURL, JSON_PRETTY_PRINT);
echo($jsonData);


?>