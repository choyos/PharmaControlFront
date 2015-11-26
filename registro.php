<?
	//Acceso al formulario
	$conn = mysqli_connect("db597300977.db.1and1.com", "dbo597300977", "PharmaControl", "db597300977");
	mysqli_set_charset($conn, "utf8");
	// Check connection
	if (mysqli_connect_errno()){
	echo "Imposible conectar: " . mysqli_connect_error();
	}

	switch ($_POST['inorout']) {
		case '1':
			$sql = "SELECT stock FROM `farmacos` WHERE id ='".$_POST['id_farmacy']."'";
			foreach ($conn->query($sql) as $value) {
				$stockAct = $value;
			}
			$stockAct = $stockAct + $_POST['cantidad'];
			$sql = "UPDATE `farmacos` SET stock = '" . $stockAct . "' WHERE id = '".$_POST['id_farmacy']."'";
			$sql = "INSERT INTO `registros` (`cantidad`,`id_farmaco`,`fecha`, `tipo`) VALUES ('".$_POST['cantidad']."','".$_POST['id_farmacy']."','".$_POST['llegada']."','".$_POST['inorout']."')";
			mysqli_query($conn, $sql);
		break;
		case '2':
			$sql = "SELECT stock FROM `farmacos` WHERE id ='".$_POST['id_farmacy']."'";
			foreach ($conn->query($sql) as $value) {
				$stockAct = $value;
			}
			$stockAct = $stockAct - $_POST['cantidad'];
			$sql = "UPDATE `farmacos` SET stock = '".$stockAct."' WHERE id = '".$_POST['id_farmacy']."'";
			$sql = "INSERT INTO `registros` (`cantidad`,`id_farmaco`,`fecha`, `tipo`) VALUES ('".$_POST['cantidad']."','".$_POST['id_farmacy']."','".$_POST['llegada']."','".$_POST['inorout']."')";
			mysqli_query($conn, $sql);
		break;
		default:
			# code...
			break;
	}

	echo True;
	header('Location: /form');
?>