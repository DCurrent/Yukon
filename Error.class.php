<?php

namespace dc\yukon;

require_once('config.php');

// Common warning codes:
	// 0:		Cursor type changed.
	// 5701:	Changed database context.
	// 5703:	Changed language setting.

<<<<<<< HEAD
// Legacy error handling.
=======
<<<<<<< HEAD
// Legacy error handling.
=======
// Error handling. This is a very crude error 
// trapping scheme put in place for development
// debugging. Any errors result in a general
// exception thrown and massive log dump.
>>>>>>> origin/master
>>>>>>> origin/master
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
	
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> origin/master
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
<<<<<<< HEAD
=======
=======
	// Detect database errors, process and send to error handling.
	public function error()
	{		
		$errors 	= NULL;		// Errors list array.
		$error 		= array();	// Individual error output array.
		$details	= NULL; 	// Error detail string.
		$result 	= NULL;		// Final result (FALSE = No Errors, TRUE = Erros).
>>>>>>> origin/master
>>>>>>> origin/master
		
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
		
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> origin/master
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
<<<<<<< HEAD
				{
					// Rewind list.
					$ignore_list->rewind();
					
					// Compare error code to items in ignore
					// list until a match is found or we
					// get to end of ignore list.
					while ($ignore_list->valid()
						  && $result)
=======
				{
					// Rewind list.
					$ignore_list->rewind();
					
					// Compare error code to items in ignore
					// list until a match is found or we
					// get to end of ignore list.
					while ($ignore_list->valid()
						  && $result)
=======
		// Any errors found?
		if($errors)					
		{			
			// Loop through error collection. 
			foreach($errors as $error)
			{				
				// Ignore cursor Type Change.
				if($error['code'] != 0)
				{
					// If requested, send a detailed report to log. PHP and MSSQL generally 
					// do not provide useful default database errors, so this information can 
					// be invaluable for debugging.
					if(\dc\yukon\DEFAULTS::DETAILS === TRUE && $error['code'] != 0)
					{			
						// Concatenate start of detail string.
						$details = 	'Database errors:'.PHP_EOL;		
						$details .= 	' SQLSTATE: '.$error['SQLSTATE'].PHP_EOL;
						$details .= 	' Code: '.$error['code'].PHP_EOL;
						$details .= 	' Message: '.$error['message'].PHP_EOL;
						$details .= 	' Dump: '.PHP_EOL;
						$details .= 	$this->var_dump_ret($this->obj_query);
						
						// Send details to log.
						$this->error_log_send($details);
					}
														
					// Catch and document the exception.								
					try 
>>>>>>> origin/master
>>>>>>> origin/master
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
