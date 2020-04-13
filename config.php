<?php

namespace dc\yukon;

abstract class DB_TYPES
{
	const MSSQL = 1;
	const MYSQL = 2;
}

abstract class LIBRARY
{
	const NAME		= "Yukon";
	const VERSION 	= "0.5";
}

// Basic configuration and default values.
abstract class DEFAULTS
{
	// Connection options
	const HOST 					= '';						// Database host (server name or address)
	const NAME 					= '';						// Database logical name.
	const USER 					= '';						// User name to access database.
	const PASSWORD				= '';						// Password to access database.
	const CHARSET				= 'UTF-8';					// Character set.
	const DB_TYPE				= DB_TYPES::MSSQL;			// Database host type.
	// Query options.
	const SCROLLABLE 			= SQLSRV_CURSOR_FORWARD;	// Cursor type.
	const SENDSTREAM 			= TRUE;						// Send whole parameter stream (TRUE), or send chunks (FALSE).
	const TIMEOUT				= 300;						// Query time out in seconds.	
	
	// Line options. 
	const FETCHTYPE				= SQLSRV_FETCH_ASSOC;		// Default row array fetch type.
	const ROW					= SQLSRV_SCROLL_NEXT;		// Row to access in a result set that uses a scrollable cursor.
	const OFFSET				= 0;						// Row to access if row is absolute or relative. 
	
	// Error exemptions. These codes will be ignored
	// by the respective layer of error trapping.
	const EXEMPT_ALL			= -1;						// If this is placed into any list, then all codes in that list will be considered exempt.
	const EXEMPT_CODES_CATCH	= '';						// Catching of thrown exceptions.
	const EXEMPT_CODES_DRIVER	= '0, 5701, 5703';			// Detection of errors from database driver.
	const EXEMPT_CODES_THROW	= '';						// Throwing exception codes.
	
	// New IDs. Passing these as IDs in upsert type quries ensures 
	// the databse engine will find no matches and create 
	// a new record.
	const NEW_ID				= -1;					
	const NEW_GUID				= '00000000-0000-0000-0000-000000000000';
}

// Codes output by thrown exceptions.
abstract class EXCEPTION_CODE
{
<<<<<<< HEAD
	const CONNECT_CLOSE_FAIL		= 0X0;		// Driver returned failure response attempting to close database connection.
	const CONNECT_CLOSE_CONNECTION	= 0X1;		// There is no connection to close.
	const CONNECT_OPEN_FAIL			= 0X2;		// Driver returned failure response attempting to connect to database.
	const CONNECT_OPEN_HOST			= 0X3; 		// Application did not provide a host target to connect.
	
	const FIELD_COUNT_ERROR			= 0X64;		// Field count returned an error code.
	const FIELD_COUNT_STATEMENT		= 0X65;		// Missing or invalid query statement getting field count.
	
	const FREE_STATEMENT_ERROR		= 0XC8;		// Free statement returned an error code.
	const FREE_STATEMENT_FAIL		= 0XC9;		// Free statement returned failure.
	const FREE_STATEMENT_STATEMENT	= 0XCA;
		
	const LINE_ARRAY_ERROR			= 0X12C;	// Line array - Line array fetch method returned an error code.
	const LINE_ARRAY_FAIL			= 0X12D;	// Line array - Line array fetch method returned a failure response.
	const LINE_ARRAY_STATEMENT		= 0X12E;	// Line array - Invalid query statement.
	
	const METADATA_ERROR			= 0X1F4;	// Metadata returned an error code.
	const METADATA_STATEMENT		= 0X1F5;	// Missing or invalid query statement getting metadata.
	
	const QUERY_EXECUTE_ERROR		= 0X258;	// Execute returned an error code.
	const QUERY_EXECUTE_FAIL		= 0X259;	// Execute returned a failure response.
	const QUERY_EXECUTE_STATEMENT	= 0X25A;	// Missing or invalid query statement on execution.
	
	const QUERY_PREPARE_CONFIG		= 0X25B;	// Prepare query - No valid database config.
	const QUERY_PREPARE_CONNECTION	= 0X25C;	// Prepare query - No connection to database.
	const QUERY_PREPARE_ERROR		= 0X25D;	// Prepare query - Driver prepare method returned an error code.
	const QUERY_PREPARE_FAIL		= 0X25E;	// Prepare query - Driver prepare method returned a failure response.
	const QUERY_PREPARE_PARAM_ARRAY	= 0X25F;	// Prepare query - No valid array of parameters.
	const QUERY_PREPARE_PARAM_LIST	= 0X260;	// Prepare query - No valid list of parameters.
	const QUERY_PREPARE_SQL			= 0X261;	// Prepare query - No valid SQL string.
	
	const QUERY_RUN_CONFIG			= 0X2BC;	// Run query - No valid database config.
	const QUERY_RUN_CONNECTION		= 0X2BD;	// Run query - No connection to database.
	const QUERY_RUN_ERROR			= 0X2BE;	// Run query - Driver prepare method returned an error code.
	const QUERY_RUN_FAIL			= 0X2BF;	// Run query - Driver prepare method returned a failure response.
	const QUERY_RUN_PARAM_ARRAY		= 0X2C0;	// Run query - No valid array of parameters.
	const QUERY_RUN_PARAM_LIST		= 0X2C1;	// Run query - No valid list of parameters.
	const QUERY_RUN_SQL				= 0X2C2;	// Run query - No valid SQL string.
	
	const ROW_COUNT_ERROR			= 0X320;	// Row count returned an error code.
	const ROW_COUNT_STATEMENT		= 0X321;	// Missing or invalid query statement getting row count.
=======
	const CONNECT_CLOSE_FAIL		= 0;	// Driver returned failure response attempting to close database connection.
	const CONNECT_CLOSE_CONNECTION	= 1;	// There is no connection to close.
	const CONNECT_OPEN_FAIL			= 2;	// Driver returned failure response attempting to connect to database.
	const CONNECT_OPEN_HOST			= 3; 	// Application did not provide a host target to connect.
	const FIELD_COUNT_ERROR			= 4;	// Field count returned an error code.
	const FIELD_COUNT_STATEMENT		= 5;	// Missing or invalid query statement getting field count.
	const FREE_STATEMENT_ERROR		= 6;	// Free statement returned an error code.
	const FREE_STATEMENT_FAIL		= 7;	// Free statement returned failure.
	const FREE_STATEMENT_STATEMENT	= 8;	// Free statement called when there is no valid statement.
	const LINE_ARRAY_FAIL			= 9;	// Failed to build line/row array.
	const LINE_ARRAY_STATEMENT		= 10;	// Line/Row array attempted with no valid statement.
	const LINE_OBJECT_FAIL			= 11;	// Failed to build line/row array.
	const LINE_OBJECT_STATEMENT		= 12;	// Line/Row array attempted with no valid statement.
	const METADATA_ERROR			= 13;	// Metadata returned an error code.
	const METADATA_STATEMENT		= 14;	// Missing or invalid query statement getting metadata.
	const QUERY_DIRECT_CONFIG		= 15;	// Prepare query - No valid database config.
	const QUERY_DIRECT_CONNECTION	= 16;	// Prepare query - No connection to database.
	const QUERY_DIRECT_PARAM_ARRAY	= 17;	// Prepare query - No valid array of parameters.
	const QUERY_DIRECT_PARAM_LIST	= 18;	// Prepare query - No valid list of parameters.
	const QUERY_DIRECT_SQL			= 19;	// Prepare query - No valid SQL string.
	const QUERY_DIRECT_STATEMENT	= 20;	// Prepare query - No statement returned.
	const QUERY_EXECUTE_ERROR		= 21;	// Execute returned an error code.
	const QUERY_EXECUTE_FAIL		= 22;	// Execute returned a failure response.
	const QUERY_EXECUTE_STATEMENT	= 23;	// Missing or invalid query statement on execution.
	const QUERY_PREPARE_CONFIG		= 24;	// Prepare query - No valid database config.
	const QUERY_PREPARE_CONNECTION	= 25;	// Prepare query - No connection to database.
	const QUERY_PREPARE_PARAM_ARRAY	= 26;	// Prepare query - No valid array of parameters.
	const QUERY_PREPARE_PARAM_LIST	= 27;	// Prepare query - No valid list of parameters.
	const QUERY_PREPARE_SQL			= 28;	// Prepare query - No valid SQL string.
	const QUERY_PREPARE_STATEMENT	= 29;	// Prepare query - No statement returned.
	const ROW_COUNT_FAIL			= 30;	// Row count returned an error code.
	const ROW_COUNT_STATEMENT		= 31;	// Missing or invalid query statement getting row count.
	const ROW_VERIFY_FAIL			= 32;	// Has rows returned error code.
	const ROW_VERIFY_STATEMENT		= 33;	// Has rows attempted with no statement.
	const UNKNOWN					= 34;	// Unhandled error.
>>>>>>> tmp
}

// Output given by interal exception handler.
abstract class EXCEPTION_MSG
{
	const CONNECT_CLOSE_FAIL		= 'Close Connection - Failed closing connection to host.';
	const CONNECT_CLOSE_CONNECTION	= 'Close Connection - No valid connection to close.';
<<<<<<< HEAD
	const CONNECT_OPEN_FAIL			= 'Close Connection - Failed to open connection with host.';
	const CONNECT_OPEN_HOST			= 'Close Connection - Missing or invalid host argument.';
	
=======
	const CONNECT_OPEN_FAIL			= 'Open Connection - Failed to open connection with host.';
	const CONNECT_OPEN_HOST			= 'Open Connection - Missing or invalid host argument.';
>>>>>>> tmp
	const FIELD_COUNT_ERROR			= 'Field Count - Error occurred.';
	const FIELD_COUNT_STATEMENT		= 'Field Count - Missing or invalid statement.';
	
	const FREE_STATEMENT_ERROR		= 'Free Statement - Error occurred.';
	const FREE_STATEMENT_FAIL		= 'Free statement - Failed to free statement.';
	const FREE_STATEMENT_STATEMENT	= 'Free Statement - No valid statement to free.';
<<<<<<< HEAD
	
	const LINE_ARRAY_ERROR			= 'Line Array - Error occurred..';
	const LINE_ARRAY_FAIL			= 'Line Array - Failed to get line array.';
	const LINE_ARRAY_STATEMENT		= 'Line Array - Missing or invalid statement.';
	
	const METADATA_ERROR			= 'Get Metadata - Error occurred.';
	const METADATA_STATEMENT		= 'Get Metadata - Missing or invalid statement.';
	
=======
	const LINE_ARRAY_FAIL			= 'Get Line Array - Failed to return line/row array.';
	const LINE_ARRAY_STATEMENT		= 'Get Line Array - No valid query statement.';
	const LINE_OBJECT_FAIL			= 'Get Line Object - Failed to return line/row array.';
	const LINE_OBJECT_STATEMENT		= 'Get Line Object - No valid query statement.';
	const METADATA_ERROR			= 'Get Metadata - Error occurred.';
	const METADATA_STATEMENT		= 'Get Metadata - Missing or invalid statement.';
	const QUERY_DIRECT_CONFIG		= 'Query Direct - Missing or invalid database config.';
	const QUERY_DIRECT_CONNECTION	= 'Query Direct - Missing or invalid database connection.';
	const QUERY_DIRECT_PARAM_ARRAY	= 'Query Direct - Missing or invalid parameter array.';
	const QUERY_DIRECT_PARAM_LIST	= 'Query Direct - Missing or invalid parameter list.';
	const QUERY_DIRECT_SQL			= 'Query Direct - Missing or invalid SQL string.';
	const QUERY_DIRECT_STATEMENT	= 'Query Direct - Failed to prepare statement.';
>>>>>>> tmp
	const QUERY_EXECUTE_ERROR		= 'Query Execute - Error occurred.';
	const QUERY_EXECUTE_FAIL		= 'Query Execute - Failed to execute prepared query.';
	const QUERY_EXECUTE_STATEMENT	= 'Query Execute - Missing or invalid statement.';
	
	const QUERY_PREPARE_CONFIG		= 'Query Prepare - Missing or invalid database config.';
	const QUERY_PREPARE_CONNECTION	= 'Query Prepare - Missing or invalid database connection.';
<<<<<<< HEAD
	const QUERY_PREPARE_ERROR		= 'Query Prepare - Error occurred.';
	const QUERY_PREPARE_FAIL		= 'Query Prepare - Failed to prepare query statement.';
	const QUERY_PREPARE_PARAM_ARRAY	= 'Query prepare - Missing or invalid parameter array.';
	const QUERY_PREPARE_PARAM_LIST	= 'Query prepare - Missing or invalid parameter list.';
	const QUERY_PREPARE_SQL			= 'Query prepare - Missing or invalid SQL string.';
	
	const QUERY_RUN_CONFIG			= 'Query Run - Missing or invalid database config.';
	const QUERY_RUN_CONNECTION		= 'Query Run - Missing or invalid database connection.';
	const QUERY_RUN_ERROR			= 'Query Run - Error occurred.';
	const QUERY_RUN_FAIL			= 'Query Run - Failed to Run query statement.';
	const QUERY_RUN_PARAM_ARRAY		= 'Query Run - Missing or invalid parameter array.';
	const QUERY_RUN_PARAM_LIST		= 'Query Run - Missing or invalid parameter list.';
	const QUERY_RUN_SQL				= 'Query Run - Missing or invalid SQL string.';
	
	const ROW_COUNT_ERROR			= 'Get Row Count - Error occurred.';
=======
	const QUERY_PREPARE_PARAM_ARRAY	= 'Query Prepare - Missing or invalid parameter array.';
	const QUERY_PREPARE_PARAM_LIST	= 'Query Prepare - Missing or invalid parameter list.';
	const QUERY_PREPARE_SQL			= 'Query Prepare - Missing or invalid SQL string.';
	const QUERY_PREPARE_STATEMENT	= 'Query Prepare - Failed to prepare statement.';
	const ROW_COUNT_FAIL			= 'Get Row Count - Failed to return row count.';
>>>>>>> tmp
	const ROW_COUNT_STATEMENT		= 'Get Row Count - Missing or invalid statement.';
	const ROW_VERIFY_FAIL			= 'Verify Has Rows - Row Count - Missing or invalid statement.';
	const ROW_VERIFY_STATEMENT		= 'Verify Has Rows - Missing or invalid statement.';
	const UNHANDLED					= 'Unknown exception. Terminating application.';
	
}

?>
