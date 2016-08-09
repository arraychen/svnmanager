<%include SVNManagerApp.global.header %>
<h1>Remove User</h1>
<com:TPanel ID="RemovePanel" Visible="true" >
<table cellspacing="0" cellpadding="5">
<tr><th>Username</th><th>Email</th></tr>
<com:TRepeater ID="UserTable" OnItemCommand="onDeleteUser" >
	<prop:HeaderTemplate>
	</prop:HeaderTemplate>		
	<prop:ItemTemplate>
		<tr class="row1">
			<td><%= htmlspecialchars($this->Parent->Data['username']) %></td>
			<td><%= htmlspecialchars($this->Parent->Data['email']) %></td>
			<td><com:TLinkButton class="warning" Text="delete" CommandName="delete" CommandParameter="#$this->Parent->Data['userid']" onclick="if(!confirm('Are you sure?')) return false;"/></td>
		</tr>
	</prop:ItemTemplate>
	<prop:AlternatingItemTemplate>
		<tr class="row2">
			<td><%= htmlspecialchars($this->Parent->Data['username']) %></td>
			<td><%= htmlspecialchars($this->Parent->Data['email']) %></td>
			<td><com:TLinkButton class="warning" Text="delete" CommandName="delete" CommandParameter="#$this->Parent->Data['userid']" onclick="if(!confirm('Are you sure?')) return false;"/></td>
		</tr>
	</prop:AlternatingItemTemplate>
	<prop:FooterTemplate>
	</prop:FooterTemplate>		
</com:TRepeater>
<tr>
	<td></td>
	<td></td>
	<td align="right">
		<com:TButton Text="Cancel" OnClick="onCancelBtn" CausesValidation="false" />
	</td>
</tr>
</table>
</com:TPanel>
<com:TPanel ID="ConfirmationPanel" Visible="false" >
	<h3 class="message">User removed!</h4>
	<com:TLinkButton Text="Go back to User Admin menu" OnClick="onGoBack"/>
</com:TPanel>
<%include SVNManagerApp.global.footer %>
