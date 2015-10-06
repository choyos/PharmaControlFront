<?php
include_once("header.php");

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "El archivo ". basename( $_FILES["fileToUpload"]["name"]). " fue cargado con éxito.";
} else {
    echo "Error al subir el fichero.";
}

$fichero = fopen($target_file, "r") or die("Imposible abrir el fichero!");
echo"<br>";
if ($fichero) {
    while (($line = fgets($fichero)) !== false) {
      $line = explode("\t", $line); //separar por tabulacion
      $fecha = date("Y-m-d", strtotime($line[0]));
      $consumo = -floatval($line[1]);
      $farmaco = "trastuzumab";
      
      $sql = "INSERT INTO `registro`(`nombre`, `cantidad`, `fecha`) VALUES ('".$farmaco."', ".$consumo.", '".$fecha."') ON DUPLICATE KEY UPDATE `cantidad` = ".$consumo;
      echo "fecha: " . $fecha . " consumo: " . -intval($line[1]) . " Query: " .mysqli_query($conn, $sql). "<br>";
    }
    fclose($fichero);
} else {
    // error opening the file.
}

?>