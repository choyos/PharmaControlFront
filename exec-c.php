<?
	function lanzaC($tipo, $horizonte, $numpedidos){
		$result = "";
		switch ($tipo) {
			case '1':
				$result .= shell_exec("./OFHMed '".$horizonte."' '".$numpedidos."'");
			break;
			case '2':
				$result .= shell_exec("./OFHLab '".$horizonte."' '".$numpedidos."'");
			break;
			case '3':
				$result .= shell_exec("./OFHHos '".$horizonte."' '".$numpedidos."'");
			break;
			case '4':
				$result .= shell_exec("./OFHMedMulti '".$horizonte."' '".$numpedidos."'");
			break;
			case '5':
				$result .= shell_exec("./OFHLabMulti '".$horizonte."' '".$numpedidos."'");
			//	$result = ". HolalanzaceS";
			break;
			default:
				echo "Error";
			break;
		}
		return $result;
	}
?>