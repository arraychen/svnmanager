<%include SVNManagerApp.global.header %>
<h1>SVN Admin</h1>
<h2>Remove group</h2>
<com:TPanel ID="RemovePanel" Visible="true">
<table cellspacing="0" cellpadding="5">
	<tr><th>Repository Name</th><th>Owner</th></tr>
<com:TRepeater ID="GroupTable" OnItemCommand="onDeleteGroup" >
	<prop:HeaderTemplate>
	</prop:HeaderTemplate>		
	<prop:ItemTemplate>
		<tr class="row1">
			<td><%= htmlspecialchars($this->Parent->Data['groupname']) %></td>
			<td><%= htmlspecialchars($this->Parent->Data['admin']) %>
			<td><com:TLinkButton class="warning" Text="delete" CommandName="delete" CommandParameter="#$this->Parent->Data['id']" onclick="if(!confirm('Are you sure?')) return false;"/></td>
		</tr>
	</prop:ItemTemplate>
	<prop:AlternatingItemTemplate>
		<tr class="row2">
			<td><%= htmlspecialchars($this->Parent->Data['groupname']) %></td>
			<td><%= htmlspecialchars($this->Parent->Data['admin']) %>
			<td><com:TLinkButton class="warning" Text="delete" CommandName="delete" CommandParameter="#$this->Parent->Data['id']" onclick="if(!confirm('Are you sure?')) return false;"/></td>
		</tr>
	</prop:AlternatingItemTemplate>
	<prop:FooterTemplate>
	</prop:FooterTemplate>		
</com:TRepeater>
	<tr>
		<td></td>
		<td></td>
		<td>
			<com:TButton Text="Cancel" OnClick="onCancelBtn" CausesValidation="false" />
		</td>
	</tr>
</table>
</com:TPanel>
<com:TPanel ID="ConfirmationPanel" Visible="false" >
	<h3 class="message">Group removed!</h4>
	<com:TLinkButton Text="Go back to Group Admin menu" OnClick="onGoBack"/>
</com:TPanel>
<%include SVNManagerApp.global.footer %>
