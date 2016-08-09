<?xml version="1.0" encoding="UTF-8"?>

<component>
	<property name="Text" get="getText" set="setText" type="string" />
	<property name="EncodeText" get="isEncodeText" set="setEncodeText" type="boolean" default="true" />
	<property name="Checked" get="isChecked" set="setChecked" type="boolean" default="false" />
	<property name="TextAlign" get="getTextAlign" set="setTextAlign" type="(Left,Right)" default="Right" />
	<property name="AutoPostBack" get="isAutoPostBack" set="setAutoPostBack" type="boolean" default="false" />
	<event name="OnCheckedChanged" />
</component>