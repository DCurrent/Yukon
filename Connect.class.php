<?php

namespace dc\yukon;

require_once('config.php');

// Database connection object.
interface iConnect 
{	
	function get_config();
	function get_connection();					// Return database connection resource.
	function set_config(ConnectConfig $value);	// Set config object.
	function open_connection();					// Attempt database connection.
}

// Database host connection manager.
class Connect implements iConnect 
{			
	private
		$connect 			= NULL,	// Database connection resource.
		$connect_params	= NULL;	// Connection parameters object.
			
	public function __construct(ConnectConfig $connect = NULL)
	{			
		// Set connection parameters member. If no argument
		// is provided, then create a blank connection
		// parameter instance.
		if($connect)
		{
			$this->set_config($connect);
		}
		else
		{
			$this->set_config(new ConnectConfig);
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
		return $this->connect_params;
	}
	
	public function get_connection()
	{	
		return $this->connect;
	}
	
	// Mutators
	public function set_config(ConnectConfig $value)
	{
		$this->connect_params = $value;
	}
	
	// Connect to database host. Returns connection.
	public function open_connection()
	{			
		$connect = NULL; // Database connection reference.
		$db_cred = NULL; // Credentials array.
		
		$config	= $this->connect_params;
		$error	= $config->get_error();
		
		// Set up credential array.
		$db_cred = array('Database'	=> $config->get_name(), 
				'UID' 		=> $config->get_user(), 
				'PWD' 		=> $config->get_password(),
				'CharacterSet' 	=> $config->get_charset());		
								
		try 
		{
			// Can't connect if there's no host.
			if(!$config->get_host())
			{
				$error->exception_throw(new ExceptionDC(EXCEPTION_MSG::CONNECT_OPEN_HOST, EXCEPTION_CODE::CONNECT_OPEN_HOST));				
			}
			
			// Establish database connection.
			$connect = sqlsrv_connect($config->get_host(), $db_cred);

			// False returned. Database connection has failed.
			if(!$connect)
			{				
				$error->exception_throw(new ExceptionDC(EXCEPTION_MSG::CONNECT_OPEN_FAIL, EXCEPTION_CODE::CONNECT_OPEN_FAIL));
			}
			
		}
		catch (ExceptionDC $exception) 
		{	
			// Catch exception internally if configured to do so.
			$error->exception_catch();
		}
		
		// Set connect data
		$this->connect = $connect;
		
		return $connect;
	}
	
	// Close database connection and returns TRUE, or 
	// return FALSE if connection does not exist.
	public function close_connection()
	{
		$result 	= FALSE;			// Connection present and closed?
		$connect 	= $this->connect;	// Database connection.
		
		try 
		{
			// Can't close if there is no connection.
			if(!$connect)
			{
				$error->exception_throw(new ExceptionDC(EXCEPTION_MSG::CONNECT_CLOSE_CONNECTION, EXCEPTION_CODE::CONNECT_CLOSE_CONNECTION));				
			}
			
			// Close database connection.
			$result = sqlsrv_close($connect);

			// Verify we were able to disconnect, else throw exception.
			if($result)
			{	
				// Clean connection member.
				$this->connect = NULL;
			}
			else
			{
				$error->exception_throw(new ExceptionDC(EXCEPTION_MSG::CONNECT_CLOSE_FAIL, EXCEPTION_CODE::CONNECT_CLOSE_FAIL));
			}
			
		}
		catch (ExceptionDC $exception) 
		{	
			// Catch exception internally if configured to do so.
			$error->exception_catch();
		}
				
		return $result;
	}
}

?>
