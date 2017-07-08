<?php

namespace dc\yukon;

require_once('config.php');

// Database connection object.
interface iConnect 
{	
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> origin/master
	function get_config();
	function get_connection();					// Return database connection resource.
	function set_config(ConnectConfig $value);	// Set config object.
	function open_connection();					// Attempt database connection.
<<<<<<< HEAD
=======
=======
	function get_connection();	// Return database connection resource.
	function open_connection();	// Attempt database connection.
>>>>>>> origin/master
>>>>>>> origin/master
}

// Database host connection manager.
class Connect implements iConnect 
{			
	private
<<<<<<< HEAD
		$connect_m 			= NULL,	// Database connection resource.
<<<<<<< HEAD
=======
=======
		$connect_m 		= NULL,	// Database connection resource.
>>>>>>> origin/master
>>>>>>> origin/master
		$connect_params_m	= NULL;	// Connection parameters object.
			
	public function __construct(ConnectConfig $connect = NULL)
	{			
		// Set connection parameters member. If no argument
		// is provided, then create a blank connection
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
	public function get_config()
	{
		return $this->connect_params_m;
	}
	
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
	
	public function set_config(ConnectConfig $value)
	{
		$this->connect_params_m = $value;
	}
	
	// Connect to database host. Returns connection.
	public function open_connection()
	{			
		$connect = NULL; // Database connection reference.
		$db_cred = NULL; // Credentials array.
		
		$connect_params 	= $this->connect_params_m;
		$exception_catch	= $this->connect_params_m->get_exception_catch();
		
		// Set up credential array.
		$db_cred = array('Database'	=> $connect_params->get_name(), 
				'UID' 		=> $connect_params->get_user(), 
				'PWD' 		=> $connect_params->get_password(),
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> origin/master
				'CharacterSet' 	=> $connect_params->get_charset());		
								
		try 
		{
			// Can't connect if there's no host.
			if(!$connect_params->get_host())
			{
				throw new \Exception(EXCEPTION_MSG::MISSING_HOST, EXCEPTION_CODE::MISSING_HOST, $e);
			}
			
			// Establish database connection.
			$connect = sqlsrv_connect($connect_params->get_host(), $db_cred);

			// False returned. Database connection has failed.
			if($connect === FALSE)
			{
				throw new \Exception(EXCEPTION_MSG::CONNECTION_FAILURE, EXCEPTION_CODE::CONNECTION_FAILURE, $e);
			}
		}
		catch (\Exception $e) 
		{	
			
			if($exception_catch == TRUE)
			{	
				// Fire error event.
				trigger_error(date(DATE_ATOM).', '.$e->getMessage(), E_USER_ERROR);
			}
		}
<<<<<<< HEAD
=======
=======
				'CharacterSet' 	=> $connect_params->get_charset());
									
		// Establish database connection.
		$connect = sqlsrv_connect($connect_params->get_host(), $db_cred);		
				
		// False returned. Database connection has failed.
		if($connect === FALSE)
		{			
			// Stop script and log error.					
		}		
>>>>>>> origin/master
>>>>>>> origin/master
		
		// Set connect data
		$this->connect_m = $connect;
		
		return $connect;
	}
	
	// Close database connection and returns TRUE, or 
	// return FALSE if connection does not exist.
	public function close_connection()
	{
		$result 	= FALSE;		// Connection present and closed?
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
