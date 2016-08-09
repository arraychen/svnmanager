<?xml version="1.0" encoding="UTF-8"?>

<component>
	<property name="Columns" get="getColumns" type="array" />
	<property name="AutoGenerateColumns" get="isAutoGenerateColumns" set="setAutoGenerateColumns" type="boolean" default="true" />
	<property name="Items" get="getItems" type="array" />
	<property name="ItemStyle" get="getItemStyle" set="setItemStyle" type="string" />
	<property name="ItemCssClass" get="getItemCssClass" set="setItemCssClass" type="string" />
	<property name="AlternatingItemStyle" get="getAlternatingItemStyle" set="setAlternatingItemStyle" type="string" />
	<property name="AlternatingItemCssClass" get="getAlternatingItemCssClass" set="setAlternatingItemCssClass" type="string" />
	<property name="EditItemStyle" get="getEditItemStyle" set="setEditItemStyle" type="string" />
	<property name="EditItemCssClass" get="getEditItemCssClass" set="setEditItemCssClass" type="string" />
	<property name="EditItemIndex" get="getEditItemIndex" set="setEditItemIndex" type="integer" default="-1" />
	<property name="EditItem" get="getEditItem" type="object" />
	<property name="SelectedItemStyle" get="getSelectedItemStyle" set="setSelectedItemStyle" type="string" />
	<property name="SelectedItemCssClass" get="getSelectedItemCssClass" set="setSelectedItemCssClass" type="string" />
	<property name="SelectedItemIndex" get="getSelectedItemIndex" set="setSelectedItemIndex" type="integer" default="-1" />
	<property name="SelectedItem" get="getSelectedItem" type="object" />
	<property name="HeaderStyle" get="getHeaderStyle" set="setHeaderStyle" type="string" />
	<property name="HeaderCssClass" get="getHeaderCssClass" set="setHeaderCssClass" type="string" />
	<property name="FooterStyle" get="getFooterStyle" set="setFooterStyle" type="string" />
	<property name="FooterCssClass" get="getFooterCssClass" set="setFooterCssClass" type="string" />
	<property name="PagerStyle" get="getPagerStyle" set="setPagerStyle" type="string" />
	<property name="PagerCssClass" get="getPagerCssClass" set="setPagerCssClass" type="string" />
	<property name="ShowHeader" get="isShowHeader" set="setShowHeader" type="boolean" default="true" />
	<property name="ShowFooter" get="isShowFooter" set="setShowFooter" type="boolean" default="false" />
	<property name="BackImageUrl" get="getBackImageUrl" set="setBackImageUrl" type="string" />
	<property name="VirtualItemCount" get="getVirtualItemCount" set="setVirtualItemCount" type="integer" default="0" />
	<property name="Header" get="getHeader" type="object" />
	<property name="Footer" get="getFooter" type="object" />
	<property name="Pager" get="getPager" type="object" />
	<property name="PageSize" get="getPageSize" set="setPageSize" type="integer" default="10" />
	<property name="PageCount" get="getPageCount" type="integer" />
	<property name="CurrentPageIndex" get="getCurrentPageIndex" set="setCurrentPageIndex" type="integer" default="0" />
	<property name="AllowSorting" get="isAllowSorting" set="setAllowSorting" type="boolean" default="false" />
	<property name="AllowPaging" get="isAllowPaging" set="setAllowPaging" type="boolean" default="false" />
	<property name="AllowCustomPaging" get="isAllowCustomPaging" set="setAllowCustomPaging" type="boolean" default="false" />
	<property name="PagerDisplay" get="getPagerDisplay" set="setPagerDisplay" type="(None,Top,Bottom,TopAndBottom)" default="Bottom" />
	<property name="PagerButtonCount" get="getPagerButtonCount" set="setPagerButtonCount" type="integer" default="10" />
	<event name="OnCancelCommand" />
	<event name="OnEditCommand" />
	<event name="OnUpdateCommand" />
	<event name="OnDeleteCommand" />
	<event name="OnSelectCommand" />
	<event name="OnItemCommand" />
	<event name="OnItemCreated" />
	<event name="OnSortCommand" />
	<event name="OnPageCommand" />
</component>