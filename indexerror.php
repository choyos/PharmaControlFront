<?
$title = "Acceso al portal";
include("header.php");
// Borrar variables de sesión
session_unset(); 

// cerrar la sesión
session_destroy(); 
?>
<h1 id="index-marco">ACCESO USUARIOS</h1>
<div id="acceso" style="text-align: center;">
  <form id="acceso-hospital" method="post" action="/comprueba">
    <br>
    <p>Usuario</p>
    <input type="text" name="nombre" title="nombre" placeholder="Usuario">
    <br>
    <p>Contraseña</p>
    <input type="password" name="clave" title="clave" placeholder="Contraseña">
    <input type="hidden" name="form"/>
    <p><input type="submit" value="Acceder" /></p>
  </form>
  <p style="color: red" >Usuario o clave incorrectos</p>
</div>

 <?
 include("footer.php");
 ?>