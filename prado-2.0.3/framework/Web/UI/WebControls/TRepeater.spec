<?xml version="1.0" encoding="UTF-8"?>

<component>
	<property name="HeaderTemplate" get="getHeaderTemplate" set="setHeaderTemplate" type="string" />
	<property name="FooterTemplate" get="getFooterTemplate" set="setFooterTemplate" type="string" />
	<property name="EmptyTemplate" get="getEmptyTemplate" set="setEmptyTemplate" type="string" />
	<property name="ItemTemplate" get="getItemTemplate" set="setItemTemplate" type="string" />
	<property name="AlternatingItemTemplate" get="getAlternatingItemTemplate" set="setAlternatingItemTemplate" type="string" />
	<property name="SeparatorTemplate" get="getSeparatorTemplate" set="setSeparatorTemplate" type="string" />
	<property name="Header" get="getHeader" type="object" />
	<property name="Footer" get="getFooter" type="object" />
	<property name="Items" get="getItems" type="array" />
	<property name="ItemCount" get="getItemCount" type="integer" />
	<property name="DataSource" get="getDataSource" set="setDataSource" type="object" />
	<event name="OnItemCommand" />
	<event name="OnItemCreated" />
</component>