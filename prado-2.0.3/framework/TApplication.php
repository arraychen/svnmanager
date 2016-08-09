<?php
/**
 * TApplication class file.
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
 * @version $Revision: 1.67 $  $Date: 2005/11/06 23:02:33 $
 * @package System
 */

/**
 * TApplication class
 *
 * A TApplication instance encapsulates the application-level data shared
 * among pages and modules. It also severs as a facade of many classes
 * including user, session, parser, locator, url, etc.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System
 */
class TApplication
{
	/**
	 * session var name for user object
	 */
	const SESSION_USER='prado_user';
	/**
	 * application state
	 */
	const STATE_ON='on';
	const STATE_OFF='off';
	const STATE_DEBUG='debug';

	/**
	 * ID of this application
	 * @var string
	 */
	protected $id='';
	/**
	 * the absolute path of the application specification file
	 * @var string
	 */
	protected $specificationFile='';
	/**
	 * application specification (parsed)
	 * @var mixed
	 */
	protected $specification=null;
	/**
	 * aliases to file paths
	 * @var array
	 */
	protected $paths=array();
	/**
	 * list of user-defined parameters, indexed by parameter names
	 * @var array
	 */
	protected $parameters=array();
	/**
	 * list of namespaces used globally
	 * @var array
	 */
	protected $preload=array();
	/**
	 * list of pages to be secured globally
	 * @var array
	 */
	protected $secured=array();
	/**
	 * list of handlers
	 */
	protected $handlers=array(	'user'=>'',
								'session'=>'TSession',
								'cache'=>'TCacheManager',
								'parser'=>'TResourceParser',
								'locator'=>'TResourceLocator',
								'request'=>'TRequest',
								'error'=>'TErrorHandler',
								'vsmanager'=>'TViewStateManager',
								'globalization' => 'TGlobalization',
								'services' => 'TServiceManager'
								);
	/**
	 * list of module specifications
	 * @var array
	 */
	protected $moduleSpecs=array();
	/**
	 * list of module instances
	 * @var array
	 */
	protected $modules=array();
	/**
	 * list of page instances
	 * @var array
	 */
	protected $pages=array();
	/**
	 * session object
	 * @var ISession
	 */
	protected $session=null;
	/**
	 * user object
	 * @var IUser
	 */
	protected $user=null;
	/**
	 * resource locator
	 * @var TResourceLocator
	 */
	protected $resourceLocator=null;
	/**
	 * resource parser
	 * @var TResourceParser
	 */
	protected $resourceParser=null;
	/**
	 * cache manager
	 * @var TCacheManager
	 */
	protected $cacheManager=null;
	/**
	 * request object
	 * @var TRequest
	 */
	protected $request=null;
	/**
	 * error handler
	 * @var TErrorHandler
	 */
	protected $errorHandler=null;
	/**
	 * viewstate manager
	 * @var TViewStateManager
	 */
	protected $vsm=null;
	/**
	 * globalization handler
	 * @var TGlobalization 
	 */
	protected $globalization=null;
	
	/**
	 * Service handler.
	 * @var TServiceManager
	 */
	protected $services=null;
	
	/**
	 * Filename of the currently loaded theme
	 * @var string
	 */
	protected $themeFile=null;
	
	/**
	 * Currently loaded theme
	 * @var array
	 */
	protected $theme=null;

	/**
	 * Constructor, parses application specification.
	 *
	 * Do not use 'new' operator to create TApplication instance.
	 * Use {@link getInstance} instead to get the TApplication singleton.
	 * @param string path of the application specification file
	 */
	function __construct($specFile)
	{
		// parse application specification file
		$this->specificationFile=realpath($specFile);
		$spec=simplexml_load_file($specFile);
		if($spec===false)
			die("Error in parsing application specification file '$specFile'.");
		$this->specification=$spec;

		$this->id=(string)$spec['ID'];
		if(!strlen($this->id))
			throw new Exception('Please specify an ID for the application in the specification.');
		$this->state=(string)$spec['state'];
		if($this->state!==self::STATE_OFF && $this->state!==self::STATE_ON)
			$this->state=self::STATE_DEBUG;

		// handler class configurations
		foreach($this->handlers as $name=>$class)
		{
			$handler=$spec->$name;
			if(!empty($handler['class']))
				$this->handlers[$name]=(string)$handler['class'];
		}

		// path alias definitions
		$this->paths=$this->readAliasConfig($spec->alias);
		$this->paths['System']=dirname(__FILE__);

		// globally used namespaces
		$this->preload=$this->readUsingConfig($spec->using);

		// globally defined user parameters
		$this->parameters=$this->readParameterConfig($spec->parameter);

		// globally secured pages
		$this->secured=$this->readSecuredConfig($spec->secured);
		
		// themes
		if (isset($spec->theme))
		{
			if (isset($spec->theme['path']))
				$themePath = $spec->theme['path'];
			else
				$themePath = $this->paths['System'];
			$themeFile = pradoGetContextPath($spec->theme['name'].".theme", $themePath);
			$this->themeFile = pradoGetContextPath($themeFile, dirname($this->specificationFile));
		}

		$needUser=count($this->secured)>0;
		// module specifications (module-level namespaces, secured pages, and parameters)
		foreach($spec->module as $module)
		{
			$name=(string)$module['ID'];
			if(!preg_match("/^[a-zA-Z]\\w*\$/",$name))
				throw new Exception("Module ID '$name' is invalid.");
			if(isset($this->moduleSpecs[$name]))
				throw new Exception("Module ID '$name' is not unique.");

			$class=empty($module['class'])?'TModule':(string)$module['class'];

			$file=(string)$module['file'];
			if(strlen($file))
			{
				$moduleFile=realpath(pradoGetContextPath($file,dirname($this->specification)));
				if($moduleFile===false || !is_file($moduleFile))
					throw new Exception("Module configuration file '$file' does not exist.");
				$moduleXML=simplexml_load_file($moduleFile);
				$preload=$this->readUsingConfig($moduleXML->using);
				$secured=$this->readSecuredConfig($moduleXML->secured);
				$parameters=$this->readParameterConfig($moduleXML->parameter);
			}
			else
			{
				$preload=array();
				$secured=array();
				$parameters=array();
			}

			$preload=array_merge($preload,$this->readUsingConfig($module->using));
			$secured=array_merge($secured,$this->readSecuredConfig($module->secured));
			$needUser=$needUser || count($secured)>0;
			$parameters=array_merge($parameters,$this->readParameterConfig($module->parameter));
			$this->moduleSpecs[$name]=array($module,$class,$preload,$secured,$parameters);
		}
		if($needUser && empty($this->handlers['user']))
			throw new Exception('A user class is required in order to secure pages.');
	}

	/**
	 * Reads the configuration for defining path aliases.
	 * @param mixed
	 * @return array list of path aliases
	 */
	protected function readAliasConfig($config)
	{
		$paths=array();
		$contextPath=dirname($this->specificationFile);
		foreach($config as $alias)
		{
			$name=(string)$alias['name'];
			$path=(string)$alias['path'];
			if(strlen($path))
				$paths[$name]=realpath(pradoGetContextPath($path,$contextPath));
			else
				throw new Exception("Path cannot be empty for alias '$name'.");
			if($paths[$name]===false || !file_exists($paths[$name]))
				throw new Exception("Path alias '$name' refers to a nonexisting path '$path'.");
		}
		return $paths;
	}

	/**
	 * Reads the configuration for declaring namespace inclusion.
	 * @param mixed
	 * @return array list of namespaces to be used
	 */
	protected function readUsingConfig($config)
	{
		$usings=array();
		foreach($config as $using)
			$usings[]=(string)$using['namespace'];
		return $usings;
	}

	/**
	 * Reads the configuration for defining secured pages.
	 * @param mixed
	 * @return array list of pages to be secured.
	 */
	protected function readSecuredConfig($config)
	{
		$secured=array();
		foreach($config as $s)
			$secured[(string)$s['page']]=(string)$s['role'];
		return $secured;
	}

	/**
	 * Reads the configuration for defining user parameters.
	 * @param mixed the parameter configuration
	 * @param string the parameter file path
	 * @return array list of user parameters.
	 */
	protected function readParameterConfig($config,$filePath='')
	{
		static $recursion=0;
		$recursion++;
		if($recursion>5)	// we allow recursive param file inclusion at most 5 levels.
			throw new Exception("Maximum parameter file inclusion level reached.");
		$parameters=array();
		$contextPath=dirname($this->specificationFile);
		foreach($config as $parameter)
		{
			$name=(string)$parameter['name'];
			$file=(string)$parameter['file'];
			$contextPath=strlen($filePath)?dirname($filePath):dirname($this->specificationFile);
			if(strlen($name))
				$parameters[$name]=$this->parseXMLParameters($parameter);
			else if(strlen($file))
			{
				$paramFile=realpath(pradoGetContextPath($file,$contextPath));
				if($paramFile===false || !is_file($paramFile))
					throw new Exception("Parameter file '$file' does not exist.");
				$paramXML=simplexml_load_file($paramFile);
				if($paramXML===false)
					throw new Exception("Error in parsing parameter file '$file'.");
				
				$ps=$this->readParameterConfig($paramXML->parameter,$paramFile);
				$parameters=array_merge($parameters,$ps);
			}
		}
		$recursion--;
		return $parameters;
	}

	/**
	 * Check the parameter for xml content, if xml return the xml
	 * otherwise return the string content.
	 * @param xml simple xml fragment
	 * @return xml|string string if only contents, if xml return xml
	 */
	private function parseXMLParameters($xml)
	{
		//found a child, return the whole xml fragment;
		foreach($xml->children() as $child)
			return $xml;

		//no children, thus only contents
		return (string)$xml;
	}

	/**
	 * Preprocessing before handling a request
	 *
	 * The method is invoked at the begin of {@link run}.
	 * It removes backslashes of input data if magic_quotes_gpc is on.
	 * It also encodes the user input data (&, ", ',  <, >).
	 * The encoded data are safe to be stored into database.
	 * If needed, you can decode the data by calling {@link pradoDecodeData}.
	 *
	 * Derived classes can override this method to provide customized initializations.
	 */
	protected function beginRequest()
	{
		if(get_magic_quotes_gpc())
		{
			if(isset($_GET))
				$_GET=array_map('pradoStripSlashes',$_GET);
			if(isset($_POST))
				$_POST=array_map('pradoStripSlashes',$_POST);
			if(isset($_REQUEST))
				$_REQUEST=array_map('pradoStripSlashes',$_REQUEST);
			if(isset($_COOKIE))
				$_COOKIE=array_map('pradoStripSlashes',$_COOKIE);
		}
	}

	/**
	 * Request cleanup work.
	 * Default implementation will save the user object to session if possible.
	 * The method is invoked at the end of {@link run} and {@link transfer}.
	 * Derived classes can override this method to provide customized cleanup work.
	 * Parent implementation should be invoked.
	 */
	protected function endRequest()
	{
		if(!is_null($this->session) && $this->session->isStarted() && !is_null($this->user))
			$this->session->set($this->id.':'.self::SESSION_USER,pradoSerializeObject($this->user));
		foreach($this->modules as $module)
			$module->onUnload(new TEventParameter);
	}

	/**
	 * Runtime initializations based on the application specification.
	 * Derived classes can override this method to provide additional initializations.
	 */
	protected function init()
	{
		foreach($this->preload as $namespace)
			using($namespace);

		$locatorClass=$this->handlers['locator'];
		$parserClass=$this->handlers['parser'];
		$cacheClass=$this->handlers['cache'];
		$errorClass=$this->handlers['error'];
		$requestClass=$this->handlers['request'];
		$sessionClass=$this->handlers['session'];
		$vsmClass=$this->handlers['vsmanager'];
		$globalizationClass=$this->handlers['globalization'];
		$serviceManagerClass=$this->handlers['services'];

		$this->resourceLocator=new $locatorClass($this->specification->locator);
		$this->resourceParser=new $parserClass($this->specification->parser);
		$this->cacheManager=new $cacheClass($this->specification->cache);
		$this->errorHandler=new $errorClass($this->specification->error);
		$this->request=new $requestClass($this->specification->request);
		$this->session=new $sessionClass($this->specification->session);
		$this->vsm=new $vsmClass($this->specification->vsmanager);

		$this->services=new $serviceManagerClass($this->specification->services);

		$this->session->start();

		$userClass=$this->handlers['user'];
		if(!empty($userClass))
		{
			if($this->session->has($this->id.':'.self::SESSION_USER))
				$this->user=pradoUnserializeObject($this->session->get($this->id.':'.self::SESSION_USER));
			if(!($this->user instanceof IUser))
				$this->user=new $userClass($this->specification->user);
			if(!($this->user instanceof IUser))
				throw new Exception('User class must implement IUser interface.');
		}
		
		// load the theme if one was declared in the app.spec file
		if (is_file($this->getThemeFile()))
		{
			$this->theme = $this->getResourceParser()->parseTheme(file_get_contents($this->getThemeFile()));
		}

		//globalization should be last, it may require Request, Session, Resource and User
		if($this->specification->globalization->length)
			$this->globalization=new $globalizationClass($this->specification->globalization);		
	}

	/**
	 * The main entry to serves a page request.
	 */
	public function run()
	{
		$this->init();
		if($this->state===self::STATE_OFF)
			$this->errorHandler->handleError(TErrorHandler::CASE_SITEOFF);
	
		//use exception handlers to allow other process to handle exceptions
		set_exception_handler(array($this, 'handleException'));
		if(!$this->handleServiceRequest())
		{
			//not a service request
			$this->beginRequest();
			$pageName=$this->request->getRequestedPage();
			// make sure page contains a valid string
			if(!preg_match('/^(\w+:)?(\w+)$/',$pageName))
				$this->errorHandler->handleError(TErrorHandler::CASE_PAGENOTFOUND);
			$page=$this->loadPage($pageName);
			$page->execute();
			$this->endRequest();
		}
		restore_exception_handler();
	}

	/**
	 * Handle exceptions.
	 * @param Exception 
	 */
	public function handleException($e)
	{
		$this->errorHandler->handleError(TErrorHandler::CASE_INTERNALERROR,$e);
	}
	
	/**
	 * Check for service requests, if able to handle them return true.
	 * @return boolean true if the request was handled, false otherwise.
	 */
	public function handleServiceRequest()
	{
		if(isset($this->services))
			return $this->services->handleRequest();
		else
			return false;
	}

	/**
	 * Terminates the execution of the current page and begins execution of a new page.
	 *
	 * This method simply redirects the browser to the new page.
	 * @param string|null the new page name (either PageType or ModuleName:PageType), null if default page
	 * @param array|null GET parameters to be passed to the new page (name=>value pairs), null if no get params
	 * @see execute(), redirect()
	 */
	public function transfer($pageName=null,$getParameters=null)
	{
		$url=$this->request->constructUrl($pageName,$getParameters);
		$this->redirect($url);
	}

	/**
	 * Redirects the browser to a URL.
	 * {@link endRequest} is invoked before the redirecting.
	 * @param string the URL to be redirected to.
	 * @see transfer()
	 */
	public function redirect($url)
	{
		$this->endRequest();		
		header("Location: $url");
		exit();
	}

	/**
	 * Executes the current request using another page.
	 *
	 * The method runs through the lifecycles of the new page including
	 * Init, Load, PreRender, save viewstate, Render and Unload.
	 * It returns with the rendering result of the new page.
	 * @param string the new page name (either PageType or ModuleName.PageType).
	 * @return string the rendering result of the new page
	 * @see transfer()
	 */
	public function execute($pageName)
	{
		$page=$this->loadPage($pageName);
		ob_start();
		$page->execute();
		$content=ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
	 * Loads a module instance.
	 * This method will create a module instance or load it from memory.
	 * @param string the module ID (must be defined in app spec)
	 * @return TModule the module instance.
	 * @throw TModuleNotDefinedException
	 */
	public function loadModule($name)
	{
		if(isset($this->modules[$name]))
			return $this->modules[$name];
		if(!isset($this->moduleSpecs[$name]))
			throw new TModuleNotDefinedException($name);
		foreach($this->moduleSpecs[$name][2] as $namespace)
			using($namespace);
		$this->cacheManager->setCurrentModule($name);
		$type=$this->moduleSpecs[$name][1];
		if(!pradoImportClass($type))
			throw new TModuleNotDefinedException($type);
		$module=$this->createComponent($type,$name);
		if(!($module instanceof TModule))  // will use is_subclass_of since PHP 5.0.3
			throw new TModuleNotDefinedException($type);
		$module->setUserParameters($this->moduleSpecs[$name][4]);
		$module->loadConfig($this->moduleSpecs[$name][0]);
		$module->initProperties();
		$this->modules[$name]=$module;
		$module->onLoad(new TEventParameter);
		return $module;
	}

	/**
	 * Loads a page instance.
	 * This method will create a page instance or load it from memory.
	 * If the page name contains module ID, the module will be also loaded.
	 * Errors happened during the loading will be handled using the error handler.
	 * @param string the page name (either PageType or ModuleName:PageType).
	 * @return TPage the loaded page instance
	 * @see createComponent()
	 */
	public function loadPage($type)
	{
		if(isset($this->pages[$type]))
			return $this->pages[$type];
		$type=trim($type,':');
		$pos=strpos($type,':');
		if($pos===false)
		{
			$moduleName='';
			$pageName=$type;
		}
		else
		{
			$moduleName=substr($type,0,$pos);
			$pageName=substr($type,$pos+1);
		}

		try
		{
			if(empty($pageName))
				throw new TPageNotDefinedException("[empty]");
			// user authentication
			$role=$this->getRequiredRole($moduleName,$pageName);
			if(!is_null($role))
			{
				if(!$this->user->isAuthenticated())
				{
					$this->user->onAuthenticationRequired($type);
					$this->errorHandler->handleError(TErrorHandler::CASE_UNAUTHORIZED,new TPageUnauthorizedException($type));
				}
			}
			// creating the module and page objects
			$module=empty($moduleName)?null:$this->loadModule($moduleName);
			if(!pradoImportClass($pageName))
				throw new TPageNotDefinedException($pageName);
			$page=$this->createComponent($pageName,$pageName);
			if(!($page instanceof TPage))   // will use is_subclass_of since PHP 5.0.3
				throw new TPageNotDefinedException($pageName);
			$page->setModule($module);

			// page authorization
			$authorized=is_null($role)?true:$this->user->isInRole($role);
			if($authorized)
				$authorized=$page->onAuthorize($this->user);
			if($authorized)
				return $page;
			else
			{
				$this->user->onAuthorizationRequired($page);
				$this->errorHandler->handleError(TErrorHandler::CASE_UNAUTHORIZED,new TPageUnauthorizedException($type));
			}
		}
		catch(TPageNotDefinedException $e)
		{
			$this->errorHandler->handleError(TErrorHandler::CASE_PAGENOTFOUND,$e);
		}
		catch(TModuleNotDefinedException $e)
		{
			$this->errorHandler->handleError(TErrorHandler::CASE_PAGENOTFOUND,$e);
		}
		catch(Exception $e)
		{
			$this->errorHandler->handleError(TErrorHandler::CASE_INTERNALERROR,$e);
		}
	}

	/**
	 * Creates a component instance.
	 * This method will create a component instance.
	 * If the $id parameter is supplied, the component will have its ID set to that
	 * after the creation.
	 * @param string the component type
	 * @param string the component ID
	 * @return TComponent the component instance
	 * @throw TComponentNotDefinedException
	 */
	public function createComponent($type,$id='')
	{
		// create component from cache if possible
		$component=$this->cacheManager->cloneComponent($type);
		if(is_null($component))
		{
			if(pradoImportClass($type))
				$component=new $type;
			else
				throw new TComponentNotDefinedException($type);
			// cache the component if needed (to facilitate future creations)
			$this->cacheManager->cacheComponent($component);
		}
		if(!empty($id))
			$component->setID($id);
		$component->initProperties();
		return $component;
	}


	/**
	 * Returns a user-defined parameter which is defined in the application specification
	 * @param string the parameter name
	 * @return string the parameter value, empty string if not defined.
	 */
	public function getUserParameter($name)
	{
		if(isset($this->parameters[$name]))
			return $this->parameters[$name];
		else
			return null;
	}

	/**
	 * Sets a user parameter.
	 * @param string parameter name
	 * @param mixed parameter value
	 */
	public function setUserParameter($name,$value)
	{
		$this->parameters[$name]=$value;
	}

	/**
	 * Determines the required role to access the page
	 * @param string the module ID, empty if module is not used
	 * @param string the page name
	 * @return string|null the role required to access the page, null if no authentication is needed
	 */
	public function getRequiredRole($moduleName,$pageName)
	{
		$role=null;
		$gname=empty($moduleName)?$pageName:"$moduleName.$pageName";
		if(isset($this->secured[$gname]))
			$role=$this->secured[$gname];
		else
		{
			foreach($this->secured as $pattern=>$value)
			{
				// we only do matching if the string contains '/', meaning a pattern
				if(strpos($pattern,'/')===0 && preg_match($pattern,$gname))
				{
					$role=$value;
					break;
				}
			}
		}
		if(is_null($role) && isset($this->moduleSpecs[$moduleName]))
		{
			if(isset($this->moduleSpecs[$moduleName][3][$pageName]))
				$role=$this->moduleSpecs[$moduleName][3][$pageName];
			else
			{
				foreach($this->moduleSpecs[$moduleName][3] as $pattern=>$value)
				{
					// we only do matching if the string contains '/', meaning a pattern
					if(strpos($pattern,'/')===0 && preg_match($pattern,$pageName))
					{
						$role=$value;
						break;
					}
				}
			}
		}
		return $role;
	}

	/**
	 * @return string the path containing the application specification file.
	 */
	public function getSpecificationFile()
	{
		return $this->specificationFile;
	}

	/**
	 * Returns the path corresponding to an alias.
	 *
	 * @param string the alias of the path defined in the application specification
	 * @return string|null the path corresponding to the alias, null if alias not defined.
	 * @depreciated Please use {@link translatePathAlias} instead.
	 */
	public function getPath($alias)
	{
		return isset($this->paths[$alias])?$this->paths[$alias]:null;
	}

	/**
	 * Returns the path corresponding to an alias.
	 * An alias is something like "System.Data" or "System.Web.UI.WebControls".
	 * The first segment in an alias will be replaced by the path corresponding
	 * to the segment alias defined in the application specification.
	 * The rest segments are appended to the translated root path with slashes.
	 * If suffix is present, it will be appended at the end.
	 * For example, assume in the application specification the directory
	 * "/usr/local/webapps/pages" is aliased as "Pages". Then
	 * the alias "Pages.UserModule.NewPage" will be translated as
	 * "/usr/local/webapps/pages/UserModule/NewPage".
	 * If a suffix ".php" is supplied, the path
	 * "/usr/local/webapps/pages/UserModule/NewPage.php" will be returned.
	 *
	 * In case the root alias is not defined, null will be returned.
	 *
	 * @param string the path alias to be translated
	 * @param string the suffix to be appended to the translated result
	 * @return string|null the path corresponding to the alias, null if the root alias not defined.
	 */
	public function translatePathAlias($alias,$suffix='')
	{
		$paths=explode('.',$alias);
		if(isset($this->paths[$paths[0]]))
		{
			$paths[0]=$this->paths[$paths[0]];
			return implode('/',$paths).$suffix;
		}
		else
			return null;
	}

	/**
	 * @return ISession the session object
	 */
	public function getSession()
	{
		return $this->session;
	}

	/**
	 * @return IUser the user object
	 */
	public function getUser()
	{
		return $this->user;
	}
	
	/**
	 * @return TResourceLocator the resource locator object
	 */
	public function getResourceLocator()
	{
		return $this->resourceLocator;
	}

	/**
	 * @return TResourceParser the resource parser object
	 */
	public function getResourceParser()
	{
		return $this->resourceParser;
	}

	/**
	 * @return TRequest the request object
	 */
	public function getRequest()
	{
		return $this->request;
	}

	/**
	 * @return TErrorHandler the error handler
	 */
	public function getErrorHandler()
	{
		return $this->errorHandler;
	}

	/**
	 * @return TGlobalization the globalization handler
	 */
	public function getGlobalization()
	{
		return $this->globalization;
	}

	/**
	 * @return TViewStateManager the viewstate manager
	 */
	public function getViewStateManager()
	{
		return $this->vsm;
	}

	/**
	 * @return string the application ID (defined in app spec)
	 */
	public function getApplicationID()
	{
		return $this->id;
	}

	/**
	 * @return string application state (on, off, debug)
	 */
	public function getApplicationState()
	{
		return $this->state;
	}
	
	/** 
	 * Sets the application state (on, off, debug) 
	 * @param string Acceptable values include on, off, debug 
	 */ 
	public function setApplicationState($value) 
	{ 
		$this->state=$value;
	} 
	
	/**
	 * Get the service manager.
	 * @return TServiceManager
	 */
	public function getServiceManager()
	{
		return $this->services;
	}
	
	/**
	 * @return string The filename for the currently loaded theme
	 */
	public function getThemeFile()
	{
		return $this->themeFile;
	}
	
	/**
	 * @return array The currently loaded theme
	 */
	public function getTheme()
	{
		return $this->theme['skins'];
	}
	
	/**
	 * @return string The name of the currently loaded theme
	 */
	public function getThemeName()
	{
		return $this->theme['name'];
	}
}

?>