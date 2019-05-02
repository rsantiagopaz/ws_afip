<?php

require_once("Conexion.php");

require_once("Wsaa.class.php");
require_once("Wsfev1.class.php");


class Ws_afip
{
 	protected $mysqli;
 	protected $rowEmisor;
 	protected $config;
 	
 	protected $Wsaa;
 	protected $Wsfev1;

	function __construct($id_emisor, $modo = null) {
		global $ws_afip_servidor, $ws_afip_usuario, $ws_afip_password;
		
		$aux = new mysqli_driver;
		$aux->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
		
		$this->mysqli = new mysqli("$ws_afip_servidor", "$ws_afip_usuario", "$ws_afip_password", "ws_afip");
		$this->mysqli->query("SET NAMES 'utf8'");
		
		$sql = "SELECT * FROM emisor WHERE id_emisor=" . $id_emisor;
		$rs = $this->mysqli->query($sql);
		$this->rowEmisor = $rs->fetch_object();
		$this->rowEmisor->cuit = (float) $this->rowEmisor->cuit;
		
		$this->mysqli->close();
		
		
		$this->config = new stdClass;
		
		if (isset($modo)) {
			if ($modo == "H") $this->config->modo = "homologacion"; else if ($modo == "P") $this->config->modo = "produccion";
		} else {
			if ($this->rowEmisor->modo == "H") $this->config->modo = "homologacion"; else if ($this->rowEmisor->modo == "P") $this->config->modo = "produccion";
		}
		
		
		$this->config->path = "ws_afip/" . $this->config->modo . "/";
		
		if ($this->config->modo == "homologacion") {
			$this->config->wsaa_url = "https://wsaahomo.afip.gov.ar/ws/services/LoginCms";
			$this->config->wsfev1_url = "https://wswhomo.afip.gov.ar/wsfev1/service.asmx";
			
			$this->config->crt = $this->rowEmisor->homologacion_crt;
			$this->config->key = $this->rowEmisor->homologacion_key;
			$this->config->passphrase = $this->rowEmisor->homologacion_passphrase;
			
		} else if ($this->config->modo == "produccion") {
			$this->config->wsaa_url = "";
			$this->config->wsfev1_url = "";
			
			$this->config->crt = $this->rowEmisor->produccion_crt;
			$this->config->key = $this->rowEmisor->produccion_key;
			$this->config->passphrase = $this->rowEmisor->produccion_passphrase;
		}
		
		
		
		
				
		$this->mysqli = new mysqli("$ws_afip_servidor", "$ws_afip_usuario", "$ws_afip_password", "ws_afip_" . $this->config->modo);
		$this->mysqli->query("SET NAMES 'utf8'");
		
		
		$this->Wsaa = new Wsaa;
		$this->Wsaa->mysqli = $this->mysqli;
		$this->Wsaa->rowEmisor = $this->rowEmisor;
		$this->Wsaa->config = $this->config;
		
		$this->Wsfev1 = new Wsfev1;
		$this->Wsfev1->mysqli = $this->mysqli;
		$this->Wsfev1->rowEmisor = $this->rowEmisor;
		$this->Wsfev1->config = $this->config;
	}


	public function FECAESolicitar($p) {
		
		$resultado = new stdClass;
		
		$GetTA = $this->Wsaa->GetTA("wsfe");
		$resultado->id_wsaa = $GetTA->id_wsaa;
		
		if ($GetTA->TA) {
			/*
			$p["Auth"] = array();
			$p["Auth"]["Token"] = $GetTA->TA->credentials->token;
			$p["Auth"]["Sign"] = $GetTA->TA->credentials->sign,
			$p["Auth"]["Cuit"] = $this->rowEmisor->cuit
			*/
			
			/*
			$p->Auth = new stdClass;
			$p->Auth->Token = $GetTA->TA->credentials->token;
			$p->Auth->Sign = $GetTA->TA->credentials->sign,
			$p->Auth->Cuit = $this->rowEmisor->cuit
			*/
			
			$p["Auth"] = array(
				"Token"	=> $GetTA->TA->credentials->token,
				"Sign"	=> $GetTA->TA->credentials->sign,
				"Cuit"	=> $this->rowEmisor->cuit
			);
		
			$FECAESolicitar = $this->Wsfev1->FECAESolicitar($p);
			
			$resultado->id_wsfev1 = $FECAESolicitar->id_wsfev1;

			$sql = "INSERT solicitud SET id_emisor=" . $this->rowEmisor->id_emisor . ", id_wsaa='" . $resultado->id_wsaa . "', id_wsfev1='" . $resultado->id_wsfev1 . "', fecha=NOW()";
			$this->mysqli->query($sql);
			$insert_id = $this->mysqli->insert_id;
		
			$resultado->id_solicitud = $insert_id;
			
			if ($FECAESolicitar->resultado == "A") {
				$xml = new SimpleXMLElement($FECAESolicitar->texto_respuesta);
				$FECAESolicitarResponse = $xml->children("soap", true)->Body->children()->FECAESolicitarResponse;
				$FECAEDetResponse = $FECAESolicitarResponse->FECAESolicitarResult->FeDetResp->FECAEDetResponse;
				
				$sql = "INSERT documento SET id_solicitud='" . $resultado->id_solicitud . "', CAE='" . $FECAEDetResponse->CAE . "', CAEFchVto='" . $FECAEDetResponse->CAEFchVto . "'";
				$this->mysqli->query($sql);
				$insert_id = $this->mysqli->insert_id;
				
				$resultado->id_documento = $insert_id;
			}
		} else {
			$sql = "INSERT solicitud SET id_wsaa='" . $resultado->id_wsaa . "', fecha=NOW()";
			$this->mysqli->query($sql);
			$insert_id = $this->mysqli->insert_id;
		
			$resultado->id_solicitud = $insert_id;			
		}
		
		

		$this->mysqli->query("COMMIT");
		
		
		return $resultado;
	}
	
	
	
	public function FECompUltimoAutorizado($p) {
		
		$GetTA = $this->Wsaa->GetTA("wsfe");
		
		if ($GetTA->TA) {
			$p["Auth"] = array(
				"Token"	=> $GetTA->TA->credentials->token,
				"Sign"	=> $GetTA->TA->credentials->sign,
				"Cuit"	=> $this->rowEmisor->cuit
			);
			
			return $this->Wsfev1->FECompUltimoAutorizado($p);
		}
	}
	
	
	
	public function FEDummy() {
		
		return $this->Wsfev1->FEDummy();
	}
}

?>