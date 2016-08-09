<?xml version="1.0" encoding="UTF-8"?>

<component>

	<property name="FinishStepButtonText" get="getFinishStepButtonText" set="setFinishStepButtonText" type="string" default="Finish"/>
	<property name="NextStepButtonText" get="getNextStepButtonText" set="setNextStepButtonText" type="string" default="Next" />
	<property name="PreviousStepButtonText" get="getPreviousStepButtonText" set="setPreviousStepButtonText" type="string" default="Previous"/>
	<property name="CancelButtonText" get="getCancelButtonText" set="setCancelButtonText" type="string" />

	<property name="ActiveStep" get="getActiveStep" type="object" />
	<property name="ActiveStepIndex" get="getActiveStepIndex" set="setActiveStepIndex" type="integer" />
	<property name="DisplaySideBar" get="isSideBarVisible" set="setDisplaySideBar" type="boolean" />
	
	<event name="OnStepChanged" />
	<event name="OnCancelCommand" />
	<event name="OnFinishCommand" />
	<event name="OnNextCommand" />
	<event name="OnPreviousCommand" />
	<event name="OnJumpToCommand" />
</component>