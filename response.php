<?php

include("exec-c.php");

if (is_ajax()) {
  if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
    $action = $_POST["action"];
    switch($action) { //Switch case for value of action
      case "test": test_function(); break;
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

  switch ($_POST['form']) {
    case '1':

      $sql = "SELECT * FROM `farmacos` WHERE id = '".$_POST['id_farmacy']."'";
      $ficheroMed = fopen("datos.pha", "w");  //Se debe escribir en "datos.pha"
      foreach ($conn->query($sql) as $medicine) {
        fwrite($ficheroMed, "".$medicine['stock']."\n");
      fwrite($ficheroMed, "".$medicine['precio_med']."\n");
      fwrite($ficheroMed, "".$medicine['coste_almacenamiento']."\n");
      fwrite($ficheroMed, "".$medicine['coste_pedido']."\n");
      fwrite($ficheroMed, "".$medicine['coste_recogida']."\n");
      fwrite($ficheroMed, "".$medicine['coste_sin_stock']."\n");
      fwrite($ficheroMed, "".$medicine['coste_oportunidad']."\n");
      $estimaciones = array();
      $estimaciones = calculaRepartidos($_POST['horizonte'], $_POST['estimador'], $medicine['id']);
      foreach ($estimaciones as $value) {
        fwrite($ficheroMed, "".$value."\n"); //Sustituir por los valores estimados a futuro 
      }
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
      }
      fclose($ficheroMed);  //Se cierra el fichero, eliminando el manejador
    break;
    case '2':


      $sql = "SELECT * FROM `farmacos` WHERE id_lab = '".$_POST['id_lab']."'";
      $ficheroLabs = fopen("ficheros.pha", "w");  //Se escriben los nombres de los ficheros en "ficheros.pha"
      $flagMed = 0;
      foreach ($conn->query($sql) as $medicine) {
        
        $farmaFile = "".$medicine['nombre'].".pha";   //El resto de datos se escriben en sus ficheros correspondientes
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
        
        $estimaciones = array();
        $estimaciones = calculaRepartidos($_POST['horizonte'], $_POST['estimador'], $medicine['id']);
        foreach ($estimaciones as $value) {
          fwrite($ficheroMed, "".$value."\n"); //Sustituir por los valores estimados a futuro 
        }
        

        fwrite($ficheroMed, "".$medicine['maximo_uds']."\n");
        fwrite($ficheroMed, "".$medicine['minimo_uds']."\n");
        $nTamPedidos = 0;
        $tamPedidos = array();
        foreach ($conn->query("SELECT * FROM `tamanos_pedidos` WHERE id_farmaco = '".$medicine['id']."'") as $pedido) {
          array_push($tamPedidos, $pedido['tam_pedido']);
          $nTamPedidos = $nTamPedidos + 1;
        }
        fwrite($ficheroMed, $nTamPedidos."\n");
        $flagPed = 0;
        foreach ($tamPedidos as $pedido) {
          if($flagPed == 0){
            fwrite($ficheroMed, "".$pedido."");
            $flagPed = 1;
          }else{
            fwrite($ficheroMed, "\n".$pedido."");
          }
        }
        /*
       */   
          fclose($ficheroMed);
      }
      fclose($ficheroLabs);
      
    break;
    default:
      # code...
    break;
  }
  
  //Ejecutar ./OFHLab
  $return['result'] = lanzaC($_POST['form'], $_POST['horizonte'], $_POST['numpedidos']);

  $return['json'] = json_encode($return);
  echo json_encode($return);
}


//Función para el calculo de la estimación
function calculaRepartidos($horizonte, $estimador, $idFarmaco){

  $conn = mysqli_connect("db597300977.db.1and1.com", "dbo597300977", "PharmaControl", "db597300977");

  $estimacion = array();
  $diasPrevios = 28;
  $segundosDia = 86400;
  
  switch ($estimador) {
    case '0': //Enfoque simple
      $repartidos = array();
      $fechaFin = date("Y-m-d");            //Hoy
        $fechaInicio = date("Y-m-d", time() - $diasPrevios*$segundosDia); //Un mes antes
      $sql = "SELECT * FROM `registros` WHERE id_farmaco = '".$idFarmaco."' AND `fecha` BETWEEN '".$fechaInicio."' AND '".$fechaFin."' ORDER BY `fecha`";

      foreach ($conn->query($sql) as $registro) {
        array_push($repartidos, $registro['cantidad']);
      }
      
      for ($i=0; $i < $horizonte; $i++) { 
        if($i == 0){
          array_push($estimacion, $repartidos[count($repartidos)-1]);
        }else{
          array_push($estimacion, $estimacion[$i-1]);
        }
      }

    break;
    
    case '1': //Media lineal

      $repartidos = array();
      $fechaFin = date("Y-m-d");            //Hoy
        $fechaInicio = date("Y-m-d", time() - $diasPrevios*$segundosDia); //Un mes antes
    
      for ($i=0; $i < $horizonte; $i++) {
        if( $i == 0){
          $sql = "SELECT * FROM `registros` WHERE id_farmaco = '".$idFarmaco."' AND `fecha` BETWEEN '".$fechaInicio."' AND '".$fechaFin."' ORDER BY `fecha`";

          foreach ($conn->query($sql) as $registro) {
            array_push($repartidos, $registro['cantidad']);
          }

          $media = round(array_sum($repartidos)/28);  //Media del mes
          array_push($estimacion, $media);
        }else{
          $sql = "SELECT * FROM `registros` WHERE id_farmaco = '".$idFarmaco."' AND `fecha` BETWEEN '".$fechaFin."' AND '".$fechaInicio."' ORDER BY `fecha`";

          foreach ($conn->query($sql) as $registro) {
            array_push($repartidos, $registro['cantidad']);
          }
          $media = array_sum($repartidos) + array_sum($estimacion);

          $media = round($media/$diasPrevios);
          array_push($estimacion, $media);
        }

        $fechaInicio = date("Y-m-d", time() - $diasPrevios*$segundosDia + ($i+1) * $segundosDia); //Avanzamos en un día la siguiente petición

      }   

    break;
    
    case '2': //Alisamiento exponencial
    
      $fechaFin = date("Y-m-d");            //Hoy
        $fechaInicio = date("Y-m-d", time() - $diasPrevios*$segundosDia); //Cuatro semanas antes
    
        $alpha = 0.5;

        $vectorPonderacion = array();

        for($i = 0; $i < $diasPrevios; $i++){
          if($diasPrevios-1-$i != 0){
            array_push($vectorPonderacion, pow(1/($diasPrevios-1-$i), $alpha));
          }
        }

        $sumPonderacion = array_sum($vectorPonderacion);  //Valor entre el cual dividir para tener media ponderada

      
      for ($i=0; $i < $horizonte; $i++) {

        $arrayMedia = array();
        $repartidos = array();
        
        //Bucle para generar la estimacion de cada dia en el horizonte
        for($j = 0; $j < $diasPrevios; $j++){
          
          if($fechaInicio <= $fechaFin){


            $sql = "SELECT * FROM `registros` WHERE id_farmaco = '".$idFarmaco."' AND `fecha` = '".$fechaInicio."'";

            $flagCal = 0;

            //Obtenemos el vector de pedidos con indice util para trabajar
            foreach ($conn->query($sql) as $registro) {
              $flagCal = 1;
              array_push($repartidos, $registro['cantidad']);
            }
            if($flagCal == 0){
              array_push($repartidos, 0);
            }
          }else{
            $index = round(abs(date_timestamp_get($fechaInicio)-date_timestamp_get($fechaFin)));
            array_push($repartidos, $estimacion[$index]);
          }

          $fechaInicio = date("Y-m-d", time() - $diasPrevios*$segundosDia + ($j+1) * $segundosDia); //Avanzamos en un día la siguiente petición
        }

        $fechaInicio = date("Y-m-d", time() - $diasPrevios*$segundosDia + ($i+1) * $segundosDia); //Avanzamos en un día la siguiente petición

        //Multiplicamos los vectores de ponderacion y repartidos para obtener la estimacion
        for($j = 0; $j < $diasPrevios; $j++){
          array_push($arrayMedia, $repartidos[$j] * $vectorPonderacion[$j]);
        }
        array_push($estimacion, round(array_sum($arrayMedia)/$sumPonderacion));

      }

    break;
    
    default:
      echo 'Error calculaRepartidos';
    break;
  }
  
  return $estimacion;
}

?>