<?php
	require("config.inc");
	class mydb extends config{
		
		var $con;
		var $result;
		var $row;

		//===========================================================================
		// Returns the text of the error message from previous MySQL operation
		//===========================================================================
		function error( )
		{
			return mysql_error( );
		}
				
		//===========================================================================
		// Constructor !
		//===========================================================================
		function mydb( $dbname = FALSE )
		{
			if( $dbname )
			{
				$this->connect( );
				$this->select_db( $dbname );
			}
			return TRUE;
		}
		
		//===========================================================================
		// Create a persistent link identifier on success, or false on error. 
		// mysql_close() will not close links established by mysql_pconnect()
		// All of the arguments are optional, and if they're missing, defaults are assumed 
		//===========================================================================
		function connect( $hostname, $username, $password)
		//function connect( $hostname = "localhost", $username = "root", $password = "")
		//function connect( $hostname = "172.23.11.209", $username = "root", $password = "")
		//function connect( $hostname = "localhost", $username = "aisystc", $password = "tee1alone")
		{
			$this->con =  mysql_pconnect( $hostname, $username, $password );
			return $this->con;
		}
		
		//===========================================================================
		// Sets the current active database on the server that's associated with $this->con
		// If no link identifier is specified, the last opened link is assumed. 
		//===========================================================================
		function select_db( $dbname )
		{
		if($this->con ){
			
			if (mysql_select_db( $dbname, $this->con )) 
				{ 
					return mysql_select_db( $dbname, $this->con );
					//exit; 
				}
			else
				{
					echo "Error: ไม่สามารถติดต่อฐานข้อมูล ทดลองอีกครั้ง "; 
					exit; 
					//return mysql_select_db( $dbname, $this->con );
					//exit;
				}	
		}
	}	
		//===========================================================================
		// Sends a query to the currently active database on the server that's associated with $this->con
		// Returns TRUE (non-zero) or FALSE to indicate whether or not the query succeeded.
		//===========================================================================
		function query( $query )
		{
			//echo $query ."<br><br><br>";
			mysql_query("SET Character_set_results=UTF8");
			mysql_query("SET Character_set_client=UTF8");
			mysql_query("SET Character_set_cennection=UTF8");
			mysql_query("SET Collation_connection= utf8_general_ci");
			mysql_query("SET Character_database= utf8_general_ci");
			mysql_query("SET Character_server=utf8_general_ci");
			$this->result = mysql_query( $query, $this->con );
			return $this->result;
		}
		
		//===========================================================================
		// Selects a database and executes a query on it. 
		// If the optional link identifier isn't specified, the function will try to find an open link to the MySQL server 
		//===========================================================================
		function db_query( $dbname, $query )
		{
			$this->result = mysql_db_query( $dbname, $query, $this->con);
			return $this->result;
		}
		
		//===========================================================================
		// Returns an array that corresponds to the fetched row, or false if there are no more rows. 
		//===========================================================================
		function fetch_row( )
		{
			$this->row =  mysql_fetch_row( $this->result );
			return $this->row;
		}

		function fetch_array( ) //tont
		{
			$this->row =  mysql_fetch_array( $this->result );
			return $this->row;
		}

		function field_name($num)
		{
			$this->column = mysql_field_name( $this->result ,0);
		}

		function ppstr($s) //tont use to prepare str for insert / update
		{
			return ereg_replace("'","\'",$s) ; //tont : before chage please ask me first 
//			return addslashes( trim( $s ) ) ;
		}

		//===========================================================================
		// Returns number of rows in result. 
		//===========================================================================
		function num_rows( )
		{
			return mysql_num_rows( $this->result );
		}

		//===========================================================================
		// Returns number of field in result. 
		//===========================================================================
		function num_fields( )
		{
			return mysql_num_fields( $this->result );
		}

		//===========================================================================
		// Free result memory
		//===========================================================================
		function free_result( )
		{
			mysql_free_result( $this->result );
		}
		
		//===========================================================================
		// All traces of the object will be remove from memory
		// Destructor !
		//===========================================================================
		function destroy( )
		{
			unset($this);
		}

		//===========================================================================
		// Returns size of Image by Tont 2000-10-11 
		//===========================================================================
		function POPGetImageSize ($fn,$w=0,$h=0) 
		{
			if (!file_exists($fn)) {
				$s[] = 0 ; //width
				$s[] = 0 ; //heigth
				$s[] = 0 ; //type
				$s[] = "width=0 heigth=0" ; //text
				return $s ;
			}
			$s = getimagesize($fn) ;
			if ($w > 0 && $h <= 0) {
				$s[1] = round($w * $s[1] / $s[0]) ;
				$s[0] = $w ;
				$s[3] = "width=".$s[0]." height=".$s[1] ;
			} else if ($w <= 0 && $h > 0) {
				$s[0] = round($h * $s[0] / $s[1]) ;
				$s[1] = $h ;
				$s[3] = "width=".$s[0]." height=".$s[1] ;
			}
			return $s ;
		}
		
		//===========================================================================
		// Get number of affected rows in previous MySQL operation
		//===========================================================================
		function affected_rows( )
		{
			return mysql_affected_rows( );
		}
	}
?>
