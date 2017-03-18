<?php

namespace dc\yukon;

interface i\dc\recordnav\DataNavigation
{
	// Accessors
	function get_id_current();	
	function get_id_first();		
	function get_id_last();	
	function get_id_next();	
	function get_id_previous();	
	function get_sort_field();	
	function get_sort_order();
		
	// Mutators	
	function set_id_current($value);
	function set_id_first($value);
	function set_id_last($value);
	function set_id_next($value);
	function set_id_previous($value);
	function set_sort_field($value);
	function set_sort_order($value);
}

class \dc\recordnav\DataNavigation implements i\dc\recordnav\DataNavigation
{
	private
		$id_current		= NULL,
		$id_first		= NULL,
		$id_last		= NULL,
		$id_next		= NULL,
		$id_previous	= NULL,
		$sort_field		= NULL,
		$sort_order		= NULL;
		
	// Accessors
	public
		function get_id_current()
		{
			return $this->id_current;
		}
		
		function get_id_first()
		{
			return $this->id_first;
		}
		
		function get_id_last()
		{
			return $this->id_last;
		}
		
		function get_id_next()
		{
			return $this->id_next;
		}
		
		function get_id_previous()
		{
			return $this->id_previous;
		}
		
		function get_sort_field()
		{
			return $this->sort_field;
		}
		
		function get_sort_order()
		{
			return $this->sort_order;
		}
		
	// Mutators
	public
		function set_id_current($value)
		{
			$this->id_current = $value;
		}
		
		function set_id_first($value)
		{
			$this->id_first = $value;
		}
		
		function set_id_last($value)
		{
			$this->id_last = $value;
		}
		
		function set_id_next($value)
		{
			$this->id_next = $value;
		}
		
		function set_id_previous($value)
		{
			$this->id_previous = $value;
		}
		
		function set_sort_field($value)
		{
			$this->sort_field = $value;
		}
		
		function set_sort_order($value)
		{
			$this->sort_order = $value;
		}
		
}

?>
