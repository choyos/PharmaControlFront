<?
$title = "Alisamiento exponencial";
include("header.php");

$fin = date("Y-m-d", strtotime("2012-12-13")); //hoy
$inicio = date("Y-m-d", strtotime("2012-09-13"));//2419200 segundos = 4 semanas;
$farmaco = "trastuzumab";

$sql = "SELECT * FROM `registro` WHERE `nombre` LIKE '$farmaco' AND `fecha` <= '$fin' AND `fecha` >= '$inicio' ";
$result = mysqli_query($conn, $sql);

$fechas = array();
$consumos = array();
while($item = mysqli_fetch_assoc($result)){
  array_push($fechas, $item['fecha']);
  array_push($consumos, $item['cantidad']);
}

$alpha = 0.1;
while($alpha <= 1){
$pronosticos = array();
$errors = array();
//$consumos = [200,230,260,180,270,240,250,300,320,350,240,210];
array_push($pronosticos, $consumos[0]);
//$alpha = 0.5;
echo "\nalpha = " . $alpha . "\n";

?>
<table border="1">
  <tr>
    <td>Periodo</td>
    <td>Consumo</td>
    <td>Pronóstico</td>
    <td>Error</td>
  </tr>
  <tr>
    <td><?=$fechas[0]?></td>    
    <td><?=$consumos[0]?></td>
    <td><?=$pronosticos[0]?></td>
    <td><?=abs($pronosticos[0] - $consumos[0])?></td>
  </tr>
<?
array_push($errors, abs($pronosticos[0] - $consumos[0]));
$document = $fechas[0]."\t".$consumos[0] . "\t".$pronosticos[0]. "<br>";

for($i = 1; $i < count($consumos); $i ++){
  $pronosticos[$i] = $consumos[$i-1] * $alpha + (1 - $alpha) * $pronosticos[$i-1];
  ?>
  <tr>
    <td><?=$fechas[$i]?></td>
    <td><?=$consumos[$i]?></td>
    <td><?=$pronosticos[$i]?></td>
    <td><?=abs($pronosticos[$i] - $consumos[$i])?></td>
  </tr>
  <?
  array_push($errors, abs($pronosticos[$i] - $consumos[$i]));
  $document .= $fechas[$i]."\t".$consumos[$i] . "\t".$pronosticos[$i]. "<br>";
  
  }
  ?>
</table>
<?
$average = array_sum($errors) / count($errors);
$alpha = $alpha + 0.1;
echo "error medio: " . $average;
echo "<br><br><br>";
echo $document;die();
}
//echo "<pre>"; print_r($pronosticos);
?>