<?
$title = "Gráficas";
include("header-graph.php");





$inicio = $_POST['inicio']; //yyyy-mm-dd
$fin = $_POST['fin'];
$farmaco = urldecode($_POST['farmaco_graf']);

$days = strtotime($fin) - strtotime($inicio);
$days = $days / 86400;

/****************************************
***OBTENEMOS STOCK INICIAL DEL PERIODO***
*****************************************/
$sql2 = "SELECT * FROM `farmacos` WHERE `id` = ".$_POST['id_farmacy']."";
$result = mysqli_query($conn, $sql2);
while($block = mysqli_fetch_assoc($result)){
  $stockIni = $block['stock'];
  ?>
  <h3>Gráficas de fármaco: <?echo ucfirst($block['nombre'])?></h3>
  <?
}
$sql = "SELECT * FROM `registros` WHERE `id_farmaco` = ".$_POST['id_farmacy']." AND `fecha` >= '".$_POST['inicio']."' ORDER BY `fecha` DESC";

$result = mysqli_query($conn, $sql);
while($block = mysqli_fetch_assoc($result)){
  if($block['tipo'] == 1){
    $stockIni = $stockIni + $block['cantidad'];
  }else{
    $stockIni = $stockIni - $block['cantidad'];
  }
   
}



/*******************************************
**GENERAMOS LOS VECTORES PARA LAS GRAFICAS**
********************************************/
$sql = "SELECT * FROM `registros` WHERE `id_farmaco` = ".$_POST['id_farmacy']." AND `fecha` <= '".$_POST['fin']."' AND `fecha` >= '".$_POST['inicio']."' ORDER BY `fecha`";

//echo $sql;die();
$result = mysqli_query($conn, $sql);
//echo mysqli_affected_rows($conn);
$index = 0;
while($block = mysqli_fetch_assoc($result)){
  if($index == 0){
    
    if($block['tipo'] == 1){
     
      $bar = "['".$block['fecha']."', ".$block['cantidad']."]";

      $stockIni = $stockIni - $block['cantidad'];
      $area = "['".$block['fecha']."', ".$stockIni."]";
    }else{

      $bar = "['".$block['fecha']."', ".$index."]";
      $stockIni = $stockIni + $block['cantidad'];
      $area = "['".$block['fecha']."', ".$stockIni."]";
    }
    
    $area = "['".$block['fecha']."', ".$block['cantidad']."]";
  }else{
    if($block['tipo'] == 1){
      $bar .= ",['".$block['fecha']."', ".$block['cantidad']."]";

      $stockIni = $stockIni - $block['cantidad'];
      $area .= ",['".$block['fecha']."', ".$stockIni."]";
      
    }else{
      $stockIni = $stockIni + $block['cantidad'];
      $area .= ",['".$block['fecha']."', ".$stockIni."]";
    }
  }
  $index++;
}

//echo $bar;
//echo $calendar;

$index = 0;
if(mysqli_num_rows($result) == 0){
  ?><p align="middle">No existen datos en la fecha seleccionada para <?=$farmaco?> </p><?
  include("footer.php");
  die();
}

/*while($block = mysqli_fetch_assoc($result)){
$cantidad[$index] = $block['cantidad'];
$fecha[$index] = strtotime($block['fecha']); //AMOUNT OF SECONDS, tabla con los dias del mes donde se ha recogido info
$index ++;
}*/
?>
<?
/*********************************************************************

                  GRAFICA DE BARRAS

*********************************************************************/
?>
<script type="text/javascript">
  google.load("visualization", "1.1", {packages:["bar"]});
  google.setOnLoadCallback(drawChartbar);
  function drawChartbar() {
    var databar = google.visualization.arrayToDataTable([
      ['Fecha', 'Consumo'],
      <?=$bar?>
    ]);

    var optionsbar = {
      chart: {
        title: 'Consumo',
        subtitle: 'Desde el <?=$inicio?> al <?=$fin?> de <?=$farmaco?>' ,
      },
      bars: 'horizontal' // Required for Material Bar Charts.
    };

    var chart = new google.charts.Bar(document.getElementById('barchart_material'));

    chart.draw(databar, optionsbar);
  }
</script>
<div id="barchart_material" style="width: 60%; height: 40%; margin: auto; margin-top: 5%;" align="center" ></div>
<?
/*********************************************************************

                  GRAFICA DE ÁREA

*********************************************************************/
?>
<script type="text/javascript">
  google.load("visualization", "1.1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChartarea);
  function drawChartarea() {
    var dataarea = google.visualization.arrayToDataTable([
      ['Fecha', 'Stock'],
      <?=$area?>
    ]);

    var optionsarea = {
      colors: ['red'],
      title: 'Stock',
      hAxis: {title: 'Fecha',  titleTextStyle: {color: '#333'}},
      vAxis: {minValue: 0}
    };

    var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
    chart.draw(dataarea, optionsarea);
  }
</script>
<div id="chart_div" style="width: 60%; height: 40%; margin: auto; margin-top: 5%;" align="center" ></div>
<?
/*********************************************************************

                  GRAFICA TIPO CALENDARIO

*********************************************************************/
/*?>
<script type="text/javascript">
  google.load("visualization", "1.1", {packages:["calendar"]});
  google.setOnLoadCallback(drawChart);

function drawChart() {
   var dataTable = new google.visualization.DataTable();
   dataTable.addColumn({ type: 'date', id: 'Date' });
   dataTable.addColumn({ type: 'number', id: 'Won/Loss' });
   dataTable.addRows([
      [new Date(2015, 1, 1), 0 ], [ new Date(2015, 1, 3), 650 ], [ new Date(2015, 1, 5), 6 ], [ new Date(2015, 1, 9), 60 ], [ new Date(2015, 1, 22), 230 ]
    ]);

   var chart = new google.visualization.Calendar(document.getElementById('calendar_basic'));

   var options = {
     title: "Red Sox Attendance",
     height: 350,
   };

   chart.draw(dataTable, options);
}
</script>
<div id="calendar_basic" style="width: 1000px; height: 350px;"></div>
<?*/
include("footer.php");
?>