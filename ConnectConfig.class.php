<?php

namespace dc\yukon;

require_once('config.php');

class ConnectConfig implements iConnectConfig
{		
	private
		$host_m		= NULL,	// Server name or address.
		$name_m		= NULL,	// Database name.
		$user_m		= NULL,	// User name to access database.
		$password_m	= NULL,	// Password for user to access database.
		$charset_m	= NULL;	// Character set.
	
	public function __construct()
	{
		$this->charset_m = \dc\yukon\DEFAULTS::CHARSET;
		$this->host_m = \dc\yukon\DEFAULTS::HOST;
		$this->name_m = \dc\yukon\DEFAULTS::NAME;
		$this->user_m = \dc\yukon\DEFAULTS::USER;
		$this->password_m = \dc\yukon\DEFAULTS::PASSWORD;
	}
	
	// Accessors.
	public function get_charset()
	{		
		return $this->charset_m;
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