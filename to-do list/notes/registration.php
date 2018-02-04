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

/*Registration new user*/
if ($_POST['userReg'] && !$_POST['checkUser']) {
	/*Preparation and execution of the request*/
	$sql = "INSERT INTO users (user, password) VALUES (:userReg, :passwordReg)";
	$statement = $pdo->prepare($sql);
	$userReg = filter_input(INPUT_POST, 'userReg');
	$statement -> bindValue(':userReg', $userReg, PDO::PARAM_STR);

	$passwordReg = filter_input(INPUT_POST, 'passwordReg');

	/*Password Hashing*/
	$passwordHash = password_hash(
		$passwordReg,
		PASSWORD_DEFAULT,
		['cost' => 10]
	);

	if ($passwordHash === false) {
		echo "Password hashing error";
		return false;
	}

	$statement -> bindValue(':passwordReg', $passwordHash, PDO::PARAM_STR);
	$statement -> execute();

	echo "<h3 class='text-center'>Регистрация прошла успешно</h3>";
}

/*Checking the login freedom*/
elseif ($_POST['checkUser']) {
	/*Preparation and execution of the request*/
	$sql = "SELECT * FROM users WHERE user = :userReg";
	$statement = $pdo->prepare($sql);
	$userReg = filter_input(INPUT_POST, 'userReg');
	$statement -> bindValue(':userReg', $userReg, PDO::PARAM_STR);
	$statement -> execute();

		if(($result = $statement->fetch(PDO::FETCH_ASSOC)) !== false){
			echo "This user exists!";
		}
		else {
			echo "";
		}
}
?>