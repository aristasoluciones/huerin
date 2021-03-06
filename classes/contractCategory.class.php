<?php

class ContractCategory extends Main
{
	private $contCatId;	
	private $name;	
	private $active;
	
	public function setContCatId($value)
	{
		$this->Util()->ValidateInteger($value);
		$this->contCatId = $value;
	}
			
	public function setName($value)
	{
		if($this->Util()->ValidateRequireField($value, "Nombre"))
			$this->Util()->ValidateString($value, $max_chars=60, $minChars = 1, "Nombre");
		
		$this->name = $value;
	}
	
	public function setActive($value)
	{
		$this->active = $value;		
	}
		
	public function Enumerate()
	{
		
		if($this->active)
			$sqlActive = " WHERE active = '1'";
						
		$sql = "SELECT 
					* 
				FROM 
					contract_category
				".$sqlActive."				
				ORDER BY 
					name ASC";
		
		$this->Util()->DB()->setQuery($sql);
		$result = $this->Util()->DB()->GetResult();
				
		return $result;
	}
		
	public function Info()
	{
		
		$sql = "SELECT 
					* 
				FROM 
					contract_category 
				WHERE 
					contCatId = '".$this->contCatId."'";
	
		$this->Util()->DB()->setQuery($sql);
		$info = $this->Util()->DB()->GetRow();
		
		$row = $this->Util->EncodeRow($info);
				
		return $row;
	}
	
	public function Save(){
		
		if($this->Util()->PrintErrors()){ 
			return false; 
		}
		
		$sqlQuery = "INSERT INTO 
					contract_category 
					(										
						name,
						active						
					)
				 VALUES 
					(						
						'".utf8_decode($this->name)."',					
						'".$this->active."'
					)";
								
		$this->Util()->DB()->setQuery($sqlQuery);
		$contCatId = $this->Util()->DB()->InsertData();
						
		$this->Util()->setError(10020, "complete");
		$this->Util()->PrintErrors();
		
		return true;
				
	}
	
	public function Update(){
		
		if($this->Util()->PrintErrors()){ 
			return false; 
		}
		
		$sql = "UPDATE 
					contract_category 
				SET 
					name =  '".utf8_decode($this->name)."',
					active = '".$this->active."'					
				WHERE 
					contCatId = ".$this->contCatId;
							
		$this->Util()->DB()->setQuery($sql);
		$this->Util()->DB()->ExecuteQuery();
						
		$this->Util()->setError(10021, "complete");
		$this->Util()->PrintErrors();
		
		return true;
				
	}
	
	public function Delete(){
		
		if($this->Util()->PrintErrors()){ 
			return false; 
		}
		
		$sql = "DELETE FROM 
					contract_category
				WHERE 
					contCatId = ".$this->contCatId;
							
		$this->Util()->DB()->setQuery($sql);
		$this->Util()->DB()->ExecuteQuery();
		
		$sql = "DELETE FROM 
					contract_subcategory
				WHERE 
					contCatId = ".$this->contCatId;
							
		$this->Util()->DB()->setQuery($sql);
		$this->Util()->DB()->ExecuteQuery();
								
		$this->Util()->setError(10022, "complete");
		$this->Util()->PrintErrors();
		
		return true;
				
	}
		
	public function GetNameById(){
			
		$sql = 'SELECT 
					name
				FROM 
					contract_category 
				WHERE 
					contCatId = '.$this->contCatId;
		
		$this->Util()->DB()->setQuery($sql);
		
		return $this->Util()->DB()->GetSingle();
		
	}	
	
}//ContractCategory

?>