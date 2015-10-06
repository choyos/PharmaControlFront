<?
include('header.php');

for($i = 0; $i <= 100; $i++){
  $fecha= rand(strtotime("now") - 2419200,strtotime("now"));
  $nombre = "farmaco " . rand(1, 10);
  $cantidad = rand(1, 10);
  //echo date("d-M-Y", $fecha);
  echo "<br/>";
  $sql = "INSERT INTO `registro` (`nombre`, `fecha`, `cantidad`) VALUES ('$nombre', '".date("Y-m-d", $fecha)."', '$cantidad') ON DUPLICATE KEY UPDATE `cantidad` = '$cantidad'";
  echo mysqli_query($conn, $sql);
}

?>