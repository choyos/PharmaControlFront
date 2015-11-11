<?
$conn = mysqli_connect("db597300977.db.1and1.com", "dbo597300977", "PharmaControl", "db597300977");
mysqli_set_charset($conn, "utf8");
// Check connection
if (mysqli_connect_errno()){
  echo "Imposible conectar: " . mysqli_connect_error();
}

$form = $_POST['form'];

switch ($form) {
	
	case "1":	//Calculo para un solo medicamento
	  $sql = "SELECT * FROM `farmacos` WHERE id = '".$_POST['id_farmacy']."'";
	  $ficheroMed = fopen("OFH/datos.pha", "w");	//Se debe escribir en "datos.pha"
	  foreach ($conn->query($sql) as $medicine) {
	  	fwrite($ficheroMed, "".$medicine['stock']."\n");
		fwrite($ficheroMed, "".$medicine['precio_med']."\n");
		fwrite($ficheroMed, "".$medicine['coste_almacenamiento']."\n");
		fwrite($ficheroMed, "".$medicine['coste_pedido']."\n");
		fwrite($ficheroMed, "".$medicine['coste_recogida']."\n");
		fwrite($ficheroMed, "".$medicine['coste_sin_stock']."\n");
		fwrite($ficheroMed, "".$medicine['coste_oportunidad']."\n");
		fwrite($ficheroMed, "Aquí van los repartidos\n");
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
	  fclose($ficheroMed);	//Se cierra el fichero, eliminando el manejador
	  //Ejecutar ./OFHMed
	break;

	case "2":	//Calculo para todos los medicamentos de un determinado laboratorio
	  $sql = "SELECT * FROM `farmacos` WHERE id_lab = '".$_POST['id_lab']."'";
	  $ficheroLabs = fopen("OFH/ficheros.pha", "w");	//Se escriben los nombres de los ficheros en "ficheros.pha"
	  foreach ($conn->query($sql) as $medicine) {
	  	$farmaFile = "".$medicine['nombre'].".pha";		//El resto de datos se escriben en sus ficheros correspondientes
	  	fwrite($ficheroLabs, "".$farmaFile."\n");
	  	$ficheroMed = fopen("OFH/".$farmaFile, "w");
	  	fwrite($ficheroMed, "".$medicine['stock']."\n");
		fwrite($ficheroMed, "".$medicine['precio_med']."\n");
		fwrite($ficheroMed, "".$medicine['coste_almacenamiento']."\n");
		fwrite($ficheroMed, "".$medicine['coste_pedido']."\n");
		fwrite($ficheroMed, "".$medicine['coste_recogida']."\n");
		fwrite($ficheroMed, "".$medicine['coste_sin_stock']."\n");
		fwrite($ficheroMed, "".$medicine['coste_oportunidad']."\n");
		fwrite($ficheroMed, "Aquí van los repartidos\n"); //Sustituir por los valores estimados a futuro 
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
	  	fclose($ficheroMed);
	  }
	  fclose($ficheroLabs);
	  //Ejecutar ./OFHLab
	break;

	case "3":	//Calculo para todos los medicamentos de un determinado hospital
	  $sql = "SELECT * FROM `laboratorios` WHERE id_hospital = '".$_POST['id_hospital']."'";
	  $ficheroHospital = fopen("OFH/labs.pha", "w");
	  foreach ($conn->query($sql) as $lab) {
	  	$labFile = "".$lab['nombre'].".pha";
	  	fwrite($ficheroHospital, "".$labFile."\n");
	  	$ficheroLabs = fopen("OFH/".$labFile, "w");
	  	$sqlFar = "SELECT * FROM `farmacos` WHERE id_lab = '".$lab['id']."'";
	  	foreach ($conn->query($sqlFar) as $medicine) {
	  		$farmaFile = "".$medicine['nombre'].".pha";		//El resto de datos se escriben en sus ficheros correspondientes
		  	fwrite($ficheroLabs, "".$farmaFile."\n");
		  	$ficheroMed = fopen("OFH/".$farmaFile, "w");
		  	fwrite($ficheroMed, "".$medicine['stock']."\n");
			fwrite($ficheroMed, "".$medicine['precio_med']."\n");
			fwrite($ficheroMed, "".$medicine['coste_almacenamiento']."\n");
			fwrite($ficheroMed, "".$medicine['coste_pedido']."\n");
			fwrite($ficheroMed, "".$medicine['coste_recogida']."\n");
			fwrite($ficheroMed, "".$medicine['coste_sin_stock']."\n");
			fwrite($ficheroMed, "".$medicine['coste_oportunidad']."\n");
			
			//Copiar al resto de posibilidades
			fwrite($ficheroMed, "Aquí van los repartidos\n"); //Sustituir por los valores estimados a futuro 
			$arrayRepartidos = array();
			$arrayRepartidos = calculaRepartidos($_POST['horizonte'], $_POST['estimador'], $medicine['id']);

			foreach ($arrayRepartidos as $value) {
				fwrite($ficheroMed, "".$value."\n");
			}

			//Final del proceso de escribir los datos estimados

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
		  	fclose($ficheroMed);
	  	}
	  	fclose($ficheroLabs);
	  }
	  fclose($ficheroHospital);
	  //Ejecutar ./OFHHos
	break;

	  default: 
	  	Echo "Error";
	  break;
}

function calculaRepartidos($horizonte, $estimador, $idFarmaco){
	

	$estimacion = array();
	$repartidos = array();
	$diasPrevios = 28;
	$segundosDia = 86400;
	
	switch ($estimador) {
		case '0':	//Enfoque simple
			$fechaFin = date("Y-m-d"); 						//Hoy
		    $fechaInicio = date("Y-m-d", time() - 2419200);	//Un mes antes
			$sql = "SELECT * FROM `registros` WHERE id_farmaco = '".$idFarmaco."' AND `fecha` BETWEEN '".$fechaFin."' AND '".$fechaInicio."' ORDER BY `fecha`";

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
		
		case '1':	//Media lineal

			$fechaFin = date("Y-m-d"); 						//Hoy
		    $fechaInicio = date("Y-m-d", time() - 2419200);	//Un mes antes
		
			for ($i=0; $i < $horizonte; $i++) {
				if( $i = 0){
					$sql = "SELECT * FROM `registros` WHERE id_farmaco = '".$idFarmaco."' AND `fecha` BETWEEN '".$fechaFin."' AND '".$fechaInicio."' ORDER BY `fecha`";

					foreach ($conn->query($sql) as $registro) {
						array_push($repartidos, $registro['cantidad']);
					}

					$media = round(array_sum($repartidos)/28);	//Media del mes
					array_push($estimacion, $media)
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
		
		case '2':	//Alisamiento exponencial
		
			$fechaFin = date("Y-m-d"); 						//Hoy
		    $fechaInicio = date("Y-m-d", time() - 2419200);	//Cuatro semanas antes
		
		    $alpha = 0.5;

		    $vectorPonderacion = array();

		    for($i = 0; $i < $diasPrevios; $i++){
		    	array_push($vectorPonderacion, (1/($diasPrevios-1-$i)) ** $alpha);
		    }

		    $sumPonderacion = array_sum($vectorPonderacion);	//Valor entre el cual dividir para tener media ponderada

			for ($i=0; $i < $horizonte; $i++) {

				$arrayMedia = array();

				//Bucle para generar la estimacion de cada dia en el horizonte
				for($j = 0; $j < $diasPrevios; $j++){
					
					if($fechaInicio <= $fechaFin){
						$sql = "SELECT * FROM `registros` WHERE id_farmaco = '".$idFarmaco."' AND `fecha` = '".$fechaInicio."'";

						$flag = 0;

						//Obtenemos el vector de pedidos con indice util para trabajar
						foreach ($conn->query($sql) as $registro) {
							$flag = 1;
							array_push($repartidos, $registro['cantidad']);
						}
						if($flag == 0){
							array_push($repartidos, 0);
						}
					}else{
						$index = round(abs(date_timestamp_get($fechaInicio)-date_timestamp_get($fechaFin)));
						array_push($repartidos, $estimacion[$index]);
					}

					$fechaInicio = date("Y-m-d", time() - $diasPrevios*$segundosDia + ($i+1) * $segundosDia); //Avanzamos en un día la siguiente petición
				}

				//Multiplicamos los vectores de ponderacion y repartidos para obtener la estimacion
				for($j = 0; $j < $diasPrevios; $j++){
					array_push($arrayMedia, $repartidos[$j] * $vectorPonderacion[$j]);
				}
				array_push($estimacion, array_sum($arrayMedia)/$sumPonderacion);

			}

		break;
		
		default:
			echo 'Error calculaRepartidos';
		break;
	}
	
	return $estimacion;
}

header('Location: /calculate');
?>