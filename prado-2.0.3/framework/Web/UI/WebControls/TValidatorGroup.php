<?php
/**
 * TValidatorGroup class file
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Xiang Wei Zhuo. 
 *
 * To contact the author write to {@link mailto: weizhuoe[at]gmail[dot]com Wei Zhuo}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Xiang Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.11 $  $Date: 2005/09/02 07:46:39 $
 * @package System.Web.UI.WebControls
 */

/**
 * TValidatorGroup class
 *
 * TValidatorGroup allows an arbituary set/group of validators to be active
 * for a Web Control object event. For example, suppose you have two set
 * of validators
 *
 * Set A :
 *		- Validator with ID "Validator_A1"
 *		- Validator with ID "Validator_A2"
 *
 * Set B :
 *		- Validator with ID "Validator_B1"
 *		- Validator with ID "Validator_B2"
 *
 * In addition, you have two buttons with IDs "ButtonA" and "ButtonB".
 * When "ButtonA" is clicked you want the validators in Set A to be
 * validator, but not the validators in Set B. And similarly for "ButtonB"
 * validators for Set B should be validated but not validators in Set A.
 *
 *<code>
 * <com:TTextbox ID="FirstName" />
 * <com:TTextbox ID="Surname" />
 *
 * <com:TRequiredFieldValidator ID="Validator_A1" ControlToValidate="FirstName" />
 * <com:TRequiredFieldValidator ID="Validator_A2" ControlToValidate="Surname" />
 *
 * <com:TTextbox ID="Phone" />
 * <com:TTextbox ID="Fax" />
 *
 * <com:TRequiredFieldValidator ID="Validator_B1" ControlToValidate="Phone" />
 * <com:TRequiredFieldValidator ID="Validator_B2" ControlToValidate="Fax" />
 *
 * <com:TValidatorGroup Members="Validator_A1, Validator_A2" Event="ButtonA:OnClick" />
 * <com:TValidatorGroup Members="Validator_B1, Validator_B2" Event="ButtonB:OnClick" />

 * <com:TButton ID="ButtonA" Text="Add Name" OnClick="addName" />
 * <com:TButton ID="ButtonB" Text="Add Contact Details" OnClick="addContact" />
 *</code>
 *
 * So when "ButtonA" is clicked, validators "Validator_A1" and "Validator_A2"
 * will be validated, but NOT validators "Validator_B1" and "Validator_B2".
 * After the validation, the event OnClick on ButtonA will be called, i.e.
 * function "addName" will be called.
 *
 * Properties
 * - <b>Members</b>, string
 *   <br>Gets or sets the validators for this group.
 *   The accepted value is in the form of validator IDs separated by commas.
 *   E.g. Members="Validator1, Validator2"
 * - <b>Event</b>, string
 *   <br>Gets or sets the Object and Event for activating this group
 *    of validators. The <b>Event</b> attribute must consist of a single web control
 *    object ID and a event on that object separated by a colon. e.g.
 *    Event="Button1:OnClick"
 *
 * @author Xiang Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.11 $  $Date: 2005/09/02 07:46:39 $
 * @package System.Web.UI.WebControls
 */
class TValidatorGroup extends TValidator
{
	/**
	 * A list of validator IDs belonging to this validator group.
	 * The attribute <b>Members</b> set this array via setMembers method.
	 * @var array
	 */
	private $members = array();

	/**
	 * The control and the event this validator is associated with.
	 * The attribute <b>Event</b> set this value via setEvent method.
	 * @var array
	 */
	private $events = array();

	/**
	 * The validator enabled/disabled states.
	 * The enable/disable states of ALL validators are stored before
	 * the group validation. Afterwards, the validator states are restored.
	 * @var array
	 */
	private $validatorStates = array();

	/**
	 * Validator group active state.
	 * @var boolean 
	 */
	private $active = false;

	/**
	 * If any group is active, then group validation is true.
	 * @var boolean 
	 */
	private static $groupValidation = false;

	/**
	 * Is the group currently active.
	 * @return boolean true if group is active, false otherwise. 
	 */
	public function isActive()
	{
		return $this->active;
	}

	/**
	 * Is group validation performed? True if any group is active.
	 * @return boolean true if any group is active, false otherwise.
	 */
	public static function isGroupValidation()
	{
		return self::$groupValidation;
	}

	/**
	 * @return array validator group member IDs.
	 */
	public function getMembers()
	{
		return $this->members;
	}

	/**
	 * Sets the members for this validator group. 
	 * The <b>Member</b> attribute is a string in the form of
	 * validator IDs separated by commas. E.g. if there are
	 * validators with IDs "VPCheck1" and "VPCheck2", then
	 * we have <em>Members="VPCheck1, VPCheck2"</em>.
	 * @param string comma separated validator IDs
	 */
	public function setMembers($members)
	{
		$this->members = explode(',',$members);
		for($i=0;$i<count($this->members); $i++)
			$this->members[$i] = trim($this->members[$i]);
	}

	/**
	 * @return string the control and event name for which the validator
	 * is activated.
	 */
	public function getEvent()
	{
		return $this->events;
	}

	/**
	 * Set the control object and the event to which the validator
	 * group is activated.
	 * The <b>Event</b> attribute must consist of a web control object ID
	 * and the event on that object separated by a colon. E.g. Suppose we
     * have a button with ID "Button1", and we want the validators to be
     * activated by this button when clicked, we set
     * <em>Event="Button1:OnClick"</em>.
	 * @param string colon separated control object ID and its event.
	 */
	public function setEvent($control)
	{
		$event = split(':|\.',trim($control));
		$this->events[trim($event[0])] = trim($event[1]);
	}

	/**
	 * Group validation event. 
	 * This event is activated on the OnLoad event of the TValidatorGroup
     * component. The validation is processed as follows.
	 *	# Get all the validators on the page.
	 *	# Save all the validator enabled/disabled states.
	 *	# Get the validators for this group.
	 *	# Disable the validators NOT belonging to this group
	 *	# Call page::validate();
	 *
	 * The restoring of the validators completed in OnPreRender event.
	 * @param TControl sender of the event
	 * @param TEventParameter event parameter
	 */
	protected function validateGroup($sender, $params)
	{
		$parent = $this->getParent();

		$validators = $this->getPage()->getValidators();

		$validatorIDs = array_keys($validators);

		$thisGroupIDs = array();
		
		foreach($this->members as $member)
		{
			$control = $parent->findObject($member);
			if(!is_null($control))
				$thisGroupIDs[] = $control->getUniqueID();
		}

		$this->saveValidatorStates();
		
		foreach($validatorIDs as $ID)
		{
			if(in_array($ID,$thisGroupIDs) == false)
				$validators[$ID]->setEnabled(false);
		}
	}

	/**
	 * Save all the validator disabled/enabled states on this page.
	 */
	protected function saveValidatorStates()
	{	
		$validators = $this->getPage()->getValidators();

		$validatorIDs = array_keys($validators);

		foreach($validatorIDs as $ID)
		{
			$this->validatorStates[$ID] = $validators[$ID]->isEnabled();
		}
	}

	/**
	 * Load/restore all the validator disabled/enabled states on this page.
	 */
	protected function loadValidatorStates()
	{
		if(count($this->validatorStates) <= 0)
			return;
	
		$validators = $this->getPage()->getValidators();

		$validatorIDs = array_keys($validators);

		foreach($validatorIDs as $ID)
		{
			$validators[$ID]->setEnabled($this->validatorStates[$ID]);
		}
	}

	/**
	 * This overrides the parent implementation by restoring all the
	 * validator disabled/enabled states. Parent::OnPreRender($param)
     * is called.
	 * @param TEventParameter event parameter to be passed to the event
     * handlers
	 */
	public function onPreRender($param) 
	{
		$this->loadValidatorStates();

		parent::onPreRender($param);
	}

	/**
	 * This overrides the parent implementation by doing the group validation.
	 * @see TValidatorGroup::validateGroup()
	 * Parent::OnLoad($params) is called.
	 * @param TEventParameter event parameter to be passed to the event
     * handlers
	 */
	protected function onLoad($params)
	{
		$parent = $this->getParent();

		$sender = $this->getPage()->getPostBackTarget();

		foreach($this->events as $controlID => $event)
		{
			$control = $parent->findObject($controlID);

			if(!is_null($control) && $sender === $control)
			{
				$this->validateGroup($sender, $params);
				$this->active = true;
				self::$groupValidation = true;
			}
		}
		parent::onLoad($params);
	}

	/**
	 * Get a list of controls to validate for this group.
     * @return array of control client IDs, each encapsulated with quotes.
	 */
	private function getMembersList() 
	{
		$memberIDs = array();
		$parent = $this->getParent();
		foreach($this->getMembers() as $member)
		{
			$control = $parent->findObject($member);
			if(!is_null($control))
				$memberIDs[] = '"'.$control->getClientID().'"';
		}
		Return $memberIDs;
	}

	/**
	 * Overrides parent implementation by registering the validation group
     * javascripts.
	 * @return string the rendering result
	 */
	public function render()
	{
		$this->renderJsValidator($this->getJsOptions());
	}

	/**
	 * Render the javascript for the client side group validation.
	 * @param array list of options for the group validator.
	 */
	protected function renderJsValidator($options)
	{
		if(!$this->isEnabled() || !$this->isClientScriptEnabled())
			return;
		$class = get_class($this);
		$option = $this->renderJsOptions($options);
		$validators = $this->renderJsMembers();
		$script = "Prado.Validation.AddGroup({$option}, $validators);";
		$this->Page->registerEndScript($options['id'].'jsValidator', $script);
	}

	/**
	 * Get a list of options for the client side javascript group validation.
	 * @return array list of options. 
	 */
	protected function getJsOptions()
	{
		$options = parent::getJsOptions();
		foreach($this->events as $controlID => $event)
		{
			$control = $this->Parent->findObject($controlID);
			if(!is_null($control))
				$options['target']=$control->ClientID;
		}	
		return $options;
	}

	/**
	 * Render the list of validator IDs as javascript array.
	 * @return string client-side validator IDs javascript array. 
	 */
	protected function renderJsMembers()
	{
		$list = array();
		foreach($this->getMembers() as $member)
		{
			$control = $this->Parent->findObject($member);
			if(!is_null($control))
				$list[] = '"'.$control->getUniqueID().'"';
		}
		return '['.implode(', ',$list).']';
	}
}
?>