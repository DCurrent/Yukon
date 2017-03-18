<?php

namespace dc\yukon;

require_once('config.php');

class LineConfig implements iLineConfig 
{
	private 
		$fetchtype_m	= NULL,		// Line array fetch type.
		$row_m			= NULL,		// Row to access in a result set that uses a scrollable cursor.
		$offset_m		= NULL,		// Row to access if row is absolute or relative. 
		$class_name_m	= NULL,		// Class to instantiate on an object fetch.
		$class_params_m = array();	// Parameter array to pass into class constructor.
	
	public function __construct()
	{
		$this->fetchtype_m = \dc\yukon\DEFAULTS::FETCHTYPE;
		$this->row_m = \dc\yukon\DEFAULTS::ROW;
		$this->offset_m = \dc\yukon\DEFAULTS::OFFSET;
	}
	
	// Accessors
	public function get_fetchtype()
	{		
		return $this->fetchtype_m;
	}
	
	public function get_row()
	{		
		return $this->row_m;
	}	
	
	public function get_offset()
	{		
		return $this->offset_m;
	}
	
	public function get_class_name()
	{
		return $this->class_name_m;
	}
	
	public function get_class_params()
	{
		return $this->class_params_m;
	}
	
	// Mutators
	public function set_class_name($value)
	{		
		$this->class_name_m = $value;
	}
	
	public function set_class_params(array $value)
	{		
		$this->class_params_m = $value;
	}
	
	public function set_fetchtype($value)
	{		
		$this->fetchtype_m = $value;
	}
	
	public function set_row($value)
	{		
		$this->row_m = $value;
	}
	
	public function set_offset($value)
	{		
		$this->offset_m = $value;
	}
}

?>