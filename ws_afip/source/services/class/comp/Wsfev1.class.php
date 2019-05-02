<?php

class Wsfev1
{
 	public $mysqli;
 	public $rowEmisor;
 	public $config;

	function __construct() {
		
		ini_set("soap.wsdl_cache_enabled", "0");
	}


	public function FECAESolicitar($p) {
		
		$resultado = new stdClass;
		
   
		$soapClient = new SoapClient(
			$this->config->path . "wsdl/wsfev1.wsdl"
			, array(
				'soap_version'   => SOAP_1_2
				, 'location'       => $this->config->wsfev1_url
				, 'trace'          => 1
				, 'exceptions'     => 0
				
				//, 'proxy_host'     => PROXY_HOST
				//, 'proxy_port'     => PROXY_PORT
			)
		);
	
		$FECAESolicitar = $soapClient->FECAESolicitar($p);
		
		file_put_contents($this->config->path . "xml/FECAESolicitar_LastRequest_" . $this->rowEmisor->id_emisor . ".xml", $soapClient->__getLastRequest());
		file_put_contents($this->config->path . "xml/FECAESolicitar_LastResponse_" . $this->rowEmisor->id_emisor . ".xml", $soapClient->__getLastResponse());

  		if (is_soap_fault($FECAESolicitar)) {
  			
  			// Error de SOAP
  			
			$resultado->resultado = "S";
			$resultado->texto_respuesta = $soapClient->__getLastResponse();

  		} else {

  			$resultado->resultado = $FECAESolicitar->FECAESolicitarResult->FeCabResp->Resultado;
  			$resultado->texto_respuesta = $soapClient->__getLastResponse();
  		}
  		
  		unset($p["Auth"]);
  		
  		$resultado->texto_solicitud = json_encode($p);
		
  		$sql = "INSERT wsfev1 SET resultado='" . $resultado->resultado . "', texto_solicitud='" . $resultado->texto_solicitud . "', texto_respuesta='" . $resultado->texto_respuesta . "'";
		$this->mysqli->query($sql);
		$insert_id = $this->mysqli->insert_id;
		
		$resultado->id_wsfev1 = $insert_id;
  		
  		return $resultado;
	}
	
	
	
	public function FECompUltimoAutorizado($p) {
		
		$resultado = new stdClass;
		
    
		$soapClient = new SoapClient(
			$this->config->path . "wsdl/wsfev1.wsdl"
			, array(
				'soap_version'   => SOAP_1_2
				, 'location'       => $this->config->wsfev1_url
				, 'trace'          => 1
				, 'exceptions'     => 0
				
				//, 'proxy_host'     => PROXY_HOST
				//, 'proxy_port'     => PROXY_PORT
			)
		);
	
		$FECompUltimoAutorizado = $soapClient->FECompUltimoAutorizado($p);
		
		file_put_contents($this->config->path . "xml/FECompUltimoAutorizado_LastRequest_" . $this->rowEmisor->id_emisor . ".xml", $soapClient->__getLastRequest());
		file_put_contents($this->config->path . "xml/FECompUltimoAutorizado_LastResponse_" . $this->rowEmisor->id_emisor . ".xml", $soapClient->__getLastResponse());

  		if (is_soap_fault($FECompUltimoAutorizado)) {
  			
  			// Error de SOAP
  			
			$resultado->resultado = "S";
			$resultado->texto_respuesta = $soapClient->__getLastResponse();

  		} else {

  			$resultado->resultado = "A";
  			$resultado->texto_respuesta = $soapClient->__getLastResponse();
  		}
  		
  		return $resultado;
	}
	
	
	
	public function FEDummy() {
		
		$resultado = new stdClass;
		
    
		$soapClient = new SoapClient(
			$this->config->path . "wsdl/wsfev1.wsdl"
			, array(
				'soap_version'   => SOAP_1_2
				, 'location'       => $this->config->wsfev1_url
				, 'trace'          => 1
				, 'exceptions'     => 0
				
				//, 'proxy_host'     => PROXY_HOST
				//, 'proxy_port'     => PROXY_PORT
			)
		);
	
		$FEDummy = $soapClient->FEDummy();
		
		file_put_contents($this->config->path . "xml/FEDummy_LastRequest_" . $this->rowEmisor->id_emisor . ".xml", $soapClient->__getLastRequest());
		file_put_contents($this->config->path . "xml/FEDummy_LastResponse_" . $this->rowEmisor->id_emisor . ".xml", $soapClient->__getLastResponse());

  		if (is_soap_fault($FEDummy)) {
  			
  			// Error de SOAP
  			
			$resultado->resultado = "S";
			$resultado->texto_respuesta = $soapClient->__getLastResponse();

  		} else {

  			$resultado->resultado = "A";
  			$resultado->texto_respuesta = $soapClient->__getLastResponse();
  		}
  		
  		return $resultado;
	}
}

?>