<?
$title = "formulario";
include("header.php");

?>
<div class="col-md-4"></div>

<div class="col-md-4">
  <form action="upload" method="post" enctype="multipart/form-data">
      <h3>Subir Fichero:</h3>
      <input type="file" name="fileToUpload" id="fileToUpload">
      <input type="submit" value="Subir Fichero" name="submit">
  </form>
</div>

<div class="col-md-4"></div>


<?
include("footer.php");
?>