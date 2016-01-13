<?
$conn = mysqli_connect("db597300977.db.1and1.com", "dbo597300977", "PharmaControl", "db597300977");
mysqli_set_charset($conn, "utf8");
// Check connection
if (mysqli_connect_errno()){
  echo "Imposible conectar: " . mysqli_connect_error();
}
  
$inicio = $_POST['inicio']; //yyyy-mm-dd
$fin = $_POST['fin'];
$farmaco = urldecode($_POST['farmaco_graf']);

$days = strtotime($fin) - strtotime($inicio);
$days = $days / 86400;

$sql = "SELECT * FROM `registros` WHERE `id_farmaco` = '".$_POST['id_farmacy']."' AND `fecha` <= '$fin' AND `fecha` >= '$inicio' ORDER BY `fecha` ";
//echo $sql;die();
$result = mysqli_query($conn, $sql);
//echo mysqli_affected_rows($conn);
$index = 0;
$response = array();

while($block = mysqli_fetch_assoc($result)){
  array_push($response, array("fecha" => $block['fecha'], "pedido" => intval($block['cantidad'])));
}
echo json_encode(array("status" => "OK", "data" => $response));
?>