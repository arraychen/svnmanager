<%include SVNManagerApp.global.header %>
<h1>Remove repository</h1>
<com:TPanel ID="RemovePanel" Visible="true">
<script type="text/javascript">
var Repos = new Array();
var i = 0;
</script>
<table cellspacing="0" cellpadding="5">
<com:TRepeater ID="RepositoryTable" OnItemCommand="onDeleteRepository" >
	<prop:HeaderTemplate>
		<tr><th>Repository Name</th><th>Owner</th></tr>
	</prop:HeaderTemplate>		
	<prop:ItemTemplate>
		<tr class="row1">
			<td><%= htmlspecialchars($this->Parent->Data['repositoryname']) %></td>
			<td><%= htmlspecialchars($this->Parent->Data['owner']) %></td>
			<td>
			  <com:TLinkButton class="warning" Text="delete" CommandName="delete" CommandParameter="#$this->Parent->Data['id']" onclick="if (!confirmDelete(this)) return false;"/>
			  <script type="text/javascript">
                            Repos[i] = "<%= htmlspecialchars($this->Parent->Data['repositoryname']) %>";
			    i++;
                          </script>
                        </td>
		</tr>
	</prop:ItemTemplate>
	<prop:AlternatingItemTemplate>
		<tr class="row2">
			<td><%= htmlspecialchars($this->Parent->Data['repositoryname']) %></td>
			<td><%= htmlspecialchars($this->Parent->Data['owner']) %></td>
			<td>
                          <com:TLinkButton class="warning" Text="delete" CommandName="delete" CommandParameter="#$this->Parent->Data['id']" onclick="if (!confirmDelete(this)) return false;"/>
			  <script type="text/javascript">
                            Repos[i] = "<%= htmlspecialchars($this->Parent->Data['repositoryname']) %>";
                            i++;
                          </script>
                        </td>
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
	<h3 class="message">Repository removed!</h4>
	<com:TLinkButton Text="Go back to Repository Admin menu" OnClick="onGoBack"/></br>
</com:TPanel>
<com:TPanel ID="FailedPanel" Visible="false" >
	<h3 class="warning">Repository could not be removed!</h4>
	<com:TLinkButton Text="Go back to Repository Admin menu" OnClick="onGoBack"/></br>
</com:TPanel>
<script text="text/javascript">
function confirmDelete(project)
{
	var id = project.id.split(":")[1].substr(4);
	return confirm("Are you sure you want to delete repository '" + Repos[id] + "'?");
}
</script>
<%include SVNManagerApp.global.footer %>