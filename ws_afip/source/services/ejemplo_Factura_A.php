<?php

require_once("class/comp/Ws_afip.class.php");




$modo = "homologacion";

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
	
	echo "AppServer: " .	$FEDummyResult->AppServer		. "<br>";
	echo "DbServer: " .		$FEDummyResult->DbServer		. "<br>";
	echo "AuthServer: " .	$FEDummyResult->AuthServer		. "<br>";
	
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
	
	echo "PtoVta: " .		$FECompUltimoAutorizadoResult->PtoVta		. "<br>";
	echo "CbteTipo: " .		$FECompUltimoAutorizadoResult->CbteTipo		. "<br>";
	echo "CbteNro: " .		$FECompUltimoAutorizadoResult->CbteNro		. "<br>";
	
	echo "<br><br>";

}

echo json_encode($FECompUltimoAutorizado);















 
//FECAESolicitar =============================================
echo "<br><br>";
echo "<br><br>";
echo "FECAESolicitar =============================================";


		
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
				"DocTipo"		=> 80,				// 80=CUIT
				"DocNro"		=> 20219021810,
				"CbteDesde"		=> 7,			// rango 1-99999999
				"CbteHasta"		=> 7,			// rango 1-99999999
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
				
				/*
				"CbtesAsoc" => array(
					"CbteAsoc" => array(
						array(
							"Tipo"		=> 1,
							"PtoVta"	=> 4000,
							"Nro"		=> 1
						)
					),

					"CbteAsoc" => array(
						array(
							"Tipo"		=> 1,
							"PtoVta"	=> 4000,
							"Nro"		=> 2
						)
					),

				),
				*/
				
				
				"Tributos" => array(
					"Tributo" => array(
						array(
							"Id"		=> 1,
							"Desc"		=> "impuesto",
							"BaseImp"	=> 0,
							"Alic"		=> 0,
							"Importe"	=> 0
						)
					),
					/*
					"Tributo" => array(
						array(
							"Id"		=> 1,
							"Desc"		=> "impuesto",
							"BaseImp"	=> 0,
							"Alic"		=> 0,
							"Importe"	=> 0
						)
					),
					*/
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









$mysqli = new mysqli("$ws_afip_servidor", "$ws_afip_usuario", "$ws_afip_password", "ws_afip_" . $modo);
$mysqli->query("SET NAMES 'utf8'");








$FECAESolicitar = $Ws_afip->FECAESolicitar($p);



if (isset($FECAESolicitar->id_documento)) {
	
	// CAE aprobado
	
	$sql = "SELECT * FROM documento WHERE id_documento='" . $FECAESolicitar->id_documento . "'";
	$rsDocumento = $mysqli->query($sql);
	$rowDocumento = $rsDocumento->fetch_object();
	
	echo "<br><br>";
	
	echo "CAE: " .			$rowDocumento->CAE			. "<br>";
	echo "CAEFchVto: " .	$rowDocumento->CAEFchVto	. "<br>";
	
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
			
			echo "Err Code: " .		$item->Code		. "<br>";
			echo "Err Msg: " .		$item->Msg		. "<br>";
			
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
					
					echo "Obs Code: " .		$item->Code		. "<br>";
					echo "Obs Msg: " .		$item->Msg		. "<br>";
					
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
				
				echo "Err Code: " .		$item->Code		. "<br>";
				echo "Err Msg: " .		$item->Msg		. "<br>";
				
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
						
						echo "Obs Code: " .		$item->Code		. "<br>";
						echo "Obs Msg: " .		$item->Msg		. "<br>";
						
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
		
		echo "Fault Code Value: " .		$Value		. "<br>";
		echo "Fault Reason Text: " .	$Text		. "<br>";
		
		echo "<br>";
		
	}
}

echo "<br><br>";
echo json_encode($FECAESolicitar);
	









//FECompConsultar =============================================
echo "<br><br>";
echo "<br><br>";
echo "FECompConsultar =============================================";

$p = array(
	"FeCompConsReq" => array(
		"PtoVta"	=> 4000,
		"CbteTipo"	=> 1,
		"CbteNro"	=> 5
	)
);

$FECompConsultar = $Ws_afip->FECompConsultar($p);
if ($FECompConsultar->resultado == "A") {
	$xml = new SimpleXMLElement($FECompConsultar->texto_respuesta);
	$FECompConsultarResult = $xml->children("soap", true)->Body->children()->FECompConsultarResponse->FECompConsultarResult;
	
	echo "<br><br>";
	
	echo "Concepto: " .		$FECompConsultarResult->ResultGet->Concepto			. "<br>";
	echo "DocTipo: " .		$FECompConsultarResult->ResultGet->DocTipo			. "<br>";
	echo "DocNro: " .		$FECompConsultarResult->ResultGet->DocNro			. "<br>";
	echo "CbteDesde: " .	$FECompConsultarResult->ResultGet->CbteDesde		. "<br>";
	echo "CbteHasta: " .	$FECompConsultarResult->ResultGet->CbteHasta		. "<br>";
	echo "CbteFch: " .		$FECompConsultarResult->ResultGet->CbteFch			. "<br>";
	echo "ImpTotal: " .		$FECompConsultarResult->ResultGet->ImpTotal			. "<br>";
	echo "ImpTotConc: " .	$FECompConsultarResult->ResultGet->ImpTotConc		. "<br>";
	echo "ImpNeto: " .		$FECompConsultarResult->ResultGet->ImpNeto			. "<br>";
	echo "ImpOpEx: " .		$FECompConsultarResult->ResultGet->ImpOpEx			. "<br>";
	echo "ImpTrib: " .		$FECompConsultarResult->ResultGet->ImpTrib			. "<br>";
	echo "ImpIVA: " .		$FECompConsultarResult->ResultGet->ImpIVA			. "<br>";
	echo "FchServDesde: " .	$FECompConsultarResult->ResultGet->FchServDesde		. "<br>";
	echo "FchServHasta: " .	$FECompConsultarResult->ResultGet->FchServHasta		. "<br>";
	echo "FchVtoPago: " .	$FECompConsultarResult->ResultGet->FchVtoPago		. "<br>";
	echo "MonId: " .		$FECompConsultarResult->ResultGet->MonId			. "<br>";
	echo "MonCotiz: " .		$FECompConsultarResult->ResultGet->MonCotiz			. "<br>";
	
	if (count($FECompConsultarResult->ResultGet->CbtesAsoc) > 0) {
		echo "<br>";
		echo "CbtesAsoc" . "<br>";
		foreach ($FECompConsultarResult->ResultGet->CbtesAsoc->CbteAsoc as $item) {
			echo "Tipo: " .		$item->Tipo		. "<br>";
			echo "PtoVta: " .	$item->PtoVta	. "<br>";
			echo "Nro: " .		$item->Nro		. "<br>";
			echo "<br>";
		}
	}
	
	if (count($FECompConsultarResult->ResultGet->Tributos) > 0) {
		echo "<br>";
		echo "Tributos" . "<br>";
		foreach ($FECompConsultarResult->ResultGet->Tributos->Tributo as $item) {
			echo "Id: " .		$item->Id			. "<br>";
			echo "Desc: " .		$item->Desc			. "<br>";
			echo "BaseImp: " .	$item->BaseImp		. "<br>";
			echo "Alic: " .		$item->Alic			. "<br>";
			echo "Importe: " .	$item->Importe		. "<br>";
			echo "<br>";
		}
	}
	
	if (count($FECompConsultarResult->ResultGet->Iva) > 0) {
		echo "<br>";
		echo "Iva" . "<br>";
		foreach ($FECompConsultarResult->ResultGet->Iva->AlicIva as $item) {
			echo "Id: " .		$item->Id		. "<br>";
			echo "BaseImp: " .	$item->BaseImp	. "<br>";
			echo "Importe: " .	$item->Importe	. "<br>";
			echo "<br>";
		}
	}
	
	if (count($FECompConsultarResult->ResultGet->Opcionales) > 0) {
		echo "<br>";
		echo "Opcionales" . "<br>";
		foreach ($FECompConsultarResult->ResultGet->Opcionales->Opcional as $item) {
			echo "Id: " .		$item->Id		. "<br>";
			echo "Valor: " .	$item->Valor	. "<br>";
			echo "<br>";
		}
	}
	
	if (count($FECompConsultarResult->ResultGet->Compradores) > 0) {
		echo "<br>";
		echo "Compradores" . "<br>";
		foreach ($FECompConsultarResult->ResultGet->Compradores->Comprador as $item) {
			echo "DocTipo: " .		$item->DocTipo		. "<br>";
			echo "DocNro: " .		$item->DocNro		. "<br>";
			echo "Porcentaje: " .	$item->Porcentaje	. "<br>";
			echo "<br>";
		}
	}
	
	echo "Resultado: " .		$FECompConsultarResult->ResultGet->Resultado			. "<br>";
	echo "CodAutorizacion: " .	$FECompConsultarResult->ResultGet->CodAutorizacion		. "<br>";
	echo "EmisionTipo: " .		$FECompConsultarResult->ResultGet->EmisionTipo			. "<br>";
	echo "FchVto: " .			$FECompConsultarResult->ResultGet->FchVto				. "<br>";
	echo "FchProceso: " .		$FECompConsultarResult->ResultGet->FchProceso			. "<br>";
	
	if (count($FECompConsultarResult->ResultGet->Observaciones) > 0) {
		echo "<br>";
		echo "Observaciones" . "<br>";
		foreach ($FECompConsultarResult->ResultGet->Observaciones->Obs as $item) {
			echo "Code: " .		$item->Code		. "<br>";
			echo "Msg: " .		$item->Msg		. "<br>";
			echo "<br>";
		}
	}
	
	echo "PtoVta: " .		$FECompConsultarResult->ResultGet->PtoVta		. "<br>";
	echo "CbteTipo: " .		$FECompConsultarResult->ResultGet->CbteTipo		. "<br>";
	
	echo "<br><br>";

} else if ($FECompConsultar->resultado == "R") {
	$xml = new SimpleXMLElement($FECompConsultar->texto_respuesta);
	$FECompConsultarResult = $xml->children("soap", true)->Body->children()->FECompConsultarResponse->FECompConsultarResult;
	
	echo "<br><br>";
	
	if (count($FECompConsultarResult->Errors) > 0) {
		echo "<br>";
		echo "Errores" . "<br>";
		foreach ($FECompConsultarResult->Errors->Err as $item) {
			echo "Code: " .		$item->Code		. "<br>";
			echo "Msg: " .		$item->Msg		. "<br>";
			echo "<br>";
		}
	}
}

echo json_encode($FECompConsultar);



?>