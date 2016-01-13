<?
$conn = mysqli_connect("db597300977.db.1and1.com", "dbo597300977", "PharmaControl", "db597300977");
//echo "<pre>";var_dump($conn);die();
mysqli_set_charset($conn, "utf8");
// Check connection
if (mysqli_connect_errno()){
  echo "Imposible conectar: " . mysqli_connect_error();
}

$fecha = $_POST['fecha'];
$consumo = $_POST['consumo'];
$farmaco = $_POST['farmaco'];
$tipo = $_POST['tipo'];

//Obtenemos stock actual
$sqlStock = "SELECT * FROM `farmacos` WHERE id = '".$farmaco."'";

foreach ($conn->query($sqlStock) as $value) {
	$stockAct = $value['stock'];
}

//Actualizamos el stock actual
if($tipo == 1){
	$stockAct = $stockAct - $consumo;
}else if($tipo == 2){
	$stockAct = $stockAct + $consumo;
}

//Lo actualizamos en la base de datos

$sqlStock = "UPDATE `farmacos` SET stock = '" . $stockAct . "' WHERE id = '".$farmaco."'";
mysqli_query($conn, $sqlStock);


//Insertamos el valor del registro
$sql = "INSERT INTO `registros`(`id_farmaco`, `cantidad`, `fecha`, `tipo`) VALUES ('".$farmaco."', ".$consumo.", '".$fecha."', '".$tipo."') ON DUPLICATE KEY UPDATE `cantidad` = ".$consumo;

echo mysqli_query($conn, $sql);

?>