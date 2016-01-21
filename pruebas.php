<?
$title = "formulario";
include("header.php");
//yyyy-mm-dd
$fin = date("Y-m-d"); //hoy
$inicio = date("Y-m-d", time() - 2419200);//2419200 segundos = 4 semanas;

?>


<?php //Querys utiles para el trabajo con los campos select
  $sql_Labs = "SELECT id, nombre FROM laboratorios";
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
      url: "respruebas.php", //Relative or absolute path to response.php file
      data: data,
      

      success: function(data) {
        var resultado = "";
        var msDia = 86400000;
        
      
        resultado = data['result'];
        //Parseo de la informacion para poder grabajar con ella en formato JSON
        /*info = JSON.parse(data['result']);
        var count = 0;
        var fechasPedido = new Array();
        var msDate;
        for (var key in info){
          far = key.substr(0, key.indexOf('.'));
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
          */
        
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
      <option value="3">Multiescenarios</option>
    </select>
    <input type="hidden" name="form" value="2" />
  <input type="submit" name="submit" value="Calcular"  />
</form>
</div>

<div class="the-return" id="response">
  Resultado:
</div>

<?
include("footer.php");
?>