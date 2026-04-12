<?php
	if( !get_class_methods( "mydb" ) ) 
		require("mydb.inc");
	
	class mydb_ext extends mydb{
		
		var $con;
		var $result;
		var $row;
		
		function mydb_ext( )
		{
			return $this->connect( );
		}
		
		function fetch_a_row( $query )
		{
			if( !$this->query( $query ))
				return FALSE;
			$this->fetch_row( );
			$this->free_result( );
			return $this->row;
		}
		
		function connect_db( $dbname )
		{
			$this->connect( ) or die("Unable to connect database $dbname");
			if( !$this->con )
				return FALSE;
			return  $this->select_db( $dbname );
		}
	}
?>
