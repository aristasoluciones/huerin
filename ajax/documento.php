<?php

include_once('../init.php');
include_once('../config.php');
include_once(DOC_ROOT.'/libraries.php');

session_start();

switch($_POST["type"])
{
	case "addDocumento": 
			$smarty->assign("DOC_ROOT", DOC_ROOT);
			$smarty->display(DOC_ROOT.'/templates/boxes/add-documento-popup.tpl');
		break;	
	case "saveAddDocumento":
			$documento->setContractId($_POST['contractId']);
			$documento->setTipoDocumentoId($_POST['tipoDocumentoId']);
			$documento->setPath($_POST['path']);
			if(!$documento->Save())
			{
				echo "fail[#]";
				$smarty->display(DOC_ROOT.'/templates/boxes/status_on_popup.tpl');
			}
			else
			{
				echo "ok[#]";
				$smarty->display(DOC_ROOT.'/templates/boxes/status_on_popup.tpl');
				echo "[#]";
				$resDocumento = $documento->Enumerate();
				$smarty->assign("resDocumento", $resDocumento);
				$smarty->assign("DOC_ROOT", DOC_ROOT);
				$smarty->display(DOC_ROOT.'/templates/lists/documento.tpl');
			}
		break;
		
	case "deleteDocumento":
	
			$documento->setDocumentoId($_POST['documentoId']);
			$info = $documento->Info();
			if($documento->Delete())
			{
				echo "ok[#]";
				$smarty->display(DOC_ROOT.'/templates/boxes/status.tpl');
				echo "[#]";
				
				//Checamos los permisos para eliminar DOCs y Archivos
	
				$permisoDel = array('Gerente','Socio','Asistente');
				
				$allowDelete = 0;
				if(in_array($User['tipoPersonal'], $permisoDel))
					$allowDelete = 1;
			
				$smarty->assign('allowDelete',$allowDelete);				
				$documento->setContractId($info["contractId"]);
				$documentos = $documento->Enumerate();
				$smarty->assign("documentos", $documentos);
				$smarty->assign("DOC_ROOT", DOC_ROOT);
				$smarty->display(DOC_ROOT.'/templates/lists/documento.tpl');
			}
			
		break;
		
	case "editDocumento": 
			$smarty->assign("DOC_ROOT", DOC_ROOT);
			$documento->setDocumentoId($_POST['documentoId']);
			$myDocumento = $documento->Info();
			$smarty->assign("post", $myDocumento);
			$smarty->display(DOC_ROOT.'/templates/boxes/edit-documento-popup.tpl');
		break;
	case "saveEditDocumento":
			$documento->setDocumentoId($_POST['documentoId']);
			$documento->setContractId($_POST['contractId']);
			$documento->setTipoDocumentoId($_POST['tipoDocumentoId']);
			$documento->setPath($_POST['path']);
			if(!$documento->Edit())
			{
				echo "fail[#]";
				$smarty->display(DOC_ROOT.'/templates/boxes/status_on_popup.tpl');
			}
			else
			{
				echo "ok[#]";
				$smarty->display(DOC_ROOT.'/templates/boxes/status_on_popup.tpl');
				echo "[#]";
				$resDocumento = $documento->Enumerate();
				$smarty->assign("resDocumento", $resDocumento);
				$smarty->assign("DOC_ROOT", DOC_ROOT);
				$smarty->display(DOC_ROOT.'/templates/lists/documento.tpl');
			}
		break;
}
?>
