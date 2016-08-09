<?php
/**
 * TRepeater class file
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
 * @version $Revision: 1.24 $  $Date: 2005/04/12 11:51:05 $
 * @package System.Web.UI.WebControls
 */

/**
 * TRepeaterItem class file
 */
require_once(dirname(__FILE__).'/TRepeaterItem.php');

/**
 * TRepeater class
 *
 * TRepeater displays its content defined in templates repeatedly based on
 * the <b>DataSource</b> property. The <b>DataSource</b> property only accepts
 * objects that implement Iterator or IteratorAggregate interface. For convenience,
 * it also accepts an array.
 *
 * The <b>HeaderTemplate</b> property specifies the content template 
 * that will be displayed at the beginning, while <b>FooterTemplate</b> at the last. 
 * If present, these two templates will only be rendered when <b>DataSource</b> is set (not null).
 * If the <b>DataSource</b> contains item data, then for each item,
 * the content defined by <b>ItemTemplate</b> will be generated and displayed once.
 * If <b>AlternatingItemTemplate</b> is not empty, then the corresponding content will
 * be displayed alternatively with that in <b>ItemTemplate</b>. The content in 
 * <b>SeparatorTemplate</b>, if not empty, will be displayed between two items.
 * These templates can contain static text, controls and special tags.
 *
 * Note, the templates are only parsed and instantiated upon <b>OnDataBinding</b>
 * event which is raised by calling <b>TControl::dataBind()</b> method. You may
 * call this method during <b>OnInit</b> or <b>OnLoad</b> life cycles.
 *
 * You can retrive the repeated contents by <b>Items</b>. 
 * The number of repeated items is given by <b>ItemCount</b>.
 * 
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>DataSource</b>, Iterator|IteratorAggregate|array
 *   <br>Gets or sets the data source object.
 * - <b>ItemTemplate</b>, string
 *   <br>Gets or sets the template for each data item.
 * - <b>AlternatingItemTemplate</b>, string
 *   <br>Gets or sets the template for alternating data item.
 * - <b>HeaderTemplate</b>, string
 *   <br>Gets or sets the template displayed at the beginning of the repeater.
 * - <b>Header</b>, TRepeaterItem
 *   <br>Gets the header repeater item.
 * - <b>FooterTemplate</b>, string
 *   <br>Gets or sets the template displayed at the end of the repeater.
 * - <b>Footer</b>, TRepeaterItem
 *   <br>Gets the footer repeater item.
 * - <b>SeparatorTemplate</b>, string
 *   <br>Gets or sets the template displayed between two items.
 * - <b>Items</b>, array, read-only
 *   <br>Gets the list of TRepeaterItem controls that correspond to each data item.
 * - <b>ItemCount</b>, integer, read-only
 *   <br>Gets the number of TRepeaterItem controls for data items.
 *
 * Events
 * - <b>OnItemCommand</b>
 *   <br>This event is raised when a TRepeaterItem contains a button control that raises <b>OnCommand</b> event.
 *   You can retrieve the item responsible for the event via event parameter
 *   <b>TRepeaterCommandEventParameter::item</b>. The control triggers the <b>OnCommand</b> event
 *   is given in <b>TRepeaterCommandEventParameter::source</b>. And the command name and parameter
 *   are in <b>name</b> and <b>parameter</b> of <b>TRepeaterCommandEventParameter</b>.
 * - <b>OnItemCreated</b>
 *   <br>This event is raised when a TRepeaterItem is created and added as a child of TRepeater.
 *   You can retrieve the item responsible for the event via event parameter
 *   <b>TRepeaterItemEventParameter::item</b>.
 *
 * Examples
 * <b>HomePage.tpl:</b>
 * <code>
 * <com:TForm>
 * <com:TRepeater ID="repeater">
 *  <prop:ItemTemplate>
 *  <com:TTextBox ID="abc" />
 *  <com:TRequiredFieldValidator ControlToValidate="abc"
 *                               ErrorMessage="required." />
 *  </prop:ItemTemplate>
 * </com:TRepeater>
 * <com:TButton Text="submit" />
 * <com:TForm>
 * </code>
 * <b>HomePage.php</b>
 * <code>
 * class HomePage extends TPage
 * {
 *   function onLoad($param)
 *   {
 *     parent::onLoad($param);
 *     if(!$this->IsPostBack)
 *     {
 *       $this->repeater->setDataSource(array(1,2,3,4));
 *       $this->repeater->dataBind();
 *     }
 *   }
 * }
 * </code>
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TRepeater extends TControl
{
	const ID_HEADER='Header';
	const ID_FOOTER='Footer';
	const ID_ITEM='Item';
	const ID_SEPARATOR='Sep';
	const ID_EMPTY='Empty';
	private $dataSource=null;
	private $itemTemplate='';
	private $alternatingItemTemplate='';
	private $headerTemplate='';
	private $footerTemplate='';
	private $separatorTemplate='';
	private $emptyTemplate='';
	private $items=array();
	private $header=null;
	private $footer=null;
	private $itemCount=0;

	/**
	 * This method overrides the parent implementation so that no body content is added from template.
	 * @param TComponent|string the newly parsed object
	 * @param TComponent the template owner
	 */
	public function addParsedObject($object,$context)
	{
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
	 * @return string the template string when there are no items
	 */
	public function getEmptyTemplate()
	{
		return $this->emptyTemplate;
	}

	/**
	 * Sets the template string when there are no items
	 * @param string the empty template
	 */
	public function setEmptyTemplate($value)
	{
		$this->emptyTemplate=$value;
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
	 * @return TRepeaterItem the header item
	 */
	public function getHeader()
	{
		return $this->header;
	}

	/**
	 * @return TRepeaterItem the footer item
	 */
	public function getFooter()
	{
		return $this->footer;
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
	 * @return array list of TRepeaterItem control
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * @return integer number of TRepeaterItem control
	 */
	public function getItemCount()
	{
		return $this->itemCount;
	}

	/**
	 * Parses and intantiates templates.
	 * This method is invoked when <b>OnDataBinding</b> event is raised.
	 * It parses and instantiates all assoicated templates for the 
	 * repeater control and raises related events.
	 * This method should only used by control developers.
	 * @param TEventParameter event parameter
	 */
	protected function onDataBinding($param)
	{
		parent::onDataBinding($param);
		$this->setViewState('Items',null,null);
		$this->removeChildren();
		$this->removeBodies();
		$this->items=array();
		$this->itemCount=0;
		$this->header=null;
		$this->footer=null;
		
		$showEmpty = count($this->dataSource) <= 0 && strlen($this->emptyTemplate);
		
		if(is_null($this->dataSource))
			return;
		if(strlen($this->headerTemplate) && !$showEmpty)
		{
			$header=pradoGetApplication()->createComponent('TRepeaterItem',self::ID_HEADER);
			$header->setType(TRepeaterItem::TYPE_HEADER);
			$header->instantiateTemplate($this->headerTemplate);
			$this->header=$header;
			$this->addChild($header);
			$this->addBody($header);
			$p=new TRepeaterItemEventParameter;
			$p->item=$header;
			$this->onItemCreated($p);
		}
		$count=0;
		
		//when the datasource is empty
		if($showEmpty)
		{
			$empty=pradoGetApplication()->createComponent('TRepeaterItem',self::ID_EMPTY);
			$empty->setType(TRepeaterItem::TYPE_EMPTY);
			$empty->instantiateTemplate($this->emptyTemplate);
			$this->addChild($empty);
			$this->addBody($empty);
			$p=new TRepeaterItemEventParameter;
			$p->item=$empty;
			$this->onItemCreated($p);
		}
		foreach($this->dataSource as $key=>$value)
		{
			if($this->itemCount>0 && strlen($this->separatorTemplate))
			{
				$separator=pradoGetApplication()->createComponent('TRepeaterItem');
				$separator->setType(TRepeaterItem::TYPE_SEPARATOR);
				$separator->instantiateTemplate($this->separatorTemplate);
				$separator->setID(self::ID_SEPARATOR."$count");
				$this->addChild($separator);
				$this->addBody($separator);
				$p=new TRepeaterItemEventParameter;
				$p->item=$separator;
				$this->onItemCreated($p);
			}
			$item=null;
			if($count%2==1 && strlen($this->alternatingItemTemplate))
			{
				$item=pradoGetApplication()->createComponent('TRepeaterItem');
				$item->instantiateTemplate($this->alternatingItemTemplate);
			}
			else if(strlen($this->itemTemplate))
			{
				$item=pradoGetApplication()->createComponent('TRepeaterItem');
				$item->instantiateTemplate($this->itemTemplate);
			}
			if(!is_null($item))
			{
				$item->setID(self::ID_ITEM."$count");
				$item->setIndex($key);
				$item->setItemIndex($count);
				$item->setData($value);
				$this->addChild($item);
				$this->addBody($item);
				$this->items[$this->itemCount]=$item;
				$this->itemCount++;
				$p=new TRepeaterItemEventParameter;
				$p->item=$item;
				$this->onItemCreated($p);
			}
			$count++;
		}
		if(strlen($this->footerTemplate) && !$showEmpty)
		{
			$footer=pradoGetApplication()->createComponent('TRepeaterItem',self::ID_FOOTER);
			$footer->setType(TRepeaterItem::TYPE_FOOTER);
			$footer->instantiateTemplate($this->footerTemplate);
			$this->footer=$footer;
			$this->addChild($footer);
			$this->addBody($footer);
			$p=new TRepeaterItemEventParameter;
			$p->item=$footer;
			$this->onItemCreated($p);
		}
	}

	/**
	 * Raises <b>OnItemCreated</b> event.
	 * This method is invoked after a repeater item is created.
	 * You may override this method to provide customized event handling.
	 * Be sure to call parent's implementation so that
	 * event handlers have chance to respond to the event.
	 * The TRepeaterItem control responsible for the event
	 * can be determined from the event parameter's <b>item</b>
	 * field.
	 * @param TRepeaterItemEventParameter event parameter
	 */
	protected function onItemCreated($param)
	{
		$this->raiseEvent('OnItemCreated',$this,$param);
	}

	/**
	 * Handles <b>OnBubbleEvent</b>.
	 * This method overrides parent's implementation to handle
	 * <b>OnItemCommand</b> event that is bubbled from 
	 * TRepeaterItem child controls.
	 * This method should only be used by control developers.
	 * @param TControl the sender of the event
	 * @param TEventParameter event parameter
	 * @return boolean whether the event bubbling should stop here.
	 */
	protected function onBubbleEvent($sender,$param)
	{
		if($param instanceof TRepeaterCommandEventParameter)
		{
			$this->onItemCommand($param);
			return true;
		}
		else
			return false;
	}

	/**
	 * Raises <b>OnItemCommand</b> event.
	 * This method is invoked after a button control in
	 * a template raises <b>OnCommand</b> event.
	 * You may override this method to provide customized event handling.
	 * Be sure to call parent's implementation so that
	 * event handlers have chance to respond to the event.
	 * The TRepeaterItem control responsible for the event
	 * can be determined from the event parameter's <b>item</b>
	 * field. The initial sender of the <b>OnCommand</b> event
	 * is in <b>source</b> field. The command name and parameter
	 * are in <b>name</b> and <b>parameter</b> fields, respectively.
	 * @param TRepeaterCommandEventParameter event parameter
	 */
	protected function onItemCommand($param)
	{
		$this->raiseEvent('OnItemCommand',$this,$param);
	}

	/**
	 * Loads viewstate into this control and its children.
	 * This method is overriden to load the items data from view state.
	 * If the items data is not empty, it will be used to 
	 * populate the repeater (to restore the previous view).
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
		if(count($this->items)>0)
		{
			$items=array();
			foreach($this->items as $item)
				$items[$item->getIndex()]=$item->getData();
			$this->setViewState('Items',$items);
		}
		return parent::saveViewState();
	}
}

/**
 * TRepeaterItemEventParameter class
 *
 * TRepeaterItemEventParameter encapsulates the parameter data for <b>OnItemCreated</b>
 * event of TRepeater controls.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TRepeaterItemEventParameter extends TEventParameter
{
	/**
	 * The TRepeaterItem control responsible for the event.
	 * @var TRepeaterItem
	 */
	public $item=null;
}

/**
 * TRepeaterCommandEventParameter class
 *
 * TRepeaterCommandEventParameter encapsulates the parameter data for <b>OnItemCommand</b>
 * event of TRepeater controls.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TRepeaterCommandEventParameter extends TCommandEventParameter
{
	/**
	 * The TRepeaterItem control responsible for the event.
	 * @var TRepeaterItem
	 */
	public $item=null;
	/**
	 * The control originally raises the <b>OnCommand</b> event.
	 * @var TControl
	 */
	public $source=null;
}

?>