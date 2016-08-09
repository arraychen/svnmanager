<?php
/**
 * TControl class file
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
 * @version $Revision: 1.39 $  $Date: 2005/08/19 02:15:30 $
 * @package System.Web.UI
 */

/**
 * TControl class
 *
 * TControl is the base class for all server controls that deal with UI. 
 *
 * TControl does not have any user interface (UI) specific features. 
 * If you are authoring a control, or combines other controls that render 
 * their own UI, derive from TControl.
 * 
 * TControl implements the container-containee presentational relationship
 * among all controls on a page. A control has <b>Container</b> and <b>Bodies</b>,
 * which refer to the container component and the body content of the component.
 * A control can have a template which describes its body content.
 * The format of the template is very similar to HTML.
 *
 * TControl provides support for viewstate maintenance. Data stored
 * in viewstate are persistent between posts to the same page. For more
 * details, see {@link setViewState} and {@link getViewState}.
 *
 * TControl defines a set of events, <b>OnInit</b>, <b>OnLoad</b>, <b>OnPreRender</b>,
 * <b>OnUnload</b>, which are closely related with page lifecycles.
 *
 * Namespace: System.Web.UI
 *
 * Properties
 * - <b>ClientID</b>, string, read-only
 *   <br>Gets the unique, hierarchically-qualified identifier for the control.
 *   It is similar to UniqueID except that ClientID is mainly used to specify
 *   the id attribute of the HTML element.
 * - <b>Container</b>, TControl, read-only
 *   <br>Gets the container control.
 * - <b>Bodies</b>, array, read-only
 *   <br>Gets the body content (list in their rendering order.)
 * - <b>EnableViewState</b>, boolean, default=true
 *   <br>Gets or sets a value indicating whether the control persists its viewstate,
 *   and the viewstate of any child controls, to the requesting client.
 * - <b>Attributes</b>, array, read-only
 *   <br>Gets the list of attributes. Attributes will be rendered as
 *   attributes of the corresponding HTML element.
 * - <b>EnableViewState</b>, boolean, default=true
 *   <br>Gets or sets a value indicating whether the control persists its viewstate,
 *   and the viewstate of any child controls, to the requesting client.
 * - <b>Visible</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets a value that indicates whether the control should render itself.
 * - <b>TagName</b>, string
 *   <br>Gets or sets the tag name that will be rendered in the HTML element.
 *   This property is used primarily by control developers.
 * - <b>Skin</b>, string
 *   <br>Gets or sets the skin associated with the control.
 *
 * Events
 * - <b>OnInit</b> Occurs when the control is initialized, which is the first step in the its lifecycle.
 * - <b>OnLoad</b> Occurs when the control is loaded into the Page object.
 * - <b>OnPreRender</b> Occurs when the control is about to render to its containing Page object.
 * - <b>OnUnload</b> Occurs when the control is unloaded from memory.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI
 */
class TControl extends TComponent
{
	/**
	 * container of the control.
	 * @var TControl
	 */
	private $container=null;
	/**
	 * list of body objects
	 * @var array
	 */
	private $bodies=null;
	/**
	 * list of attributes, indexed by attribute names
	 * @var array
	 */
	private $attributes=array();
	/**
	 * data structure for maintaining the viewstate for this control
	 * @var mixed
	 */
	private $viewState=null;
	private $viewState2=null;
	/**
	 * enable viewstate saving and restoring
	 * @var boolean
	 */
	private $enableViewState=true;
	/**
	 * tagname for this control
	 * @var string
	 */
	private $tagName='';
	/**
	 * list of element types which cannot be empty (as per xhtml strict 1.0)
	 * @var array
	 */
	private $emptyElems = array(
		"base",
		"meta",
		"link",
		"hr",
		"br",
		"param",
		"img",
		"area",
		"input",
		"col");


	/**
	 * Constructor.
	 * Initializes the body collection.
	 */
	function __construct()
	{
		$this->bodies=new TBodyCollection($this);
		parent::__construct();
	}

	/**
	 * Returns the client ID of the component.
	 *
	 * The client ID is similar to the unique ID except underscores are used as connectors instead of colons.
	 * @return string the client ID of the component
	 */
	public function getClientID()
	{
		//return strtr($this->getUniqueID(),':','_');
		return $this->getUniqueID();
	}

	/**
	 * Adds the object parsed in template as a body of the component.
	 * @param TComponent|string the newly parsed object
	 * @param TComponent the template owner
	 */
	public function addParsedObject($object,$context)
	{
		parent::addParsedObject($object,$context);
		$this->addBody($object);
	}

	/**
	 * Appends an object into the body collection of this control.
	 * This method is equivalent to $control->Bodies->add($object).
	 * @param mixed the object to be added into body content.
	 */
	final public function addBody($object)
	{
		$this->bodies->add($object);
	}

	/**
	 * This method should only be used by framework developer.
	 * It is invoked when a body control is being added.
	 * The control will be made synchronized to the current life cycle
	 * of the page hierarchy.
	 * @param TControl the control being added.
	 */
	final public function synchronizeControl($control)
	{
		$control->setContainer($this);
		$id=$control->getUniqueID();
		$page=$this->getPage();
		if(is_null($id) || is_null($page))
			return;
		$stage=$page->getStage();
		if($stage>TPage::STAGE_INIT)
		{
			$control->onInitRecursive(new TEventParameter);
			if($stage<TPage::STAGE_RENDER && $page->isPostBack() && isset($this->viewState2[$id]))
			{
				$control->loadViewState($this->viewState2[$id]);
				unset($this->viewState2[$id]);
			}
		}
		if($stage>TPage::STAGE_LOAD)
		{
			$control->onLoadRecursive(new TEventParameter);
			if($page->isPostBack())
				$page->loadPostData();
		}
	}

	/**
	 * Determines whether the control can add the object as a body.
	 * This method can be overriden to customize the types of object
	 * that can be added as a body.
	 * Default implementation only allows string and TControl or its descendant.
	 * @param mixed the object to be added
	 * @return boolean
	 */
	public function allowBody($object)
	{
		return ($object instanceof TControl || is_string($object));
	}

	/**
	 * @return TControl|null the body control with the specified ID, null if not found.
	 */
	public function findBodyControl($id)
	{
		foreach($this->bodies as $body)
		{
			if(($body instanceof TControl) && $body->getID()===$id)
				return $body;
		}
		return null;
	}

	/**
	 * Returns the container of this component.
	 * @return TControl the container control
	 */
	public function getContainer()
	{
		return $this->container;
	}
	
	/**
	 * Sets the container of this component
	 * @var TComponent the new container of this control
	 */
	public function setContainer($container)
	{
		$this->container=$container;
	}

	/**
	 * @return array the body content
	 */
	public function getBodies()
	{
		return $this->bodies;
	}

	/**
	 * Removes all body content.
	 */
	public function removeBodies()
	{
		$this->bodies->clear();
	}

	/**
	 * Sets an attribute.
	 * @param string the attribute name
	 * @param string the attribute value
	 */
	public function setAttribute($name,$value)
	{
		$this->attributes[$name]=$value;
	}
	
	/**
	 * @param string the attribute name
	 * @return string|null the attribute value, null if no such attribute
	 */
	public function getAttribute($name)
	{
		return isset($this->attributes[$name])?$this->attributes[$name]:null;
	}

	/**
	 * Returns the attribute list object.
	 * @return array the list of all attributes
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * @return string the tag name
	 */
	public function getTagName()
	{
		return $this->tagName;
	}

	/**
	 * Sets the tag name.
	 * @param string the tag name associated with the control
	 */
	public function setTagName($tagName)
	{
		$this->tagName=$tagName;
	}
	
	/**
	 * @return string the skin specified for this control
	 */
	public function getSkinName()
	{
		return $this->getViewState('SkinName', null);
	}
	
	/**
	 * Sets the skin to use for this control
	 */
	public function setSkinName($skin)
	{
		$this->setViewState('SkinName', $skin, null);
	}
	
	 
	/**
	 * Initializes a skin for the control. If a skin name is provided then that skin will be loaded.
	 * If not then the skin as defined by the SkinName property of the control will be loaded.
	 * If this property has no value then the default skin for the control will be loaded. If no
	 * default skin exists for this control then no skin will be loaded at all.
	 */
	public function initSkin($skinName=null) {
		$type = get_class($this);
		$theme = pradoGetApplication()->getTheme();
		if (is_null($skinName))
			$skinName="_default";
		
		if (isset($theme[$type][$skinName]))
		{
			if (isset($theme[$type][$skinName]['parent']))
				$this->initSkin($theme[$type][$skinName]['parent']);
			$definition=$this->getDefinition($type);
			foreach ($theme[$type][$skinName]['properties'] as $name=>$value) 
				$definition->configureProperty($this,$name,$value);
		}
		else if ($skinName != 0)
			throw new TSkinNotFoundException($skinName, get_class($this));
	}

	/**
	 * This method checks whether a control is visible.
	 * If $checkContainers is true, it will also check the visibility of the containers
	 * of the control. It returns false if any container is invisible.
	 * @param boolean whether to check the containers of the control as well.
	 * @return boolean whether the component should be rendered
	 */
	public function isVisible($checkContainers=false)
	{
		if($checkContainers)
		{
			$control=$this;
			while(!is_null($control))
			{
				if($control->isVisible())
					$control=$control->getContainer();
				else
					return false;
			}
			return true;
		}
		else
			return $this->getViewState('Visible',true);
	}

	/**
	 * @param boolean set whether the component should be rendered
	 */
	public function setVisible($value)
	{
		$this->setViewState('Visible',$value,true);
	}

	/**
	 * @return boolean whether viewstate is enabled
	 */
	public function isViewStateEnabled()
	{
		return $this->enableViewState;
	}

	/**
	 * @param boolean set whether to enable viewstate
	 */
	public function setEnableViewState($value)
	{
		$this->enableViewState=$value;
	}

	/**
	 * Calls {@link onInit} of this control and its children recursively.
	 * This method should only be used by framework developers.
	 * @param TEventParameter event parameter to be passed to {@link onInit}
	 */
	protected function onInitRecursive($param)
	{
		$this->onInit($param);
		foreach($this->bodies as $body)
			if($body instanceof TControl)
				$body->onInitRecursive($param);
	}

	/**
	 * Calls {@link onLoad} of this control and its children recursively.
	 * This method should only be used by framework developers.
	 * @param TEventParameter event parameter to be passed to {@link onLoad}
	 */
	protected function onLoadRecursive($param)
	{
		$this->onLoad($param);
		foreach($this->bodies as $body)
			if($body instanceof TControl)
				$body->onLoadRecursive($param);
	}

	/**
	 * Calls {@link onPreRender} of this control and its children recursively.
	 * This method should only be used by framework developers.
	 * @param TEventParameter event parameter to be passed to {@link onPreRender}
	 */
	protected function onPreRenderRecursive($param)
	{
		$this->onPreRender($param);
		foreach($this->bodies as $body)
			if($body instanceof TControl)
				$body->onPreRenderRecursive($param);
	}

	/**
	 * Calls {@link onUnload} of this control and its children recursively.
	 * This method should only be used by framework developers.
	 * @param TEventParameter event parameter to be passed to {@link onUnload}
	 */
	protected function onUnloadRecursive($param)
	{
		foreach($this->bodies as $body)
			if($body instanceof TControl)
				$body->onUnloadRecursive($param);
		$this->onUnload($param);
	}

	/**
	 * This method is invoked when the control enters 'Init' stage.
	 * The method raises 'OnInit' event to fire up the event delegates.
	 * If you override this method, be sure to call the parent implementation
	 * so that the event delegates can be invoked.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	protected function onInit($param)
	{
		$this->raiseEvent('OnInit',$this,$param);
	}

	/**
	 * This method is invoked when the control enters 'Load' stage.
	 * The method raises 'OnLoad' event to fire up the event delegates.
	 * If you override this method, be sure to call the parent implementation
	 * so that the event delegates can be invoked.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	protected function onLoad($param)
	{
		$this->raiseEvent('OnLoad',$this,$param);
	}

	/**
	 * This method is invoked when the control enters 'Unload' stage.
	 * The method raises 'OnUnload' event to fire up the event delegates.
	 * If you override this method, be sure to call the parent implementation
	 * so that the event delegates can be invoked.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	protected function onUnload($param)
	{
		$this->raiseEvent('OnUnload',$this,$param);
	}

	/**
	 * This method is invoked when the control enters 'PreRender' stage.
	 * The method raises 'OnPreRender' event to fire up the event delegates.
	 * If you override this method, be sure to call the parent implementation
	 * so that the event delegates can be invoked.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	protected function onPreRender($param)
	{
		$this->raiseEvent('OnPreRender',$this,$param);
	}

	/**
	 * Renders this control.
	 *
	 * Default implementation will render the children in order.
	 * If <b>TagName</b> is not empty, an enclosing tag together with
	 * <b>Attributes</b> will be rendered as well.
	 * This method can be overriden.
	 * @return string the rendering result
	 */
	public function render()
	{
		$body=$this->renderBody();
		if(strlen($this->tagName))
		{
			$rendered = "<{$this->tagName}";
			
			$attr=$this->renderAttributes();
			if(strlen($attr))
				$rendered .= " $attr";
			
			if (strlen($body) || !in_array(strtolower($this->tagName), $this->emptyElems))
				$rendered .= ">$body</{$this->tagName}>";
			else
				$rendered .= " />";
				
			return $rendered;
		}
		else
			return $body;
	}

	/**
	 * Renders the body content.
	 * Default implementation will render recursively all visible child controls and text blocks.
	 * You can override this method to render customized body content.
	 * This method is mainly used by control developers.
	 * @return string the rendering result
	 */
	protected function renderBody()
	{
		$content='';
		foreach($this->bodies as $body)
		{
			if($body instanceof TControl)
			{
				if($body->isVisible()) {
					$content.=$body->render();
				}
			}
			else if(is_string($body))
				$content.=$body;
		}
		return $content;
	}

	/**
	 * Renders the attributes.
	 * It returns a string consisting of key-value pairs based on the input array.
	 * {@link getAttributesToRender}.
	 * @param array|null the attributes to be rendered, if null {@link getAttributesToRender()} will be invoked to get the attributes
	 * @return string the rendering result
	 * @see getAttributesToRender()
	 */
	protected function renderAttributes($attributes=null)
	{
		if(is_null($attributes))
			$attributes=$this->getAttributesToRender();
		$attr='';
		foreach($attributes as $name=>$value)
			$attr.="$name=\"$value\" ";
		return trim($attr);
	}

	/**
	 * Returns attributes to be rendered.
	 * The default implementation returns the <b>Attributes</b> property
	 * that is inserted with an 'id' attribute whose value equals to
	 * the <b>ClientID</b> property of the control.
	 * You can override this method to customize attributes to be rendered.
	 * Be sure to call parent's implementation first to ensure
	 * the rendering of attributes specified in parent.
	 * This method is mainly used by control developers.
	 * @return ArrayObject the attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$attributes=$this->attributes;
		if(!isset($attributes['id']))
			$attributes['id']=$this->getClientID();
		return new ArrayObject($attributes);
	}

	/**
	 * Invokes the parent's onBubbleEvent method.
	 * A control who wants to bubble an event must call this method in its onEvent method.
	 * @param TComponent sender of the event
	 * @param TEventParameter event parameter
	 */
	public function raiseBubbleEvent(TComponent $sender,TEventParameter $param)
	{
		$object=$this;
		while(($object=$object->getContainer())!==null)
		{
			if($object->onBubbleEvent($sender,$param))
				break;
		}
	}

	/**
	 * This method responds to a bubbled event.
	 * This method should be overriden to provide customized response at a bubbled event.
	 * Check the type of event parameter to determine what event is bubbled currently.
	 * @param TComponent sender of the event
	 * @param TEventParameter event parameters
	 * @return boolean true if the event bubbling is handled and no more bubbling.
	 */
	protected function onBubbleEvent($sender,$param)
	{
		return false;
	}

	/**
	 * Sets a viewstate value.
	 *
	 * This function is very useful in defining setter functions for control properties
	 * that must be kept in viewstate.
	 * @param string the name of the viewstate value
	 * @param mixed the viewstate value to be set
	 * @param mixed default value. If $value===$defaultValue, the viewstate will be cleared up.
	 */
	public function setViewState($key,$value,$defaultValue=null)
	{
		if($value===$defaultValue)
			unset($this->viewState[$key]);
		else
			$this->viewState[$key]=$value;
	}

	/**
	 * Returns a viewstate value.
	 *
	 * This function is very useful in defining getter functions for component properties
	 * that must be kept in viewstate.
	 * @param string the name of the viewstate value to be returned
	 * @param mixed the default value. If $key is not found in viewstate, $defaultValue will be returned
	 * @return mixed the viewstate value(s)
	 */
	public function getViewState($key,$defaultValue=null)
	{
		if(isset($this->viewState[$key]))
			return $this->viewState[$key];
		else
			return $defaultValue;
	}

	/**
	 * Clears viewstate
	 * @param boolean whether children should be cleared viewstate as well
	 */
	public function clearViewState($recursive=true)
	{
		$this->viewState=null;
		if($recursive)
		{
			foreach($this->bodies as $body)
			{
				if($body instanceof TControl)
					$body->clearViewState($recursive);
			}
		}
	}

	/**
	 * Loads viewstate into this component and its children.
	 *
	 * Current implementation is that the viewstate for a component is stored as an array.
	 * The first array element stores the viewstate of this component.
	 * The rest elements store the viewstates of the child components, indexed by their IDs.
	 *
 	 * This method should only be used by framework and component developers.
	 * @param array viewstate to be loaded
	 * @see saveViewState()
	 */
	public function loadViewState($viewState)
	{
		if($this->enableViewState)
		{
			if(isset($viewState[0]))
			{
				$this->viewState=$viewState[0];
				unset($viewState[0]);
			}
			else
				$this->viewState=null;
			foreach($this->bodies as $body)
			{
				if($body instanceof TControl)
				{
					$id=$body->getUniqueID();
					if(isset($viewState[$id]))
					{
						$body->loadViewState($viewState[$id]);
						unset($viewState[$id]);
					}
					else
						$body->loadViewState(null);
				}
			}
			if(!is_null($viewState))
				foreach($viewState as $id=>$v)
					$this->viewState2[$id]=$v;
		}
	}

	/**
	 * Returns the viewstate of this component and its children.
	 * This method should only be used by framework and component developers.
	 * @return array|null viewstate to be saved
	 * @see loadViewState()
	 */
	public function saveViewState()
	{
		$viewState=null;
		if($this->enableViewState)
		{
			if(!empty($this->viewState))
				$viewState[0]=$this->viewState;
			foreach($this->bodies as $body)
			{
				if($body instanceof TControl)
				{
					$v=$body->saveViewState();
					if(!is_null($v))
						$viewState[$body->getUniqueID()]=$v;
				}
			}
		}
		return is_null($viewState)?null:new ArrayObject($viewState);
	}
	
}

/**
 * TBodyCollection class.
 * Represents a list of body controls and texts.
 * 
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI
 */
class TBodyCollection extends TCollection
{
	/**
	 * The container of the body content
	 * @var TControl
	 */
	protected $control=null;

	/**
	 * Constructor.
	 * Initializes the body container.
	 */
	public function __construct($control)
	{
		parent::__construct();
		$this->control=$control;
	}

	/**
	 * Checks if an item can be added into body collection.
	 * @param mixed the item to be added
	 * @param boolean whether the item should be added into the colleciton.
	 */
	protected function onAddItem($item)
	{
		if($this->control->allowBody($item))
		{
			if($item instanceof TControl)
				$this->control->synchronizeControl($item);
			return true;
		}
		else
			return false;
	}
}

?>