<?
$title = "Panel de control";
include("header.php");
?>

<?php //Querys utiles para el trabajo con los campos select
  $sql_pass = "SELECT id, nombre FROM hospital";
  $sql_hospital = "SELECT id, nombre FROM hospital WHERE NOT id = 0";
  $sql_Labs = "SELECT id, nombre FROM laboratorios";
?>

<h2 style="color: red; margin-left: 15px">Panel de Control de <?echo ucfirst($_SESSION['usuario'])?></h2>
<div class="col-md-3">
  <form id="nuevo-usuario" method="post" action="/admin-panel-control">
    <legend>Añadir usuario</legend>
    <input type="text" name="nombre" placeholder="nombre">
    <input type="text" name="clave" placeholder="clave">
    <?
      if($_SESSION['permisos'] == 0){
    ?>
    <select name="id_hospital">
      <option value="" disabled selected>Permisos/Hospital</option>
      <? foreach($conn->query($sql_pass) as $hospital) {
          echo '<option value= "' . $hospital['id'] .'" > ' . $hospital['nombre'].'</option>';
        } 
     ?>
    </select>
    <?}else{?>
    <select name="id_hospital">
      <option value="" disabled selected>Permisos/Hospital</option>
      <? 
      $sql = "SELECT id, nombre FROM hospital WHERE id = '".$_SESSION['permisos']."'";
      foreach ($conn->query($sql) as $hospital) {
        echo '<option value= "' . $hospital['id'] .'" > ' . $hospital['nombre'].'</option>';
      }
      ?>
    </select>
    <?}?>
    <input type="hidden" name="form" value="1" />
    <p><input type="submit" value="Añadir" /></p>
  </form>
</div>


<div class="col-md-3">
  <form id="eliminar-usuario" method="post" action="/admin-panel-control">
    <legend>Eliminar usuario</legend>
    <input type="text" name="nombre" placeholder="nombre">
    <?
      if($_SESSION['permisos'] == 0){
    ?>
    <select name="id_hospital">
      <option value="" disabled selected>Permisos/Hospital</option>
      <? foreach($conn->query($sql_pass) as $hospital) {
          echo '<option value= "' . $hospital['id'] .'" > ' . $hospital['nombre'].'</option>';
        } 
     ?>
    </select>
    <?}else{?>
    <select name="id_hospital">
      <option value="" disabled selected>Permisos/Hospital</option>
      <? 
      $sql = "SELECT id, nombre FROM hospital WHERE id = '".$_SESSION['permisos']."'";
      foreach ($conn->query($sql) as $hospital) {
        echo '<option value= "' . $hospital['id'] .'" > ' . $hospital['nombre'].'</option>';
      }
      ?>
    </select>
    <?}?>
    <input type="hidden" name="form" value="2" />
    <p><input type="submit" value="Eliminar" /></p>
  </form>
</div>


<div class="col-md-3">
  <form id="nuevo-farmaco" method="post" action="/admin-panel-control">
    <legend>Añadir fármaco</legend>
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
    <?
      if($_SESSION['permisos'] == 0){
    ?>
    <select name="id_lab">
      <option value="" disabled selected>Laboratorio</option>
      <? foreach($conn->query($sql_Labs) as $lab) {
          echo '<option value= "' . $lab['id'] .'" > ' . $lab['nombre'].'</option>';
        } 
     ?>
    </select>
    <?}else{?>
    <select name="id_lab">
      <option value="" disabled selected>Laboratorio</option>
      <? 
      $sql = "SELECT id, nombre FROM laboratorios WHERE id_hospital = '".$_SESSION['permisos']."'";
      foreach ($conn->query($sql) as $lab) {
        echo '<option value= "' . $lab['id'] .'" > ' . $lab['nombre'].'</option>';
      }
      ?>
    </select>
    <?}?>
    <input type="hidden" name="form" value="3" />
    <p><input type="submit" value="Añadir" /></p>
  </form>
</div>


<div class="col-md-3">
  <form id="eliminar-farmaco" method="post" action="/admin-panel-control">
    <legend>Eliminar fármaco</legend>
    <input type="text" name="nombre" placeholder="nombre">
    <?
      if($_SESSION['permisos'] == 0){
    ?>
    <select name="id_lab">
      <option value="" disabled selected>Laboratorio</option>
      <? foreach($conn->query($sql_Labs) as $lab) {
          echo '<option value= "' . $lab['id'] .'" > ' . $lab['nombre'].'</option>';
        } 
     ?>
    </select>
    <?}else{?>
    <select name="id_lab">
      <option value="" disabled selected>Laboratorio</option>
      <? 
      $sql = "SELECT id, nombre FROM laboratorios WHERE id_hospital = '".$_SESSION['permisos']."'";
      foreach ($conn->query($sql) as $lab) {
        echo '<option value= "' . $lab['id'] .'" > ' . $lab['nombre'].'</option>';
      }
      ?>
    </select>
    <?}?>
    <input type="hidden" name="form" value="4" />
    <p><input type="submit" value="Eliminar" /></p>
  </form>
</div>

<div class="col-md-3">
  <form id="nuevo-laboratorio" method="post" action="/admin-panel-control">
    <legend>Añadir laboratorio</legend>
    <input type="text" name="nombre" placeholder="nombre">
    <input type="number" name="retraso_pedido" placeholder="dias de retraso de pedido">
    <?
      if($_SESSION['permisos'] == 0){
    ?>
    <select name="id_hospital">
      <option value="" disabled selected>Hospital</option>
      <? foreach($conn->query($sql_hospital) as $hospital) {
          echo '<option value= "' . $hospital['id'] .'" > ' . $hospital['nombre'].'</option>';
        } 
     ?>
    </select>
    <?}else{?>
    <select name="id_hospital">
      <option value="" disabled selected>Hospital</option>
      <? 
      $sql = "SELECT id, nombre FROM hospital WHERE id = '".$_SESSION['permisos']."'";
      foreach ($conn->query($sql) as $hospital) {
        echo '<option value= "' . $hospital['id'] .'" > ' . $hospital['nombre'].'</option>';
      }
      ?>
    </select>
    <?}?>
    <input type="hidden" name="form" value="5" />
    <p><input type="submit" value="Añadir" /></p>
  </form>
</div>

<div class="col-md-3">
  <form id="eliminar-laboratorio" method="post" action="/admin-panel-control">
    <legend>Eliminar laboratorio</legend>
    <input type="text" name="nombre" placeholder="nombre">
    <?
      if($_SESSION['permisos'] == 0){
    ?>
    <select name="id_hospital">
      <option value="" disabled selected>Hospital</option>
      <? foreach($conn->query($sql_hospital) as $hospital) {
          echo '<option value= "' . $hospital['id'] .'" > ' . $hospital['nombre'].'</option>';
        } 
     ?>
    </select>
    <?}else{?>
    <select name="id_hospital">
      <option value="" disabled selected>Hospital</option>
      <? 
      $sql = "SELECT id, nombre FROM hospital WHERE id = '".$_SESSION['permisos']."'";
      foreach ($conn->query($sql) as $hospital) {
        echo '<option value= "' . $hospital['id'] .'" > ' . $hospital['nombre'].'</option>';
      }
      ?>
    </select>
    <?}?>
    <input type="hidden" name="form" value="6" />
    <p><input type="submit" value="Eliminar" /></p>
  </form>
</div>

<?
  if($_SESSION['permisos'] == 0){
?>
  <div class="col-md-3">
    <form id="nuevo-hospital" method="post" action="/admin-panel-control">
      <legend>Añadir hospital</legend>
      <input type="text" name="nombre" placeholder="nombre">
      <input type="hidden" name="form" value="7" />
      <p><input type="submit" value="Añadir" /></p>
    </form>
  </div>

    <div class="col-md-3">
    <form id="eliminar-hospital" method="post" action="/admin-panel-control">
      <legend>Eliminar hospital</legend>
      <input type="text" name="nombre" placeholder="nombre">
      <input type="hidden" name="form" value="8" />
      <p><input type="submit" value="Eliminar" /></p>
    </form>
  </div>
<?}?>

<?
include("footer.php");
?>