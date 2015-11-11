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

$sql = "INSERT INTO `registro`(`nombre`, `cantidad`, `fecha`) VALUES ('".$farmaco."', ".$consumo.", '".$fecha."') ON DUPLICATE KEY UPDATE `cantidad` = ".$consumo;

echo mysqli_query($conn, $sql);

?>