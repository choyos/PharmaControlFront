<?
$conn = mysqli_connect("db597300977.db.1and1.com", "dbo597300977", "PharmaControl", "db597300977");
mysqli_set_charset($conn, "utf8");
// Check connection
if (mysqli_connect_errno()){
  echo "Imposible conectar: " . mysqli_connect_error();
}

$form = $_POST['form'];

switch ($form) {
	
	case "1":
	  $sql = "INSERT INTO `usuarios`(`user`, `password`, `id_hospital`) VALUES ('".$_POST['nombre']."', '".$_POST['clave']."', '".$_POST['id_hospital']."')";
	  mysqli_query($conn, $sql);
	  break;
	case "2":
	  $sql = "DELETE FROM `usuarios` WHERE user = '".$_POST['nombre']."' AND id_hospital = '".$_POST['id_hospital']."'";
	  mysqli_query($conn, $sql);
	  break;
	case "3":
	  $sql = "INSERT INTO `farmacos`(`nombre`, `coste_almacenamiento`, `coste_oportunidad`, `coste_pedido`, `coste_recogida`,`coste_sin_stock`,`precio_med`, `minimo_uds`,`maximo_uds`, `id_lab`, `stock`) VALUES ('".$_POST['nombre']."', '".$_POST['coste_almacenamiento']."', '".$_POST['coste_oportunidad']."', '".$_POST['coste_pedido']."', '".$_POST['coste_recogida']."', '".$_POST['coste_sin_stock']."', '".$_POST['precio_med']."', '".$_POST['minimo_uds']."', '".$_POST['maximo_uds']."', '".$_POST['id_lab']."', '".$_POST['stock']."')";
	  mysqli_query($conn, $sql);
	  if(mysql_error()){
	  	echo "ERROR INSERT farmacos";
	  }
	  $sql = "SELECT id FROM `farmacos` WHERE nombre = '".$_POST['nombre']."' AND id_lab = '".$_POST['id_lab']."'";
	  foreach ($conn->query($sql) as $aux) {
	  	$id_farm = $aux['id'];
	  }
	  $tamPedidos = explode(',', $_POST['incremento_uds']);
	  foreach ($tamPedidos as $tamPedido) {
	  	$sql = "INSERT INTO `tamanos_pedidos` (`tam_pedido`, `id_farmaco`) VALUES ('".$tamPedido."', '".$id_farm."') ";
	  	mysqli_query($conn, $sql);
	  }
	  break;
	case "4":
	  $sql = "DELETE FROM `farmacos` WHERE nombre = '".$_POST['nombre']."' AND id_lab = '".$_POST['id_lab']."'";
	  mysqli_query($conn, $sql);
	  break;
	case "5":
	  $sql = "INSERT INTO `laboratorios`(`nombre`, `retraso_pedido`, `id_hospital`) VALUES ('".$_POST['nombre']."', '".$_POST['retraso_pedido']."', '".$_POST['id_hospital']."')";
	  mysqli_query($conn, $sql);
	  break;
	case "6":
	  $sql = "DELETE FROM `laboratorios` WHERE nombre = '".$_POST['nombre']."' AND id_hospital = '".$_POST['id_hospital']."'";
	  mysqli_query($conn, $sql);
	  break;
	case "7":
	  $sql = "INSERT INTO `hospital`(`nombre`) VALUES ('".$_POST['nombre']."')";
	  mysqli_query($conn, $sql);
	  break;
	case "8":
	  $sql = "DELETE FROM `hospital` WHERE nombre = '".$_POST['nombre']."'";
	  mysqli_query($conn, $sql);
	  break;
	default:
	  echo 'Error';
	  break;
}

header('Location: /panel-de-control');
?>