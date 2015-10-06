<?
$conn = mysqli_connect("db560723553.db.1and1.com", "dbo560723553", "h0spital", "db560723553");
mysqli_set_charset($conn, "utf8");
// Check connection
if (mysqli_connect_errno()){
  echo "Imposible conectar: " . mysqli_connect_error();
}

$form = $_POST['form'];
if($form == 1){
  $sql = "INSERT INTO `usuarios`(`user`, `password`, `permisos`) VALUES ('".$_POST['nombre']."', '".$_POST['clave']."', '".$_POST['permisos']."')";
  mysqli_query($conn, $sql);
}elseif($form == 2){
  $sql = "DELETE FROM `usuarios` WHERE user = '".$_POST['nombre']."' AND permisos = '".$_POST['permisos']."'";
  mysqli_query($conn, $sql);
  
}elseif($form == 3){
  $sql = "INSERT INTO `farmacos`(`nombre`, `coste_almacenamiento`, `retraso_pedido`, `minimo_uds`, `maximo_uds`, `incremento_uds`) VALUES ('".$_POST['nombre']."', '".$_POST['coste_almacenamiento']."', '".$_POST['retraso_pedido']."', '".$_POST['minimo_uds']."', '".$_POST['maximo_uds']."', '".$_POST['incremento_uds']."')";
  mysqli_query($conn, $sql);
  
}else{
  $sql = "DELETE FROM `farmacos` WHERE nombre = '".$_POST['nombre']."' AND hospital = '".$_POST['hospital']."'";
  mysqli_query($conn, $sql);
}

header('Location: /panel-de-control');
?>