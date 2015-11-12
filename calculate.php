<?
$title = "formulario";
include("header.php");
//yyyy-mm-dd
$fin = date("Y-m-d"); //hoy
$inicio = date("Y-m-d", time() - 2419200);//2419200 segundos = 4 semanas;

?>


<?php //Querys utiles para el trabajo con los campos select
  $conn = new mysqli("db597300977.db.1and1.com", "dbo597300977", "PharmaControl", "db597300977");
  if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
  }
  $sql_pass = "SELECT id, nombre FROM hospital";
  $sql_hospital = "SELECT id, nombre FROM hospital WHERE NOT id = 0";
  $sql_Labs = "SELECT id, nombre FROM laboratorios";
  $sql_farmacos = "SELECT id, nombre FROM farmacos";
?>

<h2 style="color: red; margin-left: 15px">Cálculo de pedidos de <?echo ucfirst($_SESSION['usuario'])?></h2>

<!--Calculo para hospital-->
<div class="col-md-3">
  <form id="calcular-farmaco" method="post" action="/admin-calculate">
    <fieldset>
    <legend>Calcular por fármaco</legend>
    <select name="id_farmacy">
      <option value="" disabled selected>Farmaco</option>
      <? foreach($conn->query($sql_farmacos) as $farmaco) {
          echo '<option value= "' . $farmaco['id'] .'" > ' . $farmaco['nombre'].'</option>';
        } 
     ?>
    </select>
    <br>
    <input type="number" name="horizonte" title="Horizonte de días de cálculo" placeholder="Horizonte de días de cálculo">
    <br>
    <input type="number" name="numpedidos" title="Número de días de pedido" placeholder="Número de días de pedido">
    <p>Estimador: </p>
    <select name="estimador">
      <option value="0">Ninguno</option>
      <option value="1">Lineal</option>
      <option value="2">Alisamiento Exponencial</option>
    </select>
    <input type="hidden" name="form" value="1" />
    <p><input type="submit" value="Calcular" /></p>
    </fieldset>
  </form>
</div>

<!--Calculo para laboratorio-->
<div class="col-md-3">
  <form id="calcular-laboratorio" method="post" action="/admin-calculate">
    <fieldset>
    <legend>Calcular por laboratorio</legend>
    <select name="id_lab">
      <option value="" disabled selected>Laboratorio</option>
      <? foreach($conn->query($sql_Labs) as $lab) {
          echo '<option value= "' . $lab['id'] .'" > ' . $lab['nombre'].'</option>';
        } 
     ?>
    </select>
    <br>
    <input type="number" name="horizonte" title="Horizonte de días de cálculo" placeholder="Horizonte de días de cálculo">
    <br>
    <input type="number" name="numpedidos" title="Número de días de pedido" placeholder="Número de días de pedido">
    <p>Estimador: </p>
    <select name="estimador">
      <option value="0">Ninguno</option>
      <option value="1">Lineal</option>
      <option value="2">Alisamiento Exponencial</option>
    </select>
    <input type="hidden" name="form" value="2" />
    <p><input type="submit" value="Calcular" /></p>
    </fieldset>
  </form>
</div>

<!--Calculo para hospital-->
<div class="col-md-3">
  <form id="calcular-hospital" method="post" action="/admin-calculate">
    <fieldset>
    <legend>Calcular por hospital</legend>
    <select name="id_hospital">
      <option value="" disabled selected>Hospital</option>
      <? foreach($conn->query($sql_hospital) as $hospital) {
          echo '<option value= "' . $hospital['id'] .'" > ' . $hospital['nombre'].'</option>';
        } 
     ?>
    </select>
    <br>
    <input type="number" name="horizonte" title="Horizonte de días de cálculo" placeholder="Horizonte de días de cálculo">
    <br>
    <input type="number" name="numpedidos" title="Número de días de pedido" placeholder="Número de días de pedido">
    <p>Estimador: </p>
    <select name="estimador">
      <option value="0">Ninguno</option>
      <option value="1">Lineal</option>
      <option value="2">Alisamiento Exponencial</option>
    </select>
    <input type="hidden" name="form" value="3" />
    <p><input type="submit" value="Calcular" /></p>
    </fieldset>
  </form>
</div>

<div class="col-md-3"></div>

<div class="col-md-12">
  <div id="columnchart_material"></div>
</div>


<?
include("footer.php");
?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

$(document).ready(function() {
  
  $('#estimador').change(function(){
    var estimador = $('#estimador').val();
    var farmaco = $('#farmaco').val();
    console.log("change: " + estimador);
  })
  
  
  
  $("#calculate").click(function(){
    
    var estimador = $('#estimador').val();
    var farmaco = $('#farmaco').val();
    console.log("calculate: " + farmaco);
    
    //
    //Aquí meter la petición Ajax que escriba los datos en el fichero datos.pha
    //
    
    drawChart();
  });
});

google.load("visualization", "1.1", {packages:["bar"]});
//google.setOnLoadCallback(drawChart);
      function drawChart() {
        $.ajax({
          url: "OFH/launch-c",
          dataType: "json",
          success: function (jsonData) {
            if(jsonData.status == "OK"){
              var data = new google.visualization.DataTable();
              data.addColumn('string', 'Fecha');
              data.addColumn('number', 'Pedido');
              data.addColumn('number', 'Óptimo');
            

              for (var i = 0; i < jsonData.data.length; i++) {
                data.addRow([jsonData.data[i].fecha, jsonData.data[i].pedido, jsonData.data[i].optimo]);
              }

              var options = {
                chart: {
                  title: 'Pedido a Realizar',
                  subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                }
              };

              var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
              chart.draw(data, options);
            }else{
              console.log("status: " + jsonData.status);
            }
          }
        });
      }
</script>