<?php
class XmlGen extends Producto{

	function CargaAtt(&$nodo, $attr) 
	{
		foreach ($attr as $key => $val) 
		{
    	if (strlen($val)>0) 
			{	   
        $nodo->setAttribute($key,$val);
   	 	}
		}
	}

	function GenerateXML($data, $serie, $totales, $nodoEmisor, $nodoReceptor, $nodosConceptos,$empresa)
	{
		$xml = new DOMdocument("1.0","UTF-8");
		$root = $xml->createElement("cfdi:Comprobante");
		$root = $xml->appendChild($root);
		
		$root->setAttribute("xmlns:cfdi", "http://www.sat.gob.mx/cfd");
		$root->setAttribute("xmlns:cfdi", "http://www.sat.gob.mx/cfd/3");
		$root->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");


		$data["fecha"] = explode(" ", $data["fecha"]);
		$data["fecha"] = $data["fecha"][0]."T".$data["fecha"][1];
	//	$data["fecha"] = "2010-09-22T07:45:09";
		$this->CargaAtt($root, array("version"=>"3.2",
													"serie"=>$this->Util()->CadenaOriginalVariableFormat($serie["serie"],false,false),
													"folio"=>$this->Util()->CadenaOriginalVariableFormat($data["folio"],false,false),
													"fecha"=>$this->Util()->CadenaOriginalVariableFormat($data["fecha"],false,false),
													"sello"=>$data["sello"],
//													"noAprobacion"=>$this->Util()->CadenaOriginalVariableFormat($serie["noAprobacion"],false,false),
//													"anoAprobacion"=>$this->Util()->CadenaOriginalVariableFormat($serie["anoAprobacion"],false,false),
													"tipoDeComprobante"=>$this->Util()->CadenaOriginalVariableFormat($data["tipoDeComprobante"],false,false),
													"formaDePago"=>$this->Util()->CadenaOriginalVariableFormat($data["formaDePago"],false,false),
													"metodoDePago"=>$this->Util()->CadenaOriginalVariableFormat($data["metodoDePago"],false,false),
													"LugarExpedicion"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["sucursal"]["identificador"],false,false),
													"NumCtaPago"=>$this->Util()->CadenaOriginalVariableFormat($data["NumCtaPago"],false,false),
													"condicionesDePago"=>$this->Util()->CadenaOriginalVariableFormat($data["condicionesDePago"],false,false),
													"noCertificado"=>$this->Util()->CadenaOriginalVariableFormat($serie["noCertificado"],false,false),
													"certificado"=>$data["certificado"],
													"subTotal"=>$this->Util()->CadenaOriginalVariableFormat($totales["subtotal"],true,false),
													"descuento"=>$this->Util()->CadenaOriginalVariableFormat($totales["descuento"], true,false),
													//tipos de cambio
													"TipoCambio"=>$this->Util()->CadenaOriginalVariableFormat($data["tipoDeCambio"], true,false,4),
													"Moneda"=>$this->Util()->CadenaOriginalVariableFormat($data["tiposDeMoneda"], false,false),
													//tipos de cambio
													"total"=>$this->Util()->CadenaOriginalVariableFormat($totales["total"], true,false)
											 )
										);


		$emisor = $xml->createElement("cfdi:Emisor");
		$emisor = $root->appendChild($emisor);
		$this->CargaAtt($emisor, array("rfc"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["rfc"]["rfc"],false,false),
													 "nombre"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["rfc"]["razonSocial"],false,false)
											 )
										);
									
		$domfis = $xml->createElement("cfdi:DomicilioFiscal");
		$domfis = $emisor->appendChild($domfis);
		
		//echo $data["nodoEmisor"]["rfc"]["pais"];
		$this->CargaAtt($domfis, array("calle"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["rfc"]["calle"],false,false),
														"noExterior"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["rfc"]["noExt"],false,false),
														"noInterior"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["rfc"]["noInt"],false,false),
														"colonia"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["rfc"]["colonia"],false,false),
														"localidad"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["rfc"]["localidad"],false,false),
														"referencia"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["rfc"]["referencia"],false,false),
														"municipio"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["rfc"]["municipio"],false,false),
														"estado"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["rfc"]["estado"],false,false),
														"pais"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["rfc"]["pais"],false,false),
														"codigoPostal"=>$this->Util()->CadenaOriginalVariableFormat($this->Util()->PadStringLeft($data["nodoEmisor"]["rfc"]["cp"], 5, "0"),false,false)
											 )
										);


	if($data["nodoEmisor"]["sucursal"]["sucursalActiva"] == 'no'){
		
		$suc = $xml->createElement("cfdi:ExpedidoEn");
		$suc = $emisor->appendChild($suc);
		
		$this->CargaAtt($suc, array("calle"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["sucursal"]["calle"],false,false),
														"noExterior"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["sucursal"]["noExt"],false,false),
														"noInterior"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["sucursal"]["noInt"],false,false),
														"colonia"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["sucursal"]["colonia"],false,false),
														"localidad"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["sucursal"]["localidad"],false,false),
														"referencia"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["sucursal"]["referencia"],false,false),
														"municipio"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["sucursal"]["municipio"],false,false),
														"estado"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["sucursal"]["estado"],false,false),
														"pais"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["sucursal"]["pais"],false,false),
														"codigoPostal"=>$this->Util()->CadenaOriginalVariableFormat($this->Util()->PadStringLeft($data["nodoEmisor"]["sucursal"]["cp"], 5, "0"),false,false)
											 )
										);
										
	}//if
	
		//expedido en aun no porque asumiremos todas las facruras en domicilio fiscal
		$regfis = $xml->createElement("cfdi:RegimenFiscal");
		$regfis = $emisor->appendChild($regfis);
		
		//echo $data["nodoEmisor"]["rfc"]["pais"];
		$this->CargaAtt($regfis, array("Regimen"=>$this->Util()->CadenaOriginalVariableFormat($data["nodoEmisor"]["rfc"]["regimenFiscal"],false,false)
										)				
										);
		
		$receptor = $xml->createElement("cfdi:Receptor");
		$receptor = $root->appendChild($receptor);
		$this->CargaAtt($receptor, array("rfc"=>$this->Util()->CadenaOriginalVariableFormat($nodoReceptor["rfc"],false,false),
															"nombre"=>$this->Util()->CadenaOriginalVariableFormat($nodoReceptor["nombre"],false,false)
													)
											);
		$domicilio = $xml->createElement("cfdi:Domicilio");
		$domicilio = $receptor->appendChild($domicilio);
		$this->CargaAtt($domicilio, array("calle"=>$this->Util()->CadenaOriginalVariableFormat($nodoReceptor["calle"],false,false),
														"noExterior"=>$this->Util()->CadenaOriginalVariableFormat($nodoReceptor["noExt"],false,false),
														"noInterior"=>$this->Util()->CadenaOriginalVariableFormat($nodoReceptor["noInt"],false,false),
													 "colonia"=>$this->Util()->CadenaOriginalVariableFormat($nodoReceptor["colonia"],false,false),
													 "localidad"=>$this->Util()->CadenaOriginalVariableFormat($nodoReceptor["localidad"],false,false),
													 "referencia"=>$this->Util()->CadenaOriginalVariableFormat($nodoReceptor["referencia"],false,false),
													 "municipio"=>$this->Util()->CadenaOriginalVariableFormat($nodoReceptor["municipio"],false,false),
													 "estado"=>$this->Util()->CadenaOriginalVariableFormat($nodoReceptor["estado"],false,false),
													 "pais"=>$this->Util()->CadenaOriginalVariableFormat($nodoReceptor["pais"],false,false),
													 "codigoPostal"=>$this->Util()->CadenaOriginalVariableFormat($this->Util()->PadStringLeft($nodoReceptor["cp"], 5, "0"),false,false),
											 )
									 );


		$conceptos = $xml->createElement("cfdi:Conceptos");
		$conceptos = $root->appendChild($conceptos);
		foreach($nodosConceptos as $concepto) 
		{
			$myConcepto = $xml->createElement("cfdi:Concepto");
			$myConcepto = $conceptos->appendChild($myConcepto);
			$this->CargaAtt($myConcepto, array("cantidad"=>$this->Util()->CadenaOriginalVariableFormat($concepto["cantidad"],true,false),
																"unidad"=>$this->Util()->CadenaOriginalVariableFormat($concepto["unidad"],false,false),
																"noIdentificacion"=>$this->Util()->CadenaOriginalVariableFormat($concepto["noIdentificacion"],false,false),
																"descripcion"=>$this->Util()->CadenaOriginalVariableFormat($concepto["descripcion"],false,false),
																"valorUnitario"=>$this->Util()->CadenaOriginalVariableFormat($concepto["valorUnitario"],true,false),
																"importe"=>$this->Util()->CadenaOriginalVariableFormat($concepto["importe"],true,false),
										 )
									);
	//		aca falta la informacion aduanera
	//		aca falta cuenta predial
		}
		
	//	todo complementoconcepto

		$impuestos = $xml->createElement("cfdi:Impuestos");
		$impuestos = $root->appendChild($impuestos);

		$this->CargaAtt($impuestos, array(
				"totalImpuestosRetenidos" => $this->Util()->CadenaOriginalVariableFormat($totales["retIsr"]+$totales["retIva"],true,false),
				"totalImpuestosTrasladados" => $this->Util()->CadenaOriginalVariableFormat($totales["iva"]+$totales["ieps"],true,false))
							);	

		$retenciones = $xml->createElement("cfdi:Retenciones");
		$retenciones = $impuestos->appendChild($retenciones);

		$retencion = $xml->createElement("cfdi:Retencion");
		$retencion = $retenciones->appendChild($retencion);

		$this->CargaAtt($retencion, array(
				"impuesto" => $this->Util()->CadenaOriginalVariableFormat("IVA",false,false),
				"importe" => $this->Util()->CadenaOriginalVariableFormat($totales["retIva"],true,false))
							);	

		$retencion = $xml->createElement("cfdi:Retencion");
		$retencion = $retenciones->appendChild($retencion);

		$this->CargaAtt($retencion, array(
				"impuesto" => $this->Util()->CadenaOriginalVariableFormat("ISR",false,false),
				"importe" => $this->Util()->CadenaOriginalVariableFormat($totales["retIsr"],true,false))
							);	

		
		$traslados = $xml->createElement("cfdi:Traslados");
		$traslados = $impuestos->appendChild($traslados);

		$traslado = $xml->createElement("cfdi:Traslado");
		$traslado = $traslados->appendChild($traslado);

		$this->CargaAtt($traslado, array(
				"impuesto" => $this->Util()->CadenaOriginalVariableFormat("IVA",false,false),
				"tasa" => $this->Util()->CadenaOriginalVariableFormat($totales["tasaIva"],true,false),
				"importe" => $this->Util()->CadenaOriginalVariableFormat($totales["iva"],true,false))
							);	

		$traslado = $xml->createElement("cfdi:Traslado");
		$traslado = $traslados->appendChild($traslado);

		$this->CargaAtt($traslado, array(
				"impuesto" => $this->Util()->CadenaOriginalVariableFormat("IEPS",false,false),
				"tasa" => $this->Util()->CadenaOriginalVariableFormat($totales["porcentajeIEPS"],true,false),
				"importe" => $this->Util()->CadenaOriginalVariableFormat($totales["ieps"],true,false))
							);	

		/* Addenda 
		if($_SESSION["impuestos"])
		{
		
			$addenda = $xml->createElement("cfdi:Addenda");
			$addenda = $root->appendChild($addenda);
			
			foreach($_SESSION["impuestos"] as $impuesto)
			{
				$factura = $xml->createElement("impuesto");
				$factura = $addenda->appendChild($factura);
				
				$this->CargaAtt($factura, array(
						"impuesto" => $impuesto["impuesto"],
						"tasa" => $impuesto["tasa"],
						"tipo" => $impuesto["tipo"]
						)
									);	
			}
		}*/
		$complemento = $xml->createElement("cfdi:Complemento");
		$complemento = $root->appendChild($complemento);


		$root->setAttribute("xsi:schemaLocation", "http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd");

	//	falta nodo complemento
/**/	
		//$xml->formatOutput = true;
		$nufa = $empresa["empresaId"]."_".$serie["serie"]."_".$data["folio"];
		$rfcActivo = $this->getRfcActive();
		$root = DOC_ROOT."/empresas/".$_SESSION["empresaId"]."/certificados/".$rfcActivo."/facturas/xml/";
		$rootFacturas = DOC_ROOT."/empresas/".$_SESSION["empresaId"]."/certificados/".$rfcActivo."/facturas/";
		
		if(!is_dir($rootFacturas))
		{
			mkdir($rootFacturas, 0777);
		}

		if(!is_dir($root))
		{
			mkdir($root, 0777);
		}
		@unlink($root.$nufa.".xml");
    $xml->save($root.$nufa.".xml");
		return $nufa;
	}
	
}
?>
