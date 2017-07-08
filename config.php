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
	
	// Error and exception handling.
	const 
		EXCEPTION_CATCH	= TRUE,						// TRUE = Catch exceptions internally.
		TRAP			= TRUE,						// Toggles error handling. If FALSE, errors will be left to the standard PHP error handler.
		DETAILS			= TRUE,						// Send all available details about error to log.
		MAXLENGTH		= 1000;						// Max length for error_log. Overflows are split into multiple submissions. Set lower than php.ini counterpart to avoid truncation.
	
	// New IDs. Passing these as IDs in upsert type quries ensures 
	// the databse engine will find no matches and create 
	// a new record.
	const 	
		NEW_ID		= -1,					
		NEW_GUID	= '00000000-0000-0000-0000-000000000000';
}

// Codes output by thrown exceptions. Use these to take action
// in catch blocks outside of yukon. See EXCEPTION_MSG for details
// on the meaning of each code.
abstract class EXCEPTION_CODE
{
	const
		CONNECTION_FAILURE	= 0,
		FIELD_COUNT			= 1,
		MISSING_HOST		= 2,	
		ROW_COUNT			= 3;
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
