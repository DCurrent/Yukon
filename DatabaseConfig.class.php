<?php

namespace dc\yukon;

require_once('config.php');

// Data structure for the options parameter when preparing SQL queries.
interface iDatabaseConfig
{	
	function get_error();				// Error handling object.	
	function get_scrollable();			// Return cursor scrollable.
	function get_sendstream();			// Return sendstream.
	function get_timeout();				// Return timeout.
	function set_error($value);			// Set exception catch toggle.
	function set_scrollable($value);	// Set cursor scrollable.
	function set_sendstream($value);	// Set sendstream.
	function set_timeout($value);		// Set timeout.
}

class DatabaseConfig implements iDatabaseConfig
{	
	private 
		$error		= NULL,	// Exception catching flag.
		$scrollable	= NULL,	// Cursor type.
		$sendstream	= NULL,	// Send all stream data at execution (TRUE), or to send stream data in chunks (FALSE)
		$timeout 	= NULL;	// Query timeout in seconds.
		
	public function __construct(Error $error = NULL)
	{
		// Populate defaults.
		$this->error		= $this->construct_error($error);
		$this->scrollable 	= DEFAULTS::SCROLLABLE;
		$this->sendstream 	= DEFAULTS::SENDSTREAM;
		$this->timeout		= DEFAULTS::TIMEOUT;
	}
	
	// Accessors
	public function get_error()
	{
		return $this->error;
	}
	
	public function get_scrollable()
	{			
		return $this->scrollable;
	}
	
	public function get_sendstream()
	{		
		return $this->timeout;
	}
	
	public function get_timeout()
	{		
		return $this->timeout;
	}
	
	// Mutators
	public function set_error($value)
	{
		$this->error = $value;
	}
	
	public function set_scrollable($value)
	{		
		$this->scrollable = $value;
	}
	
	public function set_sendstream($value)
	{		
		$this->sendstream = $value;
	}
	
	public function set_timeout($value)
	{		
		$this->timeout = $value;
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
