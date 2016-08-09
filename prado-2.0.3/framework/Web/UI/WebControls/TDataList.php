<?php
/**
 * TDataList class file
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
 * @version $Revision: 1.19 $  $Date: 2005/04/03 21:18:33 $
 * @package System.Web.UI.WebControls
 */

/**
 * TBaseDataList class file
 */
require_once(dirname(__FILE__).'/TBaseDataList.php');

/**
 * TDataListItem class file
 */
require_once(dirname(__FILE__).'/TDataListItem.php');

/**
 * TDataList class
 *
 * TDataList represents a data bound and updatable list control.
 *
 * It can be used to display and maintain a list of data items (rows, records).
 * There are three kinds of layout determined by the <b>RepeatLayout</b>
 * property. The <b>Table</b> layout displays a table and each table cell 
 * contains a data item. The <b>Flow</b> layout uses the span tag to organize
 * the presentation of data items. The <b>Raw</b> layout displays all templated
 * content without any additional decorations (therefore, you can use arbitrary
 * complex UI layout). In case when the layout is Table or Flow,
 * the number of table/flow columns is determined by the <b>RepeatColumns</b>
 * property, and the data items are enumerated according to the <b>RepeatDirection</b> property.
 *
 * To use TDataList, sets its <b>DataSource</b> property and invokes dataBind()
 * afterwards. The data will be populated into the TDataList and saved as data items.
 * A data item can be at one of three states: normal, selected and edit.
 * The state determines which template is used to display the item.
 * In particular, data items are displayed using the following templates,
 * <b>ItemTemplate</b>, <b>AlternatingItemTemplate</b>,
 * <b>SelectedItemTemplate</b>, <b>EditItemTemplate</b>. In addition, the
 * <b>HeaderTemplate</b>, <b>FooterTemplate</b>, and <b>SeparatorTemplate</b>
 * can be used to decorate the overall presentation.
 *
 * To change the state of a data item, set either the <b>EditItemIndex</b> property
 * or the <b>SelectedItemIndex</b> property.
 *
 * When an item template contains a button control that raises an <b>OnCommand</b>
 * event, the event will be bubbled up to the data list control.
 * If the event's command name is recognizable by the data list control,
 * a corresponding item event will be raised. The following item events will be
 * raised upon a specific command:
 * - OnEditCommand, edit
 * - OnCancelCommand, cancel
 * - OnSelectCommand, select
 * - OnDeleteCommand, delete
 * - OnUpdateCommand, update
 * The data list will always raise an <b>OnItemCommand</b>
 * upon its receiving a bubbled <b>OnCommand</b> event.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>RepeatColumns</b>, integer, default=1, kept in viewstate
 *   <br>Gets or sets the number of columns that the list should be displayed with.
 * - <b>RepeatDirection</b>, string (Vertical, Horizontal), default='Vertical', kept in viewstate
 *   <br>Gets or sets the direction of traversing the list
 * - <b>RepeatLayout</b>, string (Table, Flow, Raw), default=Table, kept in viewstate
 *   <br>Gets or sets how the list should be displayed, using table or using line breaks.
 * - <b>ItemTemplate</b>, string
 *   <br>Gets or sets the template for each data item.
 * - <b>AlternatingItemTemplate</b>, string
 *   <br>Gets or sets the template for alternating data item.
 * - <b>HeaderTemplate</b>, string
 *   <br>Gets or sets the template displayed at the beginning of the list.
 * - <b>FooterTemplate</b>, string
 *   <br>Gets or sets the template displayed at the end of the list.
 * - <b>SeparatorTemplate</b>, string
 *   <br>Gets or sets the template displayed between two items.
 * - <b>SelectedItemTemplate</b>, string
 *   <br>Gets or sets the template displayed for selected item if any.
 * - <b>EditItemTemplate</b>, string
 *   <br>Gets or sets the template displayed for editting item if any.
 * - <b>Items</b>, array, read-only
 *   <br>Gets the list of TDataListItem controls that correspond to each data item.
 * - <b>ItemCount</b>, integer, read-only
 *   <br>Gets the number of TDataListItem controls for data items.
 * - <b>EditItemIndex</b>, integer, default=-1, stored in viewstate
 *   <br>Gets or sets the index for edit item.
 * - <b>EditItem</b>, TDataListItem, read-only
 *   <br>Gets the edit item, null if none
 * - <b>SelectedItemIndex</b>, integer, default=-1, stored in viewstate
 *   <br>Gets or sets the index for selected item.
 * - <b>SelectedItem</b>, TDataListItem, read-only
 *   <br>Gets the selected item, null if none
 * - <b>ItemStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for each item
 * - <b>AlternatingItemStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for each alternating item
 * - <b>EditItemStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for the edit item
 * - <b>SelectedItemStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for the selected item
 * - <b>HeaderStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for the header
 * - <b>FooterStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for the footer
 * - <b>SeparatorStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for each separator
 * - <b>ShowHeader</b>, boolean, default=true, stored in viewstate
 *   <br>Gets or sets the value whether to show header
 * - <b>ShowFooter</b>, boolean, default=true, stored in viewstate
 *   <br>Gets or sets the value whether to show footer
 *
 * Events
 * - <b>OnEditCommand</b>, raised when a button control raises an <b>OnCommand</b> event with 'edit' command.
 * - <b>OnSelectCommand</b>, raised when a button control raises an <b>OnCommand</b> event with 'select' command.
 * - <b>OnUpdateCommand</b>, raised when a button control raises an <b>OnCommand</b> event with 'update' command.
 * - <b>OnCancelCommand</b>, raised when a button control raises an <b>OnCommand</b> event with 'cancel' command.
 * - <b>OnDeleteCommand</b>, raised when a button control raises an <b>OnCommand</b> event with 'delete' command.
 * - <b>OnItemCommand</b>, raised when a button control raises an <b>OnCommand</b> event.
 * - <b>OnItemCreatedCommand</b>, raised right after an item is created.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.19 $ $Date: 2005/04/03 21:18:33 $
 * @package System.Web.UI.WebControls
 */
class TDataList extends TBaseDataList
{
	/**
	 * Constants for item IDs
	 */
	const ID_HEADER='Header';
	const ID_FOOTER='Footer';
	const ID_ITEM='Item';
	const ID_SEPARATOR='Sep';
	/**
	 * Recognized item commands
	 */
	const CMD_EDIT='edit';
	const CMD_UPDATE='update';
	const CMD_SELECT='select';
	const CMD_DELETE='delete';
	const CMD_CANCEL='cancel';

	/**
	 * data source
	 * @var mixed
	 */
	private $dataSource=null;
	/**
	 * Various item templates
	 * @var string
	 */
	private $itemTemplate='';
	private $alternatingItemTemplate='';
	private $headerTemplate='';
	private $footerTemplate='';
	private $separatorTemplate='';
	private $selectedItemTemplate='';
	private $editItemTemplate='';

	/**
	 * list of items
	 * @var TDataListItemCollection
	 */
	private $items=null;
	/**
	 * header item
	 * @var TDataListItem
	 */
	private $header=null;
	/**
	 * footer item
	 * @var TDataListItem
	 */
	private $footer=null;
	/**
	 * separator list
	 * @var TCollection
	 */
	private $separators=null;

	/**
	 * Initializes the item list
	 */
	public function __construct()
	{
		$this->items=new TDataListItemCollection($this);
		$this->separators=new TCollection;
		parent::__construct();
	}

	/**
	 * This method overrides the parent implementation so that no body content is added from template.
	 * @param TComponent|string the newly parsed object
	 * @param TComponent the template owner
	 */
	public function addParsedObject($object,$context)
	{
	}

	/**
	 * Overrides parent implementation to disable body addition.
	 * @param mixed the object to be added
	 * @return boolean
	 */
	public function allowBody($object)
	{
		return ($object instanceof TDataListItem);
	}

	/**
	 * @return integer the index of the selected item in Items array
	 */
	public function getSelectedItemIndex()
	{
		return $this->getViewState('SelectedItemIndex',-1);
	}

	/**
	 * Sets the index of the selected item in Items array
	 * @param string the selected item index
	 */
	public function setSelectedItemIndex($value)
	{
		if($value>=0)
			$this->setViewState('EditItemIndex',-1,-1);
		$oldSelection=$this->getSelectedItemIndex();
		$this->setViewState('SelectedItemIndex',$value,-1);
		if($oldSelection!=$value)
			$this->raiseEvent('OnSelectionChanged',$this,new TEventParameter);
	}

	/**
	 * @return TDataListItem the selected item
	 */
	public function getSelectedItem()
	{
		$index=$this->getSelectedItemIndex();
		if($index>=0 && $index<$this->items->length())
			return $this->items[$index];
		else
			return null;
	}

	/**
	 * @return integer the index of the edit item in Items array
	 */
	public function getEditItemIndex()
	{
		return $this->getViewState('EditItemIndex',-1);
	}

	/**
	 * Sets the index of the edit item in Items array
	 * @param string the edit item index
	 */
	public function setEditItemIndex($value)
	{
		$this->setViewState('EditItemIndex',$value,-1);
		if($value>=0)
			$this->setViewState('SelectedItemIndex',-1,-1);
	}

	/**
	 * @return TDataListItem the edit item
	 */
	public function getEditItem()
	{
		$index=$this->getEditItemIndex();
		if($index>=0 && $index<$this->items->length())
			return $this->items[$index];
		else
			return null;
	}

	/**
	 * @return string the template string for the item
	 */
	public function getItemTemplate()
	{
		return $this->itemTemplate;
	}

	/**
	 * Sets the template string for the item
	 * @param string the item template
	 */
	public function setItemTemplate($value)
	{
		$this->itemTemplate=$value;
	}

	/**
	 * @return string the alternative template string for the item
	 */
	public function getAlternatingItemTemplate()
	{
		return $this->alternatingItemTemplate;
	}

	/**
	 * Sets the alternative template string for the item
	 * @param string the alternative item template
	 */
	public function setAlternatingItemTemplate($value)
	{
		$this->alternatingItemTemplate=$value;
	}

	/**
	 * @return string the header template string
	 */
	public function getHeaderTemplate()
	{
		return $this->headerTemplate;
	}

	/**
	 * Sets the header template.
	 * The template will be parsed immediately.
	 * @param string the header template
	 */
	public function setHeaderTemplate($value)
	{
		$this->headerTemplate=$value;
	}

	/**
	 * @return string the footer template string
	 */
	public function getFooterTemplate()
	{
		return $this->footerTemplate;
	}

	/**
	 * Sets the footer template.
	 * The template will be parsed immediately.
	 * @param string the footer template
	 */
	public function setFooterTemplate($value)
	{
		$this->footerTemplate=$value;
	}

	/**
	 * @return string the separator template string
	 */
	public function getSeparatorTemplate()
	{
		return $this->separatorTemplate;
	}

	/**
	 * Sets the separator template string
	 * @param string the separator template
	 */
	public function setSeparatorTemplate($value)
	{
		$this->separatorTemplate=$value;
	}

	/**
	 * @return string the selected item template string
	 */
	public function getSelectedItemTemplate()
	{
		return $this->selectedItemTemplate;
	}

	/**
	 * Sets the selected item template string
	 * @param string the selected item template
	 */
	public function setSelectedItemTemplate($value)
	{
		$this->selectedItemTemplate=$value;
	}

	/**
	 * @return string the edit item template string
	 */
	public function getEditItemTemplate()
	{
		return $this->editItemTemplate;
	}

	/**
	 * Sets the edit item template string
	 * @param string the edit item template
	 */
	public function setEditItemTemplate($value)
	{
		$this->editItemTemplate=$value;
	}

	/**
	 * @return string the style for each item
	 */
	public function getItemStyle()
	{
		return $this->getViewState('ItemStyle','');
	}

	/**
	 * Sets the style for each item
	 * @param string the style for each item
	 */
	public function setItemStyle($value)
	{
		$this->setViewState('ItemStyle',$value,'');
	}

	/**
	 * @return string the style for each alternating item
	 */
	public function getAlternatingItemStyle()
	{
		return $this->getViewState('AlternatingItemStyle','');
	}

	/**
	 * Sets the style for each alternating item
	 * @param string the style for each alternating item
	 */
	public function setAlternatingItemStyle($value)
	{
		$this->setViewState('AlternatingItemStyle',$value,'');
	}

	/**
	 * @return string the style for edit item
	 */
	public function getEditItemStyle()
	{
		return $this->getViewState('EditItemStyle','');
	}

	/**
	 * Sets the style for edit item
	 * @param string the style for edit item
	 */
	public function setEditItemStyle($value)
	{
		$this->setViewState('EditItemStyle',$value,'');
	}

	/**
	 * @return string the style for selected item
	 */
	public function getSelectedItemStyle()
	{
		return $this->getViewState('SelectedItemStyle','');
	}

	/**
	 * Sets the style for selected item
	 * @param string the style for selected item
	 */
	public function setSelectedItemStyle($value)
	{
		$this->setViewState('SelectedItemStyle',$value,'');
	}

	/**
	 * @return string the style for header
	 */
	public function getHeaderStyle()
	{
		return $this->getViewState('HeaderStyle','');
	}

	/**
	 * Sets the style for header
	 * @param string the style for header
	 */
	public function setHeaderStyle($value)
	{
		$this->setViewState('HeaderStyle',$value,'');
	}

	/**
	 * @return string the style for footer
	 */
	public function getFooterStyle()
	{
		return $this->getViewState('FooterStyle','');
	}

	/**
	 * Sets the style for footer
	 * @param string the style for footer
	 */
	public function setFooterStyle($value)
	{
		$this->setViewState('FooterStyle',$value,'');
	}

	/**
	 * @return string the style for separator
	 */
	public function getSeparatorStyle()
	{
		return $this->getViewState('SeparatorStyle','');
	}

	/**
	 * Sets the style for separator
	 * @param string the style for separator
	 */
	public function setSeparatorStyle($value)
	{
		$this->setViewState('SeparatorStyle',$value,'');
	}

	/**
	 * @return boolean whether the header should be shown
	 */
	public function isShowHeader()
	{
		return $this->getViewState('ShowHeader',true);
	}

	/**
	 * Sets the value indicating whether to show header
	 * @param boolean whether to show header
	 */
	public function setShowHeader($value)
	{
		$this->setViewState('ShowHeader',$value,true);
	}

	/**
	 * @return boolean whether the footer should be shown
	 */
	public function isShowFooter()
	{
		return $this->getViewState('ShowFooter',true);
	}

	/**
	 * Sets the value indicating whether to show footer
	 * @param boolean whether to show footer
	 */
	public function setShowFooter($value)
	{
		$this->setViewState('ShowFooter',$value,true);
	}

	/**
	 * @return Iterator the data source
	 */
	public function getDataSource()
	{
		return $this->dataSource;
	}

	/**
	 * Sets the data source.
	 * @param Iterator the data source.
	 */
	public function setDataSource($value)
	{
		if(is_array($value))
			$value=new ArrayObject($value);
		if(is_null($value) || ($value instanceof Traversable))
			$this->dataSource=$value;
		else
			throw new Exception('DataSource must implement either Iterator or IteratorAggregate interface.');
	}

	/**
	 * @return array list of TDataListItem control
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * @return integer number of TDataListItem control
	 */
	public function getItemCount()
	{
		return $this->items->length();
	}

	/**
	 * @return integer the number of columns that the list should be displayed with.
	 */
	public function getRepeatColumns()
	{
		return $this->getViewState('RepeatColumns', 1);
	}
	
	/**
	 * Sets the number of columns that the list should be displayed with.
	 * @param integer the number of columns that the list should be displayed with.
	 */
	public function setRepeatColumns($value)
	{
		if($value<=0) $value=1;
		$this->setViewState('RepeatColumns', $value,1);
	}
	
	/**
	 * @return string the direction of traversing the list (Vertical, Horizontal)
	 */
	public function getRepeatDirection()
	{
		return $this->getViewState('RepeatDirection', 'Vertical');
	}
	
	/**
	 * Sets the direction of traversing the list (Vertical, Horizontal)
	 * @param string the direction of traversing the list
	 */
	public function setRepeatDirection($value)
	{
		if($value!=='Horizontal')
			$value='Vertical';
		$this->setViewState('RepeatDirection', $value, 'Vertical');
	}
	
	/**
	 * @return string how the list should be displayed, using table or using line breaks (Table, Flow, Raw)
	 */
	public function getRepeatLayout()
	{
		return $this->getViewState('RepeatLayout', 'Table');
	}
	
	/**
	 * Sets how the list should be displayed, using table or using line breaks (Table, Flow, Raw)
	 * @param string how the list should be displayed, using table or using line breaks (Table, Flow, Raw)
	 */
	public function setRepeatLayout($value)
	{
		if($value!=='Flow' && $value!=='Raw')
			$value='Table';
		$this->setViewState('RepeatLayout', $value, 'Table');
	}

	/**
	 * Loads viewstate into this control and its children.
	 * This method is overriden to load the items data from view state.
	 * If the items data is not empty, it will be used to 
	 * populate the data list (to restore the previous view).
	 * @param array viewstate to be loaded
	 */
	public function loadViewState($viewState)
	{
		parent::loadViewState($viewState);
		$items=$this->getViewState('Items',array());
		if(count($items)>0)
		{
			$this->setDataSource($items);
			$this->dataBind(false);
		}
	}

	/**
	 * Returns the viewstate of this control and its children.
	 * This method is overriden to save the items data in view state.
	 * @return array|null viewstate to be saved
	 */
	public function saveViewState()
	{
		if($this->items->length()>0)
		{
			$items=array();
			foreach($this->items as $item)
				$items[$item->getItemIndex()]=$item->getData();
			$this->setViewState('Items',$items);
		}
		return parent::saveViewState();
	}

	/**
	 * @return array the keys used in the data listing control.
	 */
	public function getDataKeys()
	{
		$keys=array();
		$fname=$this->getDataKeyField();
		foreach($this->items as $index=>$item)
		{
			$data=$item->getData();
			if(isset($data[$fname]))
				$keys[$index]=$data[$fname];
		}
		return $keys;
	}

	/**
	 * Parses and intantiates templates.
	 * This method is invoked when <b>OnDataBinding</b> event is raised.
	 * It parses and instantiates all assoicated templates for the 
	 * data listing control and raises related events.
	 * This method should only used by control developers.
	 * @param TEventParameter event parameter
	 */
	protected function onDataBinding($param)
	{
		parent::onDataBinding($param);
		$this->setViewState('Items',null,null);
		$this->removeChildren();
		$this->removeBodies();
		$this->items->clear();
		$this->separators->clear();
		$this->header=null;
		$this->footer=null;
		if(is_null($this->dataSource))
			return;
		if(strlen($this->headerTemplate))
		{
			$this->header=$this->createComponent('TDataListItem',self::ID_HEADER);
			$this->header->setType(TDataListItem::TYPE_HEADER);
			$this->header->instantiateTemplate($this->headerTemplate);
			$this->addBody($this->header);
			$p=new TDataListItemEventParameter;
			$p->item=$this->header;
			$this->onItemCreated($p);
		}
		$selectedIndex=$this->getSelectedItemIndex();
		$editIndex=$this->getEditItemIndex();
		$count=0;
		foreach($this->dataSource as $key=>$value)
		{
			if($count>0 && strlen($this->separatorTemplate))
			{
				$separator=$this->createComponent('TDataListItem',self::ID_SEPARATOR."$count");
				$separator->setType(TDataListItem::TYPE_SEPARATOR);
				$separator->instantiateTemplate($this->separatorTemplate);
				$this->addBody($separator);
				$this->separators->add($separator);
			}
			$item=null;
			if($count==$selectedIndex && strlen($this->selectedItemTemplate))
			{
				$item=$this->createComponent('TDataListItem',self::ID_ITEM."$count");
				$item->setType(TDataListItem::TYPE_SELECTED_ITEM);
				$item->instantiateTemplate($this->selectedItemTemplate);
			}
			else if($count==$editIndex && strlen($this->editItemTemplate))
			{
				$item=$this->createComponent('TDataListItem',self::ID_ITEM."$count");
				$item->setType(TDataListItem::TYPE_EDIT_ITEM);
				$item->instantiateTemplate($this->editItemTemplate);
			}
			else if($count%2==1 && strlen($this->alternatingItemTemplate))
			{
				$item=$this->createComponent('TDataListItem',self::ID_ITEM."$count");
				$item->setType(TDataListItem::TYPE_ALTERNATING_ITEM);
				$item->instantiateTemplate($this->alternatingItemTemplate);
			}
			else if(strlen($this->itemTemplate))
			{
				$item=$this->createComponent('TDataListItem',self::ID_ITEM."$count");
				$item->setType(TDataListItem::TYPE_ITEM);
				$item->instantiateTemplate($this->itemTemplate);
			}
			if(!is_null($item))
			{
				$item->setItemIndex($count);
				$item->setData($value);
				$this->items->add($item);
				$p=new TDataListItemEventParameter;
				$p->item=$item;
				$this->onItemCreated($p);
			}
			$count++;
		}
		if(strlen($this->footerTemplate))
		{
			$this->footer=$this->createComponent('TDataListItem',self::ID_FOOTER);
			$this->footer->setType(TDataListItem::TYPE_FOOTER);
			$this->footer->instantiateTemplate($this->footerTemplate);
			$this->addBody($this->footer);
			$p=new TDataListItemEventParameter;
			$p->item=$this->footer;
			$this->onItemCreated($p);
		}
	}

	/**
	 * Handles <b>OnBubbleEvent</b>.
	 * This method overrides parent's implementation to handle
	 * <b>OnItemCommand</b> event that is bubbled from 
	 * TDataListItem child controls.
	 * This method should only be used by control developers.
	 * @param TControl the sender of the event
	 * @param TEventParameter event parameter
	 * @return boolean whether the event bubbling should stop here.
	 */
	protected function onBubbleEvent($sender,$param)
	{
		if($param instanceof TDataListCommandEventParameter)
		{
			$this->onItemCommand($param);
			return true;
		}
		else
			return false;
	}

	/**
	 * Raises <b>OnItemCreated</b> event.
	 * This method is invoked after a data list item is created.
	 * You may override this method to provide customized event handling.
	 * Be sure to call parent's implementation so that
	 * event handlers have chance to respond to the event.
	 * The TDataListItem control responsible for the event
	 * can be determined from the event parameter's <b>item</b>
	 * field.
	 * @param TDataListItemEventParameter event parameter
	 */
	protected function onItemCreated($param)
	{
		$this->raiseEvent('OnItemCreated',$this,$param);
	}

	/**
	 * Raises <b>OnItemCommand</b> and other related events.
	 * This method is invoked after a button control in
	 * a template raises <b>OnCommand</b> event.
	 * You may override this method to provide customized event handling.
	 * Be sure to call parent's implementation so that
	 * event handlers have chance to respond to the event.
	 * The TDataListItem control responsible for the event
	 * can be determined from the event parameter's <b>item</b>
	 * field. The initial sender of the <b>OnCommand</b> event
	 * is in <b>source</b> field. The command name and parameter
	 * are in <b>name</b> and <b>parameter</b> fields, respectively.
	 * @param TDataListCommandEventParameter event parameter
	 */
	protected function onItemCommand($param)
	{
		if($param->name==self::CMD_EDIT)
			$this->raiseEvent('OnEditCommand',$this,$param);
		else if($param->name==self::CMD_UPDATE)
			$this->raiseEvent('OnUpdateCommand',$this,$param);
		else if($param->name==self::CMD_DELETE)
			$this->raiseEvent('OnDeleteCommand',$this,$param);
		else if($param->name==self::CMD_CANCEL)
			$this->raiseEvent('OnCancelCommand',$this,$param);
		else if($param->name==self::CMD_SELECT)
			$this->raiseEvent('OnSelectCommand',$this,$param);
		$this->raiseEvent('OnItemCommand',$this,$param);
	}

	/**
	 * Returns the attributes to be rendered.
	 * @return ArrayObject attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$attributes=parent::getAttributesToRender();
		if($this->getRepeatLayout()==='Table')
		{
			if(($cellSpacing=$this->getCellSpacing())>=0)
				$attributes['cellspacing']=$cellSpacing;
			else
				$attributes['cellspacing']='0';
			if(($cellPadding=$this->getCellPadding())>=0)
				$attributes['cellpadding']=$cellPadding;
			else
				$attributes['cellpadding']='0';
			$grid=$this->getGridLines();
			if($grid!='None')
			{
				if($grid=='Horizontal')
					$attributes['rules']='rows';
				else if($grid=='Both')
					$attributes['rules']='all';
				else if($grid=='Vertical')
					$attributes['rules']='cols';
				if(!isset($attributes['border']))
					$attributes['border']='1';
			}
			$align=$this->getHorizontalAlign();
			if($align!='NotSet')
				$attributes['align']=$align;
		}
		return $attributes;
	}

	/**
	 * Displays the data list.
	 * @return string the rendering result.
	 */
	public function render()
	{
		$attr=$this->renderAttributes();
		if($this->getRepeatLayout()=='Raw')
		{
			$content="<span $attr>";
			foreach($this->getBodies() as $body)
				$content.=$body->render();
			return $content."</span>";
		}
		$count=$this->items->length();
		$cols=$this->getRepeatColumns();
		$rows=$count%$cols==0?$count/$cols:intval($count/$cols)+1;

		$output=array();
		if($this->getRepeatDirection()==='Vertical')
		{
			$n=0;
			for($j=0;$j<$cols;++$j)
			{
				$r=($count-$n)%($cols-$j)==0?($count-$n)/($cols-$j):intval(($count-$n)/($cols-$j))+1;
				for($i=0;$i<$rows;++$i)
				{
					if($i<$r)
					{
						$output[$i][$j]=$n;
						$n++;
					}
					else
						$output[$i][$j]=-1;
				}
			}
		}
		else
		{
			for($i=0;$i<$rows;++$i)
			{
				for($j=0;$j<$cols;++$j)
				{
					$n=$i*$cols+$j;
					if($n<$count)
						$output[$i][$j]=$n;
					else
						$output[$i][$j]=-1;
				}
			}
		}

		$itemStyle=$this->getItemStyle(); if(strlen($itemStyle)) $itemStyle=' style="'.$itemStyle.'"';
		$altItemStyle=$this->getAlternatingItemStyle(); if(strlen($altItemStyle)) $altItemStyle=' style="'.$altItemStyle.'"';
		$separatorStyle=$this->getSeparatorStyle(); if(strlen($separatorStyle)) $separatorStyle=' style="'.$separatorStyle.'"';
		$editItemStyle=$this->getEditItemStyle(); if(strlen($editItemStyle)) $editItemStyle=' style="'.$editItemStyle.'"';
		$selectedItemStyle=$this->getSelectedItemStyle(); if(strlen($selectedItemStyle)) $selectedItemStyle=' style="'.$selectedItemStyle.'"';
		$headerStyle=$this->getHeaderStyle(); if(strlen($headerStyle)) $headerStyle=' style="'.$headerStyle.'"';
		$footerStyle=$this->getFooterStyle(); if(strlen($footerStyle)) $footerStyle=' style="'.$footerStyle.'"';

		if($this->getRepeatLayout()==='Table')
		{
			if($cols<=1)
				$colspan='';
			else
			{
				if(count($seps)>0)
					$colspan=" colspan=\"".($cols+$cols)."\"";
				else
					$colspan=" colspan=\"$cols\"";
			}
			$content="<table $attr>\n";
			if($this->header && $this->isShowHeader())
			{
				$a=$this->header->renderAttributes();
				$content.="<tr>\n<td$colspan$headerStyle $a>".$this->header->render()."</td></tr>\n";
			}
			for($i=0;$i<$rows;++$i)
			{
				$content.="<tr>\n";
				for($j=0;$j<$cols;++$j)
				{
					$index=$output[$i][$j];
					if(isset($this->items[$index]))
					{
						$item=$this->items[$index];
						$type=$item->getType();
						if($type==TDataListItem::TYPE_SELECTED_ITEM)
							$style=$selectedItemStyle;
						else if($type==TDataListItem::TYPE_EDIT_ITEM)
							$style=$editItemStyle;
						else if($type==TDataListItem::TYPE_ITEM)
							$style=$itemStyle;
						else if($type==TDataListItem::TYPE_ALTERNATING_ITEM)
							$style=$altItemStyle;
						else
							$style='';
						$a=$item->renderAttributes();
						$content.="<td$style $a>".$item->render()."</td>\n";
					}
					else
						$content.="<td>&nbsp;</td>\n";
					if(isset($this->separators[$index]))
					{
						$separator=$this->separators[$index];
						$a=$separator->renderAttributes();
						if($cols>1)
							$content.="<td$separatorStyle $a>".$separator->render()."</td>\n";
						else
							$content.="</tr>\n<tr>\n<td$separatorStyle $a>".$separator->render()."</td>\n";
					}
				}
				$content.="</tr>\n";
			}
			if($this->footer && $this->isShowFooter())
			{
				$a=$this->footer->renderAttributes();
				$content.="<tr>\n<td$colspan$footerStyle $a>".$this->footer->render()."</td></tr>\n";
			}
			$content.="</table>\n";
		}
		else // RepeatLayout=Flow
		{
			$content="<span $attr>";
			if($this->header && $this->isShowHeader())
			{
				$a=$this->header->renderAttributes();
				$content.="<span$headerStyle $a>".$this->header->render()."</span><br/>\n";
			}
			for($i=0;$i<$rows;++$i)
			{
				for($j=0;$j<$cols;++$j)
				{
					$index=$output[$i][$j];
					if(isset($this->items[$index]))
					{
						$item=$this->items[$index];
						$type=$item->getType();
						if($type==TDataListItem::TYPE_SELECTED_ITEM)
							$style=$selectedItemStyle;
						else if($type==TDataListItem::TYPE_EDIT_ITEM)
							$style=$editItemStyle;
						else if($type==TDataListItem::TYPE_ITEM)
							$style=$itemStyle;
						else if($type==TDataListItem::TYPE_ALTERNATING_ITEM)
							$style=$altItemStyle;
						else
							$style='';
						$a=$item->renderAttributes();
						$content.="<span$style $a>".$item->render()."</span>\n";
					}
					if(isset($this->separators[$index]))
					{
						$separator=$this->separators[$index];
						$a=$separator->renderAttributes();
						if($cols>1)
							$content.="<span$separatorStyle $a>".$separator->render()."</span>";
						else
							$content.="</span><br/>\n<span$separatorStyle $a>".$separator->render()."</span>";
					}
				}
				$content.="<br/>\n";
			}
			if($this->footer && $this->isShowFooter())
			{
				$a=$this->footer->renderAttributes();
				$content.="<span$footerStyle $a>".$this->footer->render()."</span><br/>\n";
			}
			$content.="</span>\n";
		}
		return $content;
	}
}


/**
 * TDataListItemEventParameter class
 *
 * TDataListItemEventParameter encapsulates the parameter data for <b>OnItemCreated</b>
 * event of TDataList controls.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TDataListItemEventParameter extends TEventParameter
{
	/**
	 * The TDataListItem control responsible for the event.
	 * @var TDataListItem
	 */
	public $item=null;
}

/**
 * TDataListCommandEventParameter class
 *
 * TDataListCommandEventParameter encapsulates the parameter data for <b>OnItemCommand</b>
 * event of TDataList controls.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TDataListCommandEventParameter extends TCommandEventParameter
{
	/**
	 * The TDataListItem control responsible for the event.
	 * @var TDataListItem
	 */
	public $item=null;
	/**
	 * The control originally raises the <b>OnCommand</b> event.
	 * @var TControl
	 */
	public $source=null;
}


class TDataListItemCollection extends TCollection
{
	protected $list=null;

	public function __construct($list)
	{
		parent::__construct();
		$this->list=$list;
	}

	protected function onAddItem($item)
	{
		if($item instanceof TDataListItem)
		{
			$this->list->addBody($item);
			return true;
		}
		else
			return false;
	}

	protected function onRemoveItem($item)
	{
		$this->list->getBodies()->remove($item);
	}
}

?>