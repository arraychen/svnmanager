<?xml version="1.0" encoding="UTF-8"?>

<component>
	<property name="Items" get="getItems" type="array" />
	<property name="ItemCount" get="getItemCount" type="integer" />
	<property name="EditItemIndex" get="getEditItemIndex" set="setEditItemIndex" type="integer" default="-1" />
	<property name="EditItem" get="getEditItem" type="object" />
	<property name="SelectedItemIndex" get="getSelectedItemIndex" set="setSelectedItemIndex" type="integer" default="-1" />
	<property name="SelectedItem" get="getSelectedItem" type="object" />
	<property name="RepeatColumns" get="getRepeatColumns" set="setRepeatColumns" type="integer" default="1" />
	<property name="RepeatDirection" get="getRepeatDirection" set="setRepeatDirection" type="(Vertical,Horizontal)" default="Vertical" />
	<property name="RepeatLayout" get="getRepeatLayout" set="setRepeatLayout" type="(Table,Flow,Raw)" default="Table" />
	<property name="HeaderTemplate" get="getHeaderTemplate" set="setHeaderTemplate" type="string" />
	<property name="FooterTemplate" get="getFooterTemplate" set="setFooterTemplate" type="string" />
	<property name="SeparatorTemplate" get="getSeparatorTemplate" set="setSeparatorTemplate" type="string" />
	<property name="ItemTemplate" get="getItemTemplate" set="setItemTemplate" type="string" />
	<property name="AlternatingItemTemplate" get="getAlternatingItemTemplate" set="setAlternatingItemTemplate" type="string" />
	<property name="SelectedItemTemplate" get="getSelectedItemTemplate" set="setSelectedItemTemplate" type="string" />
	<property name="EditItemTemplate" get="getEditItemTemplate" set="setEditItemTemplate" type="string" />
	<property name="ItemStyle" get="getItemStyle" set="setItemStyle" type="string" />
	<property name="AlternatingItemStyle" get="getAlternatingItemStyle" set="setAlternatingItemStyle" type="string" />
	<property name="EditItemStyle" get="getEditItemStyle" set="setEditItemStyle" type="string" />
	<property name="SelectedItemStyle" get="getSelectedItemStyle" set="setSelectedItemStyle" type="string" />
	<property name="SeparatorStyle" get="getSeparatorStyle" set="setSeparatorStyle" type="string" />
	<property name="HeaderStyle" get="getHeaderStyle" set="setHeaderStyle" type="string" />
	<property name="FooterStyle" get="getFooterStyle" set="setFooterStyle" type="string" />
	<property name="ShowHeader" get="isShowHeader" set="setShowHeader" type="boolean" default="true" />
	<property name="ShowFooter" get="isShowFooter" set="setShowFooter" type="boolean" default="true" />
	<event name="OnCancelCommand" />
	<event name="OnEditCommand" />
	<event name="OnUpdateCommand" />
	<event name="OnDeleteCommand" />
	<event name="OnSelectCommand" />
	<event name="OnItemCommand" />
	<event name="OnItemCreated" />
</component>