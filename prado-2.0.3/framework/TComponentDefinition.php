<?php
/**
 * TComponentDefinition class file.
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
 * @version $Revision: 1.24 $  $Date: 2005/05/29 07:09:19 $
 * @package System
 */

/**
 * TComponentDefinition class
 *
 * TComponentDefinition specifies the definition of a component type.
 * It includes the component property and event definitions, the component
 * template parsing result.
 *
 * TComponentDefinition can be used to realize a component instance, i.e.,
 * setting property intial values, binding event handlers, and creating
 * child components and body content.
 *
 * Namespace: System
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System
 */
class TComponentDefinition
{
	/**
	 * Format of explicit component IDs, property names, and event names.
	 */
	const ID_FORMAT="/^[a-zA-Z]\\w*\$/";
	/**
	 * Format of property type
	 */
	const ENUM_FORMAT="/^\\(\\w+(,\\w+)*\\)\$/";
	/**
	 * boolean type for component property, used in property definition
	 */
	const TYPE_BOOLEAN='boolean';
	/**
	 * integer type for component property, used in property definition
	 */
	const TYPE_INTEGER='integer';
	/**
	 * float type for component property, used in property definition
	 */
	const TYPE_FLOAT='float';
	/**
	 * string type for component property, used in property definition
	 */
	const TYPE_STRING='string';
	/**
	 * array type for component property, used in property definition
	 */
	const TYPE_ARRAY='array';
	/**
	 * object type for component property, used in property definition
	 */
	const TYPE_OBJECT='object';

	/**
	 * component type
	 * @var string
how 	 */
	private $type;
	/**
	 * component property definitions
	 * @var array
	 */
	private $properties;
	/**
	 * component event definitions
	 * @var array
	 */
	private $events;
	/**
	 * child component configurations
	 * @var array
	 */
	private $components;
	/**
	 * template parsing result
	 * @var array
	 */
	private $template;
	/**
	 * master page name
	 * @var string
	 */
	private $masterPageName='';

	/**
	 * Constructor.
	 * Builds the definition of the specified component type.
	 * Definition of the parent component type will be imported.
	 * @param string component type
	 */
	function __construct($type)
	{
		$this->type=$type;
		if($type==='TComponent')
		{
			$this->properties=array();
			$this->events=array();
			$this->components=array();
			$this->template=null;
		}
		else
		{
			$inherit=TComponent::getDefinition(get_parent_class($type));
			$this->properties=$inherit->properties;
			$this->events=$inherit->events;
			$this->components=$inherit->components;
			$this->template=$inherit->template;
		}
		$this->build();
	}

	/**
	 * Checks whether a name/ID is in valid format.
	 * This method is used for checking explicit component IDs, 
	 * property names, and event names.
	 * @param string the name/ID to be checked
	 * @return boolean
	 */
	public function isProperName($name)
	{
		return preg_match(self::ID_FORMAT,$name);
	}

	/**
	 * @return string component type
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Adds a property definition.
	 * @param string property name
	 * @param string property type
	 * @param string property getter method
	 * @param string property setter method
	 * @throw TPropertyRedefinedException
	 * @throw TPropertyNameInvalidException
	 * @throw TPropertyTypeInvalidException
	 * @throw TPropertyGetterInvalidException
	 * @throw TPropertySetterInvalidException
	 */
	protected function defineProperty($name,$type,$getter,$setter)
	{
		if($this->hasProperty($name) || $this->hasEvent($name))
			throw new TPropertyRedefinedException($this->type,$name);
		if(!$this->isProperName($name))
			throw new TPropertyNameInvalidException($this->type,$name);
		if(!strlen($type))
			$type=self::TYPE_STRING;
		if($type!==self::TYPE_BOOLEAN && $type!==self::TYPE_INTEGER 
				&& $type!==self::TYPE_FLOAT && $type!==self::TYPE_ARRAY 
				&& $type!==self::TYPE_OBJECT && $type!==self::TYPE_STRING)
		{
			if(preg_match(self::ENUM_FORMAT,$type))
				$type=explode(',',trim($type,'()'));
			else
				throw new TPropertyTypeInvalidException($this->type,$name,$type);
		}
		if(strlen($getter) && !is_callable(array($this->type,$getter)))
			throw new TPropertyGetterInvalidException($this->type,$name,$getter);
		if(strlen($setter) && !is_callable(array($this->type,$setter)))
			throw new TPropertySetterInvalidException($this->type,$name,$setter);
		$this->properties[$name]=array($type,$getter,$setter,$this->type);
	}

	/**
	 * @param string property name
	 * @return boolean whether the property exists
	 */
	public function hasProperty($name)
	{
		return isset($this->properties[$name]);
	}

	/**
	 * @param string property name
	 * @return string|null the property type, null if the property doesn't exist
	 */
	public function getPropertyType($name)
	{
		return $this->hasProperty($name)?$this->properties[$name][0]:null;
	}

	/**
	 * @param string property name
	 * @return boolean whether the property is readable, false if the property doesn't exist
	 */
	public function canGetProperty($name)
	{
		return $this->hasProperty($name) && strlen($this->properties[$name][1]);
	}

	/**
	 * @param string property name
	 * @return boolean whether the property is writeable, false if the property doesn't exist
	 */
	public function canSetProperty($name)
	{
		return $this->hasProperty($name) && strlen($this->properties[$name][2]);
	}

	/**
	 * @param string property name
	 * @return string the property getter method, null if property doesn't exist
	 */
	public function getPropertyGetter($name)
	{
		return $this->canGetProperty($name)?$this->properties[$name][1]:null;
	}

	/**
	 * @param string property name
	 * @return string the property setter method, null if property doesn't exist
	 */
	public function getPropertySetter($name)
	{
		return $this->canSetProperty($name)?$this->properties[$name][2]:null;
	}

	/**
	 * Adds an event definition.
	 * @param string event name
	 * @throw TEventRedefinedException
	 * @throw TEventNameInvalidException
	 */
	protected function defineEvent($name)
	{
		if($this->hasEvent($name) || $this->hasProperty($name))
			throw new TEventRedefinedException($this->type,$name);
		if(!$this->isProperName($name))
			throw new TEventNameInvalidException($this->type,$name);
		$this->events[$name]=$this->type;
	}

	/**
	 * @param string event name
	 * @return boolean whether the event is defined
	 */
	public function hasEvent($name)
	{
		return isset($this->events[$name]);
	}

	public function setMasterPageName($name)
	{
		$this->masterPageName=$name;
	}

	public function getMasterPageName()
	{
		return $this->masterPageName;
	}

	/**
	 * Builds a component definition.
	 * This method will parse component specification and template,
	 * and create corresponding property, event definitions, child components,
	 * and body content.
	 * @throw TComponentTemplateRedefinedException
	 */
	protected function build()
	{
		$application=pradoGetApplication();
		$locator=$application->getResourceLocator();
		$parser=$application->getResourceParser();
		// properties, events, and components defined in specification
		$str=$locator->getSpecification($this->type);
		if(strlen($str))
		{
			$spec=$parser->parseSpecification($str);
			if(!is_null($spec))
			{
				foreach($spec['property'] as $property)
					$this->defineProperty($property[0],$property[1],$property[2],$property[3]);
				foreach($spec['event'] as $event)
					$this->defineEvent($event);
				$this->components=array_merge($this->components,$spec['component']);
			}
		}

		// save the parsing result of template
		$str=$locator->getTemplate($this->type);
		if(strlen($str))
		{
			if(is_null($this->template))
				$this->template=$parser->parseTemplate($str);
			else
				throw new TComponentTemplateRedefinedException($this->type);
		}
	}

	/**
	 * Realizes the definition for a component instance.
	 * The component instance will be initialized for its properties.
	 * The child components will be instantiated.
	 * The body content will be added.
	 * @param TComponent the component instance.
	 */
	public function applyTo($component)
	{
		$application=pradoGetApplication();
		// create components defined in specification
		foreach($this->components as $spec)
		{
			list($type,$id,$properties,$events)=$spec;
			$child=$application->createComponent($type,$id);
			foreach($properties as $name=>$value)
			{
				if(strlen($value)>1 && $value{0}==='#')
				{
					if($value{1}!=='#')
						$child->bindProperty($name,substr($value,1));
					else
						$child->setPropertyInitValue($name,substr($value,1));
				}
				else
					$child->setPropertyInitValue($name,$value);
			}
			foreach($events as $name=>$value)
				$child->attachEventHandler($name,$value);
			$component->addChild($child);
		}
		
		// check for property name conflicts
		$reflection = new ReflectionClass($component);
		$objectVars = array_keys($reflection->getDefaultProperties());
		foreach (array_keys($this->properties) as $propertyName)
		{
			if(in_array($propertyName, $objectVars))
				throw new TPropertyNameClashException($this->type,$propertyName);
		}

		// create components defined in the template
		// and add them as body of the component
		if(!is_null($this->template))
			$this->instantiateTemplate($component,$this->template);
	}

	/**
	 * Instantiates a template for a component.
	 * Components declared in the template will be instantiated
	 * and added as children of the component. The container-containee
	 * relationship will also be established.
	 * @param TComponent the owner of the template
	 * @param array the parsing result returned by TResourceParser.
	 */
	public function instantiateTemplate($component,$template)
	{
		$application=pradoGetApplication();
		if(is_string($template))
			$template=$application->getResourceParser()->parseTemplate($template);
		$components=array();
		foreach($template as $key=>$object)
		{
			//special directives
			if((string)$object[0] === 'directive')
			{
				$directiveClass = 'T'.$object[1].'Directive';
				$directive = new $directiveClass;
				$directive->assess($object[2],$this);
			}
			// there are two types of objects:
			// - string: 0: container index; 1: string content
			// - component: 0: container index; 1: type; 2: id; 3: attributes (array)
			else if(count($object)>2)
			{
				// component
				list($cid,$type,$id,$attributes)=$object;
				$child=$application->createComponent($type,$id);
				$childSpec=TComponent::getDefinition($type);
				foreach($attributes as $name=>$value)
				{
					if($childSpec->canSetProperty($name))
					{
						if(strlen($value)>1 && $value{0}==='#')
						{
							if($value{1}!=='#')
								$child->bindProperty($name,substr($value,1));
							else
								$child->setPropertyInitValue($name,substr($value,1));
						}
						else
							$child->setPropertyInitValue($name,$value);
					}
					else if($childSpec->hasEvent($name))
						$child->attachEventHandler($name,$value);
					else
						$child->setAttribute($name,$value);
				}
				if ($child instanceof TControl)
					$child->initSkin($child->getPropertyInitValue("Skin"));
				$child->initProperties();
				$components[$key]=$child;
				$container=isset($components[$cid])?$components[$cid]:$component;
				$container->addParsedObject($child,$component);
			}
			else
			{
				// static text
				$cid=(string)$object[0];
				$container=isset($components[$cid])?$components[$cid]:$component;
				$container->addParsedObject($object[1],$component);
			}
		}
	}

	/** 
	 * Converts a string to a particular type.
	 * @param string the string representation of a value
	 * @param string the target type
	 * @return mixed the converted result.
	 */
	public function convertPropertyValue($value,$type)
	{
		if($type===self::TYPE_BOOLEAN)
			return strcasecmp($value,'false')!=0;
		else if($type===self::TYPE_INTEGER)
			return intval($value);
		else if($type===self::TYPE_FLOAT)
			return floatval($value);
		else if($type===self::TYPE_ARRAY)
		{
			if(strlen($value))
			{
				eval("\$_arrayValue=array$value;");
				return $_arrayValue;
			}
			else
				return array();
		}
		else
			return $value;
	}

	/**
	 * Sets a component's property with a string value.
	 * The string value will be converted to appropriate types
	 * before setting the property.
	 * @param TComponent the component
	 * @param string the property name
	 * @param string the property value in string format
	 */
	public function configureProperty($component,$name,$value)
	{
		$type=$this->getPropertyType($name);
		if(is_null($type))
			throw new TPropertyNotDefinedException(get_class($component),$name);
		if(is_array($type)) // enumerate type
		{
			if(!in_array($value,$type,true))
				throw new TPropertyValueInvalidException(get_class($component),$name,$value);
		}
		else if($type===self::TYPE_BOOLEAN)
		{
			if(strcasecmp($value,'false')==0)
				$value=false;
			else if(strcasecmp($value,'true')==0)
				$value=true;
			else
				throw new TPropertyValueInvalidException(get_class($component),$name,$value);
		}
		else if($type===self::TYPE_INTEGER)
			$value=intval($value);
		else if($type===self::TYPE_FLOAT)
			$value=floatval($value);
		else if($type===self::TYPE_ARRAY)
		{
			if(strlen($value))
				eval("\$value=array$value;");
			else
				$value=array();
		}
		$component->$name=$value;
	}

	/**
	 * @return array list of defined properties
	 */
	public function getProperties()
	{
		return array_keys($this->properties);
	}

	/**
	 * @return array list of defined events
	 */
	public function getEvents()
	{
		return array_keys($this->events);
	}
}

?>