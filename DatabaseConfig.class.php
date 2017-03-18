<?php

namespace dc\yukon;

require_once('config.php');

class DatabaseConfig implements iDatabaseConfig
{	
	private 
		$scrollable_m 	= NULL,	// Cursor type (http://msdn.microsoft.com/en-us/library/ee376927.aspx).
		$sendstream_m	= NULL,	// Send all stream data at execution (TRUE), or to send stream data in chunks (FALSE)
		$timeout_m 		= NULL;	// Query timeout in seconds.
		
	public function __construct()
	{
		$this->scrollable_m = \dc\yukon\DEFAULTS::SCROLLABLE;
		$this->sendstream_m = \dc\yukon\DEFAULTS::SENDSTREAM;
		$this->timeout_m	= \dc\yukon\DEFAULTS::TIMEOUT;
	}
	
	// Accessors
	public function get_scrollable()
	{			
		return $this->scrollable_m;
	}
	
	public function get_sendstream()
	{		
		return $this->timeout_m;
	}
	
	public function get_timeout()
	{		
		return $this->timeout_m;
	}
	
	// Mutators
	public function set_scrollable($value)
	{		
		$this->scrollable_m = $value;
	}
	
	public function set_sendstream($value)
	{		
		$this->sendstream_m = $value;
	}
	
	public function set_timeout($value)
	{		
		$this->timeout_m = $value;
	}	
}

?>