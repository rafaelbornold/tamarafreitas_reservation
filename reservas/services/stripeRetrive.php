<?php

require_once("../vendor/stripe/stripe-php/init.php");
require_once("../config/config.php");
require_once("../stripe_checkout/stripe_php_keys.php");

$stripe = new \Stripe\StripeClient($spripe_api_key);

$jsonStr = file_get_contents('php://input');

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