<?
/******************************************************************************************************************************************
TODAS LAS FECHAS SON EN SEGUNDOS

$from = fecha de inicio en el eje x 
$to = fecha final en el eje x
$fecha = tabla con las fechas en las que se han insertado datos
        de un determinado medicamento.

$cantidad = cantidad de medicamento gastada en un determinado día.

******************************************************************************************************************************************/

function get_x_axis($from, $to){
  $x_axis = "'" . date("F j", $from) . "'";
  $actual = $from;
  while($actual < $to){
    $actual += (60 * 60 * 24);
    $x_axis .= ", '" . date("F j", $actual). "'";
  }
  echo $x_axis;
}

function get_y_axis($from, $to, $fecha, $cantidad){
  $y_axis = $cantidad[0];
  $actual = $from;
  $index = 1;
  while($actual < $to){
    $actual += (60 * 60 * 24);
    if($actual == $fecha[$index]){
      $y_axis .= ", " . $cantidad[$index];
      $index ++;
    }else{
      $y_axis .= ", 0";
    }
  }
  echo $y_axis;
}
?>