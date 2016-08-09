<?php

class RSSObjectIterator implements Iterator {

	protected $current = 0;
	protected $objectlist;

	function __construct(RSSObjectList $list) {
		$this->objectlist = $list;
		$this->objectlist->getList();
	} // end constructor

    public function valid() {
    	return ($this->current < $this->size()) ? TRUE : FALSE;
    } // end function    
    
    public function next() {
    	return $this->current++;
    } // end function

    public function &current() {
    	return $this->objectlist->objects[$this->key()];
    } // end function
    
    public function key() {
    	return $this->current;
    } // end function
    
    public function size() {
		return count($this->objectlist->objects);
    } // end function

    public function rewind() {
		$this->current = 0;
	} // end function
} // end class
?>