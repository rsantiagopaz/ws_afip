<?php

require_once("class/comp/Ws_afip.class.php");




$modo = "H";

$Ws_afip = new Ws_afip(1, $modo);







//FEDummy =============================================
echo "<br><br>";
echo "<br><br>";
echo "FEDummy =============================================";


$FEDummy = $Ws_afip->FEDummy();
if ($FEDummy->resultado == "A") {
	$xml = new SimpleXMLElement($FEDummy->texto_respuesta);
	$FEDummyResult = $xml->children("soap", true)->Body->children()->FEDummyResponse->FEDummyResult;
	
	echo "<br><br>";
	echo "AppServer: " . $FEDummyResult->AppServer;
	echo "<br>";
	echo "DbServer: " . $FEDummyResult->DbServer;
	echo "<br>";
	echo "AuthServer: " . $FEDummyResult->AuthServer;
	echo "<br><br>";

}

echo json_encode($FEDummy);







//FECompUltimoAutorizado =============================================
echo "<br><br>";
echo "<br><br>";
echo "FECompUltimoAutorizado =============================================";


$p = array(
	"PtoVta"	=> 4000,					// Punto de Venta
	"CbteTipo"	=> 1						// 1=Factura A
);

$FECompUltimoAutorizado = $Ws_afip->FECompUltimoAutorizado($p);
if ($FECompUltimoAutorizado->resultado == "A") {
	$xml = new SimpleXMLElement($FECompUltimoAutorizado->texto_respuesta);
	$FECompUltimoAutorizadoResult = $xml->children("soap", true)->Body->children()->FECompUltimoAutorizadoResponse->FECompUltimoAutorizadoResult;
	
	echo "<br><br>";
	echo "PtoVta: " . $FECompUltimoAutorizadoResult->PtoVta;
	echo "<br>";
	echo "CbteTipo: " . $FECompUltimoAutorizadoResult->CbteTipo;
	echo "<br>";
	echo "CbteNro: " . $FECompUltimoAutorizadoResult->CbteNro;
	echo "<br><br>";

}

echo json_encode($FECompUltimoAutorizado);















 
//FECAESolicitar =============================================
echo "<br><br>";
echo "<br><br>";
echo "FECAESolicitar =============================================";
echo "<br><br>";


		
/*
$p = array();

$p["FeCAEReq"] = array();
$p["FeCAEReq"]["FeCabReq"] = array();
$p["FeCAEReq"]["FeCabReq"]["CantReg"] = 1;
$p["FeCAEReq"]["FeCabReq"]["PtoVta"] = 4000;	//Punto de Venta
$p["FeCAEReq"]["FeCabReq"]["CbteTipo"] = 1;		//1=Factura A

$p["FeCAEReq"]["FeDetReq"] = array();
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"] = array();
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["Concepto"] = 1;
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["DocTipo"] = 80;			//80=CUIL
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["DocNro"] = 20219021810;
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["CbteDesde"] = 1284;
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["CbteHasta"] = 1284;
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["CbteFch"] = date('Ymd');	// fecha emision de factura
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["ImpNeto"] = 100;			// neto gravado
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["ImpTotConc"] = 0;		// no gravado
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["ImpIVA"] = 21;			// IVA liquidado
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["ImpTrib"] = 0;			// otros tributos
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["ImpOpEx"] = 0;			// operacion exentas
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["ImpTotal"] = 121;		// total de la factura. ImpNeto + ImpTotConc + ImpIVA + ImpTrib + ImpOpEx
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["FchServDesde"] = null;	// solo concepto 2 o 3
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["FchServHasta"] = null;	// solo concepto 2 o 3
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["FchVtoPago"] = null;		// solo concepto 2 o 3
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["MonId"] = 'PES';			// Id de moneda 'PES'
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["MonCotiz"] = 1;			// Cotizacion moneda. Solo exportacion

$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["Tributos"] = array();
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["Tributos"]["Tributo"] = array();
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["Tributos"]["Tributo"]["Id"] = 1;
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["Tributos"]["Tributo"]["Desc"] = 'impuesto';
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["Tributos"]["Tributo"]["BaseImp"] = 0;
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["Tributos"]["Tributo"]["Alic"] = 0;
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["Tributos"]["Tributo"]["Importe"] = 0;

$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["Iva"] = array();
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["Iva"]["AlicIva"] = array();
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["Iva"]["AlicIva"]["Id"] = 5;
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["Iva"]["AlicIva"]["BaseImp"] = 100;
$p["FeCAEReq"]["FeDetReq"]["FECAEDetRequest"]["Iva"]["AlicIva"]["Importe"] = 21;
*/



//================================================================================================================================================



/*
$p = new stdClass;

$p->FeCAEReq = new stdClass;
$p->FeCAEReq->FeCabReq = new stdClass;
$p->FeCAEReq->FeCabReq->CantReg = 1;
$p->FeCAEReq->FeCabReq->PtoVta = 4000;	//Punto de Venta
$p->FeCAEReq->FeCabReq->CbteTipo = 1;		//1=Factura A

$p->FeCAEReq->FeDetReq = new stdClass;
$p->FeCAEReq->FeDetReq->FECAEDetRequest = new stdClass;
$p->FeCAEReq->FeDetReq->FECAEDetRequest->Concepto = 1;
$p->FeCAEReq->FeDetReq->FECAEDetRequest->DocTipo = 80;			//80=CUIL
$p->FeCAEReq->FeDetReq->FECAEDetRequest->DocNro = 20219021810;
$p->FeCAEReq->FeDetReq->FECAEDetRequest->CbteDesde = 1293;
$p->FeCAEReq->FeDetReq->FECAEDetRequest->CbteHasta = 1293;
$p->FeCAEReq->FeDetReq->FECAEDetRequest->CbteFch = date('Ymd');	// fecha emision de factura
$p->FeCAEReq->FeDetReq->FECAEDetRequest->ImpNeto = 100;			// neto gravado
$p->FeCAEReq->FeDetReq->FECAEDetRequest->ImpTotConc = 0;		// no gravado
$p->FeCAEReq->FeDetReq->FECAEDetRequest->ImpIVA = 21;			// IVA liquidado
$p->FeCAEReq->FeDetReq->FECAEDetRequest->ImpTrib = 0;			// otros tributos
$p->FeCAEReq->FeDetReq->FECAEDetRequest->ImpOpEx = 0;			// operacion exentas
$p->FeCAEReq->FeDetReq->FECAEDetRequest->ImpTotal = 121;		// total de la factura. ImpNeto + ImpTotConc + ImpIVA + ImpTrib + ImpOpEx
$p->FeCAEReq->FeDetReq->FECAEDetRequest->FchServDesde = null;	// solo concepto 2 o 3
$p->FeCAEReq->FeDetReq->FECAEDetRequest->FchServHasta = null;	// solo concepto 2 o 3
$p->FeCAEReq->FeDetReq->FECAEDetRequest->FchVtoPago = null;		// solo concepto 2 o 3
$p->FeCAEReq->FeDetReq->FECAEDetRequest->MonId = 'PES';			// Id de moneda 'PES'
$p->FeCAEReq->FeDetReq->FECAEDetRequest->MonCotiz = 1;			// Cotizacion moneda. Solo exportacion

$p->FeCAEReq->FeDetReq->FECAEDetRequest->Tributos = new stdClass;
$p->FeCAEReq->FeDetReq->FECAEDetRequest->Tributos->Tributo = new stdClass;
$p->FeCAEReq->FeDetReq->FECAEDetRequest->Tributos->Tributo->Id = 1;
$p->FeCAEReq->FeDetReq->FECAEDetRequest->Tributos->Tributo->Desc = 'impuesto';
$p->FeCAEReq->FeDetReq->FECAEDetRequest->Tributos->Tributo->BaseImp = 0;
$p->FeCAEReq->FeDetReq->FECAEDetRequest->Tributos->Tributo->Alic = 0;
$p->FeCAEReq->FeDetReq->FECAEDetRequest->Tributos->Tributo->Importe = 0;

$p->FeCAEReq->FeDetReq->FECAEDetRequest->Iva = new stdClass;
$p->FeCAEReq->FeDetReq->FECAEDetRequest->Iva->AlicIva = new stdClass;
$p->FeCAEReq->FeDetReq->FECAEDetRequest->Iva->AlicIva->Id = 5;
$p->FeCAEReq->FeDetReq->FECAEDetRequest->Iva->AlicIva->BaseImp = 100;
$p->FeCAEReq->FeDetReq->FECAEDetRequest->Iva->AlicIva->Importe = 21;
*/







//================================================================================================================================================




$p = array(
	"FeCAEReq" => array(
		"FeCabReq" => array(
			"CantReg"	=> 1,
			"PtoVta"	=> 4000,					// Punto de Venta
			"CbteTipo"	=> 1						// 1=Factura A
		),
		"FeDetReq" => array(
			"FECAEDetRequest" => array(
				"Concepto"		=> 1,				// 1=Productos, 2=Servicios, 3=Productos y Servicios
				"DocTipo"		=> 80,				// 80=CUIL
				"DocNro"		=> 20219021810,
				"CbteDesde"		=> 1366,			// rango 1-99999999
				"CbteHasta"		=> 1366,			// rango 1-99999999
				"CbteFch"		=> date('Ymd'),		// fecha emision de factura
				"ImpNeto"		=> 100,				// neto gravado
				"ImpTotConc"	=> 0,				// no gravado
				"ImpIVA"		=> 21,				// IVA liquidado
				"ImpTrib"		=> 0,				// otros tributos
				"ImpOpEx"		=> 0,				// operacion exentas
				"ImpTotal"		=> 121,				// total de la factura. ImpNeto + ImpTotConc + ImpIVA + ImpTrib + ImpOpEx
				"FchServDesde"	=> null,			// solo Concepto = 2 o 3
				"FchServHasta"	=> null,			// solo Concepto = 2 o 3
				"FchVtoPago"	=> null,			// solo Concepto = 2 o 3
				"MonId"			=> "PES",			// Id de moneda "PES"
				"MonCotiz"		=> 1,				// Cotizacion moneda. Solo exportacion
				"Tributos" => array(
					"Tributo" => array(
						array(
							"Id"		=>  1,
							"Desc"		=> "impuesto",
							"BaseImp"	=> 0,
							"Alic"		=> 0,
							"Importe"	=> 0
						)
					),
				),
				"Iva" => array(
					"AlicIva" => array(
						array(
							"Id"		=> 5,
							"BaseImp"	=> 100,
							"Importe"	=> 21
						),
						/*
						array(
							"Id"		=> 1,
							"BaseImp"	=> 100,
							"Importe"	=> 21
						)
						*/
					),
				),
			), 
		), 
	), 
);




//================================================================================================================================================









if ($modo == "H") $ws_afip_modo = "homologacion"; else if ($modo == "P") $ws_afip_modo = "produccion";


$mysqli = new mysqli("$ws_afip_servidor", "$ws_afip_usuario", "$ws_afip_password", "ws_afip_" . $ws_afip_modo);
$mysqli->query("SET NAMES 'utf8'");








$FECAESolicitar = $Ws_afip->FECAESolicitar($p);



if (isset($FECAESolicitar->id_documento)) {
	
	// CAE aprobado
	
	$sql = "SELECT * FROM documento WHERE id_documento='" . $FECAESolicitar->id_documento . "'";
	$rsDocumento = $mysqli->query($sql);
	$rowDocumento = $rsDocumento->fetch_object();
	
	echo "<br><br>";
	echo "CAE: " . $rowDocumento->CAE;
	echo "<br>";
	echo "CAEFchVto: " . $rowDocumento->CAEFchVto;
	echo "<br><br>";
	
	
	
	
	// Posibles Errores u Observaciones no excluyentes
	

	$sql = "SELECT * FROM wsfev1 WHERE id_wsfev1='" . $FECAESolicitar->id_wsfev1 . "'";
	$rsWsfev1 = $mysqli->query($sql);
	$rowWsfev1 = $rsWsfev1->fetch_object();
	
	$xml = new SimpleXMLElement($rowWsfev1->texto_respuesta);
	$FECAESolicitarResponse = $xml->children("soap", true)->Body->children()->FECAESolicitarResponse;
	
	
	$Errors = $FECAESolicitarResponse->FECAESolicitarResult->Errors;
	if (count($Errors) > 0) {
		echo "<br><br>";
		echo "<br><br>";
		echo "Errores no excluyentes";
		
		foreach ($Errors->Err as $item) {
			echo "<br><br>";
			echo "Err Code: " . $item->Code;
			echo "<br>";
			echo "Err Msg: " . $item->Msg;
			echo "<br><br>";
		}
	}

	$FECAEDetResponse = $FECAESolicitarResponse->FECAESolicitarResult->FeDetResp->FECAEDetResponse;
	if (count($FECAEDetResponse) > 0) {
		foreach ($FECAEDetResponse as $item) {
			$Observaciones = $item->Observaciones;
			if (count($Observaciones) > 0) {
				echo "<br><br>";
				echo "<br><br>";
				echo "Observación no excluyente";
				
				foreach ($Observaciones->Obs as $item) {
					echo "<br><br>";
					echo "Obs Code: " . $item->Code;
					echo "<br>";
					echo "Obs Msg: " . $item->Msg;
					echo "<br><br>";
				}
			}
		}
	}
	

	
} else if (isset($FECAESolicitar->id_wsfev1)) {
	
	// Error en SOAP wsfev1 o error excluyente en logica de negocios
	
	$sql = "SELECT * FROM wsfev1 WHERE id_wsfev1='" . $FECAESolicitar->id_wsfev1 . "'";
	$rsWsfev1 = $mysqli->query($sql);
	$rowWsfev1 = $rsWsfev1->fetch_object();
	
	if ($rowWsfev1->resultado == "S") {
		
		// Error de SOAP
		
		
		echo "<br><br>";
		echo "<br><br>";
		echo "Error de SOAP wsfev1";
		
		
	} else if ($rowWsfev1->resultado == "R") {
		
		// Error excluyente en lógica de negocios
		
	
		$xml = new SimpleXMLElement($rowWsfev1->texto_respuesta);
		$FECAESolicitarResponse = $xml->children("soap", true)->Body->children()->FECAESolicitarResponse;
		
		
		$Errors = $FECAESolicitarResponse->FECAESolicitarResult->Errors;
		if (count($Errors) > 0) {
			echo "<br><br>";
			echo "<br><br>";
			echo "Errores excluyentes";
			
			foreach ($Errors->Err as $item) {
				echo "<br><br>";
				echo "Err Code: " . $item->Code;
				echo "<br>";
				echo "Err Msg: " . $item->Msg;
				echo "<br><br>";
			}
		}

		$FECAEDetResponse = $FECAESolicitarResponse->FECAESolicitarResult->FeDetResp->FECAEDetResponse;
		if (count($FECAEDetResponse) > 0) {
			foreach ($FECAEDetResponse as $item) {
				$Observaciones = $item->Observaciones;
				if (count($Observaciones) > 0) {
					echo "<br><br>";
					echo "<br><br>";
					echo "Observación excluyente";
					
					foreach ($Observaciones->Obs as $item) {
						echo "<br><br>";
						echo "Obs Code: " . $item->Code;
						echo "<br>";
						echo "Obs Msg: " . $item->Msg;
						echo "<br><br>";
					}
				}
			}
		}
	}
	
} else if (isset($FECAESolicitar->id_wsaa)) {
	
	$sql = "SELECT * FROM wsaa WHERE id_wsaa='" . $FECAESolicitar->id_wsaa . "'";
	$rsWsaa = $mysqli->query($sql);
	$rowWsaa = $rsWsaa->fetch_object();
	
	if ($rowWsaa->resultado == "S") {
		
		// Error de SOAP wsaa
		
		
		$xml = new SimpleXMLElement($rowWsaa->texto_respuesta);
		$Value = $xml->children("soapenv", true)->Body->children("soapenv", true)->Fault->children("soapenv", true)->Code->children("soapenv", true)->Value;
		$Text = $xml->children("soapenv", true)->Body->children("soapenv", true)->Fault->children("soapenv", true)->Reason->children("soapenv", true)->Text;
		
		
		echo "<br><br>";
		echo "<br><br>";
		echo "Error de SOAP wsaa";
		echo "<br><br>";
		echo "Fault Code Value: " . $Value;
		echo "<br>";
		echo "Fault Reason Text: " . $Text;
		echo "<br>";
		
	}
}

echo "<br><br>";
echo "<br><br>";
echo json_encode($FECAESolicitar);
	

?>