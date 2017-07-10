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
	function get_exception_throw();
	function get_exception_catch();
	function get_exempt_driver_codes();
	function set_exception_throw($value);
	function set_exception_catch($value);
	function set_exempt_driver_codes(\SplDoublyLinkedList $value);
	
	function driver_config($key, $value);
}

class ErrorConfig implements iErrorConfig
{
	private 
		$exempt_driver_codes		= NULL,	// List of error codes that will be ignored by error trap.
		$exception_catch	= NULL,	// Toggle internal exception handling.
		$exception_throw	= NULL;	// Toggle catching of driver errors.
	
	
	public function __construct($exception_catch = DEFAULTS::EXCEPTION_CATCH, $exception_throw = DEFAULTS::EXCEPTION_THROW, \SplDoublyLinkedList $exempt_driver_codes = NULL)
	{		
		$this->construct_exempt_driver_codes($exempt_driver_codes);
	}	
	
	// Accessors.
	public function get_exception_throw()
	{
		return $this->exception_throw;
	}
	
	public function get_exception_catch()
	{
		return $this->exception_catch;
	}
	
	public function get_exempt_driver_codes()
	{
		return $this->exempt_driver_codes;
	}
	
	// Mutators.
	public function set_exception_throw($value)
	{
		$this->exception_throw = $value;	
	}
	
	public function set_exception_catch($value)
	{
		$this->exception_catch = $value;
	}
	
	public function set_exempt_driver_codes(\SplDoublyLinkedList $value)
	{
		$this->exempt_driver_codes = $value;
	}
	
	// Construcors
	private function construct_exempt_driver_codes(\SplDoublyLinkedList $value = NULL)
	{
		$result = NULL;	// Final result.
		
		// Verify argument is an object.
		$is_object = is_object($value);
		
		if($is_object)
		{
			$result = $value;		
		}
		else
		{
			// Create new exempt code list.
			$result = new \SplDoublyLinkedList();
			
			// Break down list of codes to array.
			$exmept_default = explode(',', DEFAULTS::EXEMPT_DRIVER_CODES);
			
			// Add array elements to exempt code list.
			foreach($exmept_default as $exmept_default_element)
			{
				$result->push($exmept_default_element);
			}
		}
		
		// Populate member with result.
		$this->exempt_driver_codes = $result;
	
		return $result;
	}
	
	// 
	public function driver_config($key, $value)
	{
		return sqlsrv_configure($key, $value);
	}
}

?>