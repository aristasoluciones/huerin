<?php

include_once('../init.php');
include_once('../config.php');
include_once(DOC_ROOT.'/libraries.php');

switch($_POST["type"])
{
	
	case 'search':
			echo 'ok[#]';
			$formValues['subordinados'] = $_POST['subordinados'];			
			$formValues['respCuenta'] = $_POST['responsableCuenta'];
			$formValues['departamentoId'] = $_POST["departamentoId"];
			$formValues['cliente'] = $_POST["rfc"];
			$idContrato = $_POST['contractId'];
			$contracts = array();
			//este archivo contiene los filtros segun sea el rol, se usa en varios apartado cambiarlos por este archivo
            include_once(DOC_ROOT.'/ajax/filter.php');
			$idClientes = array();
			$idContracts = array();
			$contratosClte = array();
			foreach($contracts as $res){
								
				$contractId = $res['contractId'];
				$customerId = $res['customerId'];
				
				if($idContrato > 0 && $contractId != $idContrato)
					continue;
				
				if(!in_array($customerId,$idClientes))
					$idClientes[] = $customerId;
				
				if(!in_array($contractId,$idContracts)){
					$idContracts[] = $contractId;				
					$contratosClte[$customerId][] = $res;
				}
								
			}//foreach
						
			$clientes = array();
			foreach($idClientes as $customerId){
				
				$customer->setCustomerId($customerId);
				$infC = $customer->Info();
				
				$infC['contracts'] = $contratosClte[$customerId];
				
				$clientes[] = $infC;
				
			}//foreach
			
			$totalPeriodo = 0;
			$totalCosto = 0;
			$resClientes = array();
			foreach($clientes as $clte){
				
				$contratos = array();
				foreach($clte['contracts'] as $con){
										
					//Checamos Permisos
					$resPermisos = explode('-',$con['permisos']);
					foreach($resPermisos as $res){
						$value = explode(',',$res);
						
						$idPersonal = $value[1];
						$idDepto = $value[0];
						
						$personal->setPersonalId($idPersonal);
						$nomPers = $personal->GetNameById();
						
						$permisos[$idDepto] = $nomPers;
						$permisos2[$idDepto] = $idPersonal;
					}	
										
					$servicios = array();
					foreach($con['servicios'] as $serv) {

                        //PERSONAL

                        $sql = 'SELECT tipoServ.departamentoId, tipoServ.costoVisual FROM servicio serv
								LEFT JOIN tipoServicio tipoServ ON tipoServ.tipoServicioId = serv.tipoServicioId
								WHERE serv.servicioId = "' . $serv['servicioId'] . '"';
                        $util->DB()->setQuery($sql);
                        $rowTipoServ = $util->DB()->GetRow();
                        $departamentoId = $rowTipoServ["departamentoId"];
                        $costoVisual = $rowTipoServ["costoVisual"];

                        $resPermisos = explode('-', $con['permisos']);

                        $personalId = 0;
                        foreach ($resPermisos as $res2) {
                            $value = explode(',', $res2);

                            $idPersonal = $value[1];
                            $idDepto = $value[0];

                            if ($idDepto == $departamentoId)
                                $personalId = $idPersonal;

                        }

                        $personal->setPersonalId($personalId);
                        $infP = $personal->Info();
                        $role = $rol->getInfoByData($infP);
                        $rolArray = explode(' ',$role['name']);
                        $needle = trim($rolArray[0]);
                        switch($needle){
                            case 'Sistemas':
                            case 'Gestoria':
                            case 'Supervisor':
                                $needle='supervisor';
                                break;
                            case 'Asistente':
                            case 'Cuentas':
                            case 'Contador':
                                $needle='contador';
                                break;
                            case 'Recepcion':
                            case 'Auxiliar':
                                $needle='auxiliar';
                                break;
                            case 'Coordinador':
                            case 'Socio':
                                $needle='socio';
                                break;
                            case 'Gerente':
                                $needle='gerente';
                                break;
                        }
                        if(!empty($infP)){
                            $jefes = array();
                            $personal->findDeepJefes($personalId, $jefes,true);
                            $serv['contador'] = $jefes['Contador'];
                            $serv['supervisor'] = $jefes['Supervisor'];
                            $serv['gerente'] = $jefes['Gerente'];
                            $serv['jefeMax'] = $jefes['Socio'];
                            $serv[$needle] = $jefes['me'];
                        }else {
                            $serv['auxiliar'] = 'N/E';
                            $serv['contador'] = 'N/E';
                            $serv['supervisor'] = 'N/E';
                            $serv['gerente'] = 'N/E';
                        }
						$servicio->setServicioId($serv['servicioId']);
						$infServ = $servicio->Info();
																		
						$tipoServicio->setTipoServicioId($infServ['tipoServicioId']);
						$deptoId = $tipoServicio->GetField('departamentoId');
						
						$serv['responsable'] = $permisos[$deptoId];
						$serv['costo'] = number_format($infServ['costo'],2,'.','');
						
						if($serv['periodicidad'] == 'Mensual')
							$costoMens = $infServ['costo'];
						elseif($serv['periodicidad'] == 'Anual')
							$costoMens = $infServ['costo'] / 12;
						elseif($serv['periodicidad'] == 'Bimestral')
							$costoMens = $infServ['costo'] / 2;
							
						$costoMens = number_format($costoMens,2,'.','');
						$serv['costoMens'] = number_format($costoMens,2);
						$serv['costoVisual'] = number_format($costoVisual,2);
						
						$totalPeriodo += $serv['costo'];
						$totalMens += $costoMens;
						
						$servicios[] = $serv;
						
					}//foreach
					$con['instanciasServicio'] = $servicios;
																				
					$contratos[] = $con;
					
				}//foreach
				$clte['contracts'] = $contratos;
				
				$resClientes[] = $clte;
				
			}//foreach
			$smarty->assign('totalMens', $totalMens);
			$smarty->assign('totalPeriodo', $totalPeriodo);
			$smarty->assign("clientes", $resClientes);
			$smarty->assign("DOC_ROOT", DOC_ROOT);
			$smarty->display(DOC_ROOT.'/templates/lists/report-ingresos.tpl');
			
		break;
}

?>