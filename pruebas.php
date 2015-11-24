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
<!--Put the following in the <head>-->
<script type="text/javascript">
$("document").ready(function(){
  $(".js-ajax-php-json").submit(function(){
    var data = {
      "action": "test"
    };
    data = $(this).serialize() + "&" + $.param(data);
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "response.php", //Relative or absolute path to response.php file
      data: data,
      success: function(data) {
        var hoy = new Date();
        var dayOfMonth = hoy.getDate();
        var resultado = "" + data['result'];
      //  resultado += " " + data['result']['Coste_lab'];
      switch(data['form']){
        case '1':
          info = JSON.parse(data['result']);
        /*  var count = 0;
          for (i in info.pedidoOpt){

            if(info.pedidoOpt[i] != 0){
              fechasPedido[count] = dayOfMonth;
              fechasPedido[count].setDate(dayOfMonth + i);
              count++;
            }
          }*/
          resultado += "JSON-> " + JSON.stringify(info) + "<br />";
        break;
        case '2':
        var uno = 1;
        break;
        default:
        break;

      }
        
        $(".the-return").html(
          "Result: " + data['result'] + "<br />Resultado: " + resultado +"<br />"
        );
        alert("Form submitted successfully.\nReturned json: " + data["json"]);
      }
    });
    return false;
  });
});
</script>

<!--Put the following in the <body>-->
<!--Calculo para medicamento-->
<div class="col-md-3">
  <form action="response.php" class="js-ajax-php-json" method="post" accept-charset="utf-8">
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
    <input type="submit" name="submit" value="Submit form"  /></p>
  </form>
</div>

<div class="col-md-3">
<form action="response.php" class="js-ajax-php-json" method="post" accept-charset="utf-8">
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
  <input type="submit" name="submit" value="Submit form"  />
</form>
</div>

<div class="the-return" id="response">
  [HTML is replaced when successful.]
</div>

<?
include("footer.php");
?>