<?php

class DBRemote
{
	public $query = NULL;
	private $sqlResult = NULL;
	
	private $conn_id = false;

	private $sqlHost;
	private $sqlDatabase;
	private $sqlUser;
	private $sqlPassword;
	
	private $projectStatus = "test";
	
	public function setSqlHost($value)
	{
		$this->sqlHost = $value;
	}
	
	public function getSqlHost()
	{
		return $this->sqlHost;
	}

	public function setSqlDatabase($value)
	{
		$this->sqlDatabase = $value;
	}
	
	public function getSqlDatabase()
	{
		return $this->sqlDatabase;
	}

	public function setSqlUser($value)
	{
		$this->sqlUser = $value;
	}
	
	public function getSqlUser()
	{
		return $this->sqlUser;
	}

	public function setSqlPassword($value)
	{
		$this->sqlPassword = $value;
	}
	
	public function getSqlPassword()
	{
		return $this->sqlPassword;
	}

	public function setQuery($value)
	{
		$this->query = $value;
	}
	
	public function getQuery()
	{
		return $this->query;
	}

	public function setProjectStatus($value)
	{
		$this->projectStatus = $value;
	}
	
	public function getProjectStatus()
	{
		return $this->projectStatus;
	}

	function __construct($database = "pascacio_general")
	{
		//echo $database;
		$this->sqlHost = SQL_HOST_REMOTE;
		$this->sqlDatabase = $database;
		$this->sqlUser = SQL_USER_REMOTE;
		$this->sqlPassword = SQL_PASSWORD_REMOTE;
	}

  public function DatabaseConnect() 
	{
		//$this->sqlDatabase = "pascacio_general";
    $this->conn_id = mysql_connect($this->sqlHost, $this->sqlUser, $this->sqlPassword, 1);
    mysql_select_db($this->sqlDatabase, $this->conn_id) or die("<br/>".mysql_error()."<br/>");
  }
	
	public function ExecuteQuery()
	{
  	//if(!$this->conn_id)
   	  $this->DatabaseConnect();


		//TODO we might want to add some security in the queries here, but that can be done later, this is the place
		if($this->projectStatus == "test")
		{
			//echo "<br><br>".$this->query."<br><br>";
//				print_r(debug_backtrace());
	    	$this->sqlResult = mysql_query($this->query, $this->conn_id) or die (trigger_error(mysql_error()));
		}	
		else
		{  //echo "<br><br>".$this->query."<br><br>";
			$this->sqlResult = mysql_query($this->query, $this->conn_id);
		}	
	}
	
  function GetResult()
	{
  	$retArray = array();

		$this->ExecuteQuery();
		
	  while($rs=mysql_fetch_assoc($this->sqlResult))
		{
	    	$retArray[] = $rs;
		}	

    $this->CleanQuery();

    return $retArray;
  }

  function GetTotalRows()
	{
		$this->ExecuteQuery();
		
		return mysql_num_rows($this->sqlResult);
  }

  function GetRow()
	{
		$this->ExecuteQuery();

	  $rs=mysql_fetch_assoc($this->sqlResult);

    $this->CleanQuery();

    return $rs;
  }

  function GetSingle()
	{
		$this->ExecuteQuery();

		$rs=@mysql_result($this->sqlResult, 0);

		if(!$rs)
			$rs = 0;
			
    $this->CleanQuery();

    return $rs;
  }

  function InsertData()
	{
		$this->ExecuteQuery();
		$last_id=mysql_insert_id($this->conn_id);

    $this->CleanQuery();

    return $last_id;
  }

  function UpdateData()
	{
		$this->ExecuteQuery();

		$return = mysql_affected_rows($this->conn_id);

  	$this->CleanQuery();

    return $return;
  }

  function DeleteData()
	{
		return $this->UpdateData();
  }
	
  function CleanQuery()
	{
    @mysql_free_result($this->sqlResult);
    //$this->query = "";
  }
	
	function EnumSelect( $table , $field )
	{
		$this->query = "SHOW COLUMNS FROM `$table` LIKE '$field' ";
		$this->ExecuteQuery();

		$row = mysql_fetch_array( $this->sqlResult , MYSQL_NUM );
		$regex = "/'(.*?)'/";

		preg_match_all( $regex , $row[1], $enum_array );
		$enum_fields = $enum_array[1];

		return( $enum_fields );
	}	
}

?>