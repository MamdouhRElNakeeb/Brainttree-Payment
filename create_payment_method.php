<?php
	require_once("./btsetup.php");

if (!(isset($_POST["customerId"]) || isset($_POST["payment_method_nonce"]))){

	    return;
    }

    if (!empty($_POST["amount"])) {

        if (isset($_POST["cardHolder"]) && isset($_POST["billingAdd"])){
            $result = Braintree_Transaction::sale([
                'amount' => $_POST["amount"],
                'paymentMethodNonce' => $_POST["payment_method_nonce"],
                'options' => [
                    'storeInVaultOnSuccess' => true,
                    'submitForSettlement' => True
                ],
//                'creditCard' => [
//                    'cardholderName' => $_POST["cardHolder"]
//                ],
                'billing' => [
                    'streetAddress' => $_POST["billingAdd"]
                ]
            ]);
        }
        else{
            $result = Braintree_Transaction::sale([
                'amount' => $_POST["amount"],
                'paymentMethodNonce' => $_POST["payment_method_nonce"],
                'options' => [
                    'storeInVaultOnSuccess' => true,
                    'submitForSettlement' => True
                ]
            ]);
        }

        if ($result->success){
            if ($result->transaction) {
                $response['data'] = $result->transaction;
                $response['paySuccess'] = true;
                $response['transactionId'] = $result->transaction->id;

            } else {
                $response['paySuccess'] = false;
                $response['payError'] = "Some error occurred with your payment, Try again later." ;
            }

        }
        else {
            foreach ($result->errors->deepAll() as $error) {
                $errorFound = $error->message;
            }
            $response['paySuccess'] = false;
            $response['payError'] = $errorFound ;

        }
        $myJSON = json_encode($response, true);
        echo $myJSON;
        exit;
    }
    else {
        $result = Braintree_PaymentMethod::create([
            'customerId' => $_POST["customerId"],
            'paymentMethodNonce' => $_POST["payment_method_nonce"]
        ]);

        if ($result->success){
            $response['result'] = $result;
            $response['methodSuccess'] = true;

        }
        else {
            foreach ($result->errors->deepAll() as $error) {
                $errorFound = $error->message;
            }
            $response['methodSuccess'] = false;
            $response['methodError'] = $errorFound ;
        }
        $myJSON = json_encode($response, true);
        echo $myJSON;
        exit;
    }


?>