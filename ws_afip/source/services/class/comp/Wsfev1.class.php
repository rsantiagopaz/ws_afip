<?php

require_once("Config.php");

class Wsfev1
{
 	protected $mysqli;

	function __construct($mysqli) {
		
		ini_set("soap.wsdl_cache_enabled", "0");
				
		$this->mysqli = $mysqli;
	}


	public function FECAESolicitar($p) {
		global $path, $wsfev1_url;
		
		$resultado = new stdClass;
		
		//if ($p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["CbteDesde"] == 0) $p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["CbteDesde"] = 1;
		//if ($p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["CbteHasta"] == 0) $p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["CbteHasta"] = 1;
		
    
		$soapClient = new SoapClient(
			$path . "wsdl/wsfev1.wsdl"
			, array(
				'soap_version'   => SOAP_1_2
				, 'location'       => $wsfev1_url
				, 'trace'          => 1
				, 'exceptions'     => 0
				
				//, 'proxy_host'     => PROXY_HOST
				//, 'proxy_port'     => PROXY_PORT
			)
		);
	
		$FECAESolicitar = $soapClient->FECAESolicitar($p);
		//file_put_contents($path . "xml/FECAESolicitar_LastRequest.xml", $soapClient->__getLastRequest());
		//file_put_contents($path . "xml/FECAESolicitar_LastResponse.xml", $soapClient->__getLastResponse());

		//$e = $this->_checkErrors($results, 'FECAESolicitar');
		
  		if (is_soap_fault($FECAESolicitar)) {
  			
  			// Error de SOAP
  			
			$resultado->resultado = "S";
			$resultado->texto_respuesta = json_encode($FECAESolicitar);

  		} else {
  			echo "<br><br>" . json_encode($FECAESolicitar) . "<br><br>";
  			
  			$resultado->resultado = $FECAESolicitar->FECAESolicitarResult->FeCabResp->Resultado;
  			//$resultado->texto_respuesta = json_encode($FECAESolicitar);
  			$resultado->texto_respuesta = $soapClient->__getLastResponse();
  			
  			//file_put_contents($path . "xml/CAE.txt", $resultado->texto_respuesta);
  		}
  		
  		//unset($p->Auth);
  		unset($p["Auth"]);
  		
  		$resultado->texto_solicitud = json_encode($p);
  		
  		$aux = (array) json_decode($resultado->texto_solicitud);
  		echo "<br><br>" . "<br><br>";
  		var_dump($aux);
  		echo "<br><br>" . "<br><br>";
  		echo json_encode($aux);
  		echo "<br><br>" . "<br><br>";
  		var_dump($aux["FeCAEReq"]->FeCabReq->PtoVta);
  		echo "<br><br>" . "<br><br>";
  		
		
  		$sql = "INSERT wsfev1 SET resultado='" . $resultado->resultado . "', texto_solicitud='" . $resultado->texto_solicitud . "', texto_respuesta='" . $resultado->texto_respuesta . "'";
		$this->mysqli->query($sql);
		$insert_id = $this->mysqli->insert_id;
		
		$resultado->id_wsfev1 = $insert_id;
  		
  		return $resultado;
	}
}

?>