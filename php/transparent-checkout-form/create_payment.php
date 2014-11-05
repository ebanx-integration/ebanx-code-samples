<?php

require_once 'bootstrap.php';

$paymentData = array(
    'mode'      => 'full'
  , 'operation' => 'request'
  , 'payment'   => array(
        'merchant_payment_code' =>  isset($_POST['merchant_payment_code']) ? $_POST['merchant_payment_code'] : ''
      , 'amount_total'      => isset($_POST['amount']) ? $_POST['amount'] : ''
      , 'currency_code'     => isset($_POST['currency_code']) ? $_POST['currency_code'] : 'USD'
      , 'name'              => isset($_POST['name']) ? $_POST['name'] : ''
      , 'email'             => isset($_POST['email']) ? $_POST['email'] : ''
      , 'birth_date'        => isset($_POST['birth_date']) ? $_POST['birth_date'] : ''
      , 'document'          => isset($_POST['document']) ? $_POST['document'] : ''
      , 'address'           => isset($_POST['address']) ? $_POST['address'] : ''
      , 'street_number'     => isset($_POST['street_number']) ? $_POST['street_number'] : ''
      , 'street_complement' => isset($_POST['street_complement']) ? $_POST['street_complement'] : ''
      , 'city'              => isset($_POST['city']) ? $_POST['city'] : ''
      , 'state'             => isset($_POST['state']) ? $_POST['state'] : ''
      , 'zipcode'           => isset($_POST['zipcode']) ? $_POST['zipcode'] : ''
      , 'country'           => isset($_POST['country']) ? $_POST['country'] : 'br'
      , 'phone_number'      => isset($_POST['phone_number']) ? $_POST['phone_number'] : ''
      , 'payment_type_code' => $_POST['payment_method']
    )
);

// Handle TEF payments
if (isset($_POST['payment_method']) && $_POST['payment_method'] == 'tef')
{
  $paymentData['payment']['payment_type_code'] = $_POST['payment_type_tef'];
}

// Handle CC payments
 if (isset($_POST['payment_method']) && $_POST['payment_method'] == 'creditcard')
{
  $paymentData['payment']['payment_type_code'] = $_POST['cc_scheme'];
  $paymentData['payment']['creditcard'] = array(
      'card_name'     => $_POST['cc_name']
    , 'card_number'   => preg_replace('/\D/', '', $_POST['cc_number'])
    , 'card_cvv'      => preg_replace('/\D/', '', $_POST['cc_cvv'])
    , 'card_due_date' => preg_replace('/[^\d\/]/', '', $_POST['cc_expiration'])
  );
  $paymentData['payment']['instalments']  = $_POST['cc_instalments'];
  $paymentData['payment']['amount_total'] = calculateTotalWithInterest($_POST['amount'], $_POST['cc_instalments']);
}

try
{
  $request = \Ebanx\Ebanx::doRequest($paymentData);
}
catch (Exception $e)
{
  $response['status']  = 'error';
  $response['message'] = $e->getMessage();
}

if ($request)
{
  if ($request->status == 'SUCCESS')
  {
    $response['status'] = 'success';

    if ($_POST['payment_method'] == 'boleto')
    {
      $response['boleto_url']   = $request->payment->boleto_url;
      $response['success_popup'] = true;
    }
    else if ($_POST['payment_method'] == 'tef')
    {
      $response['redirect_url'] = $request->redirect_url;
    }
    else
    {
      $response['success_popup'] = true;
    }
  }
  else
  {
    $response['status']  = 'error';
    $response['message'] = getEbanxErrorMessage($request->status_code);
  }
}
else
{
  $response['status']  = 'error';
  $response['message'] = 'Não foi possível criar o pagamento.';
}

echo json_encode($response);