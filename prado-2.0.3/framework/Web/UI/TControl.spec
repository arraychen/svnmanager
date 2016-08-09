<?xml version="1.0" encoding="UTF-8"?>

<component>
	<property name="ClientID" get="getClientID" type="string" />
	<property name="Container" get="getContainer" type="object" />
	<property name="Bodies" get="getBodies" type="array" />
	<property name="TagName" get="getTagName" set="setTagName" type="string" />
	<property name="Visible" get="isVisible" set="setVisible" type="boolean" default="true" />
	<property name="EnableViewState" get="isViewStateEnabled" set="setEnableViewState" type="boolean" default="true" />
	<property name="Attributes" get="getAttributes" type="array" />
	<property name="Skin" get="getSkinName" set="setSkinName" type="string" />
	<event name="OnInit" />
	<event name="OnLoad" />
	<event name="OnPreRender" />
	<event name="OnUnload" />
</component>