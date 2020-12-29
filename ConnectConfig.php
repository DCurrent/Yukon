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
	function get_host();			// Return host name.
	function get_name();			// Return logical database name.
	function get_user();			// Return user.
	function get_password();		// Return password.
	
	function set_charset($value);			// Charset type (example: UTF-8).
	function set_db_type(string $value);	// Set database type.
	function set_error(Error $value);		// Set error handler.
	function set_host($value);		// Set host name.
	function set_name(string $value);		// Set logical database name.
	function set_user(string $value);		// Set user.
	function set_password(string $value);	// Set password.
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
	
	public function get_host()
	{		
		return $this->host;
	}	
	
	public function set_host($value)
	{		
		$this->host = $value;
	}
	
	public function get_name()
	{		
		return $this->name;
	}

	public function set_name(string $value)
	{		
		$this->name = $value;
	}
	
	public function get_password()
	{		
		return $this->password;
	}

	public function set_password(string $value)
	{		
		$this->password = $value;
	}
	
	public function get_user()
	{		
		return $this->user;
	}

	public function set_user(string $value)
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
	
	public function populate_config(string $config_file)
	{
		$config_array = parse_ini_file($config_file, TRUE);		
		$section_array = $config_array[__CLASS__];
		
		$this->host 	= $section_array['HOST'];
		$this->name 	= $section_array['DATABASE_NAME'];
		$this->user 	= $section_array['USER_NAME'];
		$this->password	= $section_array['USER_PASSWORD'];
	}
	
}
?>
