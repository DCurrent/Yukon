<?php

namespace dc\yukon;

// Basic configuration and default values.
abstract class DEFAULTS
{
	// Connection options.
	const 
		HOST 			= '',						// Database host (server name or address)
		NAME 			= '',						// Database logical name.
		USER 			= '',						// User name to access database.
		PASSWORD		= '',						// Password to access database.
		CHARSET			= 'UTF-8';					// Character set.
	
	// Query options.
	const 
		SCROLLABLE 		= SQLSRV_CURSOR_FORWARD,	// Cursor type.
		SENDSTREAM 		= TRUE,						// Send whole parameter stream (TRUE), or send chunks (FALSE).
		TIMEOUT			= 300;						// Query time out in seconds.	
	
	// Line options.
	const 
		FETCHTYPE		= SQLSRV_FETCH_ASSOC,		// Default row array fetch type.
		ROW				= SQLSRV_SCROLL_NEXT,		// Row to access in a result set that uses a scrollable cursor.
		OFFSET			= 0;						// Row to access if row is absolute or relative. 
	
	// Error exemptions. These codes will be ignored
	// by the respective layer of error trapping.
	const 
		EXEMPT_ALL			= -1,					// If this is placed into any list, then all codes in that list will be considered exempt.
		EXEMPT_CODES_CATCH	= '',					// Catching of thrown exceptions.
		EXEMPT_CODES_DRIVER	= '0, 5701, 5703',		// Detection of errors from database driver.
		EXEMPT_CODES_THROW	= '';					// Throwing exception codes.
	
	// New IDs. Passing these as IDs in upsert type quries ensures 
	// the databse engine will find no matches and create 
	// a new record.
	const 	
		NEW_ID		= -1,					
		NEW_GUID	= '00000000-0000-0000-0000-000000000000';
}

// Codes output by thrown exceptions. Use these to take action
// in catch blocks outside of Yukon.
abstract class EXCEPTION_CODE
{
	const
		CONNECTION_FAILURE	= 0,	// Driver returned a failure response attempting to connect to database.
		FIELD_COUNT			= 1,	// Field count returned an error code.
		MISSING_HOST		= 2,	// No database host was provided for connection.
		ROW_COUNT			= 3;	// Row count returned an error code.
}

// Output given by interal exception handler.
abstract class EXCEPTION_MSG
{
	const
		CONNECTION_FAILURE	= 'Could not connect to database host.',
		FIELD_COUNT			= 'Field count error.',
		MISSING_HOST		= 'Missing or invalid database host argument.',
		ROW_COUNT			= 'Row count error.';
}

?>
