<?php

namespace dc\yukon;

require_once('config.php');

// Common warning codes:
	// 0:		Cursor type changed.
	// 5701:	Changed database context.
	// 5703:	Changed language setting.

// Legacy error handling.
interface iErrorConfig
{
	function get_error_trap();
	function get_exception_catch();
	function get_ignore_codes();
	function set_error_trap($value);
	function set_exception_catch($value);
	function set_ignore_codes(\SplDoublyLinkedList $value);
	
	function driver_config($key, $value);
}

class ErrorConfig implements iErrorConfig
{
	private 
		$ignore_codes		= NULL,	// List of error codes that will be ignored by error trap.
		$error_trap			= NULL,	// Toggle catching of driver errors.
		$exception_catch	= NULL;	// Toggle internal exception handling. 
	
	
	public function __construct()
	{		
	}	
	
	// Accessors.
	public function get_error_trap()
	{
		return $this->error_trap;
	}
	
	public function get_exception_catch()
	{
		return $this->exception_catch;
	}
	
	public function get_ignore_codes()
	{
		return $this->ignore_codes;
	}
	
	// Mutators.
	public function set_error_trap($value)
	{
		$this->error_trap = $value;	
	}
	
	public function set_exception_catch($value)
	{
		$this->exception_catch = $value;
	}
	
	public function set_ignore_codes(\SplDoublyLinkedList $value)
	{
		$this->ignore_codes = $value;
	}
	
	// 
	public function driver_config($key, $value)
	{
		return sqlsrv_configure($key, $value);
	}
}

?>