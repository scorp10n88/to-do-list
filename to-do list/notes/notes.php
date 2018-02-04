<?php  
require_once '../settings.php';

$idSection = $_POST['idSection'];
$showNotes = $_POST['showNotes'];
$delNote = $_POST['delNote'];

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

/*Add new note*/
if (isset($_POST['strNote'])) {
	/*Preparation and execution of the request*/
	$sql = "INSERT INTO notes (note, id_section) VALUES (:strNote, :idSection)";
	$statement = $pdo->prepare($sql);
	$strNote = htmlentities($_POST['strNote'], ENT_QUOTES, 'UTF-8');
	$statement -> bindValue(':strNote', $strNote, PDO::PARAM_STR);
	$statement -> bindValue(':idSection', $idSection, PDO::PARAM_STR);
	$statement -> execute();

		/*Getting the last added id*/
		$insert_id = $pdo->lastInsertId();

			echo "<tr class='table-tr-data'><td><p>".$strNote."</p></td>
                  <td class='text-right'><button type='button' class='btn btn-sm btn-danger delNote' id='".$insert_id."'>Del</button></td></tr>";
}

/*Show existing notes*/
elseif (isset($showNotes)) {
	/*Preparation and execution of the request*/
	$sql = "SELECT id_notes, note FROM notes WHERE id_section = :idSection ORDER BY id_notes ASC";
	$statement = $pdo->prepare($sql);
	$statement -> bindValue(':idSection', $idSection, PDO::PARAM_STR);
	$statement -> execute();

	while (($result = $statement->fetch(PDO::FETCH_ASSOC)) !== false) {
		echo "<tr class='table-tr-data'><td><p>".$result['note']."</p></td>
              <td class='text-right'><button type='button' class='btn btn-sm btn-danger delNote' id='".$result['id_notes']."'>Del</button></td></tr>";
	}
}

/*Delete note*/
elseif (isset($delNote)) {
	/*Preparation and execution of the request*/
	$sql = "DELETE FROM notes WHERE id_notes = :delNote";
	$statement = $pdo->prepare($sql);
	$statement -> bindValue(':delNote', $delNote, PDO::PARAM_STR);
	$statement -> execute();

		echo "Ok";
}
?>