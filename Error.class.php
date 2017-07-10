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
	
	// Mutators
	function set_config(ErrorConfig $value);
	function detect_error();
	function exception_catch();
	function verify_exception_throw($exception_code);
}

class Error implements iError
{
	private 
		$config = NULL,
		$errors	= NULL;
	
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
		$exempt_driver_codes	= NULL;	// Collection of errors to ignore.
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
			// Error array loop.
			foreach($this->errors as $error)
			{
				$result = TRUE;
				
				$exempt_driver_codes = $this->config->get_exempt_driver_codes();

				// Verify list object.
				if(is_object($exempt_driver_codes))
				{
					// Rewind list.
					$exempt_driver_codes->rewind();
					
					// Compare error code to items in ignore
					// list until a match is found or we
					// get to end of ignore list.
					while ($exempt_driver_codes->valid()
						  && $result)
					{
						// If current ignore list item matches
						// current error code, then mark this
						// found false. We can ignore this
						// error and move on to the next.
						if($error['code'] == $exempt_driver_codes->current())
						{
							$result = FALSE;
						}
						
						$exempt_driver_codes->next();
					}
				}
				
				// If we have a true result, then 
				// there's no point doing anything
				// else as we've found an error that
				// is not in the ignore list.
				if($result)
				{
					return $result;
				}
			}		
		}		
		
		// If we made it this far, then
		// we can return the results (false).
		return $result;		
	}
	
	// Verify we are throwing exceptions, and that
	// the exception code we wan
	public function verify_exception_throw($exception_code = NULL)
	{
		$exempt_exception_codes	= NULL;	// Collection of exceptions to ignore.
		$result					= NULL;	// Final result output.
		
		$exempt_exception_codes = $this->config->get_exempt_exception_codes();

		// Verify list object.
		if(is_object($exempt_exception_codes))
		{
			// Rewind list.
			$exempt_exception_codes->rewind();
			$current = NULL;	// Current list value.
			
			// Compare error code to items in ignore
			// list until a match is found or we
			// get to end of ignore list.
			while ($exempt_exception_codes->valid()
				  && $result)
			{
				// Get current value.
				$exempt_exception_codes->current();
				
				// If current ignore list item matches the
				// exception code we are looking for, then
				// this is an exempt exception. Also, should
				// ANY item = ALL, then we return false 
				// immediately regardless.
				if($exception_code == $current
				  || $current == EXCEPTION_CODE::ALL)
				{
					$result = FALSE;
				}

				$exempt_exception_codes->next();
			}
		}

		// If we have a true result, then 
		// there's no point doing anything
		// else as we've found an error that
		// is not in the ignore list.
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
	public function exception_catch(\Exception $exception = NULL, $severity = E_ERROR)
	{		
		if($this->config->get_exception_catch() == TRUE)
		{			
			throw new \ErrorException($exception->getMessage(), $exception->getCode(), $severity, $exception->getFile(), $exception->getLine());
		}
	}
}

?>