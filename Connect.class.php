<?php

namespace dc\yukon;

// Connect
// Damon Vaughn Caskey
// 2014-04-04

// Database host connection manager.
class Connect implements iConnect 
{			
	private
		$connect_m 			= NULL,	// Database connection resource.
		$connect_params_m 	= NULL;	// Connection parameters object.
			
	public function __construct(ConnectConfig $connect = NULL)
	{	
		// Set connection parameters member. If no argument
		// is provided, then created a blank connection
		// parameter instance.
		if($connect)
		{
			$this->set_connection_params($connect);
		}
		else
		{
			$this->set_connection_params(new ConnectConfig);
		}
	
		// Connect to database server.
		$this->open_connection();
	}
	
	public function __destruct() 
	{		
		// Close DB connection.
		$this->close_connection();
   	}
	
	// Accessors.
	public function get_connection()
	{	
		return $this->connect_m;
	}
	
	public function get_connection_params()
	{	
		return $this->connect_params_m;
	}
	
	// Mutators
	public function set_connection_params($value)
	{
		$this->connect_params_m = $value;
	}
	
	// Connect to database host. Returns connection.
	public function open_connection()
	{			
		$connect = NULL; // Database connection reference.
		$db_cred = NULL; // Credentials array.
		
		$connect_params = $this->connect_params_m;
	
		// Set up credential array.
		$db_cred = array('Database' 	=> $connect_params->get_name(), 
						'UID' 			=> $connect_params->get_user(), 
						'PWD' 			=> $connect_params->get_password(),
						'CharacterSet' 	=> $connect_params->get_charset());
									
		// Establish database connection.
		$connect = sqlsrv_connect($connect_params->get_host(), $db_cred);		
				
		// False returned. Database connection has failed.
		if($connect === FALSE)
		{			
			// Stop script and log error.					
		}		
		
		// Set connect data
		$this->connect_m = $connect;
		
		return $connect;
	}
	
	// Close database connection and returns TRUE, or return FALSE if connection does not exist.
	public function close_connection()
	{
		$result 	= FALSE;			// Connection present and closed?
		$connect 	= $this->connect_m;	// Database connection.
		
		// Close DB conneciton.
		if($connect)
		{			
			sqlsrv_close($connect);
			$this->connect_m = NULL;
			$result = TRUE;
		}
		
		return $result;
	}
}

?>