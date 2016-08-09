<%include SVNManagerApp.global.header %>
<h1>Change User</h1>
<table cellspacing="0" cellpadding="5">
	<tr><th>Username</th><th>Email</th></tr>
<com:TRepeater ID="UserTable" OnItemCommand="onUserSelected">
	<prop:HeaderTemplate>
	</prop:HeaderTemplate>		
	<prop:ItemTemplate>
		<tr class="row1">
			<td><%= htmlspecialchars($this->Parent->Data['username']) %></td>
			<td><%= htmlspecialchars($this->Parent->Data['email']) %></td>
			<td><com:TLinkButton Text="select" CommandName="select" CommandParameter="#$this->Parent->Data['userid']" /></td>
		</tr>
	</prop:ItemTemplate>
	<prop:AlternatingItemTemplate>
		<tr class="row2">
			<td><%= htmlspecialchars($this->Parent->Data['username']) %></td>
			<td><%= htmlspecialchars($this->Parent->Data['email']) %></td>
			<td>
				<com:TLinkButton Text="select" CommandName="select" CommandParameter="#$this->Parent->Data['userid']" />
			</td>
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
<%include SVNManagerApp.global.footer %>
