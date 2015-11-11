<?
$title = "Panel de control";
include("header.php");
?>

<?php //Querys utiles para el trabajo con los campos select
  $conn = new mysqli("db597300977.db.1and1.com", "dbo597300977", "PharmaControl", "db597300977");
  if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
  }
  $sql_pass = "SELECT id, nombre FROM hospital";
  $sql_hospital = "SELECT id, nombre FROM hospital WHERE NOT id = 0";
  $sql_Labs = "SELECT id, nombre FROM laboratorios";
?>

<h2 style="color: red; margin-left: 15px">Panel de Control de <?echo ucfirst($_SESSION['usuario'])?></h2>
<div class="col-md-3">
  <form id="nuevo-usuario" method="post" action="/admin-panel-control">
    <p>Añadir usuario</p>
    <input type="text" name="nombre" placeholder="nombre">
    <input type="text" name="clave" placeholder="clave">
    <select name="id_hospital">
      <option value="" disabled selected>Permisos/Hospital</option>
      <? foreach($conn->query($sql_pass) as $hospital) {
          echo '<option value= "' . $hospital['id'] .'" > ' . $hospital['nombre'].'</option>';
        } 
     ?>
    </select>
    <input type="hidden" name="form" value="1" />
    <p><input type="submit" value="Añadir" /></p>
  </form>
</div>


<div class="col-md-3">
  <form id="eliminar-usuario" method="post" action="/admin-panel-control">
    <p>Eliminar usuario</p>
    <input type="text" name="nombre" placeholder="nombre">
    <select name="id_hospital">
      <option value="" disabled selected>Permisos/Hospital</option>
      <? foreach($conn->query($sql_pass) as $hospital) {
          echo '<option value= "' . $hospital['id'] .'" > ' . $hospital['nombre'].'</option>';
        } 
     ?>
    </select>
    <input type="hidden" name="form" value="2" />
    <p><input type="submit" value="Eliminar" /></p>
  </form>
</div>


<div class="col-md-3">
  <form id="nuevo-farmaco" method="post" action="/admin-panel-control">
    <p>Añadir fármaco</p>
    <input type="text" name="nombre" placeholder="nombre">
    <input type="number" name="coste_almacenamiento" placeholder="coste almacenamiento" step="0.01">
    <input type="number" name="coste_oportunidad" placeholder="coste de oportunidad" step="0.01">
    <input type="number" name="coste_pedido" placeholder="coste de pedido" step="0.01">
    <input type="number" name="coste_recogida" placeholder="coste de recogida" step="0.01">
    <input type="number" name="coste_sin_stock" placeholder="coste sin stock" step="0.01">
    <input type="number" name="precio_med" placeholder="precio medicamento" step="0.01">
    <input type="number" name="minimo_uds" placeholder="minimo">
    <input type="number" name="maximo_uds" placeholder="máximo">
    <input type="number" name="stock" placeholder="stock actual">
    <input type="text" name="incremento_uds" title="Tamaños de pedido (separar con comas sin espacio)" placeholder="Tamaños de pedido (separar con comas sin espacio)">
    <select name="id_lab">
      <option value="" disabled selected>Laboratorio</option>
      <? foreach($conn->query($sql_Labs) as $lab) {
          echo '<option value= "' . $lab['id'] .'" > ' . $lab['nombre'].'</option>';
        } 
     ?>
    </select>
    <input type="hidden" name="form" value="3" />
    <p><input type="submit" value="Añadir" /></p>
  </form>
</div>


<div class="col-md-3">
  <form id="eliminar-farmaco" method="post" action="/admin-panel-control">
    <p>Eliminar fármaco</p>
    <input type="text" name="nombre" placeholder="nombre">
    <select name="id_lab">
      <option value="" disabled selected>Laboratorio</option>
      <? foreach($conn->query($sql_Labs) as $lab) {
          echo '<option value= "' . $lab['id'] .'" > ' . $lab['nombre'].'</option>';
        } 
     ?>
    </select>
    <input type="hidden" name="form" value="4" />
    <p><input type="submit" value="Eliminar" /></p>
  </form>
</div>

<div class="col-md-3">
  <form id="nuevo-laboratorio" method="post" action="/admin-panel-control">
    <p>Añadir laboratorio</p>
    <input type="text" name="nombre" placeholder="nombre">
    <input type="number" name="retraso_pedido" placeholder="dias de retraso de pedido">
    <select name="id_hospital">
      <option value="" disabled selected>Hospital</option>
      <? foreach($conn->query($sql_hospital) as $hospital) {
          echo '<option value= "' . $hospital['id'] .'" > ' . $hospital['nombre'].'</option>';
        } 
     ?>
    </select>
    <input type="hidden" name="form" value="5" />
    <p><input type="submit" value="Añadir" /></p>
  </form>
</div>

<div class="col-md-3">
  <form id="eliminar-laboratorio" method="post" action="/admin-panel-control">
    <p>Eliminar laboratorio</p>
    <input type="text" name="nombre" placeholder="nombre">
    <select name="id_hospital">
      <option value="" disabled selected>Hospital</option>
      <? foreach($conn->query($sql_hospital) as $hospital) {
          echo '<option value= "' . $hospital['id'] .'" > ' . $hospital['nombre'].'</option>';
        } 
     ?>
    </select>
    <input type="hidden" name="form" value="6" />
    <p><input type="submit" value="Eliminar" /></p>
  </form>
</div>

<?
  if($_SESSION['permisos'] == 0){
?>
  <div class="col-md-3">
    <form id="nuevo-hospital" method="post" action="/admin-panel-control">
      <p>Añadir hospital</p>
      <input type="text" name="nombre" placeholder="nombre">
      <input type="hidden" name="form" value="7" />
      <p><input type="submit" value="Añadir" /></p>
    </form>
  </div>

    <div class="col-md-3">
    <form id="eliminar-hospital" method="post" action="/admin-panel-control">
      <p>Eliminar hospital</p>
      <input type="text" name="nombre" placeholder="nombre">
      <input type="hidden" name="form" value="8" />
      <p><input type="submit" value="Eliminar" /></p>
    </form>
  </div>
<?}?>

<?
include("footer.php");
?>