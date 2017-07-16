<?php

namespace dc\yukon;

require_once('config.php');

// Error handling object.
interface iError
{
	// Accessors
	function get_config();
	function get_exception();
	
	// Mutators
	function set_config(ErrorConfig $value);
	function set_exception(Exception $value);
	
	// Core
	function detect_error();
	function exception_catch();
}

class Error implements iError
{
	private 
		$config = NULL,
		$errors	= NULL,
		$exception = NULL;
	
	public function __construct(ErrorConfig $config = NULL)
	{	
		$this->construct_config($config);
	}
	
	// Accessors.
	public function get_config()
	{
		return $this->config;
	}
	
	public function get_exception()
	{
		return $this->exception;
	}
	
	// Mutators.
	public function set_config(ErrorConfig $value)
	{
		$this->confg = $value;
	}		
	
	public function set_exception(Exception $value)
	{
		return $this->exception;
	}
	
	// Constructors
	private function construct_config(ErrorConfig $value = NULL)
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
			$result = new ErrorConfig();
		}
		
		// Populate member with result.
		$this->config = $result;
	
		return $result;		
	}
	
	// Detect sqlsrv errors.
	// Return TRUE if errors found and
	// not on the ignore list.
	public function detect_error()
	{
		$exempt_codes	= NULL;	// Collection of errors to ignore.
		$errors			= NULL;	// Collection of errors.
		$error 			= NULL;	// Element of errors - Collection of error attributes.
		$result			= NULL;	// Final result output.
		
		// If error trapping is off, just exit. Let's
		// hope the application will be handling
		// the error instead.
		if(!$this->config->get_exception_throw())
		{
			return;
		}
			
		// Get any errors. sqlsrv driver returns
		// errors as a 2D array. Each error is an
		// element comprised of an array of error
		// attributes.
		$this->errors = sqlsrv_errors(SQLSRV_ERR_ALL);
		
		// If any errors are present, then
		// loop errors collection - we want to make
		// sure the error is one we care about.
		if(is_array($this->errors))
		{			
			$exempt_codes = $this->config->get_exempt_codes_driver();			 
			
			// Error array loop.
			foreach($this->errors as $error)
			{				
				// If this error code is not exempt then we
				// set result TRUE and exit the loop.
				$is_exempt = $this->is_exempt($exempt_codes, $error['code']);
					
				if(!$is_exempt)
				{
					$result = TRUE;
					break;
				}				
			}		
		}		
		
		// If we made it this far, then
		// we can return the results (false).
		return $result;		
	}
	
	// Verify passed code is not
	// on a list of exemptions. Return TRUE
	// if target value is in list or the list
	// contains an exmeption all value.
	//
	// @list: 	Required doubly linked list of exemptions to search.
	// @target:	Target value to search for.
	protected function is_exempt(\SplDoublyLinkedList $list, $target = NULL)
	{
		$result					= NULL;	// Final result output.

		// Verify list object.
		if(is_object($list))
		{
			// Rewind list.
			$list->rewind();
			$current 	= NULL;				// Current list value.			
			
			// Compare error code to items in ignore
			// list until a match is found or we
			// get to end of ignore list.
			while ($list->valid() && !$result)
			{
				// Get current value and status.
				$current 	= $list->current();
				
				// If current ignore list item matches the
				// target we are looking for or there
				// is an exempt all item in the list
				// we can return true.
				if($current == $target || $current == DEFAULTS::EXEMPT_ALL)
				{
					$result = TRUE;
				}

				$list->next();
			}
		}
		
		// Return result.
		return $result;
	}
	
	// Internal exception catch. Trigger an error and log the
	// thrown exception.
	public function exception_catch($severity = E_ERROR)
	{	
		$exception 		= $this->exception;
		$exempt_list	= $this->config->get_exempt_codes_catch();
		$code 			= $exception->getCode();
			
		// If this code is not on the exempt
		// list for local catching, then 
		// throw an error exception. This is our last
		// chance to catch the error before PHP engine
		// picks it up and more often than not throws 
		// a relativity useless error code.
		$is_exempt = $this->is_exempt($exempt_list, $code);
		
		if(!$is_exempt)
		{
			throw new ErrorException($exception->getMessage(), $code, $severity, $exception->getFile(), $exception->getLine());		
		}	
	}
	
	// Throw an exception if the code is
	// not exempt.
	public function exception_throw(Exception $exception_arg = NULL)
	{
		// Use new exception object if
		// passed as an argument.
		if(is_object($exception_arg))
		{
			$this->exception = $exception_arg;
		}
		
		$exception 		= $this->exception;
		$exempt_codes	= $this->config->get_exempt_codes_throw();
		$code			= $exception->getCode();
		
		// Verify code's exempt status.
		$is_exempt = $this->is_exempt($exempt_codes, $code);
		
		if(!$is_exempt)
		{
			throw $exception;
		}
		
		return $exception;
	}
}

?>