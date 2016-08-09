<?php
/**
 * TListControl class file
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
 * @author Marcus Nyeholt <tanus@users.sourceforge.net>
 * @version $Revision: 1.38 $  $Date: 2005/09/07 04:11:02 $
 * @package System.Web.UI.WebControls
 */

/**
 * TListItem class file
 */
require_once(dirname(__FILE__).'/TListItem.php');

/**
 * TListControl class
 *
 * TListControl is the parent class for list based controls in the PRADO
 * framework (TListBox, TDropDownList, TCheckBoxList, TRadioButtonList).
 *
 * There are three possible ways to define items to be listed in a list
 * control:
 * <ul>
 * <li>
 *	Inside the template file as a child control of the list control.
 * </li>
 * <li>
 *	By manually adding TListItem controls to the Items property of a list
 * <code>
 *	$object = new TListBox;
 *  $item = new TListItem;
 *  $item->Text="Text to display";
 *  $item->Value="value";
 *	$object->Items->add($item);
 *	</code>
 * </li>
 * <li>
 *	By binding a dataSource to the list control. 
 * </li>
 *	</ul>
 *	
 * The data source a TListControl can be binded to one of the 
 * following types of collection;
 * <ul>
 * <li>
 *	An array
 * </li>
 * <li>
 *	A class that implements Traversible (e.g. ArrayObject, TCollection). 
 * </li>
 *	</ul>
 * 
 * Each item in the data source can be one of the following:
 * <ul>
 * <li>
 *	An object that implements the IListItemSource interface. This is 
 * useful for binding objects to a list source, that can then be asked
 * to provide the Text and Value data for the list item.
 * </li>
 * <li>
 *	A scalar value, or object that can be converted to a string.
 * </li>
 * <li>
 *	An array or an object implementing Traversible interface.
 *  If this is the case, the DataTextField and
 * DataValueField properties of the list control MUST be set so the 
 * control knows which index of the array to get the Text and Value 
 * data for the item in the list.
 * </li>
 * </ul>
 *
 * Properties
 * - <b>EncodeText</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets the value indicating whether Text in list items should be HTML-encoded when rendering.
 * - <b>AutoPostBack</b>, boolean, kept in viewstate
 *   <br>Gets or sets whether a postback response will be initiated when the selection
 * 		of the list changes.
 * - <b>Items</b>, TCollection, read-only, kept in viewstate
 *   <br>Gets the collection of list items defined for this control.
 * - <b>DataSource</b>, object, iterable
 *   <br>Gets or sets the data source this list control will use
 * - <b>DataTextField</b>, string, kept in viewstate
 *   <br>Gets or sets the index to use into a data source item to 
 *		retrieve the Text property for the list item
 * - <b>DataValueField</b>, string, kept in viewstate
 *   <br>Gets or sets the index to use into a data source item to 
 *		retrieve the Value property for the list item
 * - <b>DataTextFormatString</b>, string, kept in viewstate
 *   <br>Gets or sets the format string to use for the Text property of the
 *		list item. The format string is used as the first parameter to
 *      the ssprintf() function to transform the item text.
 * - <b>SelectedItem</b>, TListItem, read-only
 *   <br>Gets the selected item that has the lowest cardinal index.
 * - <b>SelectedIndex</b>, integer, default=-1
 *   <br>Gets or sets the index of the selected item. Note, setting a SelectedIndex will
 *      clear all other selected indexes.
 * - <b>SelectedValue</b>, string
 *   <br>Gets or sets the item with a value. Note, when setting a selection by value,
 *      if the value is not found, an exception will be raised. If found, the rest selections
 *      will be cleared.
 *
 * Events
 * - <b>OnSelectionChanged</b> Occurs when the selection of the list changes
 * 
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Marcus Nyeholt <tanus@users.sourceforge.net>
 * @version $Revision: 1.38 $, last update on $Date: 2005/09/07 04:11:02 $
 * @package System.Web.UI.WebControls
 */
abstract class TListControl extends TWebControl implements IPostBackDataHandler
{
	/**
	 * list of TListItem controls
	 */
	protected $items;
	
	/**
	* A datasource for this control
	* @var object
	*/
	protected $dataSource=null;
	
	/**
	* Set the selected value of a given item.
	* @return void
	*/
	protected $selectedValue;
	
	/**
	* Set the items list to be empty.
	* @return void
	*/
	public function __construct()
	{
		$this->items = new TCollection();
		parent::__construct();
	}

	/**
	 * @return boolean whether the text in list items should be HTML encoded before rendering
	 */
	public function isEncodeText()
	{
		return $this->getViewState('EncodeText',true);
	}

	/**
	 * Sets the value indicating whether the text in list items should be HTML encoded before rendering
	 * @param boolean whether the text should be HTML encoded before rendering
	 */
	public function setEncodeText($value)
	{
		$this->setViewState('EncodeText',$value,true);
	}

	/**
	 * @return boolean a value indicating whether an automatic postback to the server
     * will occur whenever the user modifies the selection of the list control and
     * then tabs out of it.
	 */
	public function isAutoPostBack()
	{
		return $this->getViewState('AutoPostBack',false);
	}

	/**
	 * Sets the value indicating if postback automatically.
	 * An automatic postback to the server will occur whenever the user
	 * modifies the selection of the list control and then tabs out of it.
	 * @param boolean the value indicating if postback automatically
	 */
	public function setAutoPostBack($value)
	{
		$this->setViewState('AutoPostBack',$value,false);
	}
	
	/**
	 * @return ArrayObject list of TListItem components
	 */
	public function getItems()
	{
		return $this->items;
	}
	
	/**
	 * @return string the field of the data source that provides the text content of the list items.
	 */
	public function getDataTextField()
	{
		return $this->getViewState('DataTextField','');
	}

	/**
	 * @param string the field of the data source that provides the text content of the list items.
	 */
	public function setDataTextField($value)
	{
		$this->setViewState('DataTextField',$value,'');
	}

	/**
	 * @return string the formatting string used to control how data bound to the list control is displayed.
	 */
	public function getDataTextFormatString()
	{
		return $this->getViewState('DataTextFormatString','');
	}

	/**
	 * @param string the formatting string used to control how data bound to the list control is displayed.
	 */
	public function setDataTextFormatString($value)
	{
		$this->setViewState('DataTextFormatString',$value,'');
	}

	/**
	 * @return string the field of the data source that provides the value of each list item.
	 */
	public function getDataValueField()
	{
		return $this->getViewState('DataValueField','');
	}

	/**
	 * @param string the field of the data source that provides the value of each list item.
	 */
	public function setDataValueField($value)
	{
		$this->setViewState('DataValueField',$value,'');
	}
	
	/**
	 * @return TListItem|null the selected item with the lowest cardinal index, null if no selection.
	 */
	public function getSelectedItem()
	{
		$index=$this->getSelectedIndex();
		return $index>=0?$this->items[$index]:null;
	}
	
	/**
	 * @param integer the index of the item to be selected
	 */
	public function setSelectedIndex($index)
	{
		if($index>=0 && $index<$this->items->length())
		{
			$this->clearSelection();
			$this->items[$index]->setSelected(true);
		}
		else
			throw new Exception("Index $index is out of bound.");
	}
	
	/**
	 * @return integer the index of the item being selected, -1 if no selection
	 */
	public function getSelectedIndex()
	{
		foreach($this->items as $index=>$item)
			if($item->isSelected())
				return $index;
		return -1;
	}
	
	/**
	 * @return string the value of the selected item with the lowest cardinal index, empty if no selection
	 */
	public function getSelectedValue()
	{
		$index=$this->getSelectedIndex();
		return $index>=0?$this->items[$index]->getValue():'';
	}

	/**
	 * Sets selection by item value.
	 * @param string the value of the item to be selected.
	 */
	public function setSelectedValue($value)
    {
        $this->SelectedValue = $value;
        foreach($this->items as $item)
        {
            $item->setSelected(($item->getValue()==$value));
        }
    }

	/**
	 * Sets all items not selected.
	 */
	public function clearSelection()
	{
		foreach($this->items as $item)
			$item->setSelected(false);
	}
	
	/**
    * Sets all items selected.
    */
    public function allSelection()
    {
        foreach($this->items as $item)
        $item->setSelected(true);
    }

    /**
    * Invert items selection.
    */
    public function invertSelection()
    {
        foreach($this->items as $item)
        if($item->isSelected())
            $item->setSelected(false);
        else
            $item->setSelected(true);
    }

	/**
	* @return mixed the data source that populates the items of the list control.
	*/
	public function getDataSource()
	{
		return $this->dataSource;
	}

	/**
	 * Sets the data source that populates the items of the list control.
	 * @param mixed the data source.
	 */
	public function setDataSource($value)
	{
		if(is_array($value) || is_null($value) || ($value instanceof Traversable))
			$this->dataSource=$value;
		else
			throw new Exception('DataSource must be an array or traversable.');
	}
	
	/**
	 * This method is invoked when <b>OnDataBinding</b> event is raised.
	 * 
	 * Opens up the data source and creates all the TListItem controls
	 * needed to render the data source.
	 * 
	 * @param TEventParameter event parameter
	 */
	protected function onDataBinding($param)
	{
		parent::onDataBinding($param);
		
		if(is_null($this->dataSource))
			return;
			
		// Reset all values.
		$this->items->clear();
		$textField = $this->getDataTextField();
		$valueField = $this->getDataValueField();
		
		foreach($this->dataSource as $key=>$val)
        {
			if ($val instanceof IListItemSource) 
			{
				$text = $val->getItemText();
				$value = $val->getItemValue();
			}
			else
			{
				if(strlen($textField))
				{
					if(isset($val[$textField]))
						$text = $val[$textField];
					else
						throw new Exception('Invalid DataTextField property value');
				}
				else
					$text = $val;
				if(strlen($valueField))
				{
					if(isset($val[$valueField]))
						$value = $val[$valueField];
					else
						throw new Exception('Invalid DataValueField property value');
				}
				else
					$value = $key;
			}
			$item = new TListItem;
            $item->setText($text);
            $item->setValue("$value");
            $item->setSelected($this->SelectedValue == $value);
            $this->items->add($item);
		}
		$this->dataSource=null;
	}

	/**
	 * This method overrides the parent implementation to handle TListItem.
	 * @param TComponent|string the newly parsed object
	 * @param TComponent the template owner
	 */
	public function addParsedObject($object,$context)
	{
		if($object instanceof TListItem)
			$this->items->add($object);
	}

	/**
	 * Overrides parent implementation to disable body addition.
	 * @param mixed the object to be added
	 * @return boolean
	 */
	public function allowBody($object)
	{
		return false;
	}

	/**
	 * Loads viewstate into this control and its children.
	 * This method is overriden to load the items data from view state, 
	 * as well as the mapping between indexes and text/value pairs for
	 * the list items.
	 * @param array viewstate to be loaded
	 */
	public function loadViewState($viewState)
	{
		parent::loadViewState($viewState);
		$this->items=$this->getViewState('Items',new TCollection());
	}

	/**
	 * Returns the viewstate of this control and its children.
	 * This method is overriden to save the items data in view state,
	 * as well as the mapping between indexes and text/value pairs for
	 * the list items.
	 * @return array|null viewstate to be saved
	 */
	public function saveViewState()
	{
		if($this->items->length()>0)
			$this->setViewState('Items',$this->items);
		return parent::saveViewState();
	}
	
	/**
	 * Loads user input data.
	 * This method is primarly used by framework developers.
	 * @param string the key that can be used to retrieve data from the input data collection
	 * @param array the input data collection
	 * @return boolean whether the data of the component has been changed
	 */
	public function loadPostData($key,&$values)
	{
		if(isset($values[$key]))
		{
			$selection=$values[$key];
			if(!is_array($selection))
				$selection=array($selection);
		}
		else if($this->isEnabled())
			$selection=array();
		else
			return false;
		$oldSelection=array();
		foreach($this->items as $item)
			if($item->isSelected())
				$oldSelection[]=$item->getValue();
		if($selection===$oldSelection)
			return false;
		else
		{
			foreach($this->items as $item)
				$item->setSelected(in_array($item->getValue(),$selection));
			return true;
		}
	}

	/**
	 * Raises postdata changed event.
	 * This method calls {@link OnSelectionChanged} method.
	 * This method is primarly used by framework and component developers.
	 */
	public function raisePostDataChangedEvent()
	{
		$this->onSelectionChanged(new TEventParameter);
	}

	/**
	 * This method is invoked when the value of the <b>Selection</b> property changes between posts to the server.
	 * The method raises 'OnSelectionChanged' event to fire up the event delegates.
	 * If you override this method, be sure to call the parent implementation
	 * so that the event delegates can be invoked.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	public function onSelectionChanged($param)
	{
		$this->raiseEvent('OnSelectionChanged',$this,$param);
	}

	/**
	 * Returns the value of the property that needs validation.
	 * @return mixed the property value to be validated
	 */
	public function getValidationPropertyValue()
	{
		return $this->getSelectedValue();
	}
}

?>