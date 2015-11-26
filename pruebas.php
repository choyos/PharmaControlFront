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
        var resultado = "";
        var msDia = 86400000;
        if(data['estimador'] == 3){
          resultado += "Multiescenarios: " + data['result'];
          $(".the-return").html(
            "Resultado: <br />" + resultado +"<br />"
          );
        }else{
          switch(data['form']){
            case '1':
            info = JSON.parse(data['result']);
              var count = 0;
              var fechasPedido = new Array();
              for (i in info.pedidoOpt){
                if(info.pedidoOpt[i] != 0){
                  fechasPedido.push(new Date());
                  msDate = fechasPedido[count].getTime();
                  fechasPedido[count] = new Date(msDate + i * msDia);
                  resultado += " Fecha: " + fechasPedido[count].getDate() + "/" + (fechasPedido[count].getMonth()+1) + "/" + fechasPedido[count].getFullYear() + " -> Cantidad: " + info.pedidoOpt[i] + "<br />";
                  count++;
                }
              }
              resultado += " Coste: " + info.Coste_med + "<br />";
            break;
            case '2':
            info = JSON.parse(data['result']);
            var count = 0;
            var fechasPedido = new Array();
            var msDate;
            for (var key in info){
              far = key.substr(0, key.indexOf('.'));
              if (key != "Coste_lab"){
                resultado += "" + far + ":<br />";
                for (var key2 in info[key].pedidoOpt){
                  if(info[key].pedidoOpt[key2] != 0){
                    fechasPedido.push(new Date());
                    msDate = fechasPedido[count].getTime();
                    fechasPedido[count] = new Date(msDate + i * msDia);
                    resultado += "- Fecha: " + fechasPedido[count].getDate() + "/" + (fechasPedido[count].getMonth()+1) + "/" + fechasPedido[count].getFullYear() + " -> Cantidad: " + info[key].pedidoOpt[key2] + "<br />";
                    count++;
                  }
                }
                if(count == 0 ){
                  resultado += "- No se requieren de pedidos en este periodo <br />";
                }
                count = 0;
                fechasPedido = [];
              }else{
                resultado += "<br />Coste: " + info[key];
              }

            }

            break;
            case '3':
            resultado += "Hola->";
            default:
            break;
          }
          
          $(".the-return").html(
            "Resultado: <br />" + resultado +"<br />"
          );
        //  alert("Form submitted successfully.\nReturned json: " + data["json"]);
        }
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
      <option value="3">Multiescenarios</option>
    </select>
    <input type="hidden" name="form" value="1" />
    <input type="submit" name="submit" value="Calcular"  /></p>
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
    <!--  <option value="3">Multiescenarios</option>-->
    </select>
    <input type="hidden" name="form" value="2" />
  <input type="submit" name="submit" value="Calcular"  />
</form>
</div>

<!--Calculo para hospital 
<div class="col-md-3">
  <form action="response.php" class="js-ajax-php-json" method="post" accept-charset="utf-8">
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
      <option value="3">Multiescenarios</option>
    </select>
    <input type="hidden" name="form" value="3" />
    <input type="submit" name="submit" value="Calcular"  />
  </form>
</div>
-->
<div class="the-return" id="response">
  Resultado:
</div>

<?
include("footer.php");
?>