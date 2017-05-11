<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// Get all contacts
$app->get('/api/contacts', function(Request $request, Response $response){
	$sql = "SELECT * FROM contacts";
	try{
		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		$stmt = $db->query($sql);
		$contacts = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($contacts);
	}catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});

// Get single contact
$app->get('/api/contact/{id}', function(Request $request, Response $response){
	$id= $request->getAttribute('id');
	$sql = "SELECT * FROM contacts where id=$id";
	try{
		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		$stmt = $db->query($sql);
		$contact = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($contact);
	}catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});

// Add contact
$app->post('/api/contact/add', function(Request $request, Response $response){
	$first_name = $request->getParam('first_name');
	$last_name = $request->getParam('last_name');
	$phone = $request->getParam('phone');
	$email = $request->getParam('email');
	$address = $request->getParam('address');
	$city = $request->getParam('city');
	$country = $request->getParam('country');
	
	$sql = "INSERT INTO contacts (first_name, last_name, phone, email, address, city, country) 
	VALUES(:first_name, :last_name, :phone, :email, :address, :city, :country)";

	try{
		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':first_name', $first_name);
		$stmt->bindParam(':last_name', $last_name);
		$stmt->bindParam(':phone', $phone);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':address', $address);
		$stmt->bindParam(':city', $city);
		$stmt->bindParam(':country', $country);

		$stmt->execute();
		echo '{"notice": "Customer Added"}';
	
	}catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});

// Update contact
$app->put('/api/contact/update/{id}', function(Request $request, Response $response){
	$id = $request->getAttribute('id');
	$first_name = $request->getParam('first_name');
	$last_name = $request->getParam('last_name');
	$phone = $request->getParam('phone');
	$email = $request->getParam('email');
	$address = $request->getParam('address');
	$city = $request->getParam('city');
	$country = $request->getParam('country');
	
	$sql = "UPDATE contacts SET
	first_name = :first_name,
	last_name = :last_name,
	phone = :phone,
	email = :email,
	address = :address,
	city = :city,
	country = :country
	WHERE id=$id";

	try{
		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':first_name', $first_name);
		$stmt->bindParam(':last_name', $last_name);
		$stmt->bindParam(':phone', $phone);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':address', $address);
		$stmt->bindParam(':city', $city);
		$stmt->bindParam(':country', $country);

		$stmt->execute();
		echo '{"notice": "Customer Updated"}';
	
	}catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});

// Delete contact
$app->delete('/api/contact/delete/{id}', function(Request $request, Response $response){
	$id= $request->getAttribute('id');
	$sql = "DELETE FROM contacts where id=$id";
	try{
		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		$stmt = $db->prepare($sql);
		$stmt->execute();
		$db = null;
		echo '{"notice": "Customer Deleted"}';
	}catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});