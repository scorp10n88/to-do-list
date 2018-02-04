<?php  
require_once '../settings.php';

/*Connection to the database*/
try {
	$pdo = new PDO (
		'mysql:host='.$db_hostname.';dbname='.$db_database.';port='.$bd_port.';charset='.$bd_charset.'', 
		$db_username,
		$db_password
	);
} 
	catch (PDOException $e) {
		echo "Database connection failed";
		exit;
	}

try {

/*User login*/
if ($_POST['username']) {
	/*Preparation and execution of the request*/
	$sql = "SELECT * FROM users WHERE user = :username";
	$statement = $pdo->prepare($sql);
	$username = filter_input(INPUT_POST, 'username');
	$statement -> bindValue(':username', $username, PDO::PARAM_STR);
	$statement -> execute();

	$result = $statement->fetch(PDO::FETCH_ASSOC);
		if ($result) {
			$password = filter_input(INPUT_POST, 'password');
			if (password_verify($password, $result['password']) === false) {
				throw new Exception('{"message":"2"}');
			}
			else {
				echo '{"id":'.$result['id'].',"user": "'.$result['user'].'"}';
			}
					
		}
		else {
			throw new Exception('{"message":"1"}');
		}	
}
} 
	catch (Exception $e){
		echo $e->getMessage();
	}

?>