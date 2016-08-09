<?xml version="1.0" encoding="UTF-8"?>

<component>
	<property name="CellPadding" get="getCellPadding" set="setCellPadding" type="integer" default="-1" />
	<property name="CellSpacing" get="getCellSpacing" set="setCellSpacing" type="integer" default="-1" />
	<property name="DataSource" get="getDataSource" set="setDataSource" type="object" />
	<property name="DataKeyField" get="getDataKeyField" set="setDataKeyField" type="string" />
	<property name="DataKeys" get="getDataKeys" type="array" />
	<property name="GridLines" get="getGridLines" set="setGridLines" type="(Both,Vertical,Horizontal,None)" default="Both" />
	<property name="HorizontalAlign" get="getHorizontalAlign" set="setHorizontalAlign" type="(Right,Center,Left,Justify,NotSet)" default="NotSet" />
	<event name="OnSelectionChanged" />
</component>