<?php
		$user = $_POST['nombre'];
		$password = $_POST['clave'];
		//conexion con el servidor My_sql.
		$conn = mysqli_connect("db597300977.db.1and1.com", "dbo597300977", "PharmaControl", "db597300977") or die('No se pudo conectar: ' . mysql_error());

		// Realizar una consulta MySQL
		$sql = "SELECT * FROM usuarios WHERE user = '$user'";
		$result = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($result);

		if($password == $result['password']){
      //echo "correcta";
      session_start();
      $_SESSION["usuario"] = $user;
      $_SESSION["permisos"] = $result['id_hospital'];
      echo "OK";
      //header('Location: /form');//clave correcta
    }else {
      //echo "incorrecta";
      echo "NOK";
      //header('location: /indexerror');//clave incorrecta
    }

// Cerrar la conexión
mysql_close($conn);
?>
