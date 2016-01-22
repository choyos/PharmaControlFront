<?
include("functions.php");
session_start();

if(empty($_SESSION) && $title != "Acceso al portal"){
  header('Location: /');
  die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es"><head>
<title><?=$title?></title>

<meta charset="UTF-8">

<link type="image/x-icon" href="/img/US.gif" rel="shortcut icon"/>

<link rel="stylesheet" href="/css/main_styles2.css">
<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>

<script src="/chart/Chart.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="/js/bootstrap.min.js"></script>

</head>
<body>
<?

$conn = new mysqli("db597300977.db.1and1.com", "dbo597300977", "PharmaControl", "db597300977");
  if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
  }
//echo "<pre>";var_dump($conn);die();
mysqli_set_charset($conn, "utf8");
// Check connection
if (mysqli_connect_errno()){
  echo "Imposible conectar: " . mysqli_connect_error();
  }

if($title != "Acceso al portal"){
    ?>
    <div id="header">
      <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Menú de <?=ucfirst($_SESSION['usuario'])?>
        <span class="caret"></span></button>
        <ul class="dropdown-menu dropdown-menu-right">
          <li><a href="/form"><i class="fa fa-bar-chart"></i>  Inicio</a></li>
          <li><a href="/calculate"><i class="fa fa-calculator"></i>  Calcular pedido</a></li>
          <li><a href="/pruebas"><i class="fa  fa-exclamation-triangle"></i>  Pruebas</a></li>
          <li><a href="/panel-de-control"><i class="fa fa-cogs">  Panel de Control</i></a></li>
          <?
          if($_SESSION['permisos'] == 0){
          ?>
          <li><a href="/subir-fichero"><i class="fa fa-cloud-upload"></i>  Subir fichero</a></li>
          <?}?>
          <li><a href="/log-out"><i class="fa fa-sign-out"></i>  Cerrar sesión</a></li>
        </ul>
      </div>
    </div>
    <?
}
?>

