<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1DTD/xhtml1-transitional.dtd">
<html>
<body>
<h1>TDataList Demo</h1>
<p>This demo shows a simple application of TDataList control.</p>
<p>TDataList allows you to show a list of data items (rows), edit, select and delete them.</p>
<com:TForm>
<com:TDataList ID="list"
			CellPadding="2"
			CellSpacing="3"
			HorizontalAlign="Justify"
			GridLines="Both"
			RepeatColumns="1"
			RepeatLayout="Table"
			RepeatDirection="Vertical"
			OnEditCommand="editItem"
			OnCancelCommand="cancelItem"
			OnUpdateCommand="updateItem"
			OnDeleteCommand="deleteItem"
			OnSelectCommand="selectItem"
			HeaderStyle="background-color:#AAAADD;"
			ItemStyle="background:#EEEEEE;"
			SeparatorStyle="background:#FAFAFA;"
			FooterStyle="background:#BBBBBB;"
			EditItemStyle="background-color:LightGreen;"
			SelectedItemStyle="background-color:LightYellow;"
            style="border-width:1px;border-style:solid;font-family:Verdana;font-size:10pt;width:300px;border-collapse:collapse;">
<prop:HeaderTemplate>
<center><span style="font-weight:bold">Computer Parts</span></center>
</prop:HeaderTemplate>
<prop:FooterTemplate>
Total <%=$this->Page->list->ItemCount %> products.
</prop:FooterTemplate>
<prop:ItemTemplate>
<table border="0" width="100%">
<tr><td>
<com:TLinkButton Text="#$this->Parent->Data['id']" CommandName="select" />
<com:TLabel Text="#$this->Parent->Data['name']" />
</td>
<td align="right">
<com:TLinkButton Text="Edit" CommandName="edit" />
<com:TLinkButton Text="Delete" CommandName="delete" onclick="if(!confirm('Are you sure?')) return false;"/>
</td></tr>
</table>
</prop:ItemTemplate>

<prop:SelectedItemTemplate>
<table border="1" width="100%">
<tr><th>ID</th><th>Name</th><th>Quantity</th><th>Price</th></tr>
<tr>
<td align="right"><com:TLabel Text="#$this->Parent->Data['id']" /></td>
<td align="right"><com:TLabel Text="#$this->Parent->Data['name']" /></td>
<td align="right"><com:TLabel Text="#$this->Parent->Data['quantity']" /></td>
<td align="right">$<com:TLabel Text="#$this->Parent->Data['price']" /></td>
</tr>
<tr><td colspan="4" align="right">
<com:TLinkButton Text="Edit" CommandName="edit" />
<com:TLinkButton Text="Delete" CommandName="delete" onclick="if(!confirm('Are you sure?')) return false;"/>
</td></tr>
</table>
</prop:SelectedItemTemplate>

<prop:EditItemTemplate>
<table border="0" width="100%">
<tr><td align="right">ID</td><td><com:TLabel Text="#$this->Parent->Data['id']" Style="font-weight:bold"/></td></tr>
<tr><td align="right">Name</td><td><com:TTextBox ID="nameEdt" Text="#$this->Parent->Data['name']" /></td></tr>
<tr><td align="right">Quantity</td><td><com:TTextBox ID="quantEdt" Text="#$this->Parent->Data['quantity']" /></td></tr>
<tr><td align="right">Price</td><td><com:TTextBox ID="priceEdt" Text="#$this->Parent->Data['price']" /></td></tr>
<tr><td colspan="2" align="right">
<com:TLinkButton Text="Save" CommandName="update" />
<com:TLinkButton Text="Cancel" CommandName="cancel" />
</td></tr>
</table>
</prop:EditItemTemplate>

</com:TDataList>
</com:TForm> 
</body>
</html>