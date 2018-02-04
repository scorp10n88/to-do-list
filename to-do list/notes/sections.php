<?php  
require_once '../settings.php';

$section = htmlentities($_POST['section'], ENT_QUOTES, 'UTF-8');
$idUser = $_POST['id_user'];
$delSection = $_POST['delSection'];
$showSections = $_POST['showSections'];

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

/*Add new section*/
if (isset($idUser) && !isset($showSections)) {
	/*Preparation and execution of the request*/
	$sql = "INSERT INTO sections (name, id_user) VALUES (:section, :idUser)";
	$statement = $pdo->prepare($sql);
	$statement -> bindValue(':section', $section, PDO::PARAM_STR);
	$statement -> bindValue(':idUser', $idUser, PDO::PARAM_STR);
	$statement -> execute();

	/*Getting the last added id*/
	$insert_id = $pdo->lastInsertId();

		echo "<tr class='table-tr-data'>
			  <td><button type='button' class='btn btn-default btn-sm selectSection' id='".$insert_id."'>".$section."</button></td>
        	  <td class='text-right'><button type='button' class='btn btn-danger btn-sm delSection' id='del".$insert_id."'>Del</button></td>
              </tr>";
}

/*Delete section*/
elseif (isset($delSection)) {

	/*Start transaction*/
	$pdo->beginTransaction();	

	try{
	/*Preparation and execution of the request*/
	$sql = "DELETE FROM sections WHERE id_section = :delSection";
	$statement = $pdo->prepare($sql);
	$statement -> bindValue(':delSection', $delSection, PDO::PARAM_STR);
	$statement -> execute();

		/*Preparation and execution of the request*/
		$sql = "DELETE FROM notes WHERE id_section = :delSection";
		$statement = $pdo->prepare($sql);
		$statement -> bindValue(':delSection', $delSection, PDO::PARAM_STR);
		$statement -> execute();

			/*Confirmation of transaction*/	
			$pdo->commit();
			
	}
		catch(Exception $e) {
			echo "Uninstall error";
		}
}

/*Show section*/
if (isset($showSections)) {
	/*Preparation and execution of the request*/
	$sql = "SELECT id_section, name FROM sections WHERE id_user = :idUser ORDER BY id_section ASC";
	$statement = $pdo->prepare($sql);
	$statement -> bindValue(':idUser', $idUser, PDO::PARAM_STR);
	$statement -> execute();

		while (($result = $statement->fetch(PDO::FETCH_ASSOC)) !== false) {
			echo "<tr class='table-tr-data'>
				  <td><button type='button' class='btn btn-default btn-sm selectSection' id='".$result['id_section']."'>".$result['name']."</button></td>
	              <td class='text-right'><button type='button' class='btn btn-danger btn-sm delSection' id='del".$result['id_section']."'>Del</button></td>
	          	  </tr>";
		}
}

?>