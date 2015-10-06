<?
$title = "formulario";
include("header.php");
//yyyy-mm-dd
$fin = date("Y-m-d"); //hoy
$inicio = date("Y-m-d", time() - 2419200);//2419200 segundos = 4 semanas;

?>

<div class="col-md-3"></div>

<div class="col-md-6">
  <p>Supplied directly pleasant we ignorant ecstatic of jointure so if. These spoke house of we. Ask put yet excuse person see change. Do inhabiting no stimulated unpleasing of admiration he. Enquire explain another he in brandon enjoyed be service. Given mrs she first china. Table party no or trees an while it since. On oh celebrated at be announcing dissimilar insipidity. Ham marked engage oppose cousin ask add yet.</p>

  <p>Started his hearted any civilly. So me by marianne admitted speaking. Men bred fine call ask. Cease one miles truth day above seven. Suspicion sportsmen provision suffering mrs saw engrossed something. Snug soon he on plan in be dine some.</p><br><br>
  
  <p>Estimador: <select id="estimador">
    <option value="">Ninguno</option>
    <option value="lineal">Lineal</option>
    <option value="alisamiento">Alisamiento Exponencial</option>
  </select></p>
  <p>Fármaco: <select id="farmaco" name="farmaco">
                  <option value="farmaco 1" selected="selected">Fármaco 1</option>
                  <option value="farmaco 2">Fármaco 2</option>
                  <option value="farmaco 3">Fármaco 3</option>
                  <option value="farmaco 4">Fármaco 4</option>
                  <option value="farmaco 5">Fármaco 5</option>
                  <option value="farmaco 6">Fármaco 6</option>
                  <option value="trastuzumab">Trastuzumab</option>
              </select></p>
  <input type="button" value="Calcular" id="calculate"/>
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