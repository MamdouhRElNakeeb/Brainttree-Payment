<?php
	require_once("./btsetup.php");	 

	$nonceFromTheClient = $_POST["nonce"];
	$amountToCharge = $_POST["amount"];
	
	$result = Braintree_Transaction::sale([
	    'amount' => $amountToCharge,
	    'paymentMethodNonce' => $nonceFromTheClient,
        'options' => [
            'submitForSettlement' => True
        ]
	]);
	
	
	if ($result->success) {
		if ($result->transaction) {	
			$response['status'] = TRUE;
			$response['data'] = $result->transaction;
			$response['msg'] = "Payment is done successfully!";
			echo json_encode($response, true);; 
		} else {

			$response['status'] = FALSE;
			$response['msg'] = "An error occured!";
			echo json_encode($response, true);; 

		}
	} else {
		foreach ($result->errors->deepAll() as $error) {
			$errorMsg = $error->message;
		}
		$response['status'] = FALSE;
		$response['msg'] = $errorMsg;

		echo json_encode($response, true);; 
		exit;   
	}
?>