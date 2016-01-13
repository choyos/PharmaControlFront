<?
	function lanzaC($tipo, $horizonte, $numpedidos){
		switch ($tipo) {
			case '1':
				$result = shell_exec("./OFHMed '".$horizonte."' '".$numpedidos."'");
			break;
			case '2':
				$result = shell_exec("./OFHLab '".$horizonte."' '".$numpedidos."'");
			break;
			case '3':
				$result = shell_exec("./OFHHos '".$horizonte."' '".$numpedidos."'");
				if($result == NULL){
					echo "NOK\nHorizonte-> ".$horizonte.".\nNumPedidos->".$numpedidos."";
				}else{
					echo "Result-> ".$result.".\nHorizonte-> ".$horizonte.".\nNumPedidos->".$numpedidos."";
				}
			break;
			case '4':
				$result = shell_exec("./OFHMulti '".$horizonte."' '".$numpedidos."'");
				//$result = $restult." Hola";
			break;
			default:
				echo "Error";
			break;
		}
		return $result;
	}
?>