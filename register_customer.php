<?php 
	require_once("./btsetup.php");

	$customerId = htmlentities($_REQUEST["customerId"]);
	$name = htmlentities($_REQUEST["name"]);
	$email = htmlentities($_REQUEST["email"]);
	$phone = htmlentities($_REQUEST["phone"]);

	$result = Braintree_Customer::create([
			'id' => $customerId,
			'firstName' => $name,
			'email' => $email,
			'phone' => $phone
		]
	);

	if ($result->success){                                                       
		$response['status'] = TRUE;                        
		$response['customer_id'] = $result->customer->id;       
		$response['msg'] = "Customer is registered successfully!";
		echo json_encode($response);
		exit;

	} else {
		foreach ($result->errors->deepAll() as $error) {
			$errorFound = $error->message;
		}                                     
		$response['status'] = FALSE;          
		$response['msg'] = $errorFound;     
		echo json_encode($response);
		exit;
	}
?>
