<?php

namespace dc\yukon;

require_once('config.php');

// Structure of parameters used for database connection attempt.
interface iConnectConfig
{	
	function get_host();		// Return host name.
	function get_name();		// Return logical database name.
	function get_user();		// Return user.
	function get_password();	// Return password.
	function set_host($value);	// Set host name.
	function set_name($value);	// Set logical database name.
	function set_user($value);	// Set user.
	function set_password($value);	// Set password.	
}

class ConnectConfig implements iConnectConfig
{		
	private
		$charset_m			= NULL,	// Character set.
		$exception_catch	= NULL,	// Internal exception handling toggle.
		$host_m				= NULL,	// Server name or address.
		$name_m				= NULL,	// Database name.
		$user_m				= NULL,	// User name to access database.
		$password_m			= NULL;	// Password for user to access database.
	
	public function __construct()
	{
		// Populate defaults.
		$this->exception_catch	= DEFAULTS::EXCEPTION_CATCH;
		$this->charset_m		= DEFAULTS::CHARSET;
		$this->host_m 			= DEFAULTS::HOST;
		$this->name_m 			= DEFAULTS::NAME;
		$this->user_m 			= DEFAULTS::USER;
		$this->password_m 		= DEFAULTS::PASSWORD;
	}
	
	// Accessors.
	public function get_charset()
	{		
		return $this->charset_m;
	}	
	
	public function get_exception_catch()
	{
		return $this->exception_catch;
	}
	
	public function get_host()
	{		
		return $this->host_m;
	}	
	
	public function get_name()
	{		
		return $this->name_m;
	}

	public function get_user()
	{		
		return $this->user_m;
	}

	public function get_password()
	{		
		return $this->password_m;
	}

	// Mutators.
	public function set_charset($value)
	{		
		$this->charset_m = $value;
	}
	
	public function set_exception_catch($value)
	{
		$this->exception_catch = $value;
	}

	public function set_host($value)
	{		
		$this->host_m = $value;
	}

	public function set_name($value)
	{		
		$this->name_m = $value;
	}

	public function set_user($value)
	{		
		$this->user_m = $value;
	}

	public function set_password($value)
	{		
		$this->password_m = $value;
	}
}
?>
