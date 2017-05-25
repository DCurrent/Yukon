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
		HOST 		= '',					// Database host (server name or address)
		NAME 		= '',					// Database logical name.
		USER 		= '',					// User name to access database.
		PASSWORD	= '',					// Password to access database.
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
?>