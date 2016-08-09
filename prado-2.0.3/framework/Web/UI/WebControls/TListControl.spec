<?xml version="1.0" encoding="UTF-8"?>

<component>
	<property name="AutoPostBack" get="isAutoPostBack" set="setAutoPostBack" type="boolean" default="false" />
	<property name="DataSource" get="getDataSource" set="setDataSource" type="object" />
	<property name="DataTextField" get="getDataTextField" set="setDataTextField" type="string" />
	<property name="DataValueField" get="getDataValueField" set="setDataValueField" type="string" />
	<property name="DataTextFormatString" get="getDataTextFormatString" set="setDataTextFormatString" type="string" />
	<property name="Items" get="getItems" type="array" />
	<property name="SelectedIndex" get="getSelectedIndex" set="setSelectedIndex" type="integer" />
	<property name="SelectedItem" get="getSelectedItem" type="object" />
	<property name="SelectedValue" get="getSelectedValue" set="setSelectedValue" type="string" />
	<property name="EncodeText" get="isEncodeText" set="setEncodeText" type="boolean" default="true" />
	<event name="OnSelectionChanged" />
</component>