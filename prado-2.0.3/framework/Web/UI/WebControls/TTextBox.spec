<?xml version="1.0" encoding="UTF-8"?>

<component>
	<property name="Text" get="getText" set="setText" type="string" />
	<property name="TextMode" get="getTextMode" set="setTextMode" type="(SingleLine,MultiLine,Password)" default="SingleLine" />
	<property name="MaxLength" get="getMaxLength" set="setMaxLength" type="integer" default="0" />
	<property name="Rows" get="getRows" set="setRows" type="integer" default="0" />
	<property name="Columns" get="getColumns" set="setColumns" type="integer" default="0" />
	<property name="ReadOnly" get="isReadOnly" set="setReadOnly" type="boolean" default="false" />
	<property name="Wrap" get="isWrap" set="setWrap" type="boolean" default="true" />
	<property name="AutoPostBack" get="isAutoPostBack" set="setAutoPostBack" type="boolean" default="false" />
	<property name="AutoTrim" get="isAutoTrim" set="setAutoTrim" type="boolean" default="false" />
	<property name="EncodeText" get="isEncodeText" set="setEncodeText" type="boolean" default="true" />
	<event name="OnTextChanged" />
</component>