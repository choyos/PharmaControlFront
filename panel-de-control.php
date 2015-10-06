<?
$title = "Panel de control";
include("header.php");
?>
<h2 style="color: red">Panel de Control de <?echo ucfirst($_SESSION['usuario'])?></h2>
<div class="col-md-3">
  <form id="nuevo-usuario" method="post" action="/admin-panel-control">
    <p>Añadir usuario</p>
    <input type="text" name="nombre" placeholder="nombre">
    <input type="text" name="clave" placeholder="clave">
    <select name="permisos">
      <option value="reina_sofia">Reina Sofía</option>
      <option value="san_juan">San Juan de Dios</option>
      <option value="root">Administrador</option>
    </select>
    <input type="hidden" name="form"value="1" />
    <p><input type="submit" value="Añadir" /></p>
  </form>
</div>


<div class="col-md-3">
  <form id="eliminar-usuario" method="get" action="/admin-panel-control">
    <p>Eliminar usuario</p>
    <input type="text" name="nombre" placeholder="nombre">
    <select name="permisos">
      <option value="reina_sofia">Reina Sofía</option>
      <option value="san_juan">San Juan de Dios</option>
      <option value="root">Administrador</option>
    </select>
    <input type="hidden" name="form"value="2" />
    <p><input type="submit" value="Eliminar" /></p>
  </form>
</div>


<div class="col-md-3">
  <form id="nuevo-farmaco" method="get" action="/admin-panel-control">
    <p>Añadir fármaco</p>
    <input type="text" name="nombre" placeholder="nombre">
    <input type="number" name="coste_almacenamiento" placeholder="coste almacenamiento">
    <input type="number" name="retraso_pedido" placeholder="retraso pedido">
    <input type="number" name="minimo_uds" placeholder="minimo">
    <input type="number" name="maximo_uds" placeholder="máximo">
    <input type="number" name="incremento_uds" placeholder="incremento">
    <select name="hospital">
      <option value="reina_sofia">Reina Sofía</option>
      <option value="san_juan">San Juan de Dios</option>
    </select>
    <input type="hidden" name="form"value="3" />
    <p><input type="submit" value="Añadir" /></p>
  </form>
</div>


<div class="col-md-3">
  <form id="eliminar-farmaco" method="get" action="/admin-panel-control">
    <p>Eliminar fármaco</p>
    <input type="text" name="nombre" placeholder="nombre">
    <select>
      <option value="reina_sofia">Reina Sofía</option>
      <option value="san_juan">San Juan de Dios</option>
    </select>
    <input type="hidden" name="form" value="4" />
    <p><input type="submit" value="Eliminar" /></p>
  </form>
</div>


<?
include("footer.php");
?>