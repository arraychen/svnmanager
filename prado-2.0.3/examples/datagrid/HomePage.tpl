<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1DTD/xhtml1-transitional.dtd">
<html>
<body>
<h1>TDataGrid Demo</h1>
<p>This demo shows a simple application of TDataGrid control.</p>
<p>TDataGrid allows you to show as a table a list of data items (rows), edit, select and delete them. It also supports paging and sorting.</p>
<com:TForm>
<com:TDataGrid ID="list"
			width="600"
			CellPadding="2"
			GridLines="Both"
			AutoGenerateColumns="false"
			AllowSorting="true"
			AllowPaging="true"
			PageSize="5"
			PagerButtonCount="5"
			HeaderStyle="background-color:#BFCFFF;font-weight:bold;text-align:center;"
			PagerStyle="background-color:#FFF5E6;text-align:right;font-weight:bold;"
			PagerDisplay="Bottom"
			AllowCustomPaging="false"
			ItemStyle="background-color:#F0F0F0;"
			SelectedItemStyle="background-color:#BFCFFF;"
			AlternatingItemStyle="background-color:#FFFFFF;"
			OnEditCommand="editItem"
			OnUpdateCommand="updateItem"
			OnSelectCommand="selectItem"
			OnCancelCommand="cancelItem"
			OnSortCommand="sortItem"
			OnPageCommand="pageItem">
	<com:TButtonColumn Text="select" ButtonType="PushButton" HeaderText="Select" CommandName="select" ItemStyle="text-align:center" />
	<com:THyperLinkColumn DataTextField="name" HeaderText="Name" NavigateUrl="http://www.xisc.com/" Target="_blank"/>
	<com:TBoundColumn DataField="price" HeaderText="Price" SortExpression="price" DataFormatString="$%.2f" ItemStyle="color:green;text-align:right;"/>
	<com:TBoundColumn DataField="quantity" HeaderText="Quantity" SortExpression="quantity" ItemStyle="text-align:right;"/>
	<com:TTemplateColumn HeaderText="Imported" ItemStyle="text-align:center">
		<prop:ItemTemplate>
		<com:TCheckBox Checked="#$this->Container->Container->Data['imported']" Enabled="false" />
		</prop:ItemTemplate>
		<prop:EditItemTemplate>
		<com:TCheckBox ID="importCheck" Checked="#$this->Container->Container->Data['imported']" Enabled="true" />
		</prop:EditItemTemplate>
	</com:TTemplateColumn>
	<com:TEditCommandColumn ButtonType="PushButton" ItemStyle="text-align:center" HeaderText="Action" EditText="edit" UpdateText="update" CancelText="cancel" />
</com:TDataGrid>
<br/>Select which columns to display:<br/>
<com:TCheckBoxList ID="columnSelect" RepeatColumns="4" SelectionMode="Multiple" AutoPostBack="true" OnSelectionChanged="toggleColumn">
    <com:TListItem Text="Name" Value="1" Selected="true" />
	<com:TListItem Text="Price" Value="2" Selected="true" />
	<com:TListItem Text="Quantity" Value="3" Selected="true" />
	<com:TListItem Text="Imported" Value="4" Selected="true" />
</com:TCheckBoxList>
<br/>Tip: hold down Ctrl when clicking to make single selection change.
</com:TForm> 
</body>
</html>