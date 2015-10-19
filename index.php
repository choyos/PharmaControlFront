<?php
$title = "Acceso al portal";
include("header.php");
?>
<h1 id="index-marco">ACCESO USUARIOS</h1>
<div id="acceso" style="text-align: center;">
    <p>Usuario</p>
    <input id="usuario" name="nombre" type="text" />
    <p>Clave</p>
    <input id="clave" name="clave" type="password"/><br />
    <input value="Entrar" id="entrar" type="submit" />
    <div id="error"></div>
</div>
<script>
$(document).ready(function() {
    $("#entrar").click(function(){
      if($("#usuario").val() != "" && $("#clave").val() != "") {
          $.post("/comprueba", {nombre: $("#usuario").val(), clave: $("#clave").val()})
            .done(function( data ) {
              if(data == "OK"){
                window.location = "/form";
              }else if(data == "NOK"){
                $('#error').html('<p style="color: red" >Usuario o clave incorrectos</p>')
              }
              });
      } else {
        return false;
      }
    });
});
</script>
 <?
 include("footer.php");
 ?>