<?php

namespace dc\yukon;

require_once('config.php');

// Common warning codes:
	// 0:		Cursor type changed.
	// 5701:	Changed database context.
	// 5703:	Changed language setting.

// Legacy error handling.
interface iError
{
	// Accessors
	function get_config();
	function get_exception();
	
	// Mutators
	function set_config(ErrorConfig $value);
	function set_exception(\Exception $value);
	
	function detect_error();
	function exception_catch();
	function is_exempt(\SplDoublyLinkedList $list, $target = NULL);
	function is_exempt_throw($target = NULL);
	
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
	
	// Mutators.
	public function set_config(ErrorConfig $value)
	{
		$this->confg = $value;
	}		
	
	public function set_exception(\Exception $value)
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
			
		// Get any errors.
		$this->errors = sqlsrv_errors(SQLSRV_ERR_ALL);
		
		var_dump($this->errors);
		
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
				if(!$this->is_exempt($exempt_codes, $error['code']))
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
	public function is_exempt(\SplDoublyLinkedList $list, $target = NULL)
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
			while ($list->valid()
				  && !$result)
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

		// If we have a true result, then 
		// there's no point doing anything
		// else as we've found an error that
		// is not exempt.
		if($result)
		{
			return $result;
		}
		
		// If we made it this far, then
		// we can return the results (false).
		return $result;
	}
	
	// Internal exception catch. Trigger an error and log the
	// thrown exception.
	public function exception_catch($severity = E_ERROR)
	{	
		$exception = $this->exception;
		
		// If this code is not on the exempt
		// list for local catching, then 
		// throw an error exception. This is our last
		// chance to catch the error before PHP engine
		// picks it up and more often than not throws 
		// a relativity useless error code.
		if(!$this->is_exempt($this->config->get_exempt_codes_catch(), $exception->getCode()))
		{
			throw new \ErrorException($exception->getMessage(), $exception->getCode(), $severity, $exception->getFile(), $exception->getLine());
		}
			
	}
	
	// Throw an exception if the code is
	// not exempt.
	public function exception_throw(\Exception $exception = NULL)
	{
		if(is_object($exception))
		{
			$this->exception = $exception;
		}
		
		if(!$this->is_exempt($this->config->get_exempt_codes_throw(), $this->exception->getCode()))
		{
			throw $this->exception;
		}
	}
}

?>