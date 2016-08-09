<%include SVNManagerApp.global.header %>
<h1>SVN Admin</h1>
<h2>Change group</h2>
<com:TRepeater ID="GroupTable" OnItemCommand="onSelectGroup" >
	<prop:HeaderTemplate>
		<table cellspacing="0" cellpadding="5">
		<tr><th>Group Name</th><th>Owner</th></tr>
	</prop:HeaderTemplate>		
	<prop:ItemTemplate>
		<tr class="row1">
			<td><%= htmlspecialchars($this->Parent->Data['groupname']) %></td>
			<td><%= htmlspecialchars($this->Parent->Data['admin']) %>
			<td><com:TLinkButton Text="select" CommandName="select" CommandParameter="#$this->Parent->Data['id']" /></td>
		</tr>
	</prop:ItemTemplate>
	<prop:AlternatingItemTemplate>
		<tr class="row2">
			<td><%= htmlspecialchars($this->Parent->Data['groupname']) %></td>
			<td><%= htmlspecialchars($this->Parent->Data['admin']) %>
			<td><com:TLinkButton Text="select" CommandName="select" CommandParameter="#$this->Parent->Data['id']" /></td>
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
<%include SVNManagerApp.global.footer %>
