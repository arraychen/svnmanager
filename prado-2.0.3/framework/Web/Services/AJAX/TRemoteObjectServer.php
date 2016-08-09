<?php
/**
 * TRemoteObjectServer class file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Wei Zhuo. All rights reserved.
 *
 * To contact the author write to {@link mailto:weizhuo[at]gmail[dot]com Wei Zhuo}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */

/**
 * AJAX Remote Object server.
 *
 * Create a server to handle remote object method calls from a javascript client.
 * Usage:
 * <code>
 *  $server = new TAJAXRemoteObjectServer();
 *  $server->register('MyClass'); //register a remote object by class name
 *  $server->register($myObj); //register a remote object by instance
 *  if($server->handleRequest()) exit; //let the server handle any AJAX requests
 * </code>
 *
 * If registering remote objects using class name, that class must have a
 * zero parameter constructor, otherwise register an existing objects.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */
class TRemoteObjectServer extends TAjaxServer
{
	/**
	 * Service name space.
	 * @var string
	 */
	protected $NS = '__AJAX';
		
	/**
	 * Handles AJAX client javascript and actual AJAX requests.
	 * Assumes non-empty QUERY_STRING as AJAX requests.
	 * @return boolean true if AJAX request or client remote proxy js script request.
	 */
	public function handleRequest()
	{
		if(!empty($_SERVER['QUERY_STRING']))
		{
			if($this->uri->isClientRequest())				
				return $this->displayClient() || true;
			else if($this->uri->isServerRequest())
				return $this->serve() || true;			
		}
		return false;
	}
}

/**
 * Remote Object Info class.
 *
 * Basic object/class description, e.g. classname, methods, and an instance
 * of the class if available.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */
class TAjaxObjectInfo
{
	/**
	 * Object class name.
	 * @var string
	 */
	public $class = '';
	
	/**
	 * Object method signatures.
	 * @var array
	 */
	public $methods = array();
	
	/**
	 * Object instance.
	 * @var mixed
	 */
	public $instance = null;
	
	/**
	 * Examine an object or class and extract the web service definitions.
	 * @param string|object an object class name or an object instance
	 */
	public function __construct($object=null)
	{
		if(!is_null($object))
			$this->examine($object);
	}
	
    /**
    * Determines the "public" webservice class methods exposed by the object
    * @param object
    */
    protected function examine($object) 
    {
    	$class = new ReflectionClass($object);
  		$this->class = $class->getName();
		
		//get the method signatures
		foreach($class->getMethods() as $method)
		{
			if($method->isPublic() && $this->isWebServiceMethod($method))
			{
				$params = array();
				foreach($method->getParameters() as $param)
					$params[] = $param->getName();
				$this->methods[$method->getName()] = $params;
			}
		}	
			
		$this->instance = is_object($object) ? $object : null;
    }
    
    /**
     * Determine if the method is a web service methods.
     * @param ReflectionMethod the method to check
     * @return boolean true if contains @webservices in the method documentation.
     */
    protected function isWebServiceMethod($method)
    {
    	$doc = $method->getDocComment();
    	return strpos($doc, '@webservice') !== false;
    }	
}

/**
 * TAjaxStub class.
 *
 * Generate client-side javascript stub code.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */
class TAjaxStub
{
	/**
	 * Service url.
	 * @var string.
	 */
	protected $url;
	
	/**
	 * Create a new javascript stub code generator.
	 * @param string service url.
	 */
	public function __construct($service_url)
	{
		$this->url = $service_url;
	}
	
	/**
	 * Returns the service url.
	 * @return string url
	 */
	public function getServiceUrl()
	{
		return $this->url;
	}
	
	/**
	 * Generate client-side javascript proxy code.
	 * @param TAjaxObjectInfo object info.
	 * @return string stub code.
	 */
	public function generate($info=null)
	{
		$methods = implode(',', $this->generateMethod($info));		
		if(count($methods) == 0) return '';
$script = <<<EOD
var {$info->class} = Class.create();
{$info->class}.prototype = Object.extend(new Prado.AJAX.RemoteObject(),
{
	initialize : function(handlers, options)
	{
		this.__serverurl = '{$this->url}/{$info->class}';
		this.baseInitialize(handlers, options);
	},	 	
	{$methods}
});


EOD;
		return $script;	
	}
	
	/**
	 * Generate method signatures.
	 * @param TAjaxObjectInfo object info.
	 * @return string[] list of proxy code for each method.
	 */
	protected function generateMethod($info)
	{
		$methods = array();
		foreach($info->methods as $methodname => $params)
		{
			$args = implode(',', $params);

$methods[] = <<<EOD

	{$methodname} : function({$args})
	{
		return this.__call(this.__serverurl, '{$methodname}', arguments);
	}
EOD;
		
		}
		return $methods;
	}	
}


/**
 * AJAX client script render.
 *
 * Renders javascript for AJAX remote objects on-the-fly. Cache headers are
 * sent if the javascript has not been modified.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */
abstract class TAjaxClient
{
	/**
	 * Render the client-side javascript.
	 */
	public abstract function render();
		
	/**
	 * Get the cache identifier, a MD5 of the handlers and request URI.
	 * @param TAJAXClassInfo[] list of class info 
	 * @return string cache identifier.
	 */
	protected function getCacheETag($objects)
	{
		return md5($_SERVER['REQUEST_URI'].serialize($objects));
	}
	
	/**
	 * Determine if the given cache identifiers matches with that from
	 * the browser client. If match, send not-modified cache header.
	 * @param string cache indentifier to match
	 * @return boolean true if matches with client cache ID, false otherwise.
	 */
	protected function compareEtags($serverETag) 
	{
        if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) 
        {
    		if (strcmp($_SERVER['HTTP_IF_NONE_MATCH'],$serverETag) == 0) 
    		{
                $this->sendCacheHeaders($serverETag,true);
                return true;
            }
    	}
        $this->sendCacheHeaders($serverETag,false);
        return false;
    }
    
    /**
     * Sends cache headers with cache identifier.
     * @param string cache identifier.
     * @param boolean if not modifier, send 304 header.
     */
    protected function sendCacheHeaders($etag,$notModified) 
    {
        header('Cache-Control: must-revalidate');
        header('ETag: '.$etag);
        if ($notModified)
            header('HTTP/1.0 304 Not Modified',false,304);
    }
    
    /**
     * Sends the javascript header, i.e. content-length, and content-type.
     * @param int javascript content length.
     */
	protected function sendJsHeader($length=0)
	{
		if ($length > 0)
            $headers['Content-Length'] = $length;
        $headers['Content-Type'] = 'text/javascript; charset=utf-8';
        foreach($headers as $header => $value)
			header($header .': '.$value);		
	}
}

/**
 * AJAX Remote Object client stub render.
 *
 * Renders the javascript for remote AJAX objects.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */
class TAjaxStubClient extends TAjaxClient
{
	/**
	 * Code generator.
	 * @var TAjaxStub
	 */
	protected $stub;
	
	/**
	 * List of remote objects.
	 * @var TAjaxObjectInfo[]
	 */
	protected $objects;
	
	/**
	 * Create a new remote AJAX object render
	 * @param TAjaxStub code generator
	 * @param TAjaxObjectInfo list of classes to handle.
	 */
	public function __construct($stub, $objects)
	{
		$this->stub = $stub;
		$this->objects = $objects;
	}	
	
	/**
	 * Renders the javascript client. Checks the client-side cache id,
	 * render if the cache id differ from the current cache id.
	 */
	public function render()
	{
		$ETag = $this->getCacheETag($this->objects);
		if($this->compareEtags($ETag) == false)
			$this->renderScripts($this->objects);
	}
	
	/**
	 * Gets the javascript contents for render.
	 * @param TAjaxObjectInfo[] a list of remote objects acceptable.
	 * @return string javascript remote proxy code.
	 */
	protected function renderScripts($objects)
	{
		$code = '';
		foreach($objects as $object)
			$code .= $this->stub->generate($object);
		$this->sendJsHeader(strlen($code));
		echo $code;
	}	
}

/**
 * Prado AJAX server.
 *
 * Allows the javascript client to remotely invoke registered object methods.
 * All request parameters and return values are automatically marshalled 
 * into JSON format.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */
abstract class TAjaxServer
{
	/**
	 * List of registered remote objects.
	 * @var TAjaxObjectInfo[]
	 */
	protected $objects = array();
	
	/**
	 * AJAX Request URI.
	 * @var TAjaxUri
	 */
	protected $uri;
	
	/**
	 * Server namespace
	 * @var string
	 */
	protected $NS = '__AJAX';
	
	/**
	 * Create a new server for a particular URI.
	 * @param string server URI.
	 */
	public function __construct($uri=null)
	{
		$this->uri = new TAjaxUri($uri, $this->NS);
	}
	
	/**
	 * Sets the server URI.
	 * @param TAjaxUri
	 */
	public function setUri($uri)
	{
		$this->uri = $uri;
	}
	
	/**
	 * Gets the server URI.
	 * @return TAjaxUri
	 */
	public function getUri()
	{
		return $this->uri;
	}
	
	/**
	 * Register an object or class for remote method calls by client-side javascript.
	 * @param string|object the name of the class or any existing object.
	 */
	public function register($object)
	{
		if(is_string($object) || !($object instanceof TAjaxObjectInfo))
			$object = new TAjaxObjectInfo($object);
		$this->objects[$object->class] = $object;
	}
	
	/**
	 * Displays the remote object javascript proxy code.
	 */
	protected function displayClient()
	{
		$client = new TAjaxStubClient($this->getStubGenerator(), $this->objects);
		$client->render();
	}
	
	/**
	 * Returns the remote object stub code generator.
	 * @return TAjaxStub proxy code generator
	 */
	protected function getStubGenerator()
	{
		return new TAjaxStub($this->uri->getServerUri());
	}	
	
	/**
	 * Serve AJAX remote object requests.
	 */
	protected function serve()
	{
		$request = new TAjaxRequest($this->uri->getRequestUri(), $this->objects);
		$this->invoke($request->resolve());
	}
	
	/**
	 * Invoke the remote object proxy, thus calling the method on the requested
	 * object. The results return from the method call are marshalled into JSON
	 * and send as part of HTTP header 'X-JSON'.
	 * @param TAjaxProxy object proxy
	 */
	protected function invoke($object)
	{
		$response = new TAjaxResponse($object->invoke());
		$response->render();
	}	

	/**
	 * Passes exception details to the client-side javascript via JSON.
	 * @param Exception exception details.
	 */
	public function handleException($e)
	{
		$response = new TAJAXError($e);
		$response->render();;
	}
	
	/**
	 * Passes error details to the client-side by throwing an exception.
	 */
	public function handleError($errno, $errstr, $errfile, $errline) 
	{
		$error = new TAJAXException($errstr);
		$error->set($errfile, $errline);
		$error->setTrace(debug_backtrace());
		$this->handleException($error);
		exit();
	}	
	
	/**
	 * Returns the source URL for the client-side proxy code.
	 * @return string JS proxy code URL
	 */
	public function getJsSrc()
	{
		return $this->uri->getClientUri();
	}	
    	
	/**
	 * Handle the AJAX request.
	 */
	public abstract function handleRequest();	
}

/**
 * AJAX Request class. Resolves the request remote object call.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */
class TAjaxRequest
{
	/**
	 * List of available remote objects.
	 * @var TAjaxObjectInfo[]
	 */
	protected $objects;
	
	/**
	 * Request URI.
	 * @var string
	 */
	protected $uri;
	
	/**
	 * Create a new request to resolve for the given URI and remote objects.
	 * @param string request URI.
	 * @param TAjaxObjectInfo[] available remote objects.
	 */
	public function __construct($uri, $objects)
	{
		$this->uri = $uri;
		$this->objects = $objects;
	}
	
	/**
	 * Resolve the request, if valid returns TAJAXRemoteObject that can be invoked.
	 * @return TAJAXRemoteObject remote object for calling.
	 * @throws TAJAXInvalidRequestException
	 */
	public function resolve()
	{
		$request = explode('/',$this->uri);
		//basic check, needs 2 parameters
		if ( count($request) != 2 )
			throw new TAJAXInvalidRequestException('Invalid call syntax');
		
		//requested class name must be valid
		if ( preg_match('/^[a-zA-Z]+[0-9a-zA-Z_]*$/',$request[0]) != 1 )
			throw new TAJAXInvalidRequestException('Invalid handler name: '.$request[0]);
			
		//request class method must be valid as well
		if ( preg_match('/^[a-zA-Z]+[0-9a-zA-Z_]*$/',$request[1]) != 1 )
			throw new TAJAXInvalidRequestException('Invalid handler method: '.$request[1]);
		
		//requested class must be registered
		if ( !array_key_exists($request[0],$this->objects) )
			throw new TAJAXInvalidRequestException('Unknown handler: '.$request[0]);
					
		//requested class method must exists as well
		if ( !isset($this->objects[$request[0]]->methods[$request[1]]))
			throw new TAJAXInvalidRequestException('Unknown handler method: '
					.$request[0].'::'.$request[1].'()');
		
		$obj = $this->objects[$request[0]];
		$method = $request[1];
		$args = $this->getRequestArgs();
		
		//return the requested remote object
		return new TAjaxProxy($obj, $method, $args);
	}
	
	/**
	 * Returns the requested method parameter data.
	 * @return array parameter data.
	 */
	protected function getRequestArgs()
	{
		if(isset($_POST['__parameters']))
		{
			require_once(dirname(__FILE__).'/TJSON.php');
			$json = new TJSON();
			return $json->decode($_POST['__parameters']);
		}
		return array();
	}
}

/**
 * AJAX Response, sends back data and output.
 *
 * Returns JSON encoded (must be in UTF-8) data in X-JSON header, 
 * and renders the raw output.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */
class TAjaxResponse
{
	/**
	 * Response data.
	 * @var mixed
	 */
	protected $data;

	/**
	 * Response output.
	 * @var string
	 */
	protected $output;
	
	/**
	 * Create a new AJAX response.
	 * @param mixed response data.
	 * @param string response output.
	 */
	public function __construct($data=null, $output='')
	{
		$this->setData($data);
		$this->setOutput($output);
	}
	
	/**
	 * Sets the response data.
	 * @param mixed data.
	 */
	public function setData($data)
	{
		$this->data = $data;
	}
	
	/**
	 * Gets the response data.
	 * @return mixed response data.
	 */
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 * Sets the response output.
	 * @param string output
	 */
	public function setOutput($output)
	{
		$this->output = $output;
	}
	
	/**
	 * Returns the response output.
	 * @return string output
	 */
	public function getOutput()
	{
		return $this->output;
	}
	
	/**
	 * Renders the response, send JSON encoded data, if any, in the header
	 * X-JSON and renders the output.
	 */
	public function render()
	{
		$data = $this->getData();
		if(!is_null($data))
		{
			require_once(dirname(__FILE__).'/TJSON.php');		
			$json = new TJSON();
			header('X-JSON: '.$json->enc($data));
		}
		echo $this->getOutput();
	}	
	
}

/**
 * AJAX Request Error class.
 *
 * Returns the error details via JSON back to the calling client javascript.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */
class TAjaxError
{
	/**
	 * Exception details.
	 * @var Exception
	 */
	protected $exception;
	
	/**
	 * Create a new AJAX Exception handler.
	 * @param Exception exception to hand back to the client-side
	 */
	public function __construct($exception)
	{
		$this->exception = $exception;
	}
	
	/**
	 * Construct the exception details as an array.
	 * @return array exception details.
	 */
	protected function getExceptionDetails()
	{
		$e = $this->exception;
		$info['type'] = get_class($e);
		$info['code'] = $e->getCode();
		$info['message'] = $e->getMessage();
		$info['file'] = basename($e->getFile());
		$info['line'] = $e->getLine();
		//var_dump($e->getTrace());
		foreach($e->getTrace() as $trace)
		{
			if(isset($trace['file']))
			{
				$trace['file'] = basename($trace['file']);
				$info['trace'][] = $trace;
			}
		}
		return $info;
	}
	
	/**
	 * Send the exception details back via JSON.
	 */
	public function render()
	{
		header("HTTP/1.0 505 Server Error", true, 505);
		$msg = $this->exception->getMessage();
		$line = $this->exception->getLine();
		$file = $this->exception->getFile();
		error_log("Uncaught exception '{$msg}' near {$file}({$line})"); 
		$response = new TAjaxResponse($this->getExceptionDetails());
		$response->render();
	}
}

/**
 * AJAX Remote object class.
 *
 * Calls the remote object method from an AJAX request.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */
class TAjaxProxy
{
	/**
	 * Method request.
	 * @var string
	 */
	protected $request;
	
	/**
	 * The requested class instance.
	 * @var object
	 */
	protected $object;
	
	/**
	 * Method parameters.
	 * @var array
	 */
	protected $args = array();
	
	/**
	 * Creates a new remote object.
	 * @param TAjaxObjectInfo requested class instance.
	 * @param string requested class method.
	 * @param array requested method parameter data
	 */
	public function __construct($object, $request, $args)
	{
		$this->object = $object;
		$this->request = $request;
		$this->args = $args;
	}
	
	/**
	 * Call the requested class instance method.
	 * @return mixed data return from the method call.
	 */
	public function invoke()
	{
		$instance = $this->object->instance;
		if(is_null($instance))
			$instance = new $this->object->class;
	
		return $this->apply($instance, $this->request, $this->args);					
	}
	
	/**
	 * Instance method call, similar to apply in JS.
	 */
	protected function apply($obj, $method, $args = array())
	{
		return call_user_func_array(array($obj, $method), $args);
	}
}


/**
 * Base AJAX exception class.
 *
 * The error code are used as AJAX request HTTP status codes.
 * E.g. 
 *	- <b>505</b>, server error, 
 *	- <b>404</b>, service not found, 
 *  - <b>403</b>, authenticated failed.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */
class TAJAXException extends Exception 
{
	public function __construct($msg, $code=505)
	{
		parent::__construct($msg, $code);
	}
	
	/**
	 * Sets the error file and line number.
	 * @param string filename where the error occured
	 * @return int line number of the error
	 */
	public function set($file, $line)
	{
		$this->file = $file;
		$this->line = $line;
	}	
	
	/**
	 * Sets the trace for this exception.
	 * @param array error trace
	 */
	public function setTrace($trace)
	{
		$this->trace = $trace;
	}
}

/**
 * Invalid Handler Exception.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */
class TAJAXInvalidHandlerException extends TAJAXException 
{

}

/**
 * Thrown when an AJAX request is malformed or invalid.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */
class TAJAXInvalidRequestException extends TAJAXException 
{

}

/**
 * AJAX URI class.
 *
 * Manipulate the AJAX request URIs.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */
class TAjaxUri
{
	/**
	 * Server URL
	 * @var string
	 */
	protected $uri;
	
	/**
	 * Server URL namespace.
	 * @var string
	 */
	protected $NS;
	
	public function __construct($uri, $NS)
	{
		$this->NS = $NS;
		$this->uri = $uri ? $uri : $this->getDefaultUri();
	}
	
	/**
	 * Sets the URI url.
	 * @param string url
	 */
	public function setURL($url)
	{
		$this->uri = $url;
	}
	
	/**
	 * Gets the URL
	 * @return string url
	 */
	public function getUrl()
	{
		return $this->uri;
	}
	
	/**
	 * Gets the service NS.
	 * @return string Namespace
	 */
	public function getNS()
	{
		return $this->NS;
	}
	
	/**
	 * Sets the service Namespace
	 * @param string ns
	 */
	public function setNS($ns)
	{
		$this->NS = $ns;
	}
	
	/**
	 * Get the server service URL
	 * @return string serive URL
	 */
	public function getServerUri()
	{
		$sep = strpos($this->uri, '?') !== false ? '&' : '?';
		return $this->uri.$sep.$this->NS;
	}
	
	/**
	 * Get the client-side javascript source URL
	 * @return string client-side javascript URL
	 */
	public function getClientUri()
	{
		return htmlspecialchars($this->getServerUri().'&__client');
	}
	
	/**
	 * Returns true if the requesting a the client-side javascript code.
	 * @return boolean true if $_GET contains a '__client' parameter.
	 */
	public function isClientRequest()
	{
		return is_int(strpos($_SERVER['REQUEST_URI'], '__client')) && $this->isServerRequest();
	}
	
	/**
	 * Returns true if the request is to invoke a remote object method.
	 * @return boolean true if invoking remote method request.
	 */
	public function isServerRequest()
	{
		return is_int(strpos($_SERVER['REQUEST_URI'], $this->NS));
	}
	
 	/**
    * Returns the portion of the URL to the right of a pattern 
    * e.g. http://localhost/index.php?__AJAX/foo/bar returns
    * 'foo/bar'. 
    * @return string request URI
    */	
	public function getRequestUri() 
	{
		$find = '/^(.*)('.$this->NS.')/';
		/** this the following better ? **/
		//$find = '/^[a-zA-Z0-9:\/\\&;\.\?_%=,]+('.$this->NS.')/';
		$path = preg_replace($find, '', $_SERVER['REQUEST_URI']);
		if(preg_match('/^[a-zA-Z0-9\/_]+$/', $path))
			return substr($path, 1);
		else 
			throw new TAJAXInvalidRequestException('Invalid request URI '.$path);
    }
    
	/**
	 * Gets the default URI based on the current script name.
	 * @return string server URI
	 */
	public static function getDefaultUri()
	{
		$https = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on';
	    $prot = $https ? 'https://' : 'http://';      
        return $prot.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];		
	}	    
}

/**
 * AJAX URI using paths, e.g. mod_rewrite style.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:35 $
 * @package System.Web.Services.AJAX
 */
class TAjaxPathUri extends TAjaxUri 
{
	/**
	 * Gets the server URL
	 * @return string server url.
	 */
	public function getServerUri()
	{
		return $this->uri.'/?'.$this->NS;
	}
	
	/**
	 * Gets the client javascript source url.
	 * @return string client JS url.
	 */
	public function getClientUri()
	{
		return $this->getServerUri().'/__client';
	}
}

?>