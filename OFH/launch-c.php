<?
$output = shell_exec('./OFH 10 5');
//echo $output;die();
$output = explode("\n", trim($output, "\n"));

if(trim($output[0]) == "ERROR9: No existe ninguna posibilidad válida para nuestro problema"){
  echo json_encode(array("status" => "ERROR9"));
  die();
}

//Quitamos el inicio de la frase
$pedidos = str_replace("Pedido:", "", $output[1]);
$optimos = str_replace("Optimo:", "", $output[2]);

/*****************************************************
Separamos por espacios y quitamos los de inicio y fin
La posición cero del array se corresponde con el día 
de hoy y así sucesivamente.
******************************************************/
$pedidos = explode(" ", trim($pedidos));
$optimos = explode(" ", trim($optimos));

$date = date("Y-m-d");//Día de hoy

$data = array();
$i = 0;
for($i=0; $i<count($pedidos); $i++){
  array_push($data, array("fecha" => $date, "pedido" => intval($pedidos[$i]), "optimo" => intval($optimos[$i])));
  $date = date("Y-m-d", strtotime($date) + 86400);
}
echo json_encode(array("status" => "OK", "data" => $data));
?>
