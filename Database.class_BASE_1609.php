<?php

namespace dc\yukon;

require_once('config.php');

// Query object. Execute SQL queries and return data.
interface iDatabase
{	
	// Accessors
	function get_config();							// Return config object.
	function get_connection();						// Return connection object.
	function get_line_config();						// Return line parameters object.
	function get_param_array();						// Return query parameter array.
	function get_sql();								// Return current SQl statement.
	function get_statement();						// Return query statement data member.
	
	// Mutators
	function set_config(DatabaseConfig $value);		// Set the object to be used for query config settings.
	function set_connection(Connect $value);		// Set connection data member.
	function set_line_config(LineConfig $value);	// Set line parameters object.
	function set_param_array(array $value);			// Set query sql parameter array data member.
	function set_sql($value);						// Set query sql string data member.
	function set_statement($value);					// Set query statement reference.
	
	// Request
	function free_statement();						// Free statement and clear statement member.
	function query_execute();						// Execute prepared query with current parameters.
	function query_prepare();						// Prepare query. Returns statement reference and sends to data member.
	function query_run();							// Prepare and execute query.
	
	// Results
	function get_field_count();						// Return number of fields from query result.
	function get_field_metadata();					// Fetch and return table row's metadata array (column names, types, etc.).
	function get_line_array();						// Fetch line array from table rows.
	function get_line_array_all();					// Create and return a 2D array consisting of all line arrays from database query.
	function get_line_array_list(); 				// Create and return a linked list consisting of all line arrays from database query.
	function get_line_object();						// Fetch and return line object from table rows.
	function get_line_object_all();					// Create and return a 2D array consisting of all line arrays from database query.
	function get_line_object_list(); 				// Create and return a linked list consisting of all line objects from database query.
	function get_next_result();						// Move to and return next result set.
	function get_row_count();						// Return number of records from query result.
	function get_row_exists();						// Verify the result contains rows.
}

class Database implements iDatabase
{
	private $config			= NULL;		// Query config object.
	private	$connect		= NULL;		// DB connection object.
	private	$line_config	= NULL;		// Line get config.
	private	$params 		= array();	// SQL parameters.
	private	$sql			= NULL;		// SQL string.
	private	$statement		= NULL;		// Prepared/Executed query reference.
	
	// Magic
	public function __construct(Connect $connect = NULL, DatabaseConfig $config = NULL, LineConfig $line_config = NULL)
	{
		// Set up memeber objects we'll need. In most cases,
		// if an argument is NULL, a blank object will
		// be created and used. See individual methods
		// for details.
		$this->construct_connection($connect);
		$this->construct_config($config);
		$this->construct_line_parameters($line_config);	
	}
	
	public function __destruct()
	{		
	}
	
	// *Constructors
	private function construct_connection(Connect $value = NULL)
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
			$result = new Connect();		
		}
		
		// Populate member with result.
		$this->connect = $result;
	
		return $result;		
	}
	
	private function construct_config(DatabaseConfig $value = NULL)
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
			$result = new DatabaseConfig();		
		}
		
		// Populate member with result.
		$this->config = $result;
	
		return $result;		
	}
	
	private function construct_line_parameters(LineConfig $value = NULL)
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
			$result = new LineConfig();		
		}
		
		// Populate member with result.
		$this->line_config = $result;
	
		return $result;		
	}
	
	
	// *Accessors
	public function get_config()
	{
		return $this->config;
	}
	
	public function get_connection()
	{
		return $this->connect;
	}
	
	public function get_error()
	{
		return $this->error;	
	}
		
	public function get_line_config()
	{
		return $this->line_config;
	}
	
	public function get_param_array()
	{
		return $this->params;	
	}
	
	public function get_statement()
	{
		return $this->statement;
	}
	
	// *Mutators
	public function get_sql()
	{
		return $this->sql;
	}
	
	// Set query sql parameters data member.
	public function set_param_array(array $value)
	{		
		$this->params = $value;
	}
	
	public function set_config(DatabaseConfig $value)
	{
		$this->config = $value;
	}
	
	public function set_connection(Connect $value)
	{
		$this->connect = $value;
	}
	
	public function set_error(Error $value)
	{
		$this->error = $value;
	}
			
	public function set_line_config(LineConfig $value)
	{
		$this->line_config = $value;
	}
	
	public function set_sql($value)
	{
		$this->sql = $value;
	}
	
	public function set_statement($value)
	{
		$this->statement = $value;
	}
	
	
	// *Request
	// Free statement and clear statement member.
	public function free_statement()
	{
		$result;
		$error_handler 	= $this->config->get_error();
		
		try 
		{
			// Verify statement.
			if(!$this->statement)
			{				
				$error->exception_throw(new Exception(EXCEPTION_MSG::FREE_STATEMENT_STATEMENT, EXCEPTION_CODE::FREE_STATEMENT_STATEMENT));				
			}
			
			// Attempt to free statement.
			$result = sqlsrv_free_stmt($this->statement);
			unset($this->statement);
			
			// Any errors?
			if($error_handler->detect_error())
			{
				$error->exception_throw(new Exception(EXCEPTION_MSG::FREE_STATEMENT_ERROR, EXCEPTION_CODE::FREE_STATEMENT_ERROR));
			}
			
			// False/Failure returned.
			if(!$result)
			{				
				$error->exception_throw(new Exception(EXCEPTION_MSG::FREE_STATEMENT_FAIL, EXCEPTION_CODE::FREE_STATEMENT_FAIL));
			}			
		}
		catch (Exception $exception) 
		{
			// Catch exception internally if configured to do so.
			$error->exception_catch();
		}
	}
	
	// Execute prepared query with current parameters.
	public function query_execute()
	{
		$result;
		$error_handler 	= $this->config->get_error();
		
		try 
		{
			// Verify statement.
			if(!$this->statement)
			{				
				$error->exception_throw(new Exception(EXCEPTION_MSG::QUERY_EXECUTE_STATEMENT, EXCEPTION_CODE::QUERY_EXECUTE_STATEMENT));				
			}
			
			// Execute prepared query.
			$result = sqlsrv_execute($this->statement);
			
			// Any errors?
			if($error_handler->detect_error())
			{
				$error->exception_throw(new Exception(EXCEPTION_MSG::QUERY_EXECUTE_ERROR, EXCEPTION_CODE::QUERY_EXECUTE_ERROR));
			}
			
			// False/Failure returned.
			if(!$result)
			{				
				$error->exception_throw(new Exception(EXCEPTION_MSG::QUERY_EXECUTE_FAIL, EXCEPTION_CODE::QUERY_EXECUTE_FAIL));
			}			
		}
		catch (Exception $exception) 
		{
			// Catch exception internally if configured to do so.
			$error->exception_catch();
		}
				
		return $result;	
	}
	
	// Prepare query. Returns statement reference and updates data member.
	public function query_prepare()
	{
		$connect	= NULL;		// Database connection reference.
		$statement	= NULL;		// Database statement reference.			
		$sql		= NULL;		// SQL string.
		$params		= array(); 	// Parameter array.
		$config		= NULL;		// Query config object.
		$config_a	= array();	// Query config array.
		
		// Dereference data members.
		$connect	= $this->connect->get_connection();
		$sql 		= $this->sql;
		$params 	= $this->params;
		$config		= $this->config;
	
		// Break down config object to array.
		if($config)
		{
			$config_a['Scrollable'] 			= $config->get_scrollable();
			$config_a['SendStreamParamsAtExec']	= $config->get_sendstream();
			$config_a['QueryTimeout'] 			= $config->get_timeout();
		}
	
		// Prepare query		
		$statement = sqlsrv_prepare($connect, $sql, $params, $config_a);
		
		// Set DB statement data member.
		$this->statement = $statement;
		
		// Return statement reference.
		return $statement;		
	}
	
	// Prepare and execute query.
	public function query_run()
	{
		$connect	= NULL;		// Database connection reference.
		$statement	= NULL;		// Database result reference.			
		$sql		= NULL;		// SQL string.
		$params 	= array(); 	// Parameter array.
		$config	= NULL;		// Query config object.
		$config_a	= array();	// Query config array.
				
		// Dereference data members.
		$connect 	= $this->connect->get_connection();
		$sql 		= $this->sql;
		$params 	= $this->params;
		$config 	= $this->config;
	
		// Break down config object to array.
		if($config)
		{
			$config_a['Scrollable'] 		= $config->get_scrollable();
			$config_a['SendStreamParamsAtExec']	= $config->get_sendstream();
			$config_a['QueryTimeout'] 		= $config->get_timeout();
		}
	
		// Execute query.
		$statement = sqlsrv_query($connect, $sql, $params, $config_a);
		
		// Set data member.
		$this->statement = $statement;
		
		// Return query ID resource.
		return $statement;
	}
	
	// *Results.
	public function get_field_count()
	{
		$error_handler 	= $this->config->get_error();
		$result			= 0;
		
		try 
		{
			// Missing statement?
			if(!$this->statement)
			{
				throw new Exception(EXCEPTION_MSG::FIELD_COUNT_STATEMENT, EXCEPTION_CODE::FIELD_COUNT_STATEMENT);
			}
			
			// Get field count.
			$result = sqlsrv_num_fields($this->statement);
			
			// Any errors?
			if($error_handler->detect_error())
			{
				throw new Exception(EXCEPTION_MSG::FIELD_COUNT_ERROR, EXCEPTION_CODE::FIELD_COUNT_ERROR);
			}
			
		}
		catch (Exception $exception) 
		{	
			$error_handler->exception_catch($exception);
		}
		
		// Return field count.
		return $result;
	}
	
	// Fetch and return table row's metadata array (column names, types, etc.).
	public function get_field_metadata()
	{
		$result = array();
		
		try 
		{
			// Missing statement?
			if(!$this->statement)
			{
				throw new Exception(EXCEPTION_MSG::METADATA_STATEMENT, EXCEPTION_CODE::METADATA_STATEMENT);
			}
			
			// Get metadata array.
			$result = sqlsrv_field_metadata($this->statement);
			
			// Any errors?
			if($error_handler->detect_error())
			{
				throw new Exception(EXCEPTION_MSG::METADATA_ERROR, EXCEPTION_CODE::METADATA_ERROR);
			}
			
		}
		catch (Exception $exception) 
		{	
			$error_handler->exception_catch($exception);
		}
		
		// Return metadata array.
		return $result;
	}
	
	// Fetch line array from table rows.
	public function get_line_array()
	{
		$line		= FALSE;	// Database line array.
		$statement	= NULL; 	// Query result reference.
		$fetchType	= NULL;		// Line array fetchtype.
		$row		= NULL;		// Row type.
		$offset		= NULL;		// Row position if absolute.
		
		// Dereference data members.
		$statement 	= $this->statement;
		$fetchType	= $this->line_config->get_fetchtype();
		$row		= $this->line_config->get_row();
		$offset		= $this->line_config->get_offset();		
								
		// Get line array.
		$line = sqlsrv_fetch_array($statement, $fetchType, $row, $offset);

		
		// Return line array.
		return $line;
	}
	
	// Create and return a 2D array consisting of all line arrays from database query.
	public function get_line_array_all()
	{
		$line_array	= FALSE;	// 2D array of all line arrays.
		$line		= NULL;		// Database line array.
				
		// Loop all rows from database results.
		while($line = $this->get_line_array())
		{				
			// Add line array to 2D array of lines.
			$line_array[] = $line;				
		}		
		
		// Return line array.
		return $line_array;
	}	
	
	// Create and return a linked list consisting of all line elements from database query.
	public function get_line_array_list()
	{		
		$result = new SplDoublyLinkedList();	// Linked list object.		
		$line	= NULL;				// Database line array.
		
		// Loop all rows from database results.
		while($line = $this->get_line_array())
		{				
			// Add line array to list of arrays.
			$result->push($line);
		}
	
		// Return results.
		return $result;
	}
	
	// Fetch and return line object from table rows.
	public function get_line_object()
	{
		$line		= NULL;		// Database line object.
		$statement	= NULL;		// Query result reference.
		$fetchType	= NULL;		// Line array fetchtype.
		$row		= NULL;		// Row type.
		$offset		= NULL;		// Row position if absolute.
		$class_name	= NULL;		// Class name.
		$class_params	= array();	// Class parameter array.
		
		// Dereference data members.
		$statement 	= $this->statement;
		$fetchType	= $this->line_config->get_fetchtype();
		$row		= $this->line_config->get_row();
		$offset		= $this->line_config->get_offset();
		$class		= $this->line_config->get_class_name();
		$class_params	= $this->line_config->get_class_params();
				
		// Get line object.
		$line = sqlsrv_fetch_object($statement, $class, $class_params, $row, $offset);
			
		// Return line object.
		return $line;
	}
	
	// Create and return an array consisting of all line objects from database query.
	public function get_line_object_all()
	{
		$line_array	= array();	// 2D array of all line objects.
		$line		= NULL;		// Database line objects.
		
		// Loop all rows from database results.
		while($line = $this->get_line_object())
		{				
			// Add line object to array of object.
			$line_array[] = $line;
		}
	
		// Return line object.
		return $line_array;
	}
	
	// Create and return a linked list consisting of all line objects from database query.
	public function get_line_object_list()
	{
		$result = new \SplDoublyLinkedList();	// Linked list object.	
		$line	= NULL;				// Database line objects.
		
		// Loop all rows from database results.
		while($line = $this->get_line_object())
		{				
			// Add line object to linked list.
			$result->push($line);
		}
	
		// Return linked list object.
		return $result;
	}
	
	// Move to and return next result set.
	public function get_next_result()
	{
		$result = FALSE;
		
		$result = sqlsrv_next_result($this->statement);
		
		return $result;
	
	}
	
	// Return number of records from query result.	
	public function get_row_count()
	{
		$count = 0;
		
		// Get row count.
		$count = sqlsrv_num_rows($this->statement);	
		
		// Return count.
		return $count;
	}
	
	// Verify result set contains any rows.	
	public function get_row_exists()
	{
		$result = FALSE;
		
		// Get row count.
		$result = sqlsrv_has_rows($this->statement);	
		
		// Return result.
		return $result;
	}
	
}

?>
