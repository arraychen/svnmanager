<?php
/**
 * TCallbackPage class file.
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
 * @version $Revision: 1.2 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.Services
 */

/**
 * TCallbackPage page class handles component callback requests (AJAX).
 *
 * Any component that implements ICallbackEventHandler interface must reside
 * within a page that extends TCallbackPage. TCallbackPage
 * implement a different page life cycle than that of the normal TPage.
 * The lifecycle of TCallbackPage is as follows.
 *
 * If the page is requested is not a service request
 * - Execute the normal page lifecycles @see TPage::execute()
 *
 * If the page is requested in response to a AJAX request
 * (may be a Callback request) then
 * - OnInit event
 * - OnLoad event
 * - ** execute service **
 * - OnUnload event
 * - ** flush server outputs **
 *
 * If the page is requested in response to a valid Callback request, then
 * - OnInit event
 * - ** initialize the server request **
 * - ** load post data from request **
 * - load viewstate
 * - load post data
 * - OnLoad event
 * - load post data (for newly created components during Load event)
 * - ** raise callback event **
 * - OnUnload event
 * - ** flush server outputs **
 *
 * Namespace: System.Web.UI
 *
 * Properties
 * - <b>IsCallback</b>, boolean, read-only
 *   <br>Gets the value that indicates whether the current request is a
 *      client callback.
 * - <b>CallbackResponse</b>, boolean, read-only
 *   <br>Gets callback response object, such that response data and output
 *   can be overridden.
 *
 * Namespace: System.Web.Services
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.2 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.Services
 */
class TCallbackPage extends TPage
{
	/**
	 * A list of callback candiates, controls that implement
	 * ICallbackEventHandler.
	 * @var array
	 */
	private $callbacksCandidates = array();

	/**
	 * Callback Service.
	 * @var TService_Callback
	 */
	private $service;

	/**
	 * Initialize the callback service.
	 */
	public function __construct()
	{
		$this->initCallbackService();
		parent::__construct();
	}

	/**
	 * Creates a new Callback service. Sets the service URI
	 * and add callback server to the service list.
	 */
	protected function initCallbackService()
	{
		$this->service =  new TService_Callback();

		//set the service URI
		$request = $this->getRequest();
		$url = $request->constructUrl($request->getRequestedPage());
		$NS = $this->service->server()->getUri()->getNS();
		if($this->Request->getFormat() == TRequest::FORMAT_GET)
			$this->service->server()->setUri(new TAjaxUri($url, $NS));
		else
			$this->service->server()->setUri(new TAjaxPathUri($url, $NS));

		//add services
		if($this->service->isServiceRequest())
		{
			$serviceManager = $this->Application->getServiceManager();
			if($serviceManager)
				$serviceManager->addService(
					get_class($this->service),$this->service);
		}
	}

	/**
	 * Gets the callback service handler.
	 * @return TService_Callback
	 */
	protected function getCallbackService()
	{
		return $this->service;
	}


	/**
	 * Executes page lifecycles for a callback request
	 *
	 * If the page is requested is not a service request
	 * - Execute the normal page lifecycles @see TPage::execute()
	 *
	 * If the page is requested in response to a AJAX request
	 * (may be a Callback request) then
	 * - OnInit event
	 * - OnLoad event
     * - ** execute service **
     * - OnUnload event
     * - ** flush server outputs **
	 *
	 * If the page is requested in response to a valid Callback request, then
	 * - OnInit event
	 * - ** initialize the server request **
	 * - ** load post data from request **
	 * - load viewstate
	 * - load post data
	 * - OnLoad event
	 * - load post data (for newly created components during Load event)
	 * - ** raise callback event **
	 * - OnUnload event
     * - ** flush server outputs **
     *
	 * @see TPage::execute()
	 */
	public function execute()
	{
		//Callback life-cycle
		if($this->service->isServiceRequest())
		{
			if($this->Application->getApplicationState()
				 == TApplication::STATE_DEBUG)
			{
				//let the callback server handle errors and exceptions
				$server = $this->service->server();
				set_error_handler(array($server, 'handleError'));
				set_exception_handler(array($server, 'handleException'));
			}

			$this->onPreInit(new TEventParameter);
			$this->determinePostBackMode();
			$this->onInitRecursive(new TEventParameter);
			$this->service->register($this);

			//a valid callback request
			if($this->isCallBack())
			{
				$this->service->server()->initialize();
				$this->service->loadCallBackPostData();
				$state=$this->loadPageStateFromPersistenceMedium();
				$this->loadViewState($state);
				$this->loadPostData();
				$this->onLoadRecursive(new TEventParameter);
				$this->loadPostData();

				/** raise post back data changed or not? **/
				//$this->raisePostDataChangedEvents();
				//$this->handlePostBackEvent();
			}
			else
			{
				$this->onLoadRecursive(new TEventParameter);
			}

			$this->service->execute();
			$this->onUnloadRecursive(new TEventParameter);
			$this->service->server()->flush();
		}
		else
		{
			//normal page execution cycle
			parent::execute();
		}
	}

	/**
	 * Returns true if serving a valid callback request.
	 * @return boolean true if valid callback request, false otherwise
	 */
	public function isCallback()
	{
		return $this->service->isCallback();
	}

	/**
	 * Handle the callback request, dispatch the corresponding callback events.
	 * @param mixed callback request parameter
	 * @webservice
	 */
	public function handleCallback($param)
	{
		$id = $this->service->getRequestID();
		$obj = $id == get_class($this) ? $this : $this->findObject($id);
		if($obj instanceof ICallbackEventHandler)
			return $obj->raiseCallbackEvent($param);
		else
			throw new TCallbackInvalidRequestIDException($id);
	}

	/**
	 * Register the callback script file.
	 * @param TEventParameter pre-render parameters
	 */
	protected function onPreRender($param)
	{
		$this->registerClientScript('ajax');

		if(!empty($this->callbacksCandidates)
			&& !$this->isScriptFileRegistered(get_class($this)))
		{
			$script = $this->service->getClientUri();
			$this->registerScriptFile(get_class($this), $script);
		}
		parent::onPreRender($param);
	}

	/**
	 * Register controls that can handle callbacks. Controls that implement
	 * ICallbackEventHandler are automatically added during component
	 * registration.
	 * @param ICallbackEventHandler a control that can handle callback.
	 */
	public function registerCallbackCandidate(ICallbackEventHandler $control)
	{
		$this->callbacksCandidates[$control->getUniqueID()] = $control;
	}

	/**
	 * Remove the control from the list of callback candidates.
	 * @param ICallbackEventHandler the control to remove.
	 */
	public function unregisterCallbackCandidate($control)
	{
		if(isset($this->callbacksCandidates[$control->getUniqueID()]))
			unset($this->callbacksCandidates[$control->getUniqueID()]);
	}

	/**
	 * Registers a postdata loader. This allows the callback request
	 * to collect the form inputs.
	 * The control must implement IPostBackDataHandler interface.
	 * @param IPostBackDataHandler the control that wants to load postback data
	 */
	public function registerPostDataLoader(IPostBackDataHandler $control)
	{
		$posts = $this->service->server()->getPostIDs();
		$posts->add($control->getUniqueID());
		parent::registerPostDataLoader($control);
	}

	/**
	 * Unregister a postdata loader from the page.
	 * @param TControl the post data loader control
	 */
	public function unregisterPostDataLoader($control)
	{
		$posts = $this->service->server()->getPostIDs();
		$posts->remove($control->getUniqueID());
		parent::unregisterPostDataLoader($control);
	}

	/**
	 * Returns the callback response.
	 * @return TCallbackResponse
	 */
	public function getCallbackResponse()
	{
		return $this->service->server()->getResponse();
	}

	/**
	 * Returns the callback javascript reference.
	 * @param ICallbackEventHandler control
	 * @param mixed callback parameters
	 * @param string client-side onSuccess function
	 * @return string javascript callback code.
	 */
	public function getCallbackReference($control, $args=null, $onSuccess=null)
	{
		$id = $this->getCallbackID($control);
		if(!is_null($args))
		{
			$args = substr($args,0,11) == 'javascript:'
								? substr($args,11) : "'{$args}'";
		}
		if(!is_null($onSuccess))
			return "Prado.Callback('{$id}', $args, $onSuccess)";
		if(!is_null($args))
			return "Prado.Callback('{$id}', $args)";
		return "Prado.Callback('{$id}')";
	}

	/**
	 * Get the callback ID from controls that implements ICallbackEventHandler
	 * @param parameter
	 * @return return
	 */
	protected function getCallbackID($control)
	{
		$msg = "Unable to register callback reference, control {$control->ID}";
		$msg .= " does not implement ICallbackEventHandler";
		if(!$control instanceof ICallbackEventHandler)
			throw new TException($msg);
		return ($control instanceof TCallbackPage)
					? $control->ID : $control->UniqueID;
	}
}

/**
 * Invalid request ID exception.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.2 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.Services
 */
class TCallbackInvalidRequestIDException extends TException
{
	public function __construct($id)
	{
		parent::__construct("Invalid callback request with ID '{$id}'");
	}
}

?>