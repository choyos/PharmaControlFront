<?
$title = "formulario";
include("header.php");
//yyyy-mm-dd
$fin = date("Y-m-d"); //hoy
$inicio = date("Y-m-d", time() - 2419200);//2419200 segundos = 4 semanas;

?>

<div class="col-md-3"></div>


<div class="col-md-3">
  <form id="form-registro">
  <h3>Insertar datos</h3>
  <p>Fármaco: <select id="farmaco" name="farmaco">
                  <option value="farmaco 1" selected="selected">Fármaco 1</option>
                  <option value="farmaco 2">Fármaco 2</option>
                  <option value="farmaco 3">Fármaco 3</option>
                  <option value="farmaco 4">Fármaco 4</option>
                  <option value="farmaco 5">Fármaco 5</option>
                  <option value="farmaco 6">Fármaco 6</option>
                  <option value="trastuzumab">Trastuzumab</option>
              </select></p>
  <p>Cantidad: <input type="number" id="consumo" name="cantidad"></p>
  <p>Fecha: <input type="date" id="insert-date" name="llegada"></p>
  <input type="submit" value="Enviar">
  <input type="reset" value="Borrar">
  <p id="msg-insertar" style="color: red"></p>
  </form>
</div>


<div class="col-md-3">
  <form id="form-consulta" method="post" action="/google-graph" target="_blank">
  <h3>Histórico de datos</h3>
  <p>Fármaco: <select name="farmaco_graf">
                <option value="farmaco 1" selected="selected">Fármaco 1</option>
                <option value="farmaco 2">Fármaco 2</option>
                <option value="farmaco 3">Fármaco 3</option>
                <option value="farmaco 4">Fármaco 4</option>
                <option value="farmaco 5">Fármaco 5</option>
                <option value="farmaco 6">Fármaco 6</option>
                <option value="trastuzumab">Trastuzumab</option>
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
        url: "insertar.php",
        data: { fecha: fecha, consumo: consumo, farmaco: farmaco }
      })
        .done(function( msg ) {
          if(msg){
            $('#msg-insertar').html("Registrado con éxito");
            drawChartarea();
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