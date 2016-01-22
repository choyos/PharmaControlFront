<?
$title = "formulario";
include("header.php");
//yyyy-mm-dd
$fin = date("Y-m-d"); //hoy
$inicio = date("Y-m-d", time() - 2419200);//2419200 segundos = 4 semanas;

?>


<?php //Querys utiles para el trabajo con los campos select
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
        
        //Selecionamos entre los dos tipos de posibilidades que hay: Medicamento o Laboratorio
        switch(data['form']){

          //Por medicamento
          case '1':
          //Parseo de la informacion para poder grabajar con ella en formato JSON
          info = JSON.parse(data['result']);
            var count = 0;
            var fechasPedido = new Array();
            //Obtenemos las fechas de pedido de farmaco a partir del vector pedidoOpt
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
          
          //Por laboratorio
          case '2':
          //Parseo de la informacion para poder grabajar con ella en formato JSON
          info = JSON.parse(data['result']);
          var count = 0;
          var fechasPedido = new Array();
          var msDate;
          for (var key in info){
            name = key.substr(0, key.indexOf('.'));
            far = name.replace(/_/g,' ');
            if (key != "Coste_lab"){
              resultado += "" + far + ":<br />";
              //Obtenemos las fechas de pedido de farmaco a partir del vector pedidoOpt
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
    <input type="number" min="1" max="15" style="width: 200px" name="horizonte" title="Horizonte de días de cálculo" placeholder="Horizonte de días de cálculo">
    <br>
    <input type="number" min="1" max="8" style="width: 200px" name="numpedidos" title="Número de días de pedido" placeholder="Número de días de pedido">
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
    <input type="number" min="1" max="15" style="width: 200px" name="horizonte" title="Horizonte de días de cálculo" placeholder="Horizonte de días de cálculo">
    <br>
    <input type="number" min="1" max="8" style="width: 200px" name="numpedidos" title="Número de días de pedido" placeholder="Número de días de pedido">
    <p>Estimador: </p>
    <select name="estimador">
      <option value="0">Ninguno</option>
      <option value="1">Lineal</option>
      <option value="2">Alisamiento Exponencial</option>
      <option value="3">Multiescenarios</option>
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