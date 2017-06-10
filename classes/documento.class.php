<?php 

class Documento extends Contract
{
	private $documentoId;
	private $contractId;
	private $tipoDocumentoId;
	private $path;

	public function setDocumentoId($value)
	{
		$this->Util()->ValidateInteger($value);
		$this->documentoId = $value;
	}

	public function getDocumentoId()
	{
		return $this->documentoId;
	}

	public function setContractId($value)
	{
		$this->Util()->ValidateInteger($value);
		$this->contractId = $value;
	}

	public function getContractId()
	{
		return $this->contractId;
	}

	public function setTipoDocumentoId($value)
	{
		$this->Util()->ValidateInteger($value);
		$this->tipoDocumentoId = $value;
	}

	public function getTipoDocumentoId()
	{
		return $this->tipoDocumentoId;
	}

	public function setPath($value)
	{
		$this->Util()->ValidateString($value, 10000, 1, 'path');
		$this->path = $value;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function EnumerateAll()
	{
		$this->Util()->DB()->setQuery("SELECT * FROM documento");
		$result = $this->Util()->DB()->GetResult();
		
		foreach($result as $key => $value)
		{
			$result[$key]["filePath"] = WEB_ROOT."/documentos/".$value["contractId"]."_".$value["path"];
		}
		return $result;
	}
	
	public function Enumerate()
	{
		$this->Util()->DB()->setQuery("SELECT * FROM documento 
		LEFT JOIN tipoDocumento ON tipoDocumento.tipoDocumentoId = documento.tipoDocumentoId WHERE contractId = '".$this->getContractId()."' ORDER BY documentoId ASC");
		$result = $this->Util()->DB()->GetResult();
		
		foreach($result as $key => $value)
		{
			$result[$key]["filePath"] = WEB_ROOT."/documentos/".$value["contractId"]."_".$value["path"];
		}
		return $result;
	}

	public function Info()
	{
		$this->Util()->DB()->setQuery("SELECT * FROM documento 
		LEFT JOIN tipoDocumento ON tipoDocumento.tipoDocumentoId = documento.tipoDocumentoId WHERE documentoId = '".$this->documentoId."'");
		$row = $this->Util()->DB()->GetRow();
		return $row;
	}

	public function Edit()
	{
		if($this->Util()->PrintErrors()){ return false; }

		$this->Util()->DB()->setQuery("
			UPDATE
				documento
			SET
				`documentoId` = '".$this->documentoId."',
				`contractId` = '".$this->contractId."',
				`tipoDocumentoId` = '".$this->tipoDocumentoId."',
				`path` = '".$this->path."'
			WHERE documentoId = '".$this->documentoId."'");
		$this->Util()->DB()->UpdateData();

		$this->Util()->setError(1, "complete");
		$this->Util()->PrintErrors();
		return true;
	}

	public function Save()
	{
		if($this->Util()->PrintErrors()){ return false; }

		$this->Util()->DB()->setQuery("
			INSERT INTO
				documento
			(
				`contractId`,
				`tipoDocumentoId`
		)
		VALUES
		(
				'".$this->contractId."',
				'".$this->tipoDocumentoId."'
		);");
		
		$id =	$this->Util()->DB()->InsertData();
		$folder = DOC_ROOT."/documentos/".$this->getContractId();
		
		$nombreArchivo = preg_replace("/&#?[a-z0-9]+;/i","", basename( $_FILES["path"]['name']));
		$nombreArchivo = str_replace(" ","", $nombreArchivo);
		
		$target_path = $folder ."_". $nombreArchivo; 
		$target_path_path = $nombreArchivo; 
			
		if(move_uploaded_file($_FILES["path"]['tmp_name'], $target_path)) {
			$this->Util()->DB()->setQuery("UPDATE documento SET path = '".$target_path_path."' WHERE documentoId = '".$id."'");
			$this->Util()->DB()->UpdateData();
		}
		
		$this->Util()->setError(2, "complete");
		$this->Util()->PrintErrors();
		return true;
	}

	public function Delete()
	{
		if($this->Util()->PrintErrors()){ return false; }

		$this->Util()->DB()->setQuery("
			DELETE FROM
				documento
			WHERE
				documentoId = '".$this->documentoId."'");
		$this->Util()->DB()->DeleteData();
		$this->Util()->setError(3, "complete");
		$this->Util()->PrintErrors();
		return true;
	}

}

?>