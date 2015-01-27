<?php
/***********************************************************************
* mySQL Database Access Class
*
************************************************************************/

class DB {
  
	var $lid 				= 0;       	// Link ID for database connection
	var $qid 				= 0;				// Query ID for current query
	var $row;										// Current row in query result set
	var $record 		= array();	// Current row record data
	var $error 			= "";				// Error Message
	var $errno 			= "";				// Error Number
	var $tableList 	= array();	// List of tables in Database

	/******************************************************************************
	* connect()
	* 	Connects to DB and returns DB lid
	******************************************************************************/
	function connect() { 
		$database = DB_NAME;
		
		if ($this->lid == 0) {
			$this->lid = mysqli_connect(DB_HOST,DB_USER,DB_PWD, DB_NAME); 
			if (!$this->lid) {
				$this->halt("connect to Database failed.".DB_HOST);
			}
			
			/* Vérification de la connexion */
			if (mysqli_connect_errno()) {
			    printf("Échec de la connexion : %s\n", mysqli_connect_error());
			    return 0;
			}
		}
		return $this->lid;
	}
	
	/******************************************************************************
	* query($q)
	* 	Established connection to database and runs the query returning
	* 	a query ID if successfull.
	******************************************************************************/
	function query($q) {
    
		if (empty($q))
			return 0;
	    
		if (!$this->connect()) {
			return 0; 
		}
		if ($this->qid) {
			@mysqli_free_result($this->qid);
			$this->qid = 0;
		}
	    
		$this->qid = mysqli_query($this->lid,$q);
		$this->keyValue = mysqli_insert_id($this->lid);
		$this->row   = 0;
		$this->errno = mysqli_errno($this->lid);
		$this->error = mysqli_error($this->lid);
		if (!$this->qid) {
			$this->halt("Rizak Invalid SQL: ".$q);
		}
		return $this->qid;
	}


	/******************************************************************************
	* getTables($database)
	* 	Returns the list of all tables in database
	******************************************************************************/
	function getTables($database){
		if (!$this->connect()) {
			return 0;
		}
		$this->tableList = mysqli_list_tables($database, $this->lid);
		return $this->tableList;
	}

	/******************************************************************************
	* fieldtype($fieldname)
	* 	Returns type of field
	******************************************************************************/
	function fieldtype($field){
		// to do
  }

	/******************************************************************************
	* fieldsize($fieldname)
	* 	Returns size of field
	******************************************************************************/
  function fieldsize($field){
		// to do
	}
	
	/******************************************************************************
	* inserted_key()
	* 	Return last inserted key (auto_increment)
	******************************************************************************/
	function inserted_key() {
		return $this->keyValue;
	}
	
	/******************************************************************************
	* next_record()
	* 	Go to next record in result set
	******************************************************************************/
	function next_record() {

		if (!$this->qid) {
			$this->halt("next_record called with no query pending.");
			return 0;
		}
	    
		$this->record = @mysqli_fetch_array($this->qid);
		$this->row   += 1;
		$this->errno  = mysql_errno();
		$this->error  = mysql_error();

		$stat = is_array($this->record);
		return $stat;
	}

	/******************************************************************************
	* next_record()
	* 	Return next record in two dimensional array
	******************************************************************************/
	function next_array() {
		if (!$this->qid) {
			$this->halt("next_record called with no query pending.");
			return 0;
		}

		$this->record = @mysqli_fetch_array($this->qid);
		$this->row   += 1;
		$this->errno  = mysqli_errno($this->lid);
		$this->error  = mysqli_error($this->lid);

		return$this->record;
	}

	/******************************************************************************
	* f($field_name)
	* 	Return Field Value
	******************************************************************************/
	function f($field_name) {
		return stripslashes($this->record[$field_name]);
	}
	
	/******************************************************************************
	* p($field_name)
	* 	Print Field Value
	******************************************************************************/
	function p($field_name) {
		print stripslashes($this->record[$field_name]);
	}

	/******************************************************************************
	* sf($field_name)
	* 	Return Field Value or form value ($vars)
	******************************************************************************/
	function sf($field_name) {
		global $vars, $default;
	
		if ($vars["error"] and $vars["$field_name"]) {
			return stripslashes($vars["$field_name"]);
		} elseif ($default["$field_name"]) {
			return stripslashes($default["$field_name"]);
		} else {
			return stripslashes($this->record[$field_name]);
		}
	}

	/******************************************************************************
	* num_rows()
	* 	Returns the number of rows in query
	******************************************************************************/
	function num_rows() { 
    
		if ($this->lid) { 
			return @mysql_numrows($this->qid); 
		}else { 
			return 0; 
		} 
	}

	/******************************************************************************
	* halt($msg)
	* 	Halt and display error message
	******************************************************************************/
	function halt($msg) {
		$this->error = @mysqli_error($this->lid);
		$this->errno = @mysqli_errno($this->lid);

		printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
		printf("<b>MySQL Error</b>: %s (%s)<br>\n",
		$this->errno,
		$this->error);
		exit;
	}
}

/*******************************************************************************
* function makeSQL
*	(
* array selectFields, string type (select|insert|update, array selectValues)
*	)
*******************************************************************************/
function makeSQL($selectFields, $type="select"){

	 	switch($type) {
			case "select" :
				$i = 1;
				foreach($selectFields as $fieldName){
					if($i==count($selectFields)) $sql .= "$fieldName";
					else $sql .= "$fieldName, ";
          $i++;
				}
				break;

			case "insert" :
				$i = 1;
				foreach($selectFields as $fieldName => $fieldValue){
				  if(!get_magic_quotes_runtime() && !get_magic_quotes_gpc()) $fieldValue = addslashes($fieldValue);
					if($i==1){
						$insert .= "(";
						$sql .= " VALUES (";
					}
					if($i==count($selectFields)){
					  $insert .= "$fieldName)";
						$sql .= "'$fieldValue')";
					}else{
					  $insert .= "$fieldName,";
						$sql .= "'$fieldValue',";
					}
          $i++;
				}
				break;

			case "update" :
				$i = 1;
				foreach($selectFields as $fieldName => $fieldValue){
				  if(!get_magic_quotes_runtime() && !get_magic_quotes_gpc()) $fieldValue = addslashes($fieldValue);
					if($i==count($selectFields)) $sql .= "$fieldName='$fieldValue'";
					else $sql .= "$fieldName='$fieldValue', ";
          $i++;
				}
			  break;
		}
	return $insert.$sql;
}
?>
