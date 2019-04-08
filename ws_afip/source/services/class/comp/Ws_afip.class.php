<?php

require_once("Config.php");
require_once("Wsaa.class.php");
require_once("Wsfev1.class.php");


class Ws_afip
{
 	protected $mysqli;
 	
 	protected $Wsaa;
 	protected $Wsfev1;

	function __construct() {
		global $servidor, $usuario, $password, $modo;
		
		
		$aux = new mysqli_driver;
		$aux->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
				
		$this->mysqli = new mysqli("$servidor", "$usuario", "$password", "ws_afip_" . $modo);
		$this->mysqli->query("SET NAMES 'utf8'");
		
		
		$this->Wsaa = new Wsaa($this->mysqli);
		$this->Wsfev1 = new Wsfev1($this->mysqli);
	}


	public function FECAESolicitar($p) {
		global $path, $CUIT;
		
		$resultado = new stdClass;
		
		$TA = false;
		
		if (is_file($path . "xml/TA.xml")) {
			$TA = simplexml_load_file($path . "xml/TA.xml");
		
			if ($TA) {
				$expirationTime = $TA->header->expirationTime;
				$expirationTime = substr($expirationTime, 0, 10) . " " . substr($expirationTime, 11, 8);
				
				if ($expirationTime < date("Y-m-d h:i:s")) {
					$TA = false;
				} else {
					$sql = "SELECT id_wsaa FROM wsaa WHERE resultado='A' ORDER BY id_wsaa DESC LIMIT 1";
					$rs = $this->mysqli->query($sql);
					$row = $rs->fetch_object();
					
					$resultado->id_wsaa = $row->id_wsaa;
				}
			}
		}
		
		$sql = "START TRANSACTION";
		$this->mysqli->query($sql);
		
		if (! $TA) {
			$this->Wsaa->CreateTRA("wsfe");
			$CMS = $this->Wsaa->SignTRA();
			$CallWSAA = $this->Wsaa->CallWSAA($CMS);
			
			//echo "<br><br>" . json_encode($TA) . "<br><br>";
			
			$resultado->id_wsaa = $CallWSAA->id_wsaa;
			
			if ($CallWSAA->resultado == "A") $TA = new SimpleXMLElement($CallWSAA->texto_respuesta);
		}
		
		if ($TA) {
			/*
			$p["Auth"] = array();
			$p["Auth"]["Token"] = $TA->credentials->token;
			$p["Auth"]["Sign"] = $TA->credentials->sign;
			$p["Auth"]["Cuit"] = $CUIT;
			*/
			
			/*
			$p->Auth = new stdClass;
			$p->Auth->Token = $TA->credentials->token;
			$p->Auth->Sign = $TA->credentials->sign;
			$p->Auth->Cuit = $CUIT;
			*/
			
			$p["Auth"] = array(
				"Token"	=> $TA->credentials->token,
				"Sign"	=> $TA->credentials->sign,
				"Cuit"	=> $CUIT,
			);
		
			$FECAESolicitar = $this->Wsfev1->FECAESolicitar($p);
			
			$resultado->id_wsfev1 = $FECAESolicitar->id_wsfev1;

			$sql = "INSERT solicitud SET id_wsaa='" . $resultado->id_wsaa . "', id_wsfev1='" . $resultado->id_wsfev1 . "'";
			$this->mysqli->query($sql);
			$insert_id = $this->mysqli->insert_id;
		
			$resultado->id_solicitud = $insert_id;
			
			if ($FECAESolicitar->resultado == "A" || $FECAESolicitar->resultado == "P") {
				$sql = "INSERT documento SET id_solicitud='" . $resultado->id_solicitud . "', id_wsaa='" . $resultado->id_wsaa . "', id_wsfev1='" . $resultado->id_wsfev1 . "'";
				$this->mysqli->query($sql);
				$insert_id = $this->mysqli->insert_id;
				
				$resultado->id_documento = $insert_id;
			}
		} else {
			$sql = "INSERT solicitud SET id_wsaa='" . $resultado->id_wsaa . "'";
			$this->mysqli->query($sql);
			$insert_id = $this->mysqli->insert_id;
		
			$resultado->id_solicitud = $insert_id;			
		}
		
		
		$sql = "COMMIT";
		$this->mysqli->query($sql);
		
		
		return $resultado;
	}
}

?>