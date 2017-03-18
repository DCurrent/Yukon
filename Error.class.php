<?php

namespace dc\yukon;

require_once('config.php');

// Common warning codes:
	// 0:		Cursor type changed.
	// 5701:	Changed database context.
	// 5703:	Changed language setting. 

class Error implements iError
{
	private 
		$obj_query = NULL;
	
	public function __construct(Database $obj_query = NULL)
	{
		if($obj_query)
		{
			$this->obj_query = $obj_query;	
		}
	}
	
	// Detect database errors, process and send to error handling.
	public function error()
	{		
		/*
		error
		Damon Vaughn Caskey
		2014-04-07		
		*/
		
		$errors 	= NULL;		// Errors list array.
		$error 		= array();	// Individual error output array.
		$details	= NULL; 	// Error detail string.
		$result 	= NULL;		// Final result (FALSE = No Errors, TRUE = Erros).
		
		// Exit if error trapping is turned off.
		if (\dc\yukon\DEFAULTS::TRAP === FALSE) return;
		
		// Get error collection.
		$errors = sqlsrv_errors();		
		
		// Any errors found?
		if($errors)					
		{			
			// Loop through error collection. 
			foreach($errors as $error)
			{					
				// If requested, send a detailed report to log. PHP and MSSQL generally 
				// do not provide useful default database errors, so this information can 
				// be invaluable for debugging.
				
				// Ignore cursor Type Change.
				if($error['code'] != 0)
				{
				
					if(\dc\yukon\DEFAULTS::DETAILS === TRUE && $error['code'] != 0)
					{			
						// Concatenate start of detail string.
						$details = 'Database errors:'.PHP_EOL;		
						$details .= ' SQLSTATE: '.$error['SQLSTATE'].PHP_EOL;
						$details .= ' Code: '.$error['code'].PHP_EOL;
						$details .= ' Message: '.$error['message'].PHP_EOL;
						$details .= ' Dump: '.PHP_EOL;
						$details .= $this->var_dump_ret($this->obj_query);
						
						// Send details to log.
						$this->error_log_send($details);
					}
														
					// Catch and document the exception.								
					try 
					{
						// Throw an exception for this error.
						throw new \Exception('Database Error(s)');
					}
					catch (\Exception $e) 
					{	
						// Fire error event.			
						//trigger_error(date(DATE_ATOM).', '.$e->getMessage().' @function '.__METHOD__.'. See log.', E_USER_ERROR);
						trigger_error(date(DATE_ATOM).', '.$e->getMessage().', see log.', E_USER_ERROR);
					}
					
					$result = TRUE;				
				}
			}			
		}
		
		return $result;				
	}
	
	// Send error string to log in multiple entries to bypass string size limit.
	private function error_log_send($entry)
	{
		$parts = array();	// Split string fragments.
		
		// Break string down into smaller parts.
		$parts = str_split($entry, \dc\yukon\DEFAULTS::MAXLENGTH);
		
		// Send each smaller part to log as a new entry.
		foreach($parts as $part)
		{
			error_log("-- ".$part." --");
		}
	}
	
	// Perform a var dump on passed array, object, etc., 
	// captures the dump output, and returns.
	private function var_dump_ret($mixed = null) 
	{
	  ob_start();
	  var_dump($mixed);
	  $content = ob_get_contents();
	  ob_end_clean();
	  return $content;
	}
	
}

?>