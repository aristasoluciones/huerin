<?php

include_once('../init.php');
include_once('../config.php');
include_once(DOC_ROOT.'/libraries.php');

switch($_POST["type"])
{
	case "addDocument": 
			
			$smarty->assign("DOC_ROOT", DOC_ROOT);
			$smarty->display(DOC_ROOT.'/templates/boxes/add-document-basic-popup.tpl');
		
		break;	
		
	case "saveAddDocument":
			
			$docBasic->setName($_POST['name']);
			$docBasic->setInfo($_POST['info']);
								
			if($_POST['active'])
				$docBasic->setActive(1);
			else
				$docBasic->setActive(0);
			
			if(!$docBasic->Save())
			{
				echo "fail[#]";
				$smarty->display(DOC_ROOT.'/templates/boxes/status_on_popup.tpl');
			}
			else
			{
				echo "ok[#]";
				$smarty->display(DOC_ROOT.'/templates/boxes/status_on_popup.tpl');
				echo "[#]";
				$resDocuments = $docBasic->Enumerate();
				$documents = $util->EncodeResult($resDocuments);
				
				$smarty->assign("documents", $documents);
				$smarty->assign("DOC_ROOT", DOC_ROOT);
				$smarty->display(DOC_ROOT.'/templates/lists/document-basic.tpl');
			}
			
		break;
		
	case "deleteDocument":
			
			$docBasic->setDocBasicId($_POST['docBasicId']);
			if($docBasic->Delete())
			{
				echo "ok[#]";
				$smarty->display(DOC_ROOT.'/templates/boxes/status.tpl');
				echo "[#]";
				$resDocuments = $docBasic->Enumerate();
				$documents = $util->EncodeResult($resDocuments);
				
				$smarty->assign("documents", $documents);
				$smarty->assign("DOC_ROOT", DOC_ROOT);
				$smarty->display(DOC_ROOT.'/templates/lists/document-basic.tpl');
			}
			
		break;
		
	case "editDocument":	 
			
			$docBasic->setDocBasicId($_POST['docBasicId']);
			$info = $docBasic->Info();
						
			$smarty->assign("post", $info);
			$smarty->assign("DOC_ROOT", DOC_ROOT);
			$smarty->display(DOC_ROOT.'/templates/boxes/edit-document-basic-popup.tpl');
		
		break;
		
	case "saveEditDocument":
			
			$docBasic->setDocBasicId($_POST['docBasicId']);
			$docBasic->setName($_POST['name']);
			$docBasic->setInfo($_POST['info']);		
						
			if($_POST['active'])
				$docBasic->setActive(1);
			else
				$docBasic->setActive(0);
			
			if(!$docBasic->Update())
			{
				echo "fail[#]";
				$smarty->display(DOC_ROOT.'/templates/boxes/status_on_popup.tpl');
			}
			else
			{
				echo "ok[#]";
				$smarty->display(DOC_ROOT.'/templates/boxes/status_on_popup.tpl');
				echo "[#]";
				$resDocuments = $docBasic->Enumerate();
				$documents = $util->EncodeResult($resDocuments);
				
				$smarty->assign("documents", $documents);
				$smarty->assign("DOC_ROOT", DOC_ROOT);
				$smarty->display(DOC_ROOT.'/templates/lists/document-basic.tpl');
			}
			
		break;
		
}
?>
