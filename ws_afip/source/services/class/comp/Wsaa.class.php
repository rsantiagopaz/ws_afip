<?php

class Wsaa
{
 	public $mysqli;
 	public $rowEmisor;
 	public $config;
 	
 	protected $service;
 	protected $cadena;
 	
	function __construct() {
		
		ini_set("soap.wsdl_cache_enabled", "0");
	}

	
	function CreateTRA() {
		
		$TRA = new SimpleXMLElement(
			'<?xml version="1.0" encoding="UTF-8"?>
			<loginTicketRequest version="1.0" />'
		);
		$TRA->addChild('header');
		$TRA->header->addChild('uniqueId', date('U'));
		$TRA->header->addChild('generationTime', date('c', date('U') - 60));
		$TRA->header->addChild('expirationTime', date('c', date('U') + 60));
		$TRA->addChild('service', $this->service);
		$TRA->asXML($this->config->path . "xml/TRA" . $this->cadena . ".xml");
	}
	
	
	function SignTRA() {
		
		$fp = fopen($this->config->path . "xml/TRA" . $this->cadena . ".tmp", "w");
		fclose($fp);
		
		$STATUS = openssl_pkcs7_sign(
			realpath($this->config->path . "xml/TRA" . $this->cadena . ".xml")
			, realpath($this->config->path . "xml/TRA" . $this->cadena . ".tmp")
			, "file://" . realpath($this->config->path . "key/" . $this->config->crt)
			, array("file://" . realpath($this->config->path . "key/" . $this->config->key), $this->config->passphrase)
			, array()
			, ! PKCS7_DETACHED
		);
		
  		if (! $STATUS) {
  			//exit("ERROR generating PKCS#7 signature\n");
  			throw new Exception("ERROR generating PKCS#7 signature\n");
  		}
  		
		$inf = fopen($this->config->path . "xml/TRA" . $this->cadena . ".tmp", "r");
		$i = 0;
		$CMS = "";
		while (! feof($inf)) { 
			$buffer = fgets($inf);
			if ( $i++ >= 4 ) {
				$CMS.= $buffer;
			}
		}
		
		fclose($inf);
		
		#  unlink("TRA.xml");
		unlink($this->config->path . "xml/TRA" . $this->cadena . ".tmp");
		
		return $CMS;
	}
	
	
	function CallWSAA($CMS) {
		
		$resultado = new stdClass;
		
		$soapClient = new SoapClient(
			$this->config->path . "wsdl/wsaa.wsdl"
			, array(
				'soap_version'   => SOAP_1_2
				, 'location'       => $this->config->wsaa_url
				, 'trace'          => 1
				, 'exceptions'     => 0
				
				//, 'proxy_host'     => PROXY_HOST
				//, 'proxy_port'     => PROXY_PORT
			)
		);
           
		$loginCms = $soapClient->loginCms(array('in0' => $CMS));
		file_put_contents($this->config->path . "xml/loginCms_LastRequest" . $this->cadena . ".xml", $soapClient->__getLastRequest());
		file_put_contents($this->config->path . "xml/loginCms_LastResponse" . $this->cadena . ".xml", $soapClient->__getLastResponse());
		
  		if (is_soap_fault($loginCms)) {
  			
  			// Error de SOAP
	
			$resultado->resultado = "S";
			$resultado->texto_respuesta = $soapClient->__getLastResponse();

  		} else {
  			$loginCmsReturn = $loginCms->loginCmsReturn;
  			
  			file_put_contents($this->config->path . "xml/TA" . $this->cadena . ".xml", $loginCmsReturn);
  			
  			$resultado->resultado = "A";
  			$resultado->texto_respuesta = $loginCmsReturn;
  		}
  		
  		$sql = "INSERT wsaa SET resultado='" . $resultado->resultado . "', texto_respuesta='" . $resultado->texto_respuesta . "'";
		$this->mysqli->query($sql);
		$insert_id = $this->mysqli->insert_id;
		
		$resultado->id_wsaa = $insert_id;
  		
  		return $resultado;
	}
	
	
	public function GetTA($service = "wsfe") {
		
		$this->service = $service;
		$this->cadena = "_" . $service . "_" . $this->rowEmisor->id_emisor;
		
		
		$resultado = new stdClass;
		$resultado->TA = false;
		
		if (is_file($this->config->path . "xml/TA" . $this->cadena . ".xml")) {
			$resultado->TA = simplexml_load_file($this->config->path . "xml/TA" . $this->cadena . ".xml");
		
			if ($resultado->TA) {
				$expirationTime = $resultado->TA->header->expirationTime;
				$expirationTime = substr($expirationTime, 0, 10) . " " . substr($expirationTime, 11, 8);
				
				if ($expirationTime < date("Y-m-d H:i:s")) {
					$resultado->TA = false;
				} else {
					$sql = "SELECT * FROM wsaa WHERE resultado='A' ORDER BY id_wsaa DESC LIMIT 1";
					$rs = $this->mysqli->query($sql);
					$row = $rs->fetch_object();
					
					$resultado->id_wsaa = (int) $row->id_wsaa;
					$resultado->resultado = $row->resultado;
					$resultado->texto_respuesta = $row->texto_respuesta;
					$resultado->TA = new SimpleXMLElement($resultado->texto_respuesta);
				}
			}
		}
		

		if (! $resultado->TA) {
			$this->CreateTRA();
			$CMS = $this->SignTRA();
			$CallWSAA = $this->CallWSAA($CMS);
			
			$resultado->id_wsaa = $CallWSAA->id_wsaa;
			$resultado->resultado = $CallWSAA->resultado;
			$resultado->texto_respuesta = $CallWSAA->texto_respuesta;
			if ($resultado->resultado == "A") $resultado->TA = new SimpleXMLElement($resultado->texto_respuesta);
		}
		
		return $resultado;
	}
}

?>