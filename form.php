<?
$title = "formulario";
include("header.php");
//yyyy-mm-dd
$fin = date("Y-m-d"); //hoy
$inicio = date("Y-m-d", time() - 2419200);//2419200 segundos = 4 semanas;


  //Querys utiles para el trabajo con los campos select
  $conn = new mysqli("db597300977.db.1and1.com", "dbo597300977", "PharmaControl", "db597300977");
  if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
  }
  $sql_pass = "SELECT id, nombre FROM hospital";
  $sql_farmacos = "SELECT id, nombre FROM farmacos";
  $sql_hospital = "SELECT id, nombre FROM hospital WHERE NOT id = 0";
  $sql_Labs = "SELECT id, nombre FROM laboratorios";

?>

<div class="col-md-3"></div>


<div class="col-md-3">
  <form id="form-registro" action="registro.php">
  <h3>Insertar datos</h3>
  <p><select name="id_farmacy">
      <option value="" disabled selected>Farmaco</option>
      <? foreach($conn->query($sql_farmacos) as $farmaco) {
          echo '<option value= "' . $farmaco['id'] .'" > ' . $farmaco['nombre'].'</option>';
        } 
     ?>
  </select></p>
  <p><select name="inorout">
    <option value="" disabled selected>Entrada/Salida</option>
    <option value="1">Entrada</option>
    <option value="2">Salida</option>
  </select></p>

  <p>Cantidad: <input type="number" id="consumo" name="cantidad"></p>
  <p>Fecha: <input type="date" id="insert-date" name="llegada" value=""></p>
  <input type="submit" value="Enviar">
  <p id="msg-insertar" style="color: red"></p>
  </form>
</div>


<div class="col-md-3">
  <form id="form-consulta" method="post" action="/google-graph" target="_blank">
  <h3>Histórico de datos</h3>
  <p><select name="id_farmacy">
      <option value="" disabled selected>Farmaco</option>
      <? foreach($conn->query($sql_farmacos) as $farmaco) {
          echo '<option value= "' . $farmaco['id'] .'" > ' . $farmaco['nombre'].'</option>';
        } 
     ?>
    </select></p>
  <p>Fecha inicio: <input id="fecha-inicio" type="date" name="inicio" /></p>
  <p>Fecha fin: <input id="fecha-fin" type="date" name="fin" /></p>
  <input type="submit" value="Mostrar" />
  <input type="reset" value="Borrar">
  </form>
</div>


<div class="col-md-3"></div>

<div class="col-md-12">
  <div id="table_div"></div>
</div>

<div class="col-md-12">
  <div id="chart_div"></div>
</div>

<?
include("footer.php");
?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

$(document).ready(function() {
  
  $("#farmaco").change(function(){
    console.log("change");
    drawChartarea();
  });
  
  $('#form-registro').submit(function(){
    
    var fecha = $("#insert-date").val();
    var consumo = $("#consumo").val();
    var farmaco = $("#farmaco").val();
    
    $('#msg-insertar').empty();
    
    if(fecha != "" && consumo != "") {
      
      $.ajax({
        method: "POST",
        url: "registro.php",
        data: { fecha: fecha, consumo: consumo, farmaco: farmaco }
      })
        .done(function( msg ) {
          if(msg){
            $('#msg-insertar').html("Registrado con éxito");
            document.getElementById("msg-insertar").style.color = "green";
          //  drawChartarea();
          }
          else
            $('#msg-insertar').html("Error al registrar");
        });
      
      return false;
    } else {
      alert("Fecha o consumo incompletos");
      return false;
    }
  });
  
  $('#form-consulta').submit(function(){
    if($("#fecha-inicio").val() != "" && $("#fecha-fin").val() != "") {
      return true;
    } else {
      alert("Inicio o fin incompletos");
      return false;
    }
  });

});

google.load("visualization", "1.1", {packages:["corechart", "table"]});
google.setOnLoadCallback(drawChartarea);

function drawChartarea() {
  $.ajax({
    type: "POST",
    url: "/grafica",
    dataType: "json",
    data: { inicio: '<?=$inicio?>', fin: '<?=$fin?>', farmaco_graf: $('#farmaco').val()}
    })
    .done(function( jsonData ) {

          /**********************************************************************
                                  PRINT AREA CHART                                
          **********************************************************************/

          var data = new google.visualization.DataTable();
          data.addColumn('string', 'Fecha');
          data.addColumn('number', 'Consumo');

          for (var i = 0; i < jsonData.data.length; i++) {
            data.addRow([jsonData.data[i].fecha, jsonData.data[i].pedido]);
          }
          var optionsarea = {
            colors: ['red'],
            title: 'Consumo del ' + $('#farmaco').val(),
            hAxis: {title: 'Fecha',  titleTextStyle: {color: '#333'}},
            vAxis: {minValue: 0},
          };
          
          var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
          chart.draw(data, optionsarea);

          /**********************************************************************
                                  PRINT TABLE CHART                                
          **********************************************************************/

          var data = new google.visualization.DataTable();
          data.addColumn('string', 'Fecha');
          data.addColumn('number', 'Consumo ' + $('#farmaco').val());
          for (var i = 0; i < jsonData.data.length; i++) {
            data.addRow([jsonData.data[i].fecha, jsonData.data[i].pedido]);
          }


          console.log(document.getElementById('table_div'));
          var table = new google.visualization.Table(document.getElementById('table_div'));
          table.draw(data, {showRowNumber: true, width: '30%'});
    });
}
</script>