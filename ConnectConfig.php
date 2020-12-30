<?php

namespace dc\yukon;

//require_once('../../dc/yukon/config.php');
require_once(dirname(__FILE__).'\config.php');

// Structure of parameters used for database connection attempt.
interface iConnectConfig
{	
	function get_db_type();			// Return database type.
	function get_charset();			// Get character set.
	function get_error();			// Return error handler.
	function get_db_host();			// Return host name.
	function get_db_name();			// Return logical database name.
	function get_db_user();			// Return user.
	function get_db_password();		// Return password.
	
	function set_charset($value);			// Charset type (example: UTF-8).
	function set_db_type(string $value);	// Set database type.
	function set_error(Error $value);		// Set error handler.
	function set_db_host($value);		// Set host name.
	function set_db_name(string $value);		// Set logical database name.
	function set_db_user(string $value);		// Set user.
	function set_db_password(string $value);	// Set password.
}

class ConnectConfig implements iConnectConfig
{		
	private	$charset	= NULL;	// Character set.
	private $db_type	= NULL; // Database host type.
	private	$error		= NULL;	// Internal exception handling toggle.
	private	$host		= NULL;	// Server name or address.
	private	$name		= NULL;	// Database name.
	private	$user		= NULL;	// User name to access database.
	private	$password	= NULL;	// Password for user to access database.
	
	public function __construct(string $config_file = NULL, Error $error = NULL)
	{
		// Populate defaults.
		$this->db_type 	= DEFAULTS::DB_TYPE;
		$this->error	= $this->construct_error($error);
		$this->charset	= DEFAULTS::CHARSET;
		
		if($config_file)
		{
			$this->populate_config($config_file);
		}
	}
	
	// Accessors & Mutators.
	public function get_charset()
	{		
		return $this->charset;
	}	
	
	public function set_charset($value)
	{		
		$this->charset = $value;
	}
	
	public function get_db_type()
	{		
		return $this->db_type;
	}
	
	public function set_db_type(string $value)
	{		
		$this->db_type = $this->db_type_string_to_const($value);
	}
	
	public function get_error()
	{
		return $this->error;
	}
	
	public function set_error(Error $value)
	{
		$this->error = $value;
	}
	
	public function get_db_host()
	{		
		return $this->host;
	}	
	
	public function set_db_host($value)
	{		
		$this->host = $value;
	}
	
	public function get_db_name()
	{		
		return $this->name;
	}

	public function set_db_name(string $value)
	{		
		$this->name = $value;
	}
	
	public function get_db_password()
	{		
		return $this->password;
	}

	public function set_db_password(string $value)
	{		
		$this->password = $value;
	}
	
	public function get_db_user()
	{		
		return $this->user;
	}

	public function set_db_user(string $value)
	{		
		$this->user = $value;
	}	
	
	private function db_type_string_to_const(string $value)
	{
		if($value == "MSSQL")
		{
			return DB_TYPES::MSSQL;
		}
		else if($value == "MYSQL")
		{
			return DB_TYPES::MYSQL;
		}
	}
	
	// Constructors
	private function construct_error(Error $value = NULL)
	{
		$result = NULL;	// Final connection result.
		
		// Verify argument is an object.
		$is_object = is_object($value);
		
		if($is_object)
		{
			$result = $value;		
		}
		else
		{
			$result = new Error();		
		}
		
		// Populate member with result.
		$this->error = $result;
	
		return $result;
	}
	
	/*
	* Populates member data from supplied 
	* config file. 
	* 
	* 1. Reads config file secion matched to 
	* full class name (including namepsace).
	*
	* 2. Values in config are sent to matched
	* mutator. Example: 
	*
	* Config: user_name = "John Doe"
	* Method: $this->set_user_name($value);
	*/
	public function populate_config(string $config_file)
	{
		/*
		* If any part of this code fails we need to
		* consider it fatal and stop execution.
		* Throw an exception for any kind of notice 
		* or warning so we can catch and handle it. 
		*/
		set_error_handler(function ($severity, $message, $file, $line) {
    	throw new \ErrorException($message, $severity, $severity, $file, $line);
		});
		
		/*
		* Parse config into array, get class specfic 
		* section and pass values into members.
		*/		
		try
		{			
			$config_array = parse_ini_file($config_file, TRUE);
			$section_array = $config_array[__CLASS__];	
			
			// Interate through each class method.
			foreach(get_class_methods($this) as $method) 
			{		
				$key = str_replace('set_', '', $method);
				
				/*
				* If there is an array element with key matching
				* current method name, then the current method 
				* is a set mutator for the element. Populate 
				* the set method with the element's value.
				*/
				if(isset($section_array[$key]))
				{					
					$this->$method($section_array[$key]);					
				}
			}
		}
		catch(\Exception $exception)
		{			
			error_log(__CLASS__.' Fatal Error: '.$exception->getMessage());
			die(__NAMESPACE__.' Fatal Error: Failed to read values from config file. Please contact administrator.');
		}		
	}	
}
?>
