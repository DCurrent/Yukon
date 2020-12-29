<?php

namespace dc\yukon;

require_once('config.php');

// Data structure for line fetching parameters.
interface iLineConfig
{	
	function get_class_name();			// Return class name instantiated on an object fetch.
	function get_class_params();			// Return constructor parameter array for class instantiated on object fetch.
	function get_fetchtype();			// Return fetch type.
	function get_offset();				// Return row offset.
	function get_row();				// Return row.
	function set_class_name($value);		// Set class name instantiated on an object fetch.
	function set_class_params(array $value);	// Set constructor parameter array for class instantiated on object fetch.
	function set_fetchtype($value);			// Set fetch type.
	function set_row($value);			// Set row.
	function set_offset($value);			// Set row offset.
}

class LineConfig implements iLineConfig 
{
	private 
		$fetchtype		= NULL,		// Line array fetch type.
		$row			= NULL,		// Row to access in a result set that uses a scrollable cursor.
		$offset			= NULL,		// Row to access if row is absolute or relative. 
		$class_name		= NULL,		// Class to instantiate on an object fetch.
		$class_params	= array();	// Parameter array to pass into class constructor.
	
	public function __construct()
	{
		// Populate defaults.
		$this->fetchtype	= \dc\yukon\DEFAULTS::FETCHTYPE;
		$this->row 			= \dc\yukon\DEFAULTS::ROW;
		$this->offset 		= \dc\yukon\DEFAULTS::OFFSET;
	}
	
	// Accessors
	public function get_fetchtype()
	{		
		return $this->fetchtype;
	}
	
	public function get_row()
	{		
		return $this->row;
	}	
	
	public function get_offset()
	{		
		return $this->offset;
	}
	
	public function get_class_name()
	{
		return $this->class_name;
	}
	
	public function get_class_params()
	{
		return $this->class_params;
	}
	
	// Mutators
	public function set_class_name($value)
	{		
		$this->class_name = $value;
	}
	
	public function set_class_params(array $value)
	{		
		$this->class_params = $value;
	}
	
	public function set_fetchtype($value)
	{		
		$this->fetchtype = $value;
	}
	
	public function set_row($value)
	{		
		$this->row = $value;
	}
	
	public function set_offset($value)
	{		
		$this->offset = $value;
	}
}

?>
