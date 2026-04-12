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
			return mysqli_error( );
		}

		//===========================================================================
		// Constructor !
		//===========================================================================
		function mydb( $dbname = FALSE )
		{
			if( $dbname )
			{
				$this->connect( );
				//$this->select_db( $dbname );
			}
			return TRUE;
		}

		//===========================================================================
		// Create a persistent link identifier on success, or false on error.
		// mysql_close() will not close links established by mysql_pconnect()
		// All of the arguments are optional, and if they're missing, defaults are assumed
		//===========================================================================
		function connect( $hostname, $username, $password,$dbname)
		//function connect( $hostname = "localhost", $username = "root", $password = "")
		//function connect( $hostname = "172.23.11.209", $username = "root", $password = "")
		//function connect( $hostname = "localhost", $username = "aisystc", $password = "tee1alone")
		{
		//echo $hostname.'-'. $username.'-'. $password.'-'.$dbname;

			$this->con =  mysqli_connect( $hostname, $username, $password ,$dbname)or die("Some error occurred during connection " . mysqli_error($con));  
			return $this->con;
		}


		//===========================================================================
		// Sends a query to the currently active database on the server that's associated with $this->con
		// Returns TRUE (non-zero) or FALSE to indicate whether or not the query succeeded.
		//===========================================================================
		function query( $query )
		{
			//echo $query ."<br><br><br>";
	

			/*mysqli_query($this->con ,"SET Character_set_results=utf8");
			mysqli_query($this->con ,"SET Character_set_client=utf8");
			mysqli_query($this->con ,"SET Character_set_cennection=utf8");
			mysqli_query($this->con ,"SET Collation_connection=utf8_general_ci");
			mysqli_query($this->con ,"SET Character_database=utf8_general_ci");
			mysqli_query($this->con ,"SET Character_server=utf8_general_ci"); */
			$this->result = mysqli_query($this->con , $query );
			mysqli_store_result($this->con);
			return $this->result;
		}

		function closeconnect()
		{
			mysqli_close($this->con);
		}


		//===========================================================================
		// Returns an array that corresponds to the fetched row, or false if there are no more rows.
		//===========================================================================
		function fetch_row( )
		{
			$this->row =  mysqli_fetch_row( $this->result );
			return $this->row;
		}

		function fetch_array( ) //tont
		{
			$this->row =  mysqli_fetch_array( $this->result );
			return $this->row;
		}

		function field_name($num)
		{
			$this->column = mysqli_field_name( $this->result ,0);
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
			return mysqli_num_rows( $this->result );
		}

		//===========================================================================
		// Returns number of field in result.
		//===========================================================================
		function num_fields( )
		{
			//print_r($this->results);
			return mysqli_num_fields( $this->result );
		}

		//===========================================================================
		// Free result memory
		//===========================================================================
		function free_result( )
		{
			mysqli_free_result( $this->result );
		}

		function next_result( )
		{
			mysqli_next_result($this->con);
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
			return mysqli_affected_rows( );
		}

	}
?>
