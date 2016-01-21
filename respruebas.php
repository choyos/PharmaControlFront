<?php

include("exec-c.php");

if (is_ajax()) {
  if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
    $action = $_POST["action"];
    switch($action) { //Switch case for value of action
      case "test": 
        $result = test_function(); 
      break;
    }
  }
}

//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}


function test_function(){
  $return = $_POST;

  $conn = mysqli_connect("db597300977.db.1and1.com", "dbo597300977", "PharmaControl", "db597300977");
  mysqli_set_charset($conn, "utf8");
  // Check connection
  if (mysqli_connect_errno()){
    echo "Imposible conectar: " . mysqli_connect_error();
  }

  //Caso de calculo por multiescenario
  if($_POST['estimador'] == 3){
    if($_POST['form'] == 2){
      $sql = "SELECT * FROM `farmacos` WHERE id_lab = '".$_POST['id_lab']."' ORDER BY id DESC";
      $ficheroLabs = fopen("ficheros.pha", "w");  //Se escriben los nombres de los ficheros en "ficheros.pha"
      $flagMed = 0;
      foreach ($conn->query($sql) as $medicine) {
        $name = limpia_espacios($medicine['nombre']);
        $farmaFile = "".$name.".pha";   //El resto de datos se escriben en sus ficheros correspondientes
        if($flagMed == 0){
          fwrite($ficheroLabs, "".$farmaFile."");
          $flagMed = 1;
        }else{
          fwrite($ficheroLabs, "\n".$farmaFile."");
        }
        $ficheroMed = fopen("".$farmaFile, "w");
        fwrite($ficheroMed, "".$medicine['stock']."\n");
        fwrite($ficheroMed, "".$medicine['precio_med']."\n");
        fwrite($ficheroMed, "".$medicine['coste_almacenamiento']."\n");
        fwrite($ficheroMed, "".$medicine['coste_pedido']."\n");
        fwrite($ficheroMed, "".$medicine['coste_recogida']."\n");
        fwrite($ficheroMed, "".$medicine['coste_sin_stock']."\n");
        fwrite($ficheroMed, "".$medicine['coste_oportunidad']."\n");
        fwrite($ficheroMed, "".$medicine['maximo_uds']."\n");
        fwrite($ficheroMed, "".$medicine['minimo_uds']."\n");
        $nTamPedidos = 0;
        foreach ($conn->query("SELECT * FROM `tamanos_pedidos` WHERE id_farmaco = '".$medicine['id']."'") as $pedido) {
          $nTamPedidos = $nTamPedidos + 1;
        }
        fwrite($ficheroMed, $nTamPedidos."\n");
        foreach ($conn->query("SELECT * FROM `tamanos_pedidos` WHERE id_farmaco = '".$medicine['id']."'") as $pedido) {
          fwrite($ficheroMed, "".$pedido['tam_pedido']."\n");
        }
        //LLENE DE LOS REGISTROS UTILES DE LAS ULTIMAS x semanas
        $segundosDia = 86400;
        $diasPeriodo = $_POST['horizonte'];
        $numPeriodos = 10;
        fwrite($ficheroMed, "".$numPeriodos."\n");
        $diasTotal = $diasPeriodo * $numPeriodos;
        $fechaFin = date("d-m-Y");            //Hoy
        $fechaInicio = date("d-m-Y", time() - $diasTotal*$segundosDia); //Un mes antes
        $registros = array();
        fwrite($ficheroMed, "".$diasTotal."\n");
        for($j = 0; $j < $diasTotal; $j++){
            
          $sql = "SELECT * FROM `registros` WHERE id_farmaco = '".$medicine['id']."' AND `fecha` = '".$fechaInicio."'";

          $flagReg = 0;

          //Obtenemos el vector de pedidos con indice util para trabajar
          foreach ($conn->query($sql) as $registro) {
            $flagReg = 1;
            array_push($registros, $registro['cantidad']);
          }
          if($flagReg == 0){
            array_push($registros, 0);
          }

          $fechaInicio = date("Y-m-d", time() - $diasTotal*$segundosDia + ($j+1) * $segundosDia); //Avanzamos en un día la siguiente petición
        }
        foreach ($registros as $value) {
          fwrite($ficheroMed, "".$value."\n");
        }
        fclose($ficheroMed);
      }

      fclose($ficheroLabs);
      $tipoExe = '5';
    }
    //Ejecutar ./OFHTipo x. El parametro 4 identifica al tipo de calculo que es el código para que la funcion lanzaC ejecute ./OFHMult
    $return['result'] = shell_exec("./OFHLabMulti '".$_POST['horizonte']."' '".$_POST['numpedidos']."'");

    $return['json'] = json_encode($return);
    echo json_encode($return);
  }
}

function limpia_espacios($cadena){
  $cadena = str_replace(' ', '', $cadena);
  return $cadena;
}

?>