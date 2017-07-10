<?php

namespace dc\yukon;

require_once('config.php');

// Structure of parameters used for database connection attempt.
interface iConnectConfig
{	
	function get_charset();			// Get character set.
	function get_error();			// Return error handler.
	function get_host();			// Return host name.
	function get_name();			// Return logical database name.
	function get_user();			// Return user.
	function get_password();		// Return password.
	function set_error($value);		// Set error handler.
	function set_host($value);		// Set host name.
	function set_name($value);		// Set logical database name.
	function set_user($value);		// Set user.
	function set_password($value);	// Set password.	
}

class ConnectConfig implements iConnectConfig
{		
	private
		$charset	= NULL,	// Character set.
		$error		= NULL,	// Internal exception handling toggle.
		$host		= NULL,	// Server name or address.
		$name		= NULL,	// Database name.
		$user		= NULL,	// User name to access database.
		$password	= NULL;	// Password for user to access database.
	
	public function __construct(Error $error = NULL)
	{
		// Populate defaults.
		$this->error	= $this->construct_error($error);
		$this->charset	= DEFAULTS::CHARSET;
		$this->host 	= DEFAULTS::HOST;
		$this->name 	= DEFAULTS::NAME;
		$this->user 	= DEFAULTS::USER;
		$this->password	= DEFAULTS::PASSWORD;
	}
	
	// Accessors.
	public function get_charset()
	{		
		return $this->charset;
	}	
	
	public function get_error()
	{
		return $this->error;
	}
	
	public function get_host()
	{		
		return $this->host;
	}	
	
	public function get_name()
	{		
		return $this->name;
	}

	public function get_user()
	{		
		return $this->user;
	}

	public function get_password()
	{		
		return $this->password;
	}

	// Mutators.
	public function set_charset($value)
	{		
		$this->charset = $value;
	}
	
	public function set_error($value)
	{
		$this->error = $value;
	}

	public function set_host($value)
	{		
		$this->host = $value;
	}

	public function set_name($value)
	{		
		$this->name = $value;
	}

	public function set_user($value)
	{		
		$this->user = $value;
	}

	public function set_password($value)
	{		
		$this->password = $value;
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
	
}
?>
