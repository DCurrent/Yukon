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
	// Accessors
	function get_exempt_codes_catch();
	function get_exempt_codes_driver();
	function get_exempt_codes_throw();
	
	// Mutators
	function set_exempt_codes_catch(\SplDoublyLinkedList $value);
	function set_exempt_codes_driver(\SplDoublyLinkedList $value);
	function set_exempt_codes_throw(\SplDoublyLinkedList $value);
	
	function driver_config($key, $value);
}

class ErrorConfig implements iErrorConfig
{
	private 
		$exempt_codes_catch		= NULL,	// List of error codes that will be ignored by error trap.
		$exempt_codes_driver	= NULL,	// Toggle internal exception handling.
		$exempt_codes_throw		= NULL;	// Toggle catching of driver errors.
	
	
	public function __construct(\SplDoublyLinkedList $exempt_codes_catch = NULL, \SplDoublyLinkedList $exempt_codes_throw = NULL, \SplDoublyLinkedList $exempt_codes_driver = NULL)
	{		
		// Apply exempt code lists. We use default constants here
		// instead of in the argument block explicitly so 
		// users must create linked lists and maintain consistency.
		$this->exempt_codes_catch 	= $this->construct_exempt_codes($exempt_codes_catch, DEFAULTS::EXEMPT_CODES_CATCH);		
		$this->exempt_codes_driver	= $this->construct_exempt_codes($exempt_codes_driver, DEFAULTS::EXEMPT_CODES_DRIVER);
		$this->exempt_codes_throw	= $this->construct_exempt_codes($exempt_codes_throw, DEFAULTS::EXEMPT_CODES_THROW);
	}	
	
	// Accessors.
	public function get_exempt_codes_catch()
	{
		return $this->exempt_codes_catch;
	}
	
	public function get_exempt_codes_driver()
	{
		return $this->exempt_codes_driver;
	}
	
	public function get_exempt_codes_throw()
	{
		return $this->exempt_codes_throw;
	}
	
	// Mutators.
	public function set_exempt_codes_catch(\SplDoublyLinkedList $value)
	{
		$this->exempt_codes_catch = $value;	
	}
	
	public function set_exempt_codes_driver(\SplDoublyLinkedList $value)
	{
		$this->exempt_codes_driver = $value;
	}
	
	public function set_exempt_codes_throw(\SplDoublyLinkedList $value)
	{
		$this->exempt_codes_throw = $value;
	}
	
	// Constructors
											
	// Passes through list object or returns
	// a new object with default values if 
	// value is NULL.
	private function construct_exempt_codes(\SplDoublyLinkedList $value = NULL, $default)
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
			// Create a new list with default values.
			$result = $this->explode_to_list($default);
		}
	
		// Return final result.
		return $result;
	}	
	
	// Explode delimited list to an SplDoublyLinkedList object.										
	private function explode_to_list($list = NULL, $delimiter = ',')
	{
		// Initialize new doubly linked list object.
		$result = new \SplDoublyLinkedList();
			
		// Break down list to array.
		$value_array = explode(',', $list);

		// Add array elements to exempt code list.
		foreach($value_array as $value_element)
		{
			// For our purposes, 0 is a valid list value, 
			// but a blank string is not. We'll make sure
			// the list is clean here by filtering
			// out blank strings.
			if($value_element !== '')
			{
				$result->push($value_element);	
			}			
		}
			
		return $result;
	}
											
	// 
	public function driver_config($key, $value)
	{
		return sqlsrv_configure($key, $value);
	}
}


?>