<?php

include_once('../init.php');
include_once('../config.php');
include_once(DOC_ROOT.'/libraries.php');

switch($_POST["type"])
{
	case "addDocument": 
			
			$smarty->assign("DOC_ROOT", DOC_ROOT);
			$smarty->display(DOC_ROOT.'/templates/boxes/add-document-general-popup.tpl');
		
		break;	
		
	case "saveAddDocument":
			
			$docGral->setName($_POST['name']);
			$docGral->setInfo($_POST['info']);			
								
			if($_POST['active'])
				$docGral->setActive(1);
			else
				$docGral->setActive(0);
			
			if(!$docGral->Save())
			{
				echo "fail[#]";
				$smarty->display(DOC_ROOT.'/templates/boxes/status_on_popup.tpl');
			}
			else
			{
				echo "ok[#]";
				$smarty->display(DOC_ROOT.'/templates/boxes/status_on_popup.tpl');
				echo "[#]";
				$resDocuments = $docGral->Enumerate();
				$documents = $util->EncodeResult($resDocuments);
				
				$smarty->assign("documents", $documents);
				$smarty->assign("DOC_ROOT", DOC_ROOT);
				$smarty->display(DOC_ROOT.'/templates/lists/document-general.tpl');
			}
			
		break;
		
	case "deleteDocument":
			
			$docGral->setDocGralId($_POST['docGralId']);
			if($docGral->Delete())
			{
				echo "ok[#]";
				$smarty->display(DOC_ROOT.'/templates/boxes/status.tpl');
				echo "[#]";
				$resDocuments = $docGral->Enumerate();
				$documents = $util->EncodeResult($resDocuments);
				
				$smarty->assign("documents", $documents);
				$smarty->assign("DOC_ROOT", DOC_ROOT);
				$smarty->display(DOC_ROOT.'/templates/lists/document-general.tpl');
			}
			
		break;
		
	case "editDocument":	 
			
			$docGral->setDocGralId($_POST['docGralId']);
			$info = $docGral->Info();
						
			$smarty->assign("post", $info);
			$smarty->assign("DOC_ROOT", DOC_ROOT);
			$smarty->display(DOC_ROOT.'/templates/boxes/edit-document-general-popup.tpl');
		
		break;
		
	case "saveEditDocument":
			
			$docGral->setDocGralId($_POST['docGralId']);
			$docGral->setName($_POST['name']);
			$docGral->setInfo($_POST['info']);		
						
			if($_POST['active'])
				$docGral->setActive(1);
			else
				$docGral->setActive(0);
			
			if(!$docGral->Update())
			{
				echo "fail[#]";
				$smarty->display(DOC_ROOT.'/templates/boxes/status_on_popup.tpl');
			}
			else
			{
				echo "ok[#]";
				$smarty->display(DOC_ROOT.'/templates/boxes/status_on_popup.tpl');
				echo "[#]";
				$resDocuments = $docGral->Enumerate();
				$documents = $util->EncodeResult($resDocuments);
				
				$smarty->assign("documents", $documents);
				$smarty->assign("DOC_ROOT", DOC_ROOT);
				$smarty->display(DOC_ROOT.'/templates/lists/document-general.tpl');
			}
			
		break;
		
}
?>
