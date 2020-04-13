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
	private $config = NULL;
	private $errors	= NULL;
	private $exception = NULL;
	
	public function __construct(ErrorConfig $config = NULL)
	{	
		$this->config = $this->construct_config($config);
	}
	
	// Accessors.
	public function get_config()
	{
		return $this->config;
	}
	
	public function set_config(ErrorConfig $value)
	{
		$this->config = $value;
	}
	
	public function get_exception()
	{
		return $this->exception;
	}
	
	public function set_exception(Exception $value)
	{
		$this->exception = $value;
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
		// If this code is not on the exempt
		// list for local catching, then 
		// throw an error ourselves. This is our last
		// chance to catch our issue before it passes
		// through to PHP engine.
		
		$is_exempt = $this->is_exempt($this->config->get_exempt_codes_catch(), $this->exception->getCode());
		
		if(!$is_exempt)
		{
			// Handle common errors here. If it's something
			// else, then send it on to the general error
			// catch code.
			
			switch($this->exception->getCode())
			{
				case EXCEPTION_CODE::CONNECT_CLOSE_CONNECTION:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::CONNECT_CLOSE_CONNECTION.": </b>");
					echo(EXCEPTION_MSG::CONNECT_CLOSE_CONNECTION);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::CONNECT_CLOSE_FAIL:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::CONNECT_CLOSE_FAIL.": </b>");
					echo(EXCEPTION_MSG::CONNECT_CLOSE_FAIL);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::CONNECT_OPEN_FAIL:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::CONNECT_OPEN_FAIL.": </b>");
					echo(EXCEPTION_MSG::CONNECT_OPEN_FAIL);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::CONNECT_OPEN_HOST:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::CONNECT_OPEN_HOST.": </b>");
					echo(EXCEPTION_MSG::CONNECT_OPEN_HOST);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::FREE_STATEMENT_ERROR:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::FREE_STATEMENT_ERROR.": </b>");
					echo(EXCEPTION_MSG::FREE_STATEMENT_ERROR);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::FREE_STATEMENT_FAIL:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::FREE_STATEMENT_FAIL.": </b>");
					echo(EXCEPTION_MSG::FREE_STATEMENT_FAIL);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::FREE_STATEMENT_STATEMENT:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::FREE_STATEMENT_STATEMENT.": </b>");
					echo(EXCEPTION_MSG::FREE_STATEMENT_STATEMENT);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::LINE_ARRAY_FAIL:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::LINE_ARRAY_FAIL.": </b>");
					echo(EXCEPTION_MSG::LINE_ARRAY_FAIL);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::LINE_ARRAY_STATEMENT:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::LINE_ARRAY_STATEMENT.": </b>");
					echo(EXCEPTION_MSG::LINE_ARRAY_STATEMENT);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::LINE_OBJECT_FAIL:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::LINE_OBJECT_FAIL.": </b>");
					echo(EXCEPTION_MSG::LINE_OBJECT_FAIL);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::LINE_OBJECT_STATEMENT:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::LINE_OBJECT_STATEMENT.": </b>");
					echo(EXCEPTION_MSG::LINE_OBJECT_STATEMENT);
					echo('<br />');
					
					break;
				
				case EXCEPTION_CODE::QUERY_DIRECT_CONNECTION:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::QUERY_DIRECT_CONNECTION.": </b>");
					echo(EXCEPTION_MSG::QUERY_DIRECT_CONNECTION);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::QUERY_DIRECT_SQL:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::QUERY_DIRECT_SQL.": </b>");
					echo(EXCEPTION_MSG::QUERY_DIRECT_SQL);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::QUERY_DIRECT_PARAM_LIST:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::QUERY_DIRECT_PARAM_LIST.": </b>");
					echo(EXCEPTION_MSG::QUERY_DIRECT_PARAM_LIST);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::QUERY_DIRECT_CONFIG:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::QUERY_DIRECT_CONFIG.": </b>");
					echo(EXCEPTION_MSG::QUERY_DIRECT_CONFIG);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::QUERY_DIRECT_STATEMENT:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::QUERY_DIRECT_STATEMENT.": </b>");
					echo(EXCEPTION_MSG::QUERY_DIRECT_STATEMENT);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::QUERY_EXECUTE_ERROR:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::QUERY_EXECUTE_ERROR.": </b>");
					echo(EXCEPTION_MSG::QUERY_EXECUTE_ERROR);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::QUERY_EXECUTE_FAIL:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::QUERY_EXECUTE_FAIL.": </b>");
					echo(EXCEPTION_MSG::QUERY_EXECUTE_FAIL);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::QUERY_EXECUTE_STATEMENT:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::QUERY_EXECUTE_STATEMENT.": </b>");
					echo(EXCEPTION_MSG::QUERY_EXECUTE_STATEMENT);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::QUERY_PREPARE_CONNECTION:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::QUERY_PREPARE_CONNECTION.": </b>");
					echo(EXCEPTION_MSG::QUERY_PREPARE_CONNECTION);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::QUERY_PREPARE_SQL:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::QUERY_PREPARE_SQL.": </b>");
					echo(EXCEPTION_MSG::QUERY_PREPARE_SQL);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::QUERY_PREPARE_PARAM_LIST:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::QUERY_PREPARE_PARAM_LIST.": </b>");
					echo(EXCEPTION_MSG::QUERY_PREPARE_PARAM_LIST);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::QUERY_PREPARE_CONFIG:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::QUERY_PREPARE_CONFIG.": </b>");
					echo(EXCEPTION_MSG::QUERY_PREPARE_CONFIG);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::QUERY_PREPARE_STATEMENT:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::QUERY_PREPARE_STATEMENT.": </b>");
					echo(EXCEPTION_MSG::QUERY_PREPARE_STATEMENT);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::ROW_COUNT_FAIL:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::ROW_COUNT_FAIL.": </b>");
					echo(EXCEPTION_MSG::ROW_COUNT_FAIL);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::ROW_COUNT_STATEMENT:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::ROW_COUNT_STATEMENT.": </b>");
					echo(EXCEPTION_MSG::ROW_COUNT_STATEMENT);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::ROW_VERIFY_FAIL:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::ROW_VERIFY_FAIL.": </b>");
					echo(EXCEPTION_MSG::ROW_VERIFY_FAIL);
					echo('<br />');
					
					break;
					
				case EXCEPTION_CODE::ROW_VERIFY_STATEMENT:
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::ROW_VERIFY_STATEMENT.": </b>");
					echo(EXCEPTION_MSG::ROW_VERIFY_STATEMENT);
					echo('<br />');
					
					break;
					
				default:					
					
					echo('<br />');
					echo('<b>'.LIBRARY::NAME.' error code '.EXCEPTION_CODE::UNKNOWN.": </b>");
					echo(EXCEPTION_MSG::UNKNOWN);
					echo('<br />');
					
					// Since we don't know what happened, let's
					// kill execution just to be safe.
					die();
					
					break;
			}			
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
		
		// Verify code's exempt status.
		$is_exempt = $this->is_exempt($this->config->get_exempt_codes_throw(), $this->exception->getCode());
		
		if(!$is_exempt)
		{
			throw $this->exception;
		}
		
		return $this->exception;
	}
}

?>