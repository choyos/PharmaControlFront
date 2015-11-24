<?
	function lanzaC($tipo, $horizonte, $numpedidos){

		$output = array();
		switch ($tipo) {
			case '1':
				$result = exec('./OFHMed '.$horizonte.' '.$numpedidos.'', $output);
			break;
			case '2':
				$result = exec("./OFHLab ".$horizonte." ".$numpedidos."", $output);
				foreach ($output as $value) {
					echo "1";
					echo "Resultado->".$value."";
				}
			break;
			case '3':
				$result = exec("./OFHHos ".$horizonte." ".$numpedidos."");
			break;
			default:
				echo "Error";
			break;
		}

		return $result;
	}
?>