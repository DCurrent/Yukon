<?php

namespace dc\yukon;

require_once('config.php');

// Data structure for the options parameter when preparing SQL queries.
interface iDatabaseConfig
{	
<<<<<<< HEAD
	function get_error();			// Error handling object.	
	function get_scrollable();				// Return cursor scrollable.
	function get_sendstream();				// Return sendstream.
	function get_timeout();					// Return timeout.
	function set_error($value);	// Set exception catch toggle.
	function set_scrollable($value);		// Set cursor scrollable.
	function set_sendstream($value);		// Set sendstream.
	function set_timeout($value);			// Set timeout.
=======
	function get_scrollable();		// Return cursor scrollable.
	function get_sendstream();		// Return sendstream.
	function get_timeout();			// Return timeout.
	function set_scrollable($value);	// Set cursor scrollable.
	function set_sendstream($value);	// Set sendstream.
	function set_timeout($value);		// Set timeout.
>>>>>>> origin/master
}

class DatabaseConfig implements iDatabaseConfig
{	
	private 
<<<<<<< HEAD
		$error		= NULL,	// Exception catching flag.
		$scrollable_m 		= NULL,	// Cursor type.
		$sendstream_m		= NULL,	// Send all stream data at execution (TRUE), or to send stream data in chunks (FALSE)
		$timeout_m 			= NULL;	// Query timeout in seconds.
=======
		$scrollable_m 	= NULL,	// Cursor type.
		$sendstream_m	= NULL,	// Send all stream data at execution (TRUE), or to send stream data in chunks (FALSE)
		$timeout_m 	= NULL;	// Query timeout in seconds.
>>>>>>> origin/master
		
	public function __construct(Error $error = NULL)
	{
		// Populate defaults.
<<<<<<< HEAD
		$this->error			= $this->construct_error($error);
		$this->scrollable_m 	= DEFAULTS::SCROLLABLE;
		$this->sendstream_m 	= DEFAULTS::SENDSTREAM;
		$this->timeout_m		= DEFAULTS::TIMEOUT;
=======
		$this->scrollable_m 	= \dc\yukon\DEFAULTS::SCROLLABLE;
		$this->sendstream_m 	= \dc\yukon\DEFAULTS::SENDSTREAM;
		$this->timeout_m	= \dc\yukon\DEFAULTS::TIMEOUT;
>>>>>>> origin/master
	}
	
	// Accessors
	public function get_error()
	{
		return $this->error;
	}
	
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
	public function set_error($value)
	{
		$this->error = $value;
	}
	
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
	
	// Sub Construcors
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
