<?php
$conn  = odbc_connect('VOS', 'debatehub', 'd3b@t3hub88');
if (!$conn) {
	echo odbc_errormsg();
} else {
	$sql = "USE debatehubdev";
	odbc_exec ( $conn, $sql);
	echo odbc_errormsg();

	$sql = "SELECT TOP 3 * from (Select UserID,Name from Users) as fred";
	$result  = odbc_exec ( $conn, $sql);
	while (odbc_fetch_row($result)){
		echo"<br>".odbc_result($result,"UserID");
		echo"<br>".odbc_result($result,"Name");
	}

	/*
	$sql = "Select * from Users";
	$result  = odbc_exec ( $conn, $sql);
	while (odbc_fetch_row($result)){
		echo"<br>".odbc_result($result,"Name");
	}*/

	/*
	$sql = "CREATE TABLE AuditNode (
	  NodeID nvarchar(50) NOT NULL,
	  UserID nvarchar(50) NOT NULL DEFAULT '0',
	  Name long nvarchar,
	  Description long nvarchar,
	  ModificationDate double precision NOT NULL DEFAULT 0,
	  ChangeType nvarchar(255) NOT NULL,
	  NodeXML long nvarchar
	  )";
	  */



	/*$sql = "CREATE TABLE AuditSearch (
	  SearchID nvarchar(50) NOT NULL,
	  UserID nvarchar(50) NOT NULL DEFAULT '0',
	  SearchText long nvarchar,
	  ModificationDate double precision NOT NULL DEFAULT 0,
	  TagsOnly nvarchar(1) CHECK (TagsOnly in ('Y','N')) NOT NULL DEFAULT 'N',
	  Type nvarchar(255) NOT NULL DEFAULT 'main',
	  TypeItemID nvarchar(50) DEFAULT NULL
	)";*/

	//odbc_exec ( $conn, $sql);
	//echo odbc_errormsg();

	/*
	$res = odbc_prepare($conn, $query_string);
	if(!$res) die("could not prepare statement ".$query_string);

	if(odbc_execute($res, $parameters)) {
	    $row = odbc_fetch_array($res);
	} else {
	    // handle error
	}
	*/

	/*$result  = odbc_tables ( $conn );
	$tables = array();
	while (odbc_fetch_row($result)){
		if(odbc_result($result,"TABLE_TYPE")=="TABLE")
			echo"<br>".odbc_result($result,"TABLE_NAME");
	}

	echo odbc_errormsg();
	*/
}
?>