<?php
include_once('../init.php');
include_once('../config.php');
include_once(DOC_ROOT.'/libraries.php');

switch($_POST["type"])
{
	case "producto":
	$tipoServicio->setTipoServicioId($_POST["value"]);
	$result= $tipoServicio->Info();
	//$producto->setNoIdentificacion($_POST["value"]);
	//$result = $producto->GetProDuctoInfo();
	//echo urldecode($result["descripcion"]);
	echo urldecode($result["nombreServicio"]);
	echo "{#}";
	echo $result["costo"];
	echo $result["valorUnitario"];
	echo "{#}";
	echo "NO APLICA";
	//echo $result["unidad"];
	echo "{#}";
	break;
	case "impuesto":
//	print_r($_POST);
	$impuesto->setImpuestoId($_POST["value"]);
	$result = $impuesto->Info();
	echo urldecode($result["nombre"]);
	echo "{#}";
	echo $result["tasa"];
	echo "{#}";
	echo $result["tipo"];
	echo "{#}";
	echo $result["iva"];
	echo "{#}";
	break;

	case "datosFacturacion":
		$userId = $_POST["value"];
		$contract->setContractId($userId, 1);
		$result = $contract->Info();
		print_r($result);
		if(!$result)
		{
			echo "{#}{#}{#}{#}{#}{#}{#}{#}{#}{#}{#}{#}{#}{#}{#}{#}{#}{#}{#}";
			exit();
		}
		echo "{#}";
		echo "{#}";
		echo "{#}";
		echo $result["name"];
		echo "{#}";
		echo $result["address"];
		echo "{#}";
		echo $result["noExtAddress"];
		echo "{#}";
		echo $result["noIntAddress"];
		echo "{#}";
		echo $result["coloniaAddress"];
		echo "{#}";
		echo $result["municipioAddress"];
		echo "{#}";
		echo $result["cpAddress"];
		echo "{#}";
		echo $result["estadoAddress"];
		echo "{#}";
		echo $result["municipioAddress"];
		echo "{#}";
		echo $result["referencia"];
		echo "{#}";
		echo $result["paisAddress"];
		echo "{#}";
		// echo $result["emailContactoAdministrativo"];
		echo $result["email"];
		echo "{#}";
		echo $result["rfc"];
		echo "{#}";
		echo $result["contractId"];
		echo "{#}";

	break;
}

?>
