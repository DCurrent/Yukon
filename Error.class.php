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
	public function detect_error($type = SQLSRV_ERR_ALL)
	{
		$ignore_list	= NULL;	// Collection of errors to ignore.
		$errors			= NULL;	// Collection of errors.
		$error 			= NULL;	// Element of errors - Collection of error attributes.
		$result			= NULL;	// Final result output.
		
		// Get any errors.
		$this->errors = sqlsrv_errors($type);
		
		// If any errors are present, then
		// loop errors collection - we want to make
		// sure the error is one we care about.
		if(is_array($this->errors))
		{	
			// Error array loop.
			foreach($this->errors as $error)
			{
				$result = TRUE;
				
				$ignore_list = $this->config->get_ignore_codes();

				// Verify list object.
				if(is_object($ignore_list))
				{
					// Rewind list.
					$ignore_list->rewind();
					
					// Compare error code to items in ignore
					// list until a match is found or we
					// get to end of ignore list.
					while ($ignore_list->valid()
						  && $result)
					{
						// If current ignore list item matches
						// current error code, then mark this
						// found false. We can ignore this
						// error and move on to the next.
						if($error['code'] == $ignore_list->current())
						{
							$result = FALSE;
						}
						
						$ignore_list->next();
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
	
	// Internal exception catch. Trigger an error and log the
	// thrown exception.
	public function exception_catch(\Exception $exception = NULL, $severity = E_ERROR)
	{		
		if($this->config->get_exception_catch() == TRUE)
		{			
			log_write($this->errors);
			
			throw new \ErrorException($exception->getMessage(), $exception->getCode(), $severity, $exception->getFile(), $exception->getLine());
		}
	}
	
	// Send data to log.
	private function log_write($data, $file_name = ERROR_LOG_FILE) 
	{
		$result			= FALSE;	// Output result.
		$file_handle	= NULL; 	// Target file reference.
		
		$file_handle = fopen($file_name, 'a+');
		
		// If $data is an array,
		// break it down into
		// human readable text.
		if(is_array($data)) 
		{
			$data = print_r($data, 1);
		}
		
		// Attempt to write and get result.
		$status = fwrite($file_handle, $data);
		
		// Close the file.
		fclose($file_handle);
		
		// Return result of write
		// attempt.
		if($status)
		{
			$result = TRUE;	
		}
		
		return $status;
	}	
}

?>