<?php
/**
 * TPage class file.
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
 * @version $Revision: 1.52 $  $Date: 2005/11/06 23:02:33 $
 * @package System.Web.UI
 */

/**
 * TPage class
 *
 * TPage implements the postback mechanism as well as many functions related to client scripting.
 *
 * A page, an instance of TPage or its descendent class is a top-level control
 * in a PRADO application. It has neither parent nor container.
 * In order for an application to load a page, the page name and the location of the page class
 * must be defined in the application specification.
 *
 * Namespace: System.Web.UI
 *
 * Properties
 * - <b>IsValid</b>, boolean, read-only
 *   <br>Gets the value that indicates whether the page has passed validation.
 * - <b>IsPostBack</b>, boolean, read-only
 *   <br>Gets the value that indicates whether the current request is a postback.
 * - <b>Form</b>, TForm, read-only
 *   <br>Gets the form control associated with the page.
 * - <b>Validators</b>, array, read-only
 *   <br>Gets the list of registered validators.
 * - <b>MasterPage</b>, TPage, read-only
 *   <br>Gets the master page, null if none.
 * - <b>MasterPageName</b>, string
 *   <br>Gets or sets the name of the master page.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI
 */
class TPage extends TControl
{
	/**
	 * hidden form field name for viewstate
	 */
	const INPUT_VIEWSTATE='__VIEWSTATE';
	/**
	 * hidden form field name for event target
	 */
	const INPUT_EVENTTARGET='__EVENTTARGET';
	/**
	 * hidden form field name for event parameter
	 */
	const INPUT_EVENTPARAMETER='__EVENTPARAMETER';
	/**
	 * name of javascript function that submits a form
	 */
	const JS_POSTBACK='__doPostBack';
	/**
	 * the current life cycle that the page is in
	 */
	const STAGE_CREATE=0;
	const STAGE_INIT=1;
	const STAGE_LOADVIEWSTATE=2;
	const STAGE_LOAD=3;
	const STAGE_RAISEEVENTS=4;
	const STAGE_PRERENDER=5;
	const STAGE_SAVEVIEWSTATE=6;
	const STAGE_RENDER=7;
	const STAGE_UNLOAD=8;

	/**
	 * the current life cycle that the page is in
	 * @var string
	 */
	private $stage=0;
	/**
	 * whether the validations on the postback data are successful.
	 * The value will be available after calling {@link validate}. It is true by default.
	 * @var boolean
	 */
	private $isValid=true;
	/**
	 * whether current request is a postback.
	 * The value will be available since Init stage. It is false by default.
	 * @var boolean
	 */
	private $isPostBack=false;
	/**
	 * postback event parameter (string)
	 * @var string
	 */
	private $postBackParameter='';
	/**
	 * postback event target, string, the unique ID of a control triggering the event
	 * @var string
	 */
	private $postBackTarget='';
	/**
	 * reference to the form control if any
	 * @var TForm
	 */
	private $form=null;
	/**
	 * Collection of forms in this page
	 */
	private $forms=null;
	/**
	 * reference to the head control if any
	 * @var THead
	 */
	private $head;
	
	/**
	 * list of all validators on this page
	 * @var array
	 */
	private $validators=array();
	/**
	 * list of all content placeholders
	 * @var array
	 */
	private $placeHolders=array();
	/**
	 * list of controls wishing to check post data, indexed by their unique IDs
	 * @var array
	 */
	private $postDataLoaders=array();
	/**
	 * list of controls whose data is changed
	 * @var array
	 */
	private $postDataChanged=array();
	/**
	 * list of controls wishing to respond to postback event
	 * @var array
	 */
	private $postBackCandidates=array();
	/**
	 * the module that this page is within
	 * @var TModule
	 */
	private $module=null;
	/**
	 * the master page object
	 * @var TPage
	 */
	private $masterPage=null;
	/**
	 * the master page name
	 * @var string
	 */
	private $masterPageName='';

	/**
	 * Constructor, initiates the root to itself.
	 * Use pradoGetApplication()->loadPage() to create a page instead of using the new operator.
	 */
	function __construct()
	{
		$this->setRoot($this);
		$this->forms=new ArrayObject();
		parent::__construct();
	}

	/**
	 * Determines whether the current request is a postback.
	 * This method should only be used by framework developers.
	 */
	public function determinePostBackMode()
	{
		if(!is_null($this->masterPage))
			$this->masterPage->determinePostBackMode();
		$this->isPostBack=isset($_REQUEST[self::INPUT_VIEWSTATE]);
		if($this->isPostBack)
		{
			if(isset($_REQUEST[self::INPUT_EVENTPARAMETER]))
				$this->postBackParameter=$_REQUEST[self::INPUT_EVENTPARAMETER];
			if(isset($_REQUEST[self::INPUT_EVENTTARGET]))
				$this->postBackTarget=$_REQUEST[self::INPUT_EVENTTARGET];
		}
	}

	/**
	 * Indicates whether the current request is a postback.
	 * The value is available since Init stage.
	 * @return boolean|false
	 */
	public function isPostBack()
	{
		return $this->isPostBack;
	}

	/**
	 * Indicates whether all validators have successfully validated the post data.
	 * The value is available since PostBack stage.
	 * @return boolean
	 */
	public function isValid()
	{
		return $this->isValid;
	}

	/**
	 * Attaches the form object to the page.
	 * If the 
	 * @param TForm an instance of TForm or its desendent class
	 */
	public function setForm(TForm $form)
	{
		/*	if(!is_null($this->form))   // TODO: uniqueness check with master pages
			throw new Exception('A page can only contain one server form');
		$this->form=$form;*/
		$this->forms[$form->getUniqueID()] = $form;

		// The last form set must ALWAYS be the getForm form, as there could
		// be instances where controls set something (eg registerBeginScript) 
		// in the Page->Form variable, but if this doesn't refer to the last form
		// in the page it's possible that the Page->Form could be rendered before
		// the beginScripts are added to the Form, and won't show on the page.
		$this->form = $form;
	}

	/**
	 * Detaches the form object from the page.
	 * @param TForm an instance of TForm or its desendent class
	 */
	public function unsetForm(TForm $form)
	{
		if (isset($this->forms[$form->getUniqueID()]))
			unset($this->forms[$form->getUniqueID()]);
			
		if(!is_null($this->form) && $form->getUniqueID()==$this->form->getUniqueID())
			$this->form=null;
	}

	/**
	 * Returns the currently attached form object.
	 * @param 	string		$id		The unique id of the form to detach
	 * @return TForm the reference to the currently attached form object
	 */
	public function getForm($id='')
	{
		// If given an id, return the specified one from the forms list.
		if (strlen($id)) {
			if (!isset($this->forms[$id])) {
				if (is_null($this->masterPage))
						return null;
				return $this->masterPage->getForm($id);
			}
			
			return $this->forms[$id];	
		}
		
		return is_null($this->form)&&!is_null($this->masterPage)?$this->masterPage->getForm():$this->form;
	}
	
	/**
	 * Returns the current page's Head object.
	 * @return THead	The reference to the THead object
	 */
	public function getHead()
	{
		return is_null($this->head)&&!is_null($this->masterPage)?$this->masterPage->getHead():$this->head;
	}
	
	/**
	 * sets the current page's Head object.
	 * @param		THead		$head		The head object
	 * @return 		void 
	 */
	public function setHead(THead $head)
	{
		if(!is_null($this->head))  
			throw new Exception('A page can only contain one THead control');
		$this->head=$head;
	}
	
	/**
	 * @return string the name of the theme to be applied to the page
	 */
	public function getTheme()
	{
		return $this->theme;
	}

	/**
	 * @param string the name of the theme to be applied to the page
	 */
	public function setTheme($theme)
	{
		$this->theme=$theme;
	}

	/**
	 * Runs through every postdata loader to load postdata.
	 * This method should only be used by framework developers.
	 */
	public function loadPostData()
	{
		if(!is_null($this->masterPage))
			$this->masterPage->loadPostData();
		foreach($this->postDataLoaders as $id=>$control)
		{
			if($control->isVisible(true) && $control->loadPostData($id,$_REQUEST))
				$this->postDataChanged[]=$control;
			unset($this->postDataLoaders[$id]);
		}
	}

	/**
	 * Raises PostDataChanged event for each postdata loader whose data is changed.
	 * This method should only be used by framework developers.
	 */
	public function raisePostDataChangedEvents()
	{
		if(!is_null($this->masterPage))
			$this->masterPage->raisePostDataChangedEvents();
		foreach($this->postDataChanged as $control)
			$control->raisePostDataChangedEvent();
	}

	/**
	 * Loads viewstate of the page and all its children from a persistence medium.
	 *
	 * By default, a hidden field is used as persistence medium.
	 * You can override this function to provide your own viewstate maintenance method.
	 * For example, you can keep the viewstate in session data or in database.
	 * If you override, you have to override {@link savePageStateToPersistenceMedium}
	 * as well to match the load and save processes.
	 *
	 * This method should only be used by framework developers.
	 * @return array reference to the loaded viewstate array.
	 * @see savePageStateToPersistenceMedium()
	 */
	public function loadPageStateFromPersistenceMedium()
	{
		$vsm=pradoGetApplication()->getViewStateManager();
		if($vsm->isEnabled())
		{
			$id=isset($_REQUEST[self::INPUT_VIEWSTATE])?intval($_REQUEST[self::INPUT_VIEWSTATE]):0;
			$data=&$vsm->loadViewState($id);
			if(is_null($data))
				throw new Exception('Out of the limit of page state buffer.');
		}
		else
		{
			if(!isset($_REQUEST[self::INPUT_VIEWSTATE]))
				throw new Exception('No viewstate found in request');
			$data=&$_REQUEST[self::INPUT_VIEWSTATE];
		}
		$viewState=unserialize($vsm->decode($data));
		return $viewState;
	}

	/**
	 * Saves viewstate of the page and all its children to a persistence medium.
	 *
	 * This method should only be used by framework developers.
	 * @param array the viewstate structure of the page and all its children
	 * @see loadPageStateFromPersistenceMedium()
	 */
	public function savePageStateToPersistenceMedium($viewState)
	{
		$vsm=pradoGetApplication()->getViewStateManager();
		//$data=&$vsm->encode(serialize($viewState));
		$ser = serialize($viewState);
        $data=&$vsm->encode($ser);
		if($vsm->isEnabled())
		{
			$id=$vsm->saveViewState($data);
			$this->registerViewState($id);
		}
		else 
		{
			$this->registerViewState($data);
		}
	}

	/**
	 * Registers a validator with the page.
	 * The validator must implement IValidator interface
	 * @param IValidator the validator to be registered
	 */
	public function registerValidator(IValidator $validator)
	{
		$this->validators[$validator->getUniqueID()]=$validator;
	}

	/**
	 * Registers a content placeholder with the page.
	 * @param TContentPlaceHolder
	 */
	public function registerContentPlaceHolder($placeHolder)
	{
		$this->placeHolders[$placeHolder->getID()]=$placeHolder;
	}

	/**
	 * Attaches a content control to a content placeholder.
	 * The attachment is based on ID matching.
	 * @param TContent
	 */
	public function attachContent($content)
	{
		$id=$content->getID();
		if(isset($this->placeHolders[$id]))
			$this->placeHolders[$id]->setContent($content);
	}

	/**
	 * Unregisters a content placeholder with the page.
	 * @param TContentPlaceHolder
	 */
	public function unregisterContentPlaceHolder($placeHolder)
	{
		$id=$placeHolder->getID();
		unset($this->placeHolders[$id]);
	}

	/*
	 * @return array list of the registered validators
	 */
	public function getValidators()
	{
		return $this->validators;
	}

	/**
	 * Unregisters a validator from the page.
	 * @param TControl the validator control
	 */
	public function unregisterValidator($control)
	{
		if(isset($this->validators[$control->getUniqueID()]))
			unset($this->validators[$control->getUniqueID()]);
	}

	/**
	 * Registers a postdata loader.
	 * The control must implement IPostBackDataHandler interface.
	 * @param IPostBackDataHandler the control that wants to load postback data
	 */
	public function registerPostDataLoader(IPostBackDataHandler $control)
	{
		$this->postDataLoaders[$control->getUniqueID()]=$control;
	}

	/**
	 * Unregister a postdata loader from the page.
	 * @param TControl the post data loader control
	 */
	public function unregisterPostDataLoader($control)
	{
		if(isset($this->postDataLoaders[$control->getUniqueID()]))
			unset($this->postDataLoaders[$control->getUniqueID()]);
	}	

	/**
	 * Registers a postback event handler.
	 * @param IPostBackEventHandler the control that wants to respond to postback event
	 */
	public function registerPostBackCandidate(IPostBackEventHandler $control)
	{
		$this->postBackCandidates[$control->getUniqueID()]=$control;
	}

	/**
	 * Unregisters a postback handler from the page.
	 * @param TControl the postback candidate control
	 */
	public function unregisterPostBackCandidate($control)
	{
		if(isset($this->postBackCandidates[$control->getUniqueID()]))
			unset($this->postBackCandidates[$control->getUniqueID()]);
	}

	/**
	 * Sets postback target by its unique ID
	 * @param string the unique ID of the postback target
	 */
	public function setPostBackTarget($uniqueID)
	{
		$this->postBackTarget=$uniqueID;
	}

	/**
	 * @return Tcontrol the control responsible for the postback event, null if no postback target available
	 */
	public function getPostBackTarget()
	{
		if(strlen($this->postBackTarget))
		{
			if(isset($this->postBackCandidates[$this->postBackTarget]))
				return $this->postBackCandidates[$this->postBackTarget];
		}
		else
		{
			foreach(array_keys($this->postBackCandidates) as $name)
			{
				if(isset($_REQUEST[$name]))
					return $this->postBackCandidates[$name];
			}
		}
		return null;
	}

	/**
	 * Sets postback parameter
	 * @param string postback parameter to be passed to postback event handler
	 */
	public function setPostBackParameter($value)
	{
		$this->postBackParameter=$value;
	}

	/**
	 * @return string the parameter of the postback event
	 */
	public function getPostBackParameter()
	{
		return $this->postBackParameter;
	}

	/**
	 * Invokes validate() method of every registered validator
	 * and update {@link isValid} accordingly.
	 * @see isValid()
	 */
	public function validate()
	{
		$result=true;
		foreach($this->validators as $validator)
			if($validator->isVisible(true))
				$result=$validator->validate()&&$result;
		$this->isValid=$result;
	}

	/**
	 * Returns a javascript that can be used to trigger client-side postback event.
	 * You have to add your own javascript surrounding elements if necessary.
	 * @param TComponent the control that needs to trigger the postback event
	 * @param string a parameter to be included with the postback event
	 */
	public function getPostBackClientEvent($target,$param)
	{
		if(is_null($this->form))
		{
			if(is_null($this->masterPage))
				throw new Exception('A form control is required');
			else
				return $this->masterPage->getPostBackClientEvent($target,$param);
		}
		$name=$target->getUniqueID();
		$postBack=self::JS_POSTBACK;
		$event="$postBack('$name','$param');";
		if($this->isBeginScriptRegistered(self::JS_POSTBACK))
			return $event;
		$formName=$this->form->getClientID();
		$script="
function $postBack(eventTarget, eventParameter) {
	var validation = typeof(Prado) != 'undefined' && typeof(Prado.Validation) != 'undefined';
	var theform = document.getElementById ? document.getElementById(\"$formName\") : document.forms[\"$formName\"];
	theform.__EVENTTARGET.value = eventTarget.split(\"$\").join(\":\");
	theform.__EVENTPARAMETER.value = eventParameter;
	if(!validation || Prado.Validation.OnSubmit(theform))
	{
	   theform.submit();
	}
}
";
		$this->registerBeginScript(self::JS_POSTBACK,$script);
		$this->registerHiddenField(self::INPUT_EVENTTARGET,'');
		$this->registerHiddenField(self::INPUT_EVENTPARAMETER,'');
		return $event;
	}
	
	/**
	 * Registers the viewstate into each form so that regardless of which
	 * form triggers the postback, viewstate is found for the whole page
	 * @param string $value 	The value to register for the viewstate
	 */
	public function registerViewState($value)
	{
		if(!is_null($this->masterPage))
			$this->masterPage->registerViewState($value);
			
		foreach ($this->forms as $form) {
			$form->registerHiddenField(self::INPUT_VIEWSTATE, $value);
		}
	}

	/**
	 * Registers a hidden field to be submitted upon client postback event.
	 * @param string name of the hidden field
	 * @param string value of the hidden field
	 */
	public function registerHiddenField($name,$value)
	{
		$form=$this->getForm();
		if(is_null($form))
			throw new Exception('A server form control is required.');
		$form->registerHiddenField($name,$value);
	}

	/**
	 * Indicates whether the named hidden field has been registered before.
	 * @param string the name of the hidden field
	 * @return boolean
	 * @see registerHiddenField()
	 */
	public function isHiddenFieldRegistered($name)
	{
		$form=$this->getForm();
		if(is_null($form))
			throw new Exception('A server form control is required.');
		return $form->isHiddenFieldRegistered($name);
	}

	/**
	 * Registers a javascript statement to be executed upon client postback event.
	 * @param string a key that identifies the statement to avoid repetitive registration
	 * @param string the javascript statement to be registered
	 */
	public function registerOnSubmitStatement($key,$script)
	{
		$form=$this->getForm();
		if(is_null($form))
			throw new Exception('A server form control is required.');
		$form->registerOnSubmitStatement($key,$script);
	}

	/**
	 * Indicates whether the named onsubmit statement has been registered before.
	 * @param string the key that identifies the onsubmit statement
	 * @return boolean
	 * @see registerOnSubmitStatement()
	 */
	public function isOnSubmitStatementRegistered($name)
	{
		$form=$this->getForm();
		if(is_null($form))
			throw new Exception('A server form control is required.');
		return $form->isOnSubmitStatementRegistered($name);
	}

	/**
	 * Register an element of a javascript array to be created on client side.
	 * The elements of multiple registration of the same array name will be merged together.
	 * @param string the name of the array
	 * @param string the value of the array element
	 */
	public function registerArrayDeclaration($name,$value)
	{
		$form=$this->getForm();
		if(is_null($form))
			throw new Exception('A server form control is required.');
		$form->registerArrayDeclaration($name,$value);
	}

	/**
	 * Indicates whether the named array has been registered before.
	 * @param string the array name
	 * @return boolean
	 * @see registerArrayDeclaration()
	 */
	public function isArrayDeclarationRegistered($name)
	{
		$form=$this->getForm();
		if(is_null($form))
			throw new Exception('A server form control is required.');
		return $form->isArrayDeclarationRegistered($name);
	}

	/**
	 * Indicates whether the named beginscript has been registered before.
	 * @param string the key that identifies a beginscript
	 * @return boolean
	 * @see registerBeginScript()
	 */
	public function isBeginScriptRegistered($key)
	{
		$form=$this->getForm();
		if(is_null($form))
			throw new Exception('A server form control is required.');
		return $form->isBeginScriptRegistered($key);
	}

	/**
	 * Registers a javascript block to be rendered right after the openning form element.
	 * @param string a key that identifies the script block to avoid repetitive registration
	 * @param string the javascript block
	 * @see isBeginScriptRegistered()
	 */
	public function registerBeginScript($key,$script)
	{
		$form=$this->getForm();
		if(is_null($form))
			throw new Exception('A server form control is required.');
		$form->registerBeginScript($key,$script);
	}

	/**
	 * Indicate whether the named endscript has been registered before.
	 * @param string the key that identifies a beginscript
	 * @return boolean
	 * @see registerEndScript()
	 */
	public function isEndScriptRegistered($key)
	{
		$form=$this->getForm();
		if(is_null($form))
			throw new Exception('A server form control is required.');
		return $form->isEndScriptRegistered($key);
	}

	/**
	 * Register a javascript block to be rendered right before the closing form element.
	 * @param string a key that identifies the script block to avoid repetitive registration
	 * @param string the javascript block
	 * @see isEndScriptRegistered()
	 */
	public function registerEndScript($key,$script)
	{
		$form=$this->getForm();
		if(is_null($form))
			throw new Exception('A server form control is required.');
		$form->registerEndScript($key,$script);
	}

	/**
	 * Indicates whether the named scriptfile has been registered before.
	 * @param string the name of the scriptfile
	 * @return boolean 
	 * @see registerScriptFile()
	 */
	public function isScriptFileRegistered($key)
	{
		// First off check if we have a head control
		$head = $this->getHead();
		if (!is_null($head)) 
			return $head->isScriptFileRegistered($key);
			
		$form=$this->getForm();
		if(is_null($form))
			throw new Exception('A server form control is required.');
		return $form->isScriptFileRegistered($key);
	}

	/**
	 * Registers a javascript file to be loaded in client side
	 * @param string a key that identifies the script file to avoid repetitive registration
	 * @param string the javascript file which can be relative or absolute URL
	 * @see isScriptFileRegistered()
	 */
	public function registerScriptFile($key,$scriptFile)
	{
		// First off check if we have a head control
		$head = $this->getHead();
		if (!is_null($head)) {
			$head->registerScriptFile($key, $scriptFile);
			return;
		}
		
		$form=$this->getForm();
		if(is_null($form))
			throw new Exception('A server form control is required.');
		$form->registerScriptFile($key,$scriptFile);
	}

	/**
	 * Indicates whether the named CSS style file has been registered before.
	 * @param string the name of the style file
	 * @return boolean 
	 * @see registerStyleFile()
	 */
	public function isStyleFileRegistered($key)
	{
		// First off check if we have a head control
		$head = $this->getHead();
		if (!is_null($head)) 
			return $head->isStyleFileRegistered($key);
		
		$form=$this->getForm();
		if(is_null($form))
			throw new Exception('A server form control is required.');
		return $form->isStyleFileRegistered($key);
	}

	/**
	 * Registers a CSS style file to be imported with the page body
	 * @param string a key that identifies the style file to avoid repetitive registration
	 * @param string the javascript file which can be relative or absolute URL
	 * @see isStyleFileRegistered()
	 */
	public function registerStyleFile($key,$styleFile)
	{
		// First off check if we have a head control
		$head = $this->getHead();
		if (!is_null($head)) {
			$head->registerStyleFile($key, $styleFile);
			return;
		}
		
		$form=$this->getForm();
		if(is_null($form))
			throw new Exception('A server form control is required.');
		$form->registerStyleFile($key,$styleFile);
	}

	/**
	 * Checks if the user is authorized to access this page.
	 * Default implement always returns true.
	 * Derived classes may override this method to do real authorization work.
	 * @param IUser the user object
	 * @return boolean whether the user is authorized
	 */
	public function onAuthorize($user)
	{
		return true;
	}


	/**
	 * Returns the module object.
	 * This method overrides the TComponent implementation.
	 * @return TModule
	 */
	public function getModule()
	{
		return $this->module;
	}

	/**
	 * Sets the module object.
	 * This method should only be used by framework developers.
	 * @param TModule
	 */
	public function setModule($module)
	{
		$this->module=$module;
	}

	/**
	 * @return null|TPage the master page
	 */
	public function getMasterPage()
	{
		return $this->masterPage;
	}

	/**
	 * @return string the master page name
	 */
	public function getMasterPageName()
	{
		return $this->masterPageName;
	}

	/**
	 * Sets the master page name.
	 * This method should only be invoked at the beginning of onPreInit() method.
	 * @param string
	 */
	public function setMasterPageName($pageName)
	{
		$this->masterPageName=$pageName;
	}

	/**
	 * @param array|null GET parameters (name=>value pairs), null if no GET parameters
	 * @return string a URL for this page
	 */
	public function getUrl($getParameters=null)
	{
		$pageName=$this->getPageName();
		return $this->getRequest()->constructUrl($pageName,$getParameters);
	}

	/**
	 * @return string full qualified page name including module name if any.
	 */
	public function getPageName()
	{
		return is_null($this->module)?$this->getID():$this->module->getID().':'.$this->getID(); 
	}

	/**
	 * Returns the current life cycle the page is in.
	 * This method should only be used by framework developers.
	 * @return string the current life cycle the page is in
	 */
	public function getStage()
	{
		return $this->stage;
	}

	/**
	 * Executes page lifecycles.
	 *
	 * Starting with TModule.onLoad
	 *
	 * If the page is requested for the first time, it consists of the following lifecycles,
	 * - OnInit event
	 * - OnLoad event
	 * - OnPreRender event
	 * - save viewstate
	 * - render page
	 * - OnUnload event
	 *
	 * If the page is requested in response to a form submission (called postback), the life cycles include
	 * - OnInit event
	 * - load viewstate
	 * - load post data
	 * - OnLoad event
	 * - load post data (for newly created components during Load event)
	 * - OnPostDataChanged event
	 * - Input validation if the postback event handler is enabled with CausesValidation property
	 * - PostBack event
	 * - OnPreRender event
	 * - save viewstate
	 * - render page
	 * - OnUnload event
	 *
	 * The execution is ended with TMOdule.onUnload
	 */
	public function execute()
	{
		$this->onPreInit(new TEventParameter);
		$this->determinePostBackMode();
		//d("*** onInit ***");
		$this->onInitRecursive(new TEventParameter);
		if($this->isPostBack())
		{
			$state=$this->loadPageStateFromPersistenceMedium();
			$this->loadViewState($state);
			$this->loadPostData();
			//d("*** onLoad ***");
			$this->onLoadRecursive(new TEventParameter);
			$this->loadPostData();
			$this->raisePostDataChangedEvents();
			$this->handlePostBackEvent();
		}
		else
		{
			//d("*** onLoad ***");
			$this->onLoadRecursive(new TEventParameter);
		}
		//d("*** onPreRender ***");
		$this->onPreRenderRecursive(new TEventParameter);

		$state=$this->saveViewState();
		$this->savePageStateToPersistenceMedium($state);
		
		//find the globalization class, and sender the proper headers
		$globalizer = $this->Application->getGlobalization();
		if($globalizer) $globalizer->sendContentTypeHeader();
		
		$this->renderContent();
		$this->onUnloadRecursive(new TEventParameter);

		//try to save untranslated messages
		if($globalizer) 
		{
			if(class_exists('Translation', FALSE))
			{
				try { Translation::saveMessages(); }
				catch (exception $e) { } 
			}
		}
	}

	protected function onPreInit($param)
	{
		if(!strlen($this->masterPageName))
			$this->masterPageName=$this->getDefinition(get_class($this))->getMasterPageName();
		if(strlen($this->masterPageName))
		{
			$this->masterPage=pradoGetApplication()->loadPage($this->masterPageName);
			$this->masterPage->onPreInit($param);
		}
	}

	protected function handlePostBackEvent()
	{
		$sender=$this->getPostBackTarget();
		if(is_null($sender))
		{
			if(!is_null($this->masterPage))
				$this->masterPage->handlePostBackEvent();
		}
		else
		{
			if($sender->hasProperty('CausesValidation') && $sender->CausesValidation)
				$this->validate();
			if($sender instanceof IPostBackEventHandler)
				$sender->raisePostBackEvent($this->getPostBackParameter());
		}
	}

	protected function renderContent()
	{
		$this->stage=self::STAGE_RENDER;
		if(is_null($this->masterPage))
			echo $this->render();
		else
		{
			foreach($this->getBodies() as $body)
			{
				if($body instanceof TContent)
					$this->masterPage->attachContent($body);
			}
			echo $this->masterPage->renderContent();
		}
	}
	
	/**
	 * Renders the body content.
	 * This is overridden so that the THead control can be caught
	 * and have its rendering deferred. This is so that if any
	 * other controls on the page want to register scripts or styles,
	 * the rendering of the head will be delayed until later.
	 * Thanks to stever on the prado forums for the very elegant
	 * memory location reassignment method for building the content
	 * string whether or not there's a THead present or not.
	 * @return string the rendering result
	 * @author Marcus Nyeholt <tanus@users.sourceforge.net>
	 */
	protected function renderBody()
	{
		$content1='';
		$content2='';
		$content=&$content1;
		$head = null;
		foreach($this->getBodies() as $body)
		{
			if($body instanceof THead)
			{
				$head = $body;
				$content=&$content2;
			}
			else if($body instanceof TControl)
			{
				if($body->isVisible())
				$content.=$body->render();
			}
			else if(is_string($body))
			$content.=$body;
		}
		if(!is_null($head))
		{
			$content = $content1.$head->render().$content2;
		}
		return $content;
	}

	protected function onInitRecursive($param)
	{
		$this->stage=self::STAGE_INIT;
		if(!is_null($this->masterPage))
			$this->masterPage->onInitRecursive($param);
		parent::onInitRecursive($param);
		$this->stage=self::STAGE_LOADVIEWSTATE;
	}

	protected function onLoadRecursive($param)
	{
		$this->stage=self::STAGE_LOAD;
		if(!is_null($this->masterPage))
			$this->masterPage->onLoadRecursive($param);
		parent::onLoadRecursive($param);
		$this->stage=self::STAGE_RAISEEVENTS;
	}

	protected function onPreRenderRecursive($param)
	{
		$this->stage=self::STAGE_PRERENDER;
		if(!is_null($this->masterPage))
			$this->masterPage->onPreRenderRecursive($param);
		parent::onPreRenderRecursive($param);
		$this->stage=self::STAGE_SAVEVIEWSTATE;
	}

	protected function onUnloadRecursive($param)
	{
		$this->stage=self::STAGE_UNLOAD;
		if(!is_null($this->masterPage))
			$this->masterPage->onUnloadRecursive($param);
		parent::onUnloadRecursive($param);
	}

	public function loadViewState($viewState)
	{
		if(isset($viewState[1]))
		{
			if(!is_null($this->masterPage))
				$this->masterPage->loadViewState($viewState[1]);
			unset($viewState[1]);
		}
		parent::loadViewState($viewState);

	}

	public function saveViewState()
	{
		$viewState=parent::saveViewState();
		if(!is_null($this->masterPage))
		{
			$mv=$this->masterPage->saveViewState();
			if(!is_null($mv))
				$viewState[1]=$mv;
		}
		return $viewState;
	}
	
	public function registerClientScript($scripts)
	{
		TClientScript::register($this, $scripts);
	}
}

?>