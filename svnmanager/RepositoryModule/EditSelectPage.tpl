<%include SVNManagerApp.global.header %>
<h1>Change Repository</h1>
	<table cellspacing="0" cellpadding="5">
	<tr><th>Repository Name</th><th>Owner</th></tr>
<com:TRepeater ID="RepositoryTable" OnItemCommand="onSelectRepository" >
	<prop:HeaderTemplate>
	</prop:HeaderTemplate>		
	<prop:ItemTemplate>
		<tr class="row1">
			<td><b><%= htmlspecialchars($this->Parent->Data['repositoryname']) %></td>
			<td><b><%= htmlspecialchars($this->Parent->Data['owner']) %>
			<td ><com:TLinkButton Text="select" CommandName="select" CommandParameter="#$this->Parent->Data['id']" /></td>
      </tr>
		<tr class="row1">
      <td colspan="2"><%= $this->Parent->Data['description'] %></td>
      <td></td>
    </tr>
	</prop:ItemTemplate>
	<prop:AlternatingItemTemplate>
		<tr class="row2">
			<td><b><%= htmlspecialchars($this->Parent->Data['repositoryname']) %></td>
			<td><b><%= htmlspecialchars($this->Parent->Data['owner']) %>
			<td><com:TLinkButton Text="select" CommandName="select" CommandParameter="#$this->Parent->Data['id']" /></td>
		</tr>
		<tr class="row2">
      <td colspan="2"><%= $this->Parent->Data['description'] %></td>
      <td></td>
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
