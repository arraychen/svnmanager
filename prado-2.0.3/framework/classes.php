<?php
/**
 * Common classes file
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Qiang Xue. All rights reserved.
 *
 * To contact the author write to {@link mailto:qiang.xue@gmail.com Qiang Xue}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.21 $  $Date: 2005/01/23 17:50:44 $
 * @package System
 */

/**
 * ISession interface
 *
 * Session class must implement this interface.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/10/24 12:00:00
 * @package System
 */
interface ISession
{
	/**
	 * Checks if the named session variable exists.
	 * @return boolean whether the named session variable exists
	 */
	public function has($name);
	/**
	 * Returns the value of the named session variable
	 * @param the name of the session variable
	 * @return mixed the value of the session variable
	 */
	public function get($name);
	/**
	 * Sets a session variable
	 * @param string the session variable name
	 * @param mixed the variable value
	 */
	public function set($name,$value);
	/**
	 * Unsets a session variable.
	 * @param string the session variable name
	 */
	public function clear($name);
	/**
	 * Starts the session.
	 */
	public function start();
	/**
	 * Destroys the session.
	 */
	public function destroy();
	/**
	 * @return boolean whether the session is started
	 */
	public function isStarted();
}

/**
 * IUser interface
 *
 * User classes must implement this interface.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/10/24 12:00:00
 * @package System
 */
interface IUser
{
	/**
	 * @return boolean whether the user is authenticated
	 */
	public function isAuthenticated();
	/**
	 * Checks if the user is of certain role.
	 * @param string the role to be checked
	 * @return boolean if the user is of the role
	 */
	public function isInRole($role);
	/**
	 * This method is invoked by the framework when authentication fails.
	 * @param string the name of the page that requires authentication.
	 */
	public function onAuthenticationRequired($pageName);
	/**
	 * This method is invoked by the framework when authorization fails.
	 * @param TPage the page object that are not authorized to be accessed.
	 */
	public function onAuthorizationRequired($page);
}

 /**
 * TEventParameter class
 *
 * TEventParameter is the base class for all event parameter instances.
 * It contains no data. Derived classes may provide specific data storage for event parameters.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System
 */
class TEventParameter
{
}


/**
 * TCommandEventParameter class
 *
 * TCommandEventParameter encapsulates the parameter data for <b>OnCommand</b>
 * event of TButton controls.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System
 */
class TCommandEventParameter extends TEventParameter
{
	/**
	 * command name
	 * @var string
	 */
	public $name='';
	/**
	 * command parameter
	 * @var string
	 */
	public $parameter='';
}

/**
 * IPostBackEventHandler interface
 *
 * If a components wants to respond to postback event, it must implement IPostBackEventHandler.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System
 */
interface IPostBackEventHandler
{
	/**
	 * Raises postback event.
	 * The implementation of this function should raise appropriate event(s) (e.g. OnClick, OnCommand)
	 * indicating the component is responsible for the postback event.
	 * @param string the parameter associated with the postback event
	 */
	public function raisePostBackEvent($param);
}


/**
 * IPostBackDataHandler interface
 *
 * If a controls wants to load post data, it must implement IPostBackDataHandler.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System
 */
interface IPostBackDataHandler
{
	/**
	 * Loads user input data.
	 * The implementation of this function can use $values[$key] to get the user input
	 * data that are meant for the particular control.
	 * @param string the key that can be used to retrieve data from the input data collection
	 * @param array the input data collection
	 * @return boolean whether the data of the control has been changed
	 */
	public function loadPostData($key,&$values);
	/**
	 * Raises postdata changed event.
	 * The implementation of this function should raise appropriate event(s) (e.g. OnTextChanged)
	 * indicating the control data is changed.
	 */
	public function raisePostDataChangedEvent();
	/**
	 * Returns the value of the property to be validated.
	 */
	public function getValidationPropertyValue();
}

/**
 * IValidator interface
 *
 * If a control wants to validate user input, it must implement IValidator.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System
 */
interface IValidator
{
	/**
	 * Validates certain data.
	 * The implementation of this function should validate certain data
	 * (e.g. data entered into TTextBox control).
	 * @return boolean whether the data passes the validation
	 */
	public function validate();
	/**
	 * @return boolean whether the previous {@link validate()} is successful.
	 */
	public function isValid();
}

/**
 * TCollection class
 *
 * TCollection implements basic collection functionalities.
 * It requires SPL support of PHP 5.
 * You can use a TCollection object like an array with cardinal
 * indexes starting from 0. For example,
 * <code>
 *   $collection=new TCollection;
 *   $collection[]='item 1';
 *   $collection[]='item 2';
 *   unset($collection[0]);
 * </code>
 * Note, if you unset any element in the collection, the rest
 * elements will be re-indexed.
 * It is recommended you use TCollection in OO fashion, i.e.,
 * <code>
 *   $collection=new TCollection;
 *   $collection->add('item 1');
 *   $collection->addAt(0,'item 2');
 *   $collection->removeAt(0);
 *   $collection->clear();
 * </code>
 * You can use a TCollection object in foreach() like following,
 * <code>
 *   foreach($collection as $item)
 *   { }
 * </code>
 * Note, to get the number of items in a collection, use
 * $collection->length(). Calling count($collection) will always
 * return 1.
 * 
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2005/01/17 21:44:52
 * @package System
 */
class TCollection implements Iterator,ArrayAccess
{
	/**
	 * internal data storage
	 * @var array
	 */
	protected $data=array();
	/**
	 * whether this collection allows addition/removal of elements
	 * @var boolean
	 */
	protected $readOnly=false;

	/**
	 * Constructor.
	 * @param mixed initial collection data
	 * @param boolean whether the collection is read-only
	 */
	public function __construct($data=null,$readOnly=false)
	{
		if(is_array($data) || $data instanceof Traversable)
		{
			foreach($data as $value)
				$this->add($value);
		}
		$this->readOnly=$readOnly;
	}

	/**
	 * Rewinds internal array pointer.
	 * This method should only be used by framework and component developers.
	 */
	public function rewind()
	{
		reset($this->data);
	}

	/**
	 * Returns the key of the current array element.
	 * This method should only be used by framework and component developers.
	 * @return integer the key of the current array element
	 */
	public function key()
	{
		return key($this->data);
	}

	/**
	 * Returns the current array element.
	 * This method should only be used by framework and component developers.
	 * @return mixed the current array element
	 */
	public function current()
	{
		return current($this->data);
	}

	/**
	 * Moves the internal pointer to the next array element.
	 * This method should only be used by framework and component developers.
	 */
	public function next()
	{
		return next($this->data);
	}

	/**
	 * Returns whether there is an element at current position.
	 * This method should only be used by framework and component developers.
	 * @return boolean
	 */
	public function valid()
	{
		return $this->current()!==false;
	}

	/**
	 * Returns whether there is an element at the specified offset.
	 * This method should only be used by framework and component developers.
	 * @param integer the offset to check on
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		return isset($this->data[$offset]);
	}

	/**
	 * Returns the element at the specified offset.
	 * This method should only be used by framework and component developers.
	 * @param integer the offset to retrieve element.
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		if(isset($this->data[$offset]))
			return $this->data[$offset];
		else
			pradoFatalError(get_class($this).": invalid offset '$offset'");
	}

	/**
	 * Required by interface.
	 * Do not call this method.
	 */
	public function offsetSet($offset,$item)
	{
		if(is_null($offset))
			$this->add($item);
		else if(isset($this->data[$offset]))
		{
			if($item!==$this->data[$offset])
			{
				$this->removeAt($offset);
				$this->addAt($offset,$item);
			}
		}
		else
			pradoFatalError(get_class($this).": invalid offset '$offset'");
	}

	/**
	 * Required by interface.
	 * Do not call this method.
	 */
	public function offsetUnset($offset)
	{
		if(isset($this->data[$offset]))
			$this->removeAt($offset);
		else
			pradoFatalError(get_class($this).": invalid offset '$offset'");
	}

	/**
	 * @return integer the number of elements in the collection
	 */
	public function length()
	{
		return count($this->data);
	}

	/**
	 * @return boolean whether this collection allows addition/removal of elements.
	 */
	public function isReadOnly()
	{
		return $this->readOnly;
	}

	/**
	 * Appends an item at the end of the collection.
	 * @param mixed new item
	 */
	public function add($item)
	{
		$this->addAt(count($this->data),$item);
	}

	/**
	 * Inserts an item at the specified position.
	 * Original item at the position and the next items 
	 * will be moved one step towards the end.
	 * @param integer the speicified position.
	 * @param mixed new item
	 */
	public function addAt($index,$item)
	{
		if($this->readOnly)
			throw new Exception('Collection '.get_class($this).' is read-only.');
		if($index===count($this->data))
		{
			if($this->onAddItem($item))
				$this->data[]=$item;
		}
		else if(is_integer($index) && $index>=0 && $index<count($this->data))
		{
			if($this->onAddItem($item))
				array_splice($this->data,$index,0,array($item));
		}
		else
			throw new Exception("Invalid collection offset '$index'.");
	}

	/**
	 * Removes all items in the collection.
	 */
	public function clear()
	{
		if($this->readOnly)
			throw new Exception('Collection '.get_class($this).' is read-only.');
		foreach($this->data as $item)
			$this->onRemoveItem($item);
		$this->data=array();
	}

	/**
	 * Removes an item from the collection.
	 * The collection will first search for the item.
	 * The first item found will be removed from the collection.
	 * @param mixed the item to be removed.
	 */
	public function remove($item)
	{
		if(($index=$this->indexOf($item))>=0)
			$this->removeAt($index);
	}

	/**
	 * Removes an item at the specified position.
	 * @param integer the index of the item to be removed.
	 */
	public function removeAt($index)
	{
		if($this->readOnly)
			throw new Exception('Collection '.get_class($this).' is read-only.');
		if(is_integer($index) && $index>=0 && $index<count($this->data))
		{
			$this->onRemoveItem($this->data[$index]);
			array_splice($this->data,$index,1);
		}
		else
			throw new Exception("Invalid collection offset '$index'.");
	}

	/**
	 * @param mixed the item
	 * @return boolean whether the collection contains the item
	 */
	public function contains($item)
	{
		return $this->indexOf($item)>=0;
	}

	/**
	 * @param mixed the item
	 * @return integer the index of the item in the collection, -1 if not found.
	 */
	public function indexOf($item)
	{
		$index=array_search($item,$this->data,true);
		if($index===false)
			$index=-1;
		return $index;
	}

	/**
	 * This method will be invoked when an item is being added to the collection.
	 * @param mixed the item to be added.
	 * @return boolean whether the item should be added.
	 */
	protected function onAddItem($item)
	{
		return true;
	}

	/**
	 * This method will be invoked when an item is being removed from the collection.
	 * @param mixed the item to be removed.
	 */
	protected function onRemoveItem($item)
	{
	}

	/**
	 * @return array array representation of the data in the collection
	 */
	public function getArray()
	{
		return $this->data;
	}
}

?>