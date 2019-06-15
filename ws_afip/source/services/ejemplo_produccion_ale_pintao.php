<?php

require_once("class/comp/Ws_afip.class.php");




$modo = "produccion";

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
	"PtoVta"	=> 4,					// Punto de Venta
	"CbteTipo"	=> 6					// 1=Factura A
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

echo htmlentities(json_encode($FECompUltimoAutorizado));














//FECompConsultar =============================================
echo "<br><br>";
echo "<br><br>";
echo "FECompConsultar =============================================";

$p = array(
	"FeCompConsReq" => array(
		"PtoVta"	=> 4,
		"CbteTipo"	=> 6,
		"CbteNro"	=> 1
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