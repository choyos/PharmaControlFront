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
        <input name="button" type="button" class="btn btn-primary dropdown-toggle" onclick="window.close();" value="Cerrar esta ventana" />
      </div>
    </div>
    <?
}
?>

