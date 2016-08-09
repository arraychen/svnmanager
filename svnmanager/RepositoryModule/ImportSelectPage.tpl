<%include SVNManagerApp.global.header %>
<h1>Import Repository</h1>
<com:TPanel ID="ImportPanel" Visible="true" >
<table cellspacing="0" cellpadding="5">
	<tr><th>Repository Name</th></tr>
<com:TRepeater ID="RepositoryTable" OnItemCommand="onSelectRepository" >
	<prop:HeaderTemplate>
	</prop:HeaderTemplate>		
	<prop:ItemTemplate>
		<tr class="row1">
			<td><%= htmlspecialchars($this->Parent->Data['repositoryname']) %></td>
			<td ><com:TLinkButton Text="select" CommandName="select" CommandParameter="#$this->Parent->Data['repositoryname']" /></td>
		</tr>
	</prop:ItemTemplate>
	<prop:AlternatingItemTemplate>
		<tr class="row2">
			<td><%= htmlspecialchars($this->Parent->Data['repositoryname']) %></td>
			<td ><com:TLinkButton Text="select" CommandName="select" CommandParameter="#$this->Parent->Data['repositoryname']" /></td>
		</tr>
	</prop:AlternatingItemTemplate>
	<prop:FooterTemplate>
	</prop:FooterTemplate>		
</com:TRepeater>
	<tr>
		<td></td>
		<td>
			<com:TButton Text="Cancel" OnClick="onCancelBtn" CausesValidation="false" />
		</td>
	</tr>
</table>
</com:TPanel>
<com:TPanel ID="RepoImportedPanel" Visible="false" >
	<h3 class="message">Repository imported!</h4>
	<com:TLinkButton Text="Go back to Import Repository menu" OnClick="onGoBack"/></br>
</com:TPanel>

<%include SVNManagerApp.global.footer %>
