<?php
/**
 * TDataGrid class file
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
 * @version $Revision: 1.24 $  $Date: 2005/08/08 08:39:31 $
 * @package System.Web.UI.WebControls
 */

/**
 * TDataGridItem class file
 */
require_once(dirname(__FILE__).'/TDataGridItem.php');
/**
 * TDataGridColumn class file
 */
require_once(dirname(__FILE__).'/TDataGridColumn.php');
/**
 * TBoundColumn class file
 */
require_once(dirname(__FILE__).'/TBoundColumn.php');
/**
 * TTableCell class file
 */
require_once(dirname(__FILE__).'/TTableCell.php');
/**
 * TLinkButton class file
 */
require_once(dirname(__FILE__).'/TLinkButton.php');

/**
 * TDataGrid class
 *
 * TDataGrid represents a data bound and updatable grid control.
 *
 * To use TDataGrid, sets its <b>DataSource</b> property and invokes dataBind()
 * afterwards. The data will be populated into the TDataGrid and saved in the <b>Items</b> property.
 *
 * Each item is associated with a row of data and will be displayed as a row in table.
 * A data item can be at one of three states: normal, selected and edit.
 * The state determines how the item will be displayed. For example, if an item
 * is in edit state, it may be displayed as a table row with input text boxes instead
 * static text as in normal state.
 * To change the state of a data item, set either the <b>EditItemIndex</b> property
 * or the <b>SelectedItemIndex</b> property.
 *
 * A datagrid is specified with a list of columns. Each column specifies how the corresponding
 * table column will be displayed. For example, the header/footer text of that column,
 * the cells in that column, and so on. The following column types are provided by the framework,
 * - TBoundColumn, associated with a specific field in datasource and displays the corresponding data.
 * - TEditCommandColumn, displaying edit/update/cancel command buttons
 * - TButtonColumn, displaying generic command buttons that may be bound to specific field in datasource.
 * - THyperLinkColumn, displaying a hyperlink that may be boudn to specific field in datasource.
 * - TTemplateColumn, displaying content based on templates.
 *
 * There are three ways to specify columns for a datagrid.
 * <ul>
 *  <li>Automatically generated based on data source. By setting <b>AutoGenerateColumns</b>
 *  to true, a list of columns will be automatically generated based on the schema of the data source.
 *  Each column corresponds to a column of the data.</li>
 *  <li>Specified in template. For example,
 *    <code>
 *     <com:TDataGrid ...>
 *        <com:TBoundColumn .../>
 *        <com:TEditCommandColumn .../>
 *     </com:TDataGrid>
 *    </code>
 *  </li>
 *  <li>Manually created in code. Columns can be manipulated via the <b>Columns</b> property of 
 *  the datagrid. For example,
 *    <code>
 *    $column=$datagrid->createComponent('TBoundColumn');
 *    $datagrid->Columns->add($column);
 *    </code>
 *  </li>
 * </ul>
 * Note, automatically generated columns cannot be accessed via <b>Columns</b> property.
 *
 * TDataGrid supports sorting. If the <b>AllowSorting</b> is set to true, a column
 * whose <b>SortExpression</b> is not empty will have its header text displayed as a link button.
 * Clicking on the link button will raise <b>OnSortCommand</b> event. You can respond to this event,
 * sort the data source according to the event parameter, and then invoke databind on the datagrid.
 *
 * TDataGrid supports paging. If the <b>AllowPaging</b> is set to true, a pager will be displayed
 * on top and/or bottom of the table. How the pager will be displayed is determined by <b>PagerDisplay</b>
 * and <b>PagerButtonCount</b> properties. The former decides the position of the pager and latter
 * specifies how many buttons are to be used for paging purpose. Clicking on a pager button will raise
 * an <b>OnPageCommand</b> event. You can respond to this event, specify the page to be displayed by
 * setting <b>CurrentPageIndex</b> property, and then invoke databind on the datagrid.
 *
 * TDataGrid supports two kinds of paging. The first one is based on the number of data items in
 * datasource. The number of pages <b>PageCount</b> is calculated based the item number and the
 * <b>PageSize</b> property. The datagrid will manage which section of the data source to be displayed
 * based on the <b>CurrentPageIndex</b> property.
 * The second approach calculates the page number based on the <b>VirtualItemCount</b> property and
 * the <b>PageSize</b> property. The datagrid will always display from the beginning of the datasource
 * upto the number of <b>PageSize> data items. This approach is especially useful when the datasource may
 * contain too many data items to be managed by the datagrid efficiently.
 *
 * When the datagrid contains a button control that raises an <b>OnCommand</b>
 * event, the event will be bubbled up to the datagrid control.
 * If the event's command name is recognizable by the datagrid control,
 * a corresponding item event will be raised. The following item events will be
 * raised upon a specific command:
 * - OnEditCommand, edit
 * - OnCancelCommand, cancel
 * - OnSelectCommand, select
 * - OnDeleteCommand, delete
 * - OnUpdateCommand, update
 * - OnPageCommand, page
 * - OnSortCommand, sort
 * The data list will always raise an <b>OnItemCommand</b>
 * upon its receiving a bubbled <b>OnCommand</b> event.
 *
 * An <b>OnItemCreated</b> event will be raised right after each item is created in the datagrid.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>Items</b>, TDataGridItemCollection, read-only
 *   <br>Gets the list of TDataGridItem controls that correspond to each data item.
 * - <b>Columns</b>, TCollection, read-only
 *   <br>Gets the list of TDataGridColumn controls that are manually specified or created.
 * - <b>AutoGenerateColumns</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets the value indicating whether columns should be generated automatically based on the data in datasource.
 * - <b>AllowSorting</b>, boolean, default=false, kept in viewstate
 *   <br>Gets or sets the value indicating whether sorting should be enabled.
 * - <b>AllowPaging</b>, boolean, default=false, kept in viewstate
 *   <br>Gets or sets the value indicating whether paging should be enabled.
 * - <b>AllowCustomPaging</b>, boolean, default=false, kept in viewstate
 *   <br>Gets or sets the value indicating whether custom paging should be enabled.
 * - <b>CurrentPageIndex</b>, integer, default=0, stored in viewstate
 *   <br>Gets or sets the index for the page to be displayed
 * - <b>PageSize</b>, integer, default=10, stored in viewstate
 *   <br>Gets or sets the number of data items to be displayed in each page.
 * - <b>PageCount</b>, integer, read-only
 *   <br>Gets the number of pages to be displayed.
 * - <b>VirtualItemCount</b>, integer, default=0, stored in viewstate
 *   <br>Gets or sets the number of data items available for paging purpose when custom paging is enabled.
 * - <b>PagerButtonCount</b>, integer, default=10, stored in viewstate
 *   <br>Gets or sets the number of buttons to be displayed in pager for navigating among pages.
 * - <b>PagerDisplay</b>, string (None,Top,Bottom,TopAndBottom), default=Bottom, stored in viewstate
 *   <br>Gets or sets where the pager should be displayed.
 * - <b>EditItemIndex</b>, integer, default=-1, stored in viewstate
 *   <br>Gets or sets the index for edit item.
 * - <b>EditItem</b>, TDataGridItem, read-only
 *   <br>Gets the edit item, null if none
 * - <b>EditItemStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for the edit item
 * - <b>EditItemCssClass</b>, string, stored in viewstate
 *   <br>Gets or sets the css classs for the edit item
 * - <b>SelectedItemIndex</b>, integer, default=-1, stored in viewstate
 *   <br>Gets or sets the index for selected item.
 * - <b>SelectedItem</b>, TDataGridItem, read-only
 *   <br>Gets the selected item, null if none
 * - <b>SelectedItemStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for the selected item
 * - <b>SelectedItemCssClass</b>, string, stored in viewstate
 *   <br>Gets or sets the css class for the selected item
 * - <b>ItemStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for each item
 * - <b>ItemCssClass</b>, string, stored in viewstate
 *   <br>Gets or sets the css class for each item
 * - <b>AlternatingItemStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for each alternating item
 * - <b>AlternatingItemCssClass</b>, string, stored in viewstate
 *   <br>Gets or sets the css class for each alternating item
 * - <b>HeaderStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for the header
 * - <b>HeaderCssClass</b>, string, stored in viewstate
 *   <br>Gets or sets the css class for the header
 * - <b>FooterStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for the footer
 * - <b>FooterCssClass</b>, string, stored in viewstate
 *   <br>Gets or sets the css class for the footer
 * - <b>PagerStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for the pager
 * - <b>PagerCssClass</b>, string, stored in viewstate
 *   <br>Gets or sets the css class for the pager
 * - <b>ShowHeader</b>, boolean, default=true, stored in viewstate
 *   <br>Gets or sets the value whether to show header
 * - <b>ShowFooter</b>, boolean, default=true, stored in viewstate
 *   <br>Gets or sets the value whether to show footer
 * - <b>Header</b>, TDataGridItem
 *   <br>Gets the header of the data grid.
 * - <b>Footer</b>, TDataGridItem
 *   <br>Gets the footer of the data grid.
 * - <b>Pager</b>, TDataGridItem
 *   <br>Gets the pager of the data grid.
 * - <b>BackImageUrl</b>, string, kept in viewstate
 *   <br>Gets or sets the URL of the background image to display behind the datagrid.
 *
 * Events
 * - <b>OnEditCommand</b>, raised when a button control raises an <b>OnCommand</b> event with 'edit' command.
 * - <b>OnSelectCommand</b>, raised when a button control raises an <b>OnCommand</b> event with 'select' command.
 * - <b>OnUpdateCommand</b>, raised when a button control raises an <b>OnCommand</b> event with 'update' command.
 * - <b>OnCancelCommand</b>, raised when a button control raises an <b>OnCommand</b> event with 'cancel' command.
 * - <b>OnDeleteCommand</b>, raised when a button control raises an <b>OnCommand</b> event with 'delete' command.
 * - <b>OnPageCommand</b>, raised when a button control raises an <b>OnCommand</b> event with 'page' command.
 * - <b>OnSortCommand</b>, raised when a button control raises an <b>OnCommand</b> event with 'sort' command.
 * - <b>OnItemCommand</b>, raised when a button control raises an <b>OnCommand</b> event.
 * - <b>OnItemCreatedCommand</b>, raised right after an item is created.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.24 $ $Date: 2005/08/08 08:39:31 $
 * @package System.Web.UI.WebControls
 */
class TDataGrid extends TBaseDataList
{
	/**
	 * the maximum number of items that can be displayed within a page
	 */
	const MAX_PAGE_SIZE=10000;
	/**
	 * Constants for item IDs
	 */
	const ID_HEADER='Header';
	const ID_FOOTER='Footer';
	const ID_ITEM='Item';
	const ID_PAGER='Pager';

	/**
	 * Recognized item commands
	 */
	const CMD_EDIT='edit';
	const CMD_UPDATE='update';
	const CMD_SELECT='select';
	const CMD_DELETE='delete';
	const CMD_CANCEL='cancel';
	const CMD_PAGE='page';
	const CMD_SORT='sort';

	/**
	 * data source
	 * @var mixed
	 */
	private $dataSource=null;
	/**
	 * number of pages available
	 * @var string
	 */
	private $pageCount=1;

	/**
	 * the header, footer and pager items
	 * @var TDataGridItem
	 */
	private $header=null;
	private $footer=null;
	private $pager=null;

	/**
	 * number of data items in data source
	 * @var integer
	 */
	private $dataItemCount=0;
	/**
	 * list of items
	 * @var TDataGridItemCollection
	 */
	private $items=null;
	/**
	 * list of columns
	 * @var TCollection
	 */
	private $columns=null;
	/**
	 * list of automatically generated columns
	 * @var TCollection
	 */
	private $autoColumns=null;

	/**
	 * Initializes the columns list
	 */
	public function __construct()
	{
		$this->columns=new TCollection;
		$this->autoColumns=new TCollection;
		$this->items=new TDataGridItemCollection($this);
		parent::__construct();
		$this->setTagName('table');
	}

	/**
	 * This method overrides the parent implementation so that no body content is added from template.
	 * @param TComponent|string the newly parsed object
	 */
	public function addParsedObject($object,$context)
	{
		if($object instanceof TDataGridColumn)
		{
			$this->addChild($object);
			$this->synchronizeControl($object);
			//$this->addChild($object);
			$this->columns->add($object);
		}
	}

	/**
	 * Overrides parent implementation to disable body addition.
	 * @param mixed the object to be added
	 * @return boolean
	 */
	public function allowBody($object)
	{
		return ($object instanceof TDataGridItem);
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
	 * @return TDataGridItem the selected item
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
	 * @return TDataGridItem the edit item
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
	 * @return string the style for each item
	 */
	public function getItemCssClass()
	{
		return $this->getViewState('ItemCssClass','');
	}

	/**
	 * Sets the style for each item
	 * @param string the style for each item
	 */
	public function setItemCssClass($value)
	{
		$this->setViewState('ItemCssClass',$value,'');
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
	 * @return string the style for each alternating item
	 */
	public function getAlternatingItemCssClass()
	{
		return $this->getViewState('AlternatingItemCssClass','');
	}
 
	/**
	 * Sets the style for each alternating item
	 * @param string the style for each alternating item
	 */
	public function setAlternatingItemCssClass($value)
	{
		$this->setViewState('AlternatingItemCssClass',$value,'');
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
	 * @return string the style for edit item
	 */
	public function getEditItemCssClass()
	{
		return $this->getViewState('EditItemCssClass','');
	}

	/**
	 * Sets the style for edit item
	 * @param string the style for edit item
	 */
	public function setEditItemCssClass($value)
	{
		$this->setViewState('EditItemCssClass',$value,'');
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
	 * @return string the style for selected item
	 */
	public function getSelectedItemCssClass()
	{
		return $this->getViewState('SelectedItemCssClass','');
	}

	/**
	 * Sets the style for selected item
	 * @param string the style for selected item
	 */
	public function setSelectedItemCssClass($value)
	{
		$this->setViewState('SelectedItemCssClass',$value,'');
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
	 * @return string the style for header
	 */
	public function getHeaderCssClass()
	{
		return $this->getViewState('HeaderCssClass','');
	}

	/**
	 * Sets the style for header
	 * @param string the style for header
	 */
	public function setHeaderCssClass($value)
	{
		$this->setViewState('HeaderCssClass',$value,'');
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
	 * @return string the style for footer
	 */
	public function getFooterCssClass()
	{
		return $this->getViewState('FooterCssClass','');
	}

	/**
	 * Sets the style for footer
	 * @param string the style for footer
	 */
	public function setFooterCssClass($value)
	{
		$this->setViewState('FooterCssClass',$value,'');
	}

	/**
	 * @return string the style for pager
	 */
	public function getPagerStyle()
	{
		return $this->getViewState('PagerStyle','');
	}

	/**
	 * Sets the style for pager
	 * @param string the style for pager
	 */
	public function setPagerStyle($value)
	{
		$this->setViewState('PagerStyle',$value,'');
	}

  	/**
	 * @return string the style for pager
	 */
	public function getPagerCssClass()
	{
		return $this->getViewState('PagerCssClass','');
	}
 
	/**
	 * Sets the style for pager
	 * @param string the style for pager
	 */
	public function setPagerCssClass($value)
	{
		$this->setViewState('PagerCssClass',$value,'');
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
		return $this->getViewState('ShowFooter',false);
	}

	/**
	 * Sets the value indicating whether to show footer
	 * @param boolean whether to show footer
	 */
	public function setShowFooter($value)
	{
		$this->setViewState('ShowFooter',$value,false);
	}

	/**
	 * @return Traversable the data source
	 */
	public function getDataSource()
	{
		return $this->dataSource;
	}

	/**
	 * Sets the data source.
	 * @param Traversable|array the data source.
	 */
	public function setDataSource($value)
	{
		if(is_null($value))
			$this->dataSource=null;
		else if(is_array($value) || ($value instanceof Traversable))
			$this->dataSource=new TCollection($value);
		else
			throw new Exception('DataSource must be an array or an object implementing Traversable interface.');
	}

	/**
	 * @return TCollection list of explicitly declared columns
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * @return TDataGridItemCollection list of TDataGridItem control
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * @return integer virtual number of items in the grid
	 */
	public function getVirtualItemCount()
	{
		return $this->getViewState('VirtualItemCount',0);
	}

	/**
	 * @param integer virtual number of items in the grid
	 */
	public function setVirtualItemCount($value)
	{
		if(!is_integer($value) || $value<0)
			throw new Exception('VirtualItemCount must be an integer greater equal than 0.');
		$this->setViewState('VirtualItemCount',$value,0);
	}

	/**
	 * @return integer the number of pages available
	 */
	public function getPageCount()
	{
		$itemCount=$this->isAllowCustomPaging()?$this->getVirtualItemCount():$this->dataItemCount;
		$pageCount=intval(($itemCount+$this->getPageSize()-1)/$this->getPageSize());
		return $pageCount>0?$pageCount:1;
	}

	/**
	 * @return integer the number of rows displayed within a page
	 */
	public function getPageSize()
	{
		return $this->getViewState('PageSize',10);
	}

	/**
	 * @param integer the number of rows displayed within a page
	 */
	public function setPageSize($value)
	{
		if(!is_integer($value) || $value<=0 || $value>self::MAX_PAGE_SIZE)
			throw new Exception('Page size must be an integer between 1 and '.self::MAX_PAGE_SIZE.'.');
		$this->setViewState('PageSize',$value,10);
	}

	/**
	 * @return integer the index of the current page
	 */
	public function getCurrentPageIndex()
	{
		return $this->getViewState('CurrentPageIndex',0);
	}

	/**
	 * @param integer the index of the current page
	 */
	public function setCurrentPageIndex($value)
	{
		$pageCount=$this->getPageCount()-1;
		if(!is_integer($value) || $value<0 || $value>$pageCount)
			throw new Exception("CurrentPageIndex must be an integer between 0 and $pageCount.");
		$this->setViewState('CurrentPageIndex',$value,0);
	}

	/**
	 * @return string where the pager is displayed
	 */
	public function getPagerDisplay()
	{
		return $this->getViewState('PagerDisplay','Bottom');
	}

	/**
	 * @param string  where the pager is displayed
	 */
	public function setPagerDisplay($value)
	{
		if($value!=='None' && $value!=='Bottom' && $value!=='Top' && $value!=='TopAndBottom')
			$value='Bottom';
		$this->setViewState('PagerDisplay',$value,'Bottom');
	}

	/**
	 * @return integer number of link buttons to be displayed in pager
	 */
	public function getPagerButtonCount()
	{
		return $this->getViewState('PagerButtonCount',10);
	}

	/**
	 * @param integer number of link buttons to be displayed in pager
	 */
	public function setPagerButtonCount($value)
	{
		if(!is_integer($value) || $value<=0 || $value>50)
			throw new Exception("Property PagerButtonCount must be set with an integer between 1 and 50.");
		$this->setViewState('PagerButtonCount',$value,10);
	}

	/**
	 * Loads viewstate into this control and its children.
	 * This method is overriden to load the items data from view state.
	 * If the items data is equal to or greater than 0, it will be used to 
	 * populate the data list (to restore the previous view).
	 * @param array viewstate to be loaded
	 */
	public function loadViewState($viewState)
	{
		parent::loadViewState($viewState);
		$cols=$this->getViewState('Columns',array());
		foreach($this->columns as $index=>$column)
			if(isset($cols[$index]))
				$column->loadViewState($cols[$index]);
		$this->setViewState('Columns',null,null);
		$items=$this->getViewState('Items',array());
		$this->setViewState('Items',null,null);
		if(count($items)>=0)
		{
			$this->setDataSource($items);
			$this->dataBind();
		}
	}

	/**
	 * Returns the viewstate of this control and its children.
	 * This method is overriden to save the items data in view state.
	 * @return array|null viewstate to be saved
	 */
	public function saveViewState()
	{
		$cols=array();
		foreach($this->columns as $column)
			$cols[]=$column->saveViewState();
		$this->setViewState('Columns',$cols);
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
	 * @return TDataGridItem the header of the data grid.
	 */
	public function getHeader()
	{
		return $this->header;
	}

	/**
	 * @return TDataGridItem the footer of the data grid.
	 */
	public function getFooter()
	{
		return $this->footer;
	}

	/**
	 * @return TDataGridItem the pager of the data grid.
	 */
	public function getPager()
	{
		return $this->pager;
	}

	/**
	 * @return boolean whether the paging is enabled
	 */
	public function isAllowPaging()
	{
		return $this->getViewState('AllowPaging',false);
	}

	/**
	 * @param boolean whether the paging is enabled
	 */
	public function setAllowPaging($value)
	{
		$this->setViewState('AllowPaging',$value,false);
	}

	/**
	 * @return boolean whether the sorting is enabled
	 */
	public function isAllowSorting()
	{
		return $this->getViewState('AllowSorting',false);
	}

	/**
	 * @param boolean whether the sorting is enabled
	 */
	public function setAllowSorting($value)
	{
		$this->setViewState('AllowSorting',$value,false);
	}


	/**
	 * @return boolean whether the custom paging is enabled
	 */
	public function isAllowCustomPaging()
	{
		return $this->getViewState('AllowCustomPaging',false);
	}

	/**
	 * @param boolean whether the custom paging is enabled
	 */
	public function setAllowCustomPaging($value)
	{
		$this->setViewState('AllowCustomPaging',$value,false);
	}

	/**
	 * @return boolean whether columns should be automatically generated
	 */
	public function isAutoGenerateColumns()
	{
		return $this->getViewState('AutoGenerateColumns',true);
	}

	/**
	 * @param boolean whether columns should be automatically generated
	 */
	public function setAutoGenerateColumns($value)
	{
		$this->setViewState('AutoGenerateColumns',$value,true);
	}

	/**
	 * @return string the background image url for the grid table
	 */
	public function getBackImageUrl()
	{
		return $this->getViewState('BackImageUrl',false);
	}

	/**
	 * @param boolean the background image url for the grid table
	 */
	public function setBackImageUrl($value)
	{
		$this->setViewState('BackImageUrl',$value,false);
	}

	/**
	 * Creates table cells according to column definitions.
	 * This method should only be used by control developers.
	 * @param TDataGridItem the datagrid item to be initialized
	 */
	protected function initializeItem($item)
	{
		$itemType=$item->getType();
		$cellType=$itemType===TDataGridItem::TYPE_HEADER?'TTableHeaderCell':'TTableCell';
		foreach($this->columns as $index=>$column)
		{
			$cell=$item->createComponent($cellType);
			$item->Cells->add($cell);
			$column->initializeCell($cell,$index,$itemType);
		}
		foreach($this->autoColumns as $index=>$column)
		{
			$cell=$item->createComponent($cellType);
			$item->Cells->add($cell);
			$column->initializeCell($cell,$index,$itemType);
		}
	}

	/**
	 * Generates the content in the data grid.
	 * This method is invoked when <b>OnDataBinding</b> event is raised.
	 * It builds up the data grid according to column definitions
	 * data source.
	 * This method should only used by control developers.
	 * @param TEventParameter event parameter
	 */
	protected function onDataBinding($param)
	{
		parent::onDataBinding($param);
		$this->setViewState('Items',null,null);
		$this->removeBodies();
		foreach($this->items as $item)
			$this->removeChild($item);
		if(!is_null($this->header))
		{
			$this->removeChild($this->header);
			$this->header=null;
		}
		if(!is_null($this->footer))
		{
			$this->removeChild($this->footer);
			$this->footer=null;
		}
		if(!is_null($this->pager))
		{
			$this->removeChild($this->pager);
			$this->pager=null;
		}
		$this->items->clear();
		foreach($this->columns as $column)
			$column->dataBind();
		if(is_null($this->dataSource))
			return;
		if($this->isAllowPaging())
		{
			$pageSize=$this->getPageSize();
			$offset=$this->isAllowCustomPaging()?0:$pageSize*$this->getCurrentPageIndex();
		}
		else
		{
			$offset=0;
			$pageSize=self::MAX_PAGE_SIZE;
		}
		$index=0;
		$editIndex=$this->getEditItemIndex();
		$selectedIndex=$this->getSelectedItemIndex();
		$this->dataItemCount=0;
		$dataSource=array();
		foreach($this->dataSource as $data)
		{
			$this->dataItemCount++;
			$dataSource[]=$data;
			if($this->isAllowCustomPaging() && $this->dataItemCount>$offset+$pageSize)
				break;
			if($this->dataItemCount<=$offset || $this->dataItemCount>$offset+$pageSize)
				continue;
			if($index==0 && $this->isAutoGenerateColumns())
			{
				//foreach($this->autoColumns as $column)
				//	$this->removeChild($column);
				$this->autoColumns->clear();
				$columnIndex=0;
				foreach($data as $key=>$value)
				{
					$column=pradoGetApplication()->createComponent('TBoundColumn','AutoColumn'.$columnIndex);
					$column->setHeaderText($key);
					$column->setDataField($key);
					$column->setSortExpression($key);
					$column->dataBind();
					$this->autoColumns->add($column);
					$columnIndex++;
				}
			}
			$item=$this->createComponent('TDataGridItem',self::ID_ITEM.$index);
			if($index==$editIndex)
				$type=TDataGridItem::TYPE_EDIT_ITEM;
			else if($index==$selectedIndex)
				$type=TDataGridItem::TYPE_SELECTED_ITEM;
			else if($index%2)
				$type=TDataGridItem::TYPE_ALTERNATING_ITEM;
			else
				$type=TDataGridItem::TYPE_ITEM;
			$item->setType($type);
			$item->setData($data);
			$item->setItemIndex($index);
			$this->items->add($item);
			$this->initializeItem($item);
			$p=new TDataGridItemEventParameter;
			$p->item=$item;
			$this->onItemCreated($p);
			$index++;
		}
		$this->setViewState('Items',$dataSource,array());
		$this->header=$this->createComponent('TDataGridItem',self::ID_HEADER);
		$this->header->setType(TDataGridItem::TYPE_HEADER);
		$this->addBody($this->header);
		$this->initializeItem($this->header);
		$p=new TDataGridItemEventParameter;
		$p->item=$this->header;
		$this->onItemCreated($p);
		$this->footer=$this->createComponent('TDataGridItem',self::ID_FOOTER);
		$this->footer->setType(TDataGridItem::TYPE_FOOTER);
		$this->addBody($this->footer);
		$this->initializeItem($this->footer);
		$p=new TDataGridItemEventParameter;
		$p->item=$this->footer;
		$this->onItemCreated($p);
		if($this->isAllowPaging())
		{
			$this->pager=$this->createComponent('TDataGridItem',self::ID_PAGER);
			$this->pager->setType(TDataGridItem::TYPE_PAGER);
			$this->addBody($this->pager);
			$cell=$this->pager->createComponent('TTableCell');
			$this->pager->addBody($cell);
			$p=new TDataGridItemEventParameter;
			$p->item=$this->pager;
			$this->onItemCreated($p);
			$currentPage=$this->getCurrentPageIndex();
			$pageCount=$this->getPageCount();
			if($currentPage>=$pageCount)
				$currentPage=$pageCount-1;
			if($currentPage<0)
				$currentPage=0;
			$this->setCurrentPageIndex($currentPage);
			$buttonCount=$this->getPagerButtonCount();
			$fromPage=(intval($currentPage/$buttonCount))*$buttonCount;
			$toPage=$fromPage+$buttonCount;
			if($fromPage>0)
			{
				$button=$cell->createComponent('TLinkButton');
				$button->setText('...');
				$button->setCommandName(self::CMD_PAGE);
				$button->setCommandParameter($fromPage-1);
				$button->setCausesValidation(false);
				$cell->addBody($button);
				$cell->addBody(' ');
			}
			for($i=$fromPage;$i<$toPage;++$i)
			{
				if($i>=$pageCount)
					break;
				if($i==$currentPage)
				{
					$cell->addBody(strval($i+1));
					$cell->addBody(' ');
				}
				else
				{
					$button=$cell->createComponent('TLinkButton');
					$button->setText(strval($i+1));
					$button->setCommandName(self::CMD_PAGE);
					$button->setCommandParameter($i);
					$button->setCausesValidation(false);
					$cell->addBody($button);
					$cell->addBody(' ');
				}
			}
			if($toPage<$pageCount)
			{
				$button=$cell->createComponent('TLinkButton');
				$button->setText('...');
				$button->setCommandName(self::CMD_PAGE);
				$button->setCommandParameter($toPage);
				$button->setCausesValidation(false);
				$cell->addBody($button);
			}
		}
	}

	/**
	 * Handles <b>OnBubbleEvent</b>.
	 * This method overrides parent's implementation to handle
	 * <b>OnItemCommand</b> event that is bubbled from 
	 * TDataGridItem child controls.
	 * This method should only be used by control developers.
	 * @param TControl the sender of the event
	 * @param TEventParameter event parameter
	 * @return boolean whether the event bubbling should stop here.
	 */
	protected function onBubbleEvent($sender,$param)
	{
		if($param instanceof TDataGridCommandEventParameter)
		{
			$this->onItemCommand($param);
			return true;
		}
		else
			return false;
	}

	/**
	 * Raises <b>OnItemCreated</b> event.
	 * This method is invoked after a data grid item is created.
	 * You may override this method to provide customized event handling.
	 * Be sure to call parent's implementation so that
	 * event handlers have chance to respond to the event.
	 * The TDataGridItem control responsible for the event
	 * can be determined from the event parameter's <b>item</b>
	 * field.
	 * @param TDataGridItemEventParameter event parameter
	 */
	protected function onItemCreated($param)
	{
		$this->raiseEvent('OnItemCreated',$this,$param);
	}

	/**
	 * Raises <b>OnItemCommand</b> and related events.
	 * This method is invoked after a button control in
	 * a template raises <b>OnCommand</b> event.
	 * You may override this method to provide customized event handling.
	 * Be sure to call parent's implementation so that
	 * event handlers have chance to respond to the event.
	 * The TDataGridItem control responsible for the event
	 * can be determined from the event parameter's <b>item</b>
	 * field. The initial sender of the <b>OnCommand</b> event
	 * is in <b>source</b> field. The command name and parameter
	 * are in <b>name</b> and <b>parameter</b> fields, respectively.
	 * @param TDataGridCommandEventParameter event parameter
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
		else if($param->name==self::CMD_SORT)
			$this->raiseEvent('OnSortCommand',$this,$param);
		else if($param->name==self::CMD_PAGE)
			$this->raiseEvent('OnPageCommand',$this,$param);
		$this->raiseEvent('OnItemCommand',$this,$param);
	}

	/**
	 * Returns the attributes to be rendered.
	 * This method overrides the parent's implementation to default to 
	 * adding a [] to the name
	 * @return ArrayObject attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$url=$this->getBackImageUrl();
		if(strlen($url))
			$this->setStyle(array('background-image'=>"url($url)"));
		$attributes=parent::getAttributesToRender();
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
		return $attributes;
	}

	/**
	 * Renders the body content of the datagrid.
	 * @return string the rendering result
	 */
	protected function renderBody()
	{
		$content="\n";
		if(!is_null($this->pager))
		{
			$columnCount=0;
			foreach($this->columns as $column)
				if($column->isVisible())
					$columnCount++;
			foreach($this->autoColumns as $column)
				if($column->isVisible())
					$columnCount++;
			$this->pager->Cells[0]->setColumnSpan($columnCount);
		}
		if(!is_null($this->pager) && $this->pager->isVisible())
		{
			$pagerStyle=$this->getPagerStyle();
			if(strlen($pagerStyle))
				$this->pager->setStyle($pagerStyle);
			$pagerCssClass=$this->getPagerCssClass();
			if(strlen($pagerCssClass))
				$this->pager->setCssClass($pagerCssClass);
			if($this->getPagerDisplay()==='Top' || $this->getPagerDisplay()==='TopAndBottom')
				$content.=$this->pager->render()."\n";
		}
		if(!is_null($this->header) && $this->header->isVisible() && $this->isShowHeader())
		{
			$headerStyle=$this->getHeaderStyle();
			if(strlen($headerStyle))
				$this->header->setStyle($headerStyle);
			$headerCssClass=$this->getHeaderCssClass();
			if(strlen($headerCssClass))
				$this->header->setCssClass($headerCssClass);
			$content.=$this->header->render()."\n";
		}
		foreach($this->items as $index=>$item)
		{
			if($item->isVisible())
			{
				$type=$item->getType();
				if($type===TDataGridItem::TYPE_ITEM)
				{
					$style=$this->getItemStyle();
					$cssClass=$this->getItemCssClass();
				}
				else if($type===TDataGridItem::TYPE_ALTERNATING_ITEM)
				{
					$style=$this->getAlternatingItemStyle();
					$cssClass=$this->getAlternatingItemCssClass();
				}
				else if($type===TDataGridItem::TYPE_SELECTED_ITEM)
				{
					$style=$this->getSelectedItemStyle();
					$cssClass=$this->getSelectedItemCssClass();
				}
				else if($type===TDataGridItem::TYPE_EDIT_ITEM)
				{
					$style=$this->getEditItemStyle();
					$cssClass=$this->getEditItemCssClass();
				}
				if(strlen($style))
					$item->setStyle($style);

				if(strlen($cssClass))
					$item->setCssClass($cssClass);

				$content.=$item->render()."\n";
			}
		}
		if(!is_null($this->footer) && $this->footer->isVisible() && $this->isShowFooter())
		{
			$footerStyle=$this->getFooterStyle();
			if(strlen($footerStyle))
				$this->footer->setStyle($footerStyle);
			$footerCssClass=$this->getFooterCssClass();
			if(strlen($footerCssClass))
				$this->footer->setCssClass($footerCssClass);

			$content.=$this->footer->render()."\n";
		}
		if(!is_null($this->pager) && $this->pager->isVisible() && $this->getPagerDisplay()==='Bottom' || $this->getPagerDisplay()==='TopAndBottom')
			$content.=$this->pager->render()."\n";
		return $content;
	}
}


/**
 * TDataGridItemEventParameter class
 *
 * TDataGridItemEventParameter encapsulates the parameter data for <b>OnItemCreated</b>
 * event of TDataGrid controls.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TDataGridItemEventParameter extends TEventParameter
{
	/**
	 * The TDataGridItem control responsible for the event.
	 * @var TDataGridItem
	 */
	public $item=null;
}

/**
 * TDataGridCommandEventParameter class
 *
 * TDataGridCommandEventParameter encapsulates the parameter data for <b>OnItemCommand</b>
 * event of TDataGrid controls.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TDataGridCommandEventParameter extends TCommandEventParameter
{
	/**
	 * The TDataGridItem control responsible for the event.
	 * @var TDataGridItem
	 */
	public $item=null;
	/**
	 * The control originally raises the <b>OnCommand</b> event.
	 * @var TControl
	 */
	public $source=null;
}

/**
 * TDataGridItemCollection class
 *
 * TDataGridItemCollection is used to maintain the data items
 * in TDataGrid.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TDataGridItemCollection extends TCollection
{
	/**
	 * TDataGrid object
	 * @var TDataGrid
	 */
	protected $grid=null;

	/**
	 * Constructor.
	 * Sets up the datagrid object.
	 */
	public function __construct($grid)
	{
		parent::__construct();
		$this->grid=$grid;
	}

	/**
	 * Adds TDataGridItem object to the body collection.
	 * This method will be invoked when adding an item to the collection.
	 * @param mixed the item to be added.
	 * @return boolean whether the item should be added.
	 */
	protected function onAddItem($item)
	{
		if($item instanceof TDataGridItem)
		{
			$this->grid->addBody($item);
			return true;
		}
		else
			return false;
	}

	/**
	 * Removes the item from the body collection.
	 * This method will be invoked when an item is to be removed from the collection.
	 * @param mixed the item to be removed.
	 */
	protected function onRemoveItem($item)
	{
		$this->grid->getBodies()->remove($item);
	}
}

?>