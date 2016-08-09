<?php
/**
 * TComponent class file.
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
 * @version $Revision: 1.39 $  $Date: 2005/09/30 04:41:28 $
 * @package System
 */
 
/**
 * TComponent class
 *
 * TComponent is the base class for all PRADO components.
 * A component, an instance of TComponent or its descendent class, is
 * a basic building-block in a PRADO application.
 *
 * A component has
 * - properties, which can be accessed by other components or functions.
 * - events, which can be assigned with delegate functions. When an event happens, the corresponding delegates will be automatically invoked.
 *
 * Properties and events are inheritable. They cannot be redefined.
 * The corresponding property getter and setter methods can be overriden, however.
 *
 * Components define their properties and events in component specification files.
 * The property initial values and event handlers can be set in component class files,
 * specification files or control's template files.
 *
 * TComponent provides support for component composition and templating.
 * A component has <b>Children</b> and <b>Parent</b>, which establishes
 * a tree based on the parent-child relationship.
 *
 * Each component has an <b>ID</b> that uniquely identifies itself among
 * its siblings. Besides, each component has a <b>UniqueID</b> that uniquely
 * identifies itself in the page hierarchy. You can use <b>UniqueID</b>
 * to render the id or name attribute of the corresponding HTML element.
 *
 * Namespace: System
 * Properties
 * - <b>ID</b>, string, read-only
 *   <br>Gets the programmatic identifier assigned to the component. 
 *   The value is unique among all the sibling components.
 * - <b>UniqueID</b>, string, read-only
 *   <br>Gets the unique, hierarchically-qualified identifier for the component.
 *   The value is unique among all components in the page/module hierarchy. It is mainly
 *   used to identify postback target and specify the id attribute of the corresponding
 *   HTML element.
 * - <b>Parent</b>, TComponent, read-only
 *   <br>Gets the component's parent component in the page/module hierarchy.
 *   Note, a page/module component has no parent and its Parent property is null.
 * - <b>Children</b>, array, read-only
 *   <br>Gets the list of children index 
 * - <b>Page</b>, TPage, read-only
 *   <br>Gets the page instance that contains the component.
 * - <b>Module</b>, TModule, read-only
 *   <br>Gets the module instance that contains the component or the page containing the component.
 * - <b>User</b>, IUser, read-only
 *   <br>Gets the user object associated with the application.
 * - <b>Session</b>, ISession, read-only
 *   <br>Gets the session object associated with the application.
 * - <b>Application</b>, TApplication, read-only
 *   <br>Gets the application object.
 * - <b>Request</b>, TRequest, read-only
 *   <br>Gets the request object
 * - <b>Definition</b>, TComponentDefinition, read-only
 *   <br>Gets the definition of the component.
 *
 * Events
 * - <b>OnDataBinding</b> Occurs when the component evaluates expressions bound to its properties.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System
 */
class TComponent
{
	/**
	 * list of component definitions indexed by types.
	 * @var array
	 */
	private static $definitions=array();
	/**
	 * ID of the component
	 * @var string
	 */
	private $id='';
	/**
	 * unique ID of the component in a page hierarchy
	 * @var string
	 */
	private $uniqueID='';
	/**
	 * root component (either a page or a module)
	 * @var TComponent
	 */
	private $root=null;
	/**
	 * parent (creator) of this component
	 * @var TComponent
	 */
	private $parent=null;
	/**
	 * children of this component
	 * @var TComponent
	 */
	protected $children=array();
	/**
	 * child ID allocator
	 * @var integer
	 */
	private $nextID=0;
	/**
	 * list of event handlers
	 * @var array
	 */
	private $eventHandlers=array();
	/**
	 * list of property data bindings
	 * @var array
	 */
	private $bindings=array();
	/**
	 * list of property initializations
	 * @var array
	 */
	private $initValues=null;

	/**
	 * Constructor.
	 *
	 * Parses specification and template to form compoent definition.
	 * Child components and body content will be constructed and configured.
	 *
	 * To inheritors, be sure to call the parent constructor first
	 * so that the component properties and events are defined.
	 */
	public function __construct()
	{
		$this->getDefinition(get_class($this))->applyTo($this);
	}

	/**
	 * Returns or builds a component definition.
	 * This method should only be used by framework developers.
	 * @param string the component type
	 * @return TComponentSpecification the component definition
	 */
	public static function getDefinition($type='')
	{
		if(empty($type))
			return self::$definitions;
		if(!isset(self::$definitions[$type]))
			self::$definitions[$type]=new TComponentDefinition($type);
		return self::$definitions[$type];
	}

	/**
	 * Sets a component definition.
	 * This method should only be used by framework developers.
	 * @param string the component type
	 * @param TComponentSpecification the component definition
	 */
	public static function setDefinition($type,$definition)
	{
		if(is_null($type))
		{
			foreach($definition as $type=>$def)
				if(!isset(self::$definitions[$type]))
					self::$definitions[$type]=$def;
		}
		else
		{
			if(!isset(self::$definitions[$type]))
				self::$definitions[$type]=$definition;
		}
	}

	/**
	 * Parses a template string and instantiates the content.
	 * The method will parse the template string and instantiate
	 * the components defined in the template. Components instantiated
	 * will be added as children of this component. Components and
	 * static text are added as body content of their corresponding
	 * container components.
	 * @param string the template string
	 */
	public function instantiateTemplate($str)
	{
		$this->getDefinition(get_class($this))->instantiateTemplate($this,$str);
		$this->initProperties();
	}

	/**
	 * Handles a component or string met in template.
	 * This method will be invoked when a component or a string is
	 * parsed by the framework.
	 * This method can be overriden to provide customize
	 * treatment of parsed objects (e.g., adding the object
	 * into the body collection of the component).
	 * The default implement will add a component as a child
	 * of the template owner ($context) or the container's parent
	 * the container is not the template owner.
	 * @param TComponent|string the newly parsed object
	 * @param TComponent the template owner
	 */
	public function addParsedObject($object,$context)
	{
		if($object instanceof TComponent)
		{
			if($this===$context)
				$context->addChild($object);
			else if(($parent=$this->getParent())!==null)
				$parent->addChild($object);
		}
	}

	/**
	 * @return boolean whether the component has any child
	 */
	public function hasChildren()
	{
		return count($this->children)>0;
	}

	/**
	 * @return array the list of all children
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * @param string the child component ID
	 * @return TComponent|null the child component, null if not found
	 */
	public function getChild($id)
	{
		return isset($this->children[$id])?$this->children[$id]:null;
	}

	/**
	 * Adds a child component.
	 *
	 * The component must have a unique ID among its sibling components,
	 * or TComponentIdNotUniqueException will be raised.
	 *
	 * If the component does not have an ID, the method will generate one for it.
	 *
	 * If the parent component is in a page hierarchy, the child component
	 * will be registered to the page recursively.
	 *
	 * This method is usually used for dynamically creating components.
	 * @param TComponent the component to be added as a child.
	 * @return string|null the component ID, null if not added
	 * @throw TComponentIdNotUniqueException
	 * @see registerComponent()
	 */
	public function addChild(TComponent $child)
	{
		if(!is_null($child->parent))
			$child->parent->removeChild($child);
		if(empty($child->id))
			$child->id=$this->allocateID(get_class($child));
		if(isset($this->children[$child->id]))
			throw new TComponentIdNotUniqueException(get_class($this),$child->id);
		$reflection = new ReflectionClass($this);
		if (in_array($child->id,array_keys($reflection->getDefaultProperties())))
			throw new TComponentIdNameClashException(get_class($this),$child->id);
		$child->setParent($this);
		$this->children[$child->id]=$child;
		if(!is_null($this->root))
			$this->registerComponent($child);
		return $child->id;
	}


	/**
	 * Removes a child component.
	 * If the child component is registered in a page hierarchy,
	 * it will be unregistered.
	 * @param string the ID of the child component to be removed
	 */
	public function removeChild(TComponent $child)
	{
		if(isset($this->children[$child->id]))
		{
			if(!is_null($this->root))
				$this->unregisterComponent($child);
			unset($this->children[$child->id]);
		}
	}

	/**
	 * Removes all child components.
	 * If the child component is registered in a page hierarchy,
	 * it will be unregistered.
	 */
	public function removeChildren()
	{
		foreach($this->children as $child)
			$this->removeChild($child);
		$this->children=array();
	}

	/**
	 * Registers a component into the existing page hierarchy.
	 *
	 * This method generates UniqueID for the registered component.
	 * It also registers the component to the page if it implements
	 * certain interfaces (IPostBackDataHandler, IPostBackEventHandler,
	 * IValidator, ICallbackEventHandler). The component's children are also registered recursively.
	 * @param TComponent the component to be registered
	 */
	private function registerComponent($component)
	{
		$component->root=$this->root;
		$component->uniqueID=strlen($this->uniqueID)?$this->uniqueID.':'.$component->id:$component->id;
		if($this->root instanceof TPage)
		{
			if($component instanceof TForm)
				$this->root->setForm($component);
			if($component instanceof THead)
				$this->root->setHead($component);
			if($component instanceof TContentPlaceHolder)
				$this->root->registerContentPlaceHolder($component);
			if($component instanceof IPostBackDataHandler)
				$this->root->registerPostDataLoader($component);
			if($component instanceof IPostBackEventHandler)
				$this->root->registerPostBackCandidate($component);
			if($component instanceof IValidator)
				$this->root->registerValidator($component);
			if(interface_exists('ICallbackEventHandler', FALSE))
			{
				if($component instanceof ICallbackEventHandler)				
					$this->root->registerCallbackCandidate($component);
				if($this->root instanceof ICallbackEventHandler)
					$this->root->registerCallbackCandidate($this->root);
			}	
			foreach($component->children as $child)
				$component->registerComponent($child);
		}
	}

	/**
	 * Unregisters a control from the existing page hierarchy.
	 *
	 * This method unregisters a control and all its children recursively from
	 * the existing page hierarchy. If the control implements certain interfaces
	 * or extends from certain classes, it will also be unregistered from the page.
	 * @param TControl the control to be removed
	 */
	private function unregisterComponent($component)
	{
		if($this->root instanceof TPage)
		{
			foreach($component->children as $child)
				$component->unregisterComponent($child);
			if(interface_exists('ICallbackEventHandler', FALSE))
			{
				if($this->root instanceof ICallbackEventHandler)
					$this->root->unregisterCallbackCandidate($this->root);
				if($component instanceof ICallbackEventHandler)
					$this->root->unregisterCallbackCandidate($component);
			}
			if($component instanceof IPostBackDataHandler)
				$this->root->unregisterPostDataLoader($component);
			if($component instanceof IPostBackEventHandler)
				$this->root->unregisterPostBackCandidate($component);
			if($component instanceof IValidator)
				$this->root->unregisterValidator($component);
			if($component instanceof TContentPlaceHolder)
				$this->root->unregisterContentPlaceHolder($component);
			if($component instanceof TForm)
				$this->root->unsetForm($component);
		}
		$component->uniqueID='';
	}

	/**
	 * @param TApplication the application instance
	 */
	public function getApplication()
	{
		return pradoGetApplication();
	}

	/**
	 * Sets the root component in the component hierarchy.
	 * The root component can be either a page or a module object.
	 * This method should only be used by framework developers.
	 * @param TComponent the root component
	 */
	public function setRoot($root)
	{
		$this->root=$root;
	}

	/**
	 * @param TPage the page object, null if the component is not in a page (maybe in a module).
	 */
	public function getPage()
	{
		return ($this->root instanceof TPage)?$this->root:null;
	}

	/**
	 * Returns the module object that contains the component or the page containing the component.
	 * This method is overriden by TPage.
	 * @return TModule the module object that contains the component or the page containing the component.
	 */
	public function getModule()
	{
		if($this->root instanceof TPage)
			return $this->root->getModule();
		else
			return $this->root;
	}

	/**
	 * Returns the user object associated with the application.
	 * @return IUser the user object associated with the application.
	 */
	public function getUser()
	{
		return pradoGetApplication()->getUser();
	}

	/**
	 * Returns the session object associated with the application.
	 * @return ISession the session object associated with the application.
	 */
	public function getSession()
	{
		return pradoGetApplication()->getSession();
	}

	/**
	 * Returns the request object associated with application.
	 * @return TRequest
	 */
	public function getRequest()
	{
		return pradoGetApplication()->getRequest();
	}

	/**
	 * Returns the Globalization instance for the application.
	 * @return TGlobalization
	 */
	public function getGlobalization()
	{
		return pradoGetApplication()->getGlobalization();
	}

	/**
	 * Returns the service manager for this application.
	 * @return TServiceManager
	 */
	public function getServiceManager()
	{
		return pradoGetApplication()->getServiceManager();
	}

	/**
	 * Sets an attribute.
	 * The default implementation does nothing.
	 * This method is overriden by TControl.
	 * @param string the attribute name
	 * @param string the attribute value
	 */
	public function setAttribute($name,$value)
	{
	}

	/**
	 * Determines whether an event is defined.
	 * @param string the event name
	 * @return boolean
	 */
	public function hasEvent($name)
	{
		return $this->getDefinition(get_class($this))->hasEvent($name);
	}

	/**
	 * Attaches a handler function to an event.
	 *
	 * An ID path should be used to refer to a handler method
	 * within the context object. By default, the context object is null
	 * meaning the context is the component itself.
	 * For example, you can use 'Parent.onButtonClick' to refer
	 * to the method 'onButtonClick' that is defined in the Parent component.
	 *
	 * If the method name doesn't contain any dot and the context object is null,
	 * the context will be assumed the Parent of the component.
	 * Therefore, 'onButtonClick' is equivalent to 'Parent.onButtonClick'.
	 *
	 * @param string the event name.
	 * @param string the ID path of the method to be attached as an event handler.
	 * @param object the context object for the method (default=null)
	 * @throw TEventNotDefinedException
	 */
	public function attachEventHandler($name,$handler,$context=null)
	{
		if($this->hasEvent($name))
			$this->eventHandlers[$name][]=array($context,$handler);
		else
			throw new TEventNotDefinedException(get_class($this),$name);
	}

	/**
	 * Invokes all attached event handler functions for a particular event.
	 * @param string the event name
	 * @param TComponent the component that fires the event
	 * @param TEventParameter the event parameter
	 * @throw TEventNotDefinedException
	 */
	public function raiseEvent($name,$sender,$param)
	{
		if(isset($this->eventHandlers[$name]))
		{
			foreach($this->eventHandlers[$name] as $h)
			{
				$object=$h[0];
				$handler=$h[1];
				$pos=strrpos($handler,'.');
				if($pos===false)
				{
					if(is_null($object))
						$object=$this->parent;
					$method=$handler;
				}
				else
				{
					if(is_null($object))
						$object=$this;
					$object=$object->findObject(substr($handler,0,$pos));
					$method=substr($handler,$pos+1);
				}
				if(method_exists($object,$method))
					$object->$method($sender,$param);
				else
					throw new TEventHandlerInvalidException(get_class($this),$name,$handler);
			}
		}
	}

	/**
	 * Returns a property value by name or a child component by ID.
	 * If the child component ID is the same as the property name,
	 * the property takes precedence. If none are present,
	 * the method raises an exception.
	 *
	 * This function is provided so that you can get a property or
	 * a component by ID paths, e.g.: $this->MenuBar->HomeLink->Text
	 * where Text is the property name of HomeLink which is a child
	 * component of MenuBar which is a child component of $this.
	 * Since property name takes precedence over component ID,
	 * you may need to call {@link getChild} if you want to get
	 * a child component whose ID is identical to a property name.
	 * @param string the child component ID or the property name
	 * @return mixed the child component or the property value
	 * @throw TPropertyNotDefinedException
	 */
	public function __get($name)
	{
		$definition=$this->getDefinition(get_class($this));
		if($definition->hasProperty($name))
		{
			$getter=$definition->getPropertyGetter($name);
			return $this->$getter();
		}
		else if(isset($this->children[$name]))
			return $this->children[$name];
		else
		{
			pradoFatalError('Property is not defined: '.get_class($this).'.'.$name);
			// can't throw exception in magic method, may cause server crash
			// throw new TPropertyNotDefinedException(get_class($this),$name);
		}
	}

	/**
	 * Sets value of a component property.
	 *
	 * The property must be defined in the component specification.
	 * Otherwise an exception will be raised.
	 * @param string the property name
	 * @param mixed the property value
	 * @throw TPropertyReadOnlyException
	 * @throw TPropertyNotDefinedException
	 */
	public function __set($name,$value)
	{
		$definition=$this->getDefinition(get_class($this));
		if($definition->hasProperty($name))
		{
			$setter=$definition->getPropertySetter($name);
			if(empty($setter))
			{
				pradoFatalError('Property is read-only: '.get_class($this).'.'.$name);
				// throw new TPropertyReadOnlyException(get_class($this),$name);
			}
			else
				$this->$setter($value);
		}
		else
		{
			pradoFatalError('Property is not defined: '.get_class($this).'.'.$name);
			//throw new TPropertyNotDefinedException(get_class($this),$name);
		}
	}

	/**
	 * Returns the type of a property
	 * @return string|null the property type, null if property is not defined.
	 */
	public function getPropertyType($name)
	{
		return $this->getDefinition(get_class($this))->getPropertyType($name);
	}

	/**
	 * Determines whether a property is defined.
	 * @param string the property name
	 * @return boolean
	 */
	public function hasProperty($name)
	{
		return $this->getDefinition(get_class($this))->hasProperty($name);
	}

	/**
	 * Determines whether a property can be written.
	 * @param string the property name
	 * @return boolean
	 */
	public function canSetProperty($name)
	{
		return $this->getDefinition(get_class($this))->canSetProperty($name);
	}

	/**
	 * Determines whether a property can be read.
	 * @param string the property name
	 * @return boolean
	 */
	public function canGetProperty($name)
	{
		return $this->getDefinition(get_class($this))->canGetProperty($name);
	}
	
	/**
	 * Gets the initial value of a property.
	 * The assignment will be realized by calling {@link initProperties}.
	 * This method should only be used by framework developers.
	 * @param string property name
	 */
	public function getPropertyInitValue($name)
	{
		return isset($this->initValues[$name])?$this->initValues[$name]:null;
	}

	/**
	 * Sets the initial value of a property.
	 * The assignment will be realized by calling {@link initProperties}.
	 * This method should only be used by framework developers.
	 * @param string property name
	 * @param string property value in string format
	 */
	public function setPropertyInitValue($name,$value)
	{
		$this->initValues[$name]=$value;
	}

	/**
	 * Initializes the property values.
	 * This method should only be used by framework developers.
	 * @throw TPropertyNotDefinedException
	 */
	public function initProperties()
	{
		if(is_array($this->initValues))
		{
			$definition=$this->getDefinition(get_class($this));
			foreach($this->initValues as $name=>$value)
				$definition->configureProperty($this,$name,$value);
			$this->initValues=null;
		}
	}

	/**
	 * Evaluates a list of PHP statements.
	 * @param string PHP statements
	 * @return string content echoed or printed by the PHP statements
	 * @throw TStatementsInvalidException
	 */
	public function evaluateStatements($statements)
	{
		try
		{
			ob_start();
			if(eval($statements)===false)
				throw new Exception('');
			$content=ob_get_contents();
			ob_end_clean();
		}
		catch(Exception $e)
		{
			throw new TStatementsInvalidException($this->getUniqueID(),$e->getMessage());
		}
		return $content;
	}

	/**
	 * Evaluates a PHP expression.
	 * @param string PHP expression
	 * @return string the evaluation result
	 * @throw TExpressionInvalidException
	 */
	public function evaluateExpression($expression)
	{
		try
		{
			if(eval("\$result=$expression;")===false)
				throw new Exception('');
		}
		catch(Exception $e)
		{
			throw new TExpressionInvalidException($this->getUniqueID(),$expression,$e->getMessage());
		}
		return $result;
	}
	
	/**
	 * Sets up the binding between a property and an expression.
	 * The context of the expression is the component itself.
	 * @param string the property name
	 * @param string the expression
	 * @throw TPropertyReadOnlyException
	 * @throw TPropertyNotDefinedException
	 */
	public function bindProperty($name,$expression)
	{
		if($this->canSetProperty($name))
			$this->bindings[$name]=$expression;
		else if($this->hasProperty($name))
			throw new TPropertyReadOnlyException(get_class($this),$name);
		else
			throw new TPropertyNotDefinedException(get_class($this),$name);
	}

	/**
	 * Breaks the binding between a property and an expression.
	 * @param string the property name
	 */
	public function unbindProperty($name)
	{
		unset($this->bindings[$name]);
	}

	/**
	 * Sets a component ID.
	 * A component cannot change its ID once it is set,
	 * or an exception will be raised.
	 * It is recommended that the ID should be set by supplying
	 * the ID parameter to the createComponent method of TApplication.
	 * @param string the component ID
	 * @throw TPropertyReadOnlyException
	 * @throw TComponentIdInvalidException
	 */
	public function setID($id)
	{
		if(strlen($this->id))
			throw new TPropertyReadOnlyException(get_class($this),'ID');
		if($this->getDefinition(get_class($this))->isProperName($id))
			$this->id=$id;
		else
			throw new TComponentIdInvalidException(get_class($this),$id);
	}

	/**
	 * @return string the component ID
	 */
	public function getID()
	{
		return $this->id;
	}

	/**
	 * Sets the parent of this component.
	 *
	 * This method should only be used by framework developers.
	 * If you want to add a child component, use {@link addChild} instead.
	 * @param TComponent the parent
	 */
	public function setParent($parent)
	{
		$this->parent=$parent;
	}

	/**
	 * @return TComponent|null the parent object, null if no parent
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * Returns the unique ID of the component.
	 *
	 * The unique ID can be used for id attribute or name attribute
	 * of a HTML element. Internally, it is used by the framework
	 * to identify postback target and post data loaders.
	 * @return string the unique ID for the component
	 */
	public function getUniqueID()
	{
		return $this->uniqueID;
	}

	/**
	 * Finds an object by its ID path.
	 * For example, if HomePage is the parent of MenuBar which is the parent
	 * of UserName, then the UserName component can be located by the ID path
	 * "MenuBar.UserName" in HomePage.
	 * @param string the ID path of the object to be located
	 * @return mixed the object, null if not found
	 */
	public function findObject($idPath)
	{
		if(!strlen($idPath))
			return null;
		$object=$this;
		foreach(explode('.',$idPath) as $id)
		{
			if(is_null($object))
				return null;
			else
				$object=$object->$id;
		}
		return $object;
		// TODO: a complete ID path looks like 'ModuleID:PageID.ChildID.GrandChildID'
	}

	/**
	 * Returns an ID for a new child component.
	 * The ID will be ensured to be unique among the component's
	 * child components.
	 * @param string the component type
	 * @return string the ID allocated
	 */
	private function allocateID($type)
	{
		$this->nextID++;
		return '_'.$type.$this->nextID;
	}

	/**
	 * Performs the databinding for this component.
	 * Databinding a property includes evaluating the binded expression
	 * and setting the property with the evaluation result.
	 * This method will invoke {@link TComponent::onDataBinding()}.
	 * If $recusive is set true, databinding will also be performed
	 * for child components recursively.
	 * 
	 * @param boolean whether to databind child components recursively, default is true.
	 */
	public function dataBind($recursive=true)
	{
		foreach($this->bindings as $name=>$expression)
			$this->$name=$this->evaluateExpression($expression);
		$this->onDataBinding(new TEventParameter);
		if($recursive)
		{
			foreach($this->children as $child)
				$child->dataBind();
		}
	}

	/**
	 * This method is invoked when {@link dataBind} is invoked for the component.
	 * The method raises 'OnDataBinding' event.
	 * If you override this method, be sure to call the parent implementation
	 * so that the event handlers are invoked.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	protected function onDataBinding($param)
	{
		$this->raiseEvent('OnDataBinding',$this,$param);
	}

	/**
	 * Creates a child component.
	 * This is a convenient function for creating a component
	 * and adding it as a child component.
	 * @param string the component type
	 * @param string the component ID, empty for implicit ID
	 * @return TComponent the created component.
	 */
	public function createComponent($type,$id='')
	{
		$component=pradoGetApplication()->createComponent($type,$id);
		$this->addChild($component);
		return $component;
	}

	/**
	 * Returns a session variable.
	 *
	 * This function is used to fetch a variable persistent within a user session.
	 * @param string the session variable name
	 * @return mixed the session variable value, null if no session or the named variable doesn't exist.
	 */
	public function getSessionState($key)
	{
		$session=$this->getSession();
		if(is_null($session) || !$session->has($key))
			return null;
		else
			return $session->get($key);
	}

	/**
	 * Sets a session variable.
	 *
	 * This function is used to save a variable in session.
	 * @param string the session variable name
	 * @param mixed the session variable value
	 * @throw TSessionRequiredException
	 */
	public function setSessionState($key,$value)
	{
		$session=$this->getSession();
		if(is_null($session))
			throw new TSessionRequiredException(get_class($this)."::setSessionState()");
		$session->set($key,$value);
	}
}

?>