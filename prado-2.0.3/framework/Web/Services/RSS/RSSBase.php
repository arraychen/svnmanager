<?php

abstract class RSSBase {
	
	protected $allowed_datatypes = array('string', 'int', 'boolean',
								   'object', 'float', 'array'
								  );
		
	protected function setVar($data = FALSE, $var_name = '', $type = 'string') 
	{
		if (!in_array($type, $this->allowed_datatypes) || 
			$type != 'boolean' && ($data === FALSE || 
			$this->isFilledString($var_name) === FALSE)) {
			return FALSE;
		} // end if

		switch ($type) {
			case 'string':
				if ($this->isFilledString($data) === TRUE) {
					$this->$var_name = trim($data);
					return TRUE;
				} // end if
			case 'int':
				if (is_numeric($data)) {
					$this->$var_name = $data;
					return  TRUE;
				} // end if
			case 'boolean':
				if (is_bool($data)) {
					$this->$var_name =  $data;
					return  TRUE;
				}  // end if
			case 'object':
				if (is_object($data)) {
					$this->$var_name = $data;
					return  TRUE;
				} // end if
			case 'array':
				if (is_array($data)) {
					$this->$var_name = $data;
					return  TRUE;
				} // end if
		} // end switch
		return  FALSE;
	} // end function

	protected function getVar($var_name = 'dummy') 
	{
		return (isset($this->$var_name)) ? $this->$var_name: FALSE;
	} // end function
	
	public static function isFilledString($var = '', $min_length = 0) 
	{
		return  (strlen(trim($var)) > $min_length) ? TRUE : FALSE;
	} // end function	
	
} // end class
?>