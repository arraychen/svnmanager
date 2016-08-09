<?php
/**
 * TCallbackServer class file.
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
 * @version $Revision: 1.7 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.Services.AJAX
 */

/**
 * Include the generic RemoteObject AJAX server.
 */
require_once(dirname(__FILE__).'/TRemoteObjectServer.php');

/**
 * Callback server class.
 *
 * Callback server hooks AJAX remote object requests into the
 * Prado page life cycle. Form input data are automatically
 * de-marshalled (unserialized).
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.7 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.Services.AJAX
 */
class TCallbackServer extends TRemoteObjectServer 
{
	/**
	 * Service namespace.
	 * @var string
	 */
	protected $NS = "__CALLBACK";
	
	/**
	 * List of inputs that are posted during an AJAX request.
	 * @var TCollection
	 */
	protected $posts;
	
	/**
	 * Callback response.
	 * @var TAjaxResponse
	 */
	protected $response;
	
	/**
	 * Callback request
	 * @var TCallbackRequest
	 */
	protected $request;
	
	/**
	 * Create a new callback server.
	 * @param string server URI.
	 */
	public function __construct($uri=null)
	{
		parent::__construct($uri);
		$this->posts = new TCollection();
		$this->posts->add(TPage::INPUT_VIEWSTATE);		
		$this->response = new TCallbackResponse();
	}
	
	/**
	 * Returns the callback response.
	 * @return TCallbackResponse
	 */
	public function getResponse()
	{
		return $this->response;
	}
	
	/**
	 * Returns the callback request.
	 * @return TAjaxRequest request info
	 */
	public function getRequest()
	{
		return $this->request;
	}
	
	/**
	 * Initialize the callback server. Create a new callback request.
	 */
	public function initialize()
	{
		$uri = $this->uri->getRequestUri();
		$this->request = new TCallbackRequest($uri, $this->objects, $this->posts);
	}
	
	/**
	 * Returns the form input IDs that are returned for each callback request.
	 * @return TCollection input IDs returned on callback.
	 */
	public function getPostIDs()
	{
		return $this->posts;
	}
	
	/**
	 * Get the callback stub generator.
	 * @return TCallbackStub
	 */
	protected function getStubGenerator()
	{
		return new TCallbackStub($this->uri->getServerUri(), $this->posts);
	}
	
	/**
	 * Call the proxy method.
	 * Data returned from the proxy method is saved into 
	 * TCallback::$data.
	 */
	protected function serve()
	{
		$data = $this->request->resolve()->invoke();
		if(!is_null($data)) $this->response->data = $data;
	}
	
	/**
	 * Return the callback request by flushing the response.
	 */
	public function flush()
	{
		$response = new TAjaxResponse(
			$this->response->data, $this->response->output);
		$response->render();
	}
	
	/**
	 * Returns true if the callback request.
	 * @return boolean true if callback request, false otherwise.
	 */
	public function isCallbackRequest()
	{
		if($this->uri->isServerRequest() && !$this->uri->isClientRequest())
		{
			$uri = $this->uri->getRequestUri();
			$request = new TAjaxRequest($uri, $this->objects);
			return ($request->resolve() instanceof TAjaxProxy);
		}
		return false;
	}
}

/**
 * Callback client stubs.
 *
 * Generate the callback server url and a list of form inputs required
 * on a callback request.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.7 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.Services.AJAX
 */
class TCallbackStub extends TAjaxStub  
{
	/**
	 * List of form input IDs.
	 * @var array
	 */
	protected $callback_inputs = array();
		
	/**
	 * Create a new callback client stub.
	 * @param string server url
	 * @param array list of form input IDs required on callback request.
	 */
	public function __construct($url,  $callback_inputs)
	{
		parent::__construct($url);
		$this->callback_inputs = $callback_inputs;
	}
	
	/**
	 * Generate the stub code.
	 * @return string callback client stub code.
	 */
	public function generate($info=null)
	{
		$IDs = TJavascript::toArray($this->callback_inputs);
		$script = <<<EOD
Prado.AJAX.Callback.Server = '{$this->url}/{$info->class}';
Prado.AJAX.Callback.IDs = {$IDs};

EOD;
		return $script;
	}
}

/**
 * Callback request class.
 *
 * Unserialize the post data.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.7 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.Services.AJAX
 */
class TCallbackRequest extends TAJAXRequest 
{
	/**
	 * List of post back control input IDs.
	 * @var TCollection	
	 */
	protected $posts;
	
	/**
	 * Create a new callback request. Use loadCallbackPostData to load data.
	 */
	public function __construct($uri, $objects, $posts)
	{
		parent::__construct($uri, $objects);
		$this->posts = $posts;
	}
	
	/**
	 * Gets the control ID that will handle the callback request.
	 * @return string control ID.
	 */
	public function getRequestID()
	{
		if(isset($_POST['__ID']))
		{
			$id = trim($_POST['__ID'],'"');
			if(preg_match('/[0-9a-zA-Z_:]+/', $id))
				return $id;
		}
	}

	/**
	 * Gets the data collected during the callback request. It contains
	 * all the inputs for controls that implements IPostBackEventHandler.
	 * @return array callback data.
	 */
	public function getPostData()
	{
		if(isset($_POST['__data']))
		{
			require_once(dirname(__FILE__).'/TJSON.php');
			$json = new TJSON();
			return $json->decode($_POST['__data']);
		}
		return array();		
	}
	
	/**
	 * Load the allowable callback post data into $_REQUEST array.
	 */
	public function loadCallBackPostData()
	{
		$data = $this->getPostData();
		foreach($data as $k => $v)
		{
			if($this->posts->contains($k))
				$_REQUEST[$k] = $v;
		}
	}
}

/**
 * Callback response data.
 *
 * Callback response output and response JSON data.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.7 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.Services.AJAX
 */
class TCallbackResponse
{
	/**
	 * Response output.
	 * @var string
	 */
	public $output;
	
	/**
	 * Response data, will be marshalled into JSON format when rendered.
	 * @var mixed
	 */
	public $data;	
}

?>