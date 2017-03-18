<?php

namespace dc\yukon;

require_once('config.php');

class Database implements iDatabase
{
	private 
		$sql_m			= NULL,		// SQL string.
		$params_m 		= array(),	// SQL parameters.
		$options_m		= NULL,		// Query options object.
		$statement_m	= NULL,		// Prepared/Executed query reference.
		$line_params_m	= NULL,		// Line get options.
		$connect_m		= NULL,		// DB connection object.
		$error_m		= NULL;		// Error handler.
	
	public function __construct(Connect $connect = NULL, DatabaseConfig $options = NULL, LineConfig $line_params = NULL)
	{
		// Initialize error handler.
		$this->error_m = new Error($this);
				
		// Set up memeber objects we'll need. In most cases,
		// if an argument is NULL, a blank object will
		// be created and used. See individual methods
		// for details.
		$this->construct_connection($connect);
		$this->construct_options($options);
		$this->construct_line_parameters($line_params);	
	}
	
	public function __destruct()
	{		
	}
	
	// Accessors
	public function get_connection()
	{
		return $this->connect_m;
	}
	
	public function get_line_params()
	{
		return $this->line_params_m;
	}
	
	public function get_options()
	{
		return $this->options_m;
	}
	
	// Mutators	
	public function set_options(DatabaseConfig $value)
	{
		$this->options_m = $value;
	}
		
	public function set_line_params(LineConfig $value)
	{
		$this->line_params_m = $value;
	}
	
	public function set_connection(Connect $value)
	{
		$this->connect_m = $value;
	}
	
	// Populate connection member with argument if 
	// the argument is a valid connection object.
	// Otherwise populate with a new connection
	// object. 
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
		$this->connect_m = $result;
	
		return $result;		
	}
	
	private function construct_options(DatabaseConfig $value = NULL)
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
			$result = new \dc\yukon\DatabaseConfig();		
		}
		
		// Populate member with result.
		$this->options_m = $result;
	
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
		$this->line_params_m = $result;
	
		return $result;		
	}
	
	// Frees statement and clears associated member.
	public function free_statement()
	{
		// Free statement resources.
		if($this->statement_m)
		{
			sqlsrv_free_stmt($this->statement_m);
			unset($this->statement_m);
		}
	}
	
	// Return number of fields from query result.
	public function get_field_count()
	{
		/*
		field_count
		Damon Vaughn Caskey
		2014-04-05		
		*/
		
		$count = 0;
		
		// Get field count.
		$count = sqlsrv_num_fields($this->statement_m);
		
		// Error trapping.
		$this->error_m->error();
		
		// Return field count.
		return $count;
	}
	
	// Fetch and return table row's metadata array (column names, types, etc.).
	public function get_field_metadata()
	{
		/*
		db_field_metadata
		Damon Vaughn Caskey
		2014-04-06		
		*/
		
		$meta = array();
		
		// Get metadata array.
		$meta = sqlsrv_field_metadata($this->statement_m);
		
		// Error trapping.
		$this->error_m->error();
		
		// Return metadata array.
		return $meta;
	}
	
	// Fetch line array from table rows.
	public function get_line_array()
	{
		/*
		line_array
		Damon Vaughn Caskey
		2014-04-06
		*/
		
		$line		= FALSE;	// Database line array.
		$statement	= NULL; 	// Query result reference.
		$fetchType	= NULL;		// Line array fetchtype.
		$row		= NULL;		// Row type.
		$offset		= NULL;		// Row position if absolute.
		
		// Dereference data members.
		$statement 	= $this->statement_m;
		$fetchType	= $this->line_params_m->get_fetchtype();
		$row		= $this->line_params_m->get_row();
		$offset		= $this->line_params_m->get_offset();		
		
		// Valid statement and rows found?
		//if($statement && $query->get_row_exists())
		//{				
			// Get line array.
			$line = sqlsrv_fetch_array($statement, $fetchType, $row, $offset);
		//}
		
		// Error trapping.
		$this->error_m->error();
		
		// Return line array.
		return $line;
	}
	
	// Create and return a 2D array consisting of all line arrays from database query.
	public function get_line_array_all()
	{
		/*
		line_array_all
		Damon Vaughn Caskey
		2014-04-06
		*/
	
		$line_array	= FALSE;	// 2D array of all line arrays.
		$line		= NULL;		// Database line array.
				
		// Loop all rows from database results.
		while($line = $this->get_line_array())
		{	
			// Error trapping.
			$this->error_m->error();
				
			// Add line array to 2D array of lines.
			$line_array[] = $line;				
		}		
		
		// Return line array.
		return $line_array;
	}	
	
	// Create and return a linked list consisting of all line objects from database query.
	public function get_line_array_list()
	{
		/*
		line_array_list
		Damon Vaughn Caskey
		2015-06-15
		*/
		
		$result = new SplDoublyLinkedList();		
		$line	= NULL;		// Database line array.
		
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
		/*
		line_object
		Damon Vaughn Caskey
		2014-04-06
		*/
		
		$line			= NULL;		// Database line object.
		$statement		= NULL;		// Query result reference.
		$fetchType		= NULL;		// Line array fetchtype.
		$row			= NULL;		// Row type.
		$offset			= NULL;		// Row position if absolute.
		$class_name		= NULL;		// Class name.
		$class_params	= array();	// Class parameter array.
		
		// Dereference data members.
		$statement 		= $this->statement_m;
		$fetchType		= $this->line_params_m->get_fetchtype();
		$row			= $this->line_params_m->get_row();
		$offset			= $this->line_params_m->get_offset();
		$class			= $this->line_params_m->get_class_name();
		$class_params	= $this->line_params_m->get_class_params();
		
		// Valid statement and rows exist?		
		//if($statement !== FALSE && $this->get_row_exists())
		//{	
		
										
					
		// Get line object.
		$line = sqlsrv_fetch_object($statement, $class, $class_params, $row, $offset);
	
			
			
					
			// Error trapping.
			$this->error_m->error();
		//}
		
		// Return line object.
		return $line;
	}
	
	// Create and return a 2D array consisting of all line arrays from database query.
	public function get_line_object_all()
	{
		/*
		line_object_all
		Damon Vaughn Caskey
		2014-04-06
		*/
		
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
		/*
		line_object_list
		Damon Vaughn Caskey
		2015-06-15
		*/
		
		$result = new \SplDoublyLinkedList();		
		$line	= NULL;		// Database line objects.
		
		// Loop all rows from database results.
		while($line = $this->get_line_object())
		{				
			// Add line object to list of object.
			$result->push($line);
		}
	
		// Return line object.
		return $result;
	}
	
	// Move to and return next result set.
	public function get_next_result()
	{
		$result = FALSE;
		
		$result = sqlsrv_next_result($this->statement_m);
	
		// Error trapping.
		$this->error_m->error();
		
		return $result;
	
	}
	
	// Execute prepared query with current parameters.
	public function execute()
	{
		/*
		db_execute
		Damon Vaughn Caskey
		2014-04-04
		*/
		
		$result     = FALSE;	// Result of execution.
		
		sqlsrv_execute($this->statement_m);
				
		// Error trapping.
		$this->error_m->error();
		
		return $result;	
	}
	
	// Prepare query. Returns statement reference and sends to data member.
	public function prepare()
	{
		/*
		db_prepare
		Damon Vaughn Caskey
		2012-11-13
		~2014-04-03
		*/
	
		$connect	= NULL;		// Database connection reference.
		$statement	= NULL;		// Database statement reference.			
		$sql		= NULL;		// SQL string.
		$params		= array(); 	// Parameter array.
		$options	= NULL;		// Query options object.
		$options_a	= array();	// Query options array.
		
		// Dereference data members.
		$connect = $this->connect_m->get_connection();
		$sql = $this->sql_m;
		$params = $this->params_m;
		$options = $this->options_m;
	
		// Break down options object to array.
		if($options)
		{
			$options_a['Scrollable'] = $options->get_scrollable();
			$options_a['SendStreamParamsAtExec'] = $options->get_sendstream();
			$options_a['QueryTimeout'] = $options->get_timeout();
		}
	
		// Prepare query		
		$statement = sqlsrv_prepare($connect, $sql, $params, $options_a);
		
		// Error trapping.
		$this->error_m->error();
		
		// Set DB statement data member.
		$this->statement_m = $statement;
		
		// Return statement reference.
		return $statement;		
	}
	
	// Set query sql string data member.
	public function set_sql($value)
	{
		$this->sql_m = $value;
	}
	
	// Return query sql string data member.
	public function get_sql()
	{
		return $this->sql_m;
	}
	
	// Set query sql parameters data member.
	public function set_params(array $value)
	{		
		$this->params_m = $value;
	}
	
	// Return number of records from query result.	
	public function get_row_count()
	{
		/*
		row_count
		Damon Vaughn Caskey
		2014-04-06	
		*/
		
		$count = 0;
		
		// Get row count.
		$count = sqlsrv_num_rows($this->statement_m);	
		
		// Error trapping.
		$this->error_m->error();	
		
		// Return count.
		return $count;
	}
	
	// Verify result set contains any rows.	
	public function get_row_exists()
	{
		/*
		row_exists
		Damon Vaughn Caskey
		2014-08-08	
		*/
		
		$result = FALSE;
		
		// Get row count.
		$result = sqlsrv_has_rows($this->statement_m);	
		
		// Error trapping.
		$this->error_m->error();	
		
		// Return result.
		return $result;
	}
	
	// Prepare and execute query.
	public function query()
	{
		/*
		query
		Damon Vaughn Caskey
		2014-11-13
		*/
		
		$connect	= NULL;		// Database connection reference.
		$statement	= NULL;		// Database result reference.			
		$sql		= NULL;		// SQL string.
		$params 	= array(); 	// Parameter array.
		$options	= NULL;		// Query options object.
		$options_a	= array();	// Query options array.
				
		// Dereference data members.
		$connect = $this->connect_m->get_connection();
		$sql = $this->sql_m;
		$params = $this->params_m;
		$options = $this->options_m;
	
		// Break down options object to array.
		if($options)
		{
			$options_a['Scrollable'] = $options->get_scrollable();
			$options_a['SendStreamParamsAtExec'] = $options->get_sendstream();
			$options_a['QueryTimeout'] = $options->get_timeout();
		}
	
		// Execute query.
		$statement = sqlsrv_query($connect, $sql, $params, $options_a);
		
		// Error trapping.
		$this->error_m->error();
		
		// Set data member.
		$this->statement_m = $statement;
		
		// Return query ID resource.
		return $statement;
	}
	
	// Return query statement data member.
	public function get_statement()
	{
		return $this->statement_m;
	}
}

?>