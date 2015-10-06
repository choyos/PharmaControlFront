<?
//$title = "Media aritmética";
//include("header.php");

$conn = mysqli_connect("db560723553.db.1and1.com", "dbo560723553", "h0spital", "db560723553");
//echo "<pre>";var_dump($conn);die();
mysqli_set_charset($conn, "utf8");
// Check connection
if (mysqli_connect_errno()){
  echo "Imposible conectar: " . mysqli_connect_error();
}


$inicio = $_GET['inicio']; //yyyy-mm-dd
$fin = $_GET['fin'];

$fin = date("Y-m-d"); //hoy
$inicio = date("Y-m-d", time() - 2419200);//2419200 segundos = 4 semanas;

$farmaco = urldecode($_GET['farmaco']);

//Fichero donde escribiremos
$file = 'OFH/datos.pha';
//Abrimos el fichero
$contenido = file_get_contents($file);
//Borramos el fichero
$contenido = "";

$days = strtotime($fin) - strtotime($inicio);
$days = $days / 86400;

$sql = "SELECT cantidad, fecha FROM `registro` WHERE `nombre` LIKE '$farmaco' AND `fecha` <= '$fin' AND `fecha` >= '$inicio' ";
//echo $sql;die();
$result = mysqli_query($conn, $sql);
$sum = 0;
while($data = mysqli_fetch_assoc($result)){
  $sum += $data['cantidad'];
  echo $data['cantidad'] . "<br>";
  $contenido .= $data['cantidad'] . "\n";
}

$media = $sum / mysqli_affected_rows($conn);
echo "media: " . round($media);

for($i= 40-mysqli_affected_rows($conn); $i>0; $i--){
  $contenido .= round($media) . "\n";
}

// Escribir en el fichero
file_put_contents($file, trim($contenido, "\n"));

?>