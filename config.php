<?php

// DC Data
// Caskey, Damon V.
// 2014-04-18

// Object oriented database handler for MSSQL hosts.

namespace dc\yukon;

abstract class DEFAULTS
{
	// Connection options.
	const 
		HOST 		= 'gensql.ad.uky.edu\general',		// Database host (server name or address)
		NAME 		= 'inspection',			// Database logical name.
		USER 		= 'EHSInfo_User',		// User name to access database.
		PASSWORD	= 'ehsinfo',			// Password to access database.
		CHARSET		= 'UTF-8';				// Character set.
	
	// Query options.
	const 
		SCROLLABLE 	= SQLSRV_CURSOR_FORWARD, // Cursor type.
		SENDSTREAM 	= TRUE,					// Send whole parameter stream (TRUE), or send chunks (FALSE).
		TIMEOUT		= 300;					// Query time out in seconds.	
	
	// Line options.
	const 
		FETCHTYPE	= SQLSRV_FETCH_ASSOC,	// Default row array fetch type.
		ROW			= SQLSRV_SCROLL_NEXT,	// Row to access in a result set that uses a scrollable cursor.
		OFFSET		= 0;					// Row to access if row is absolute or relative. 
	
	// Error handling.
	const 
		TRAP		= TRUE,					// Toggles error handling. If turned off, errors will be left to the standard PHP error handler.
		DETAILS		= TRUE,					// Send all available details about error to log.
		MAXLENGTH	= 1000,					// Max length for error_log (set in php.ini). Error details exceeding this limit are split into multiple submissions.
		NEW_ID		= -1,					// Using this ID in upsert type quries ensures the databse will find no matches and creaate new record.
		NEW_GUID	= '00000000-0000-0000-0000-000000000000';
}

// Error handling.
interface iError
{
	function error();	// Trap and log errors reported by database.
}

// Structure of parameters used for database connection attempt.
interface iConnectConfig
{	
	function get_host();			// Return host name.
	function get_name();			// Return logical database name.
	function get_user();			// Return user.
	function get_password();		// Return password.
	function set_host($value);		// Set host name.
	function set_name($value);		// Set logical database name.
	function set_user($value);		// Set user.
	function set_password($value);	// Set password.	
}

// Database connection object.
interface iConnect 
{	
	function get_connection();		// Return database connection resource.
	function open_connection();		// Attempt database connection.
}

// Query object. Execute SQL queries and return data.
interface iDatabase
{	
	function execute();										// Execute prepared query with current parameters.
	function get_field_count();								// Return number of fields from query result.
	function get_field_metadata();							// Fetch and return table row's metadata array (column names, types, etc.).
	function get_line_array();								// Fetch line array from table rows.
	function get_line_array_all();							// Create and return a 2D array consisting of all line arrays from database query.
	function get_line_array_list(); 						// Create and return a linked list consisting of all line arrays from database query.
	function get_line_object();								// Fetch and return line object from table rows.
	function get_line_object_all();							// Create and return a 2D array consisting of all line arrays from database query.
	function get_line_object_list(); 						// Create and return a linked list consisting of all line objects from database query.
	function get_line_params();								// Return line parameters object.
	function get_next_result();								// Move to and return next result set.
	function get_options();									// Return options object.
	function get_row_count();								// Return number of records from query result.
	function get_row_exists();								// Verify the result contains rows.
	function get_sql();										// Return current SQl statement.
	function get_statement();								// Return query statement data member.
	function prepare();										// Prepare query. Returns statement reference and sends to data member.
	function query();										// Prepare and execute query.
	function set_connection(Connect $value);	// Set connection data member.
	function set_sql($value);								// Set query sql string data member.
	function set_options(DatabaseConfig $value); 	// Set the object to be used for query options settings.
	function set_params(array $value);						// Set query sql parameters data member.
	function set_line_params(LineConfig $value);	// Set line parameters object.
}

// Data structure for the options parameter when preparing SQL queires.
interface iDatabaseConfig
{	
	function get_scrollable();			// Return cursor scrollable.
	function get_sendstream();			// Return sendstream.
	function get_timeout();				// Return timeout.
	function set_scrollable($value);	// Set cursor scrollable.
	function set_sendstream($value);	// Set sendstream.
	function set_timeout($value);		// Set timeout.
}

// Data structure for line fetching parameters.
interface iLineConfig
{	
	function get_class_name();			// Return class name instantiated on an object fetch.
	function get_class_params();		// Return constructor parameter array for class instantiated on object fetch.
	function get_fetchtype();			// Return fetch type.
	function get_offset();				// Return row offset.
	function get_row();					// Return row.
	function set_class_name($value);	// Set class name instantiated on an object fetch.
	function set_class_params(array $value); 	// Set constructor parameter array for class instantiated on object fetch.
	function set_fetchtype($value);		// Set fetch type.
	function set_row($value);			// Set row.
	function set_offset($value);		// Set row offset.
}

?>